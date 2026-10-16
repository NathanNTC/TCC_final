<?php

namespace App\Http\Controllers;

use App\Http\Requests\Triagem\EnviarMensagemRequest;
use App\Models\Triagem;
use App\Services\IA\GeminiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class TriagemMensagemController extends Controller
{
    public function __construct(
        private readonly GeminiService $geminiService
    ) {
    }

    /**
     * Envia uma mensagem do paciente para uma triagem.
     */
    public function store(
        EnviarMensagemRequest $request,
        Triagem $triagem
    ): RedirectResponse {
        $paciente = $request->user()->paciente;

        abort_unless($paciente, 403);

        /*
        |--------------------------------------------------------------------------
        | Verifica se a triagem pertence ao paciente
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $triagem->paciente_id === $paciente->id,
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Verifica se a triagem ainda está em andamento
        |--------------------------------------------------------------------------
        */

        abort_if(
            $triagem->status !== 'em_andamento',
            422,
            'Esta triagem não está mais em andamento.'
        );

        /*
        |--------------------------------------------------------------------------
        | Verifica se o paciente possui perfil de saúde
        |--------------------------------------------------------------------------
        */

        $perfilSaude = $paciente->perfilSaude;

        if (!$perfilSaude) {
            return back()->withErrors([
                'mensagem' => 'Complete seu perfil de saúde antes de iniciar a triagem.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Salva a mensagem do paciente
        |--------------------------------------------------------------------------
        */

        try {
            DB::transaction(function () use ($request, $triagem) {
                $ultimaOrdem = $triagem
                    ->mensagens()
                    ->max('ordem');

                $triagem->mensagens()->create([
                    'remetente' => 'paciente',
                    'mensagem' => $request->validated('mensagem'),
                    'ordem' => ($ultimaOrdem ?? 0) + 1,
                    'enviada_em' => now(),
                ]);
            });
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors([
                'mensagem' => 'Não foi possível salvar sua mensagem. Tente novamente.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Envia a conversa para o Gemini
        |--------------------------------------------------------------------------
        */

        try {
            $resultado = $this->geminiService->analisarTriagem(
                $triagem->fresh('mensagens'),
                $perfilSaude
            );
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors([
                'mensagem' =>
                    'Sua mensagem foi registrada, mas não foi possível obter uma resposta da IA. Tente novamente em alguns instantes.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Salva resposta da IA e atualiza a triagem
        |--------------------------------------------------------------------------
        */

        try {
            DB::transaction(function () use (
                $triagem,
                $resultado
            ) {
                $ultimaOrdem = $triagem
                    ->mensagens()
                    ->max('ordem');

                $triagem->mensagens()->create([
                    'remetente' => 'ia',
                    'mensagem' => $resultado['resposta'] ?? 'Não foi possível gerar uma resposta.',
                    'ordem' => ($ultimaOrdem ?? 0) + 1,
                    'enviada_em' => now(),
                ]);

                $dadosAtualizacao = [
                    'resumo_ia' => $resultado['resumo'] ?? null,

                    'orientacao_ia' =>
                        $resultado['orientacao'] ?? null,

                    'possiveis_causas_ia' =>
                        $resultado['possiveis_causas'] ?? [],

                    'resultado_completo_ia' =>
                        $resultado,

                    'modelo_ia' =>
                        config(
                            'services.gemini.model',
                            'gemini-2.5-flash'
                        ),

                    'tempo_processamento_ms' =>
                        $resultado['tempo_processamento_ms'] ?? null,
                ];

                /*
                |--------------------------------------------------------------------------
                | Prioridade
                |--------------------------------------------------------------------------
                */

                $prioridade = $resultado['prioridade'] ?? null;

                if (
                    is_string($prioridade) &&
                    $prioridade !== '' &&
                    $prioridade !== 'null'
                ) {
                    $dadosAtualizacao['prioridade_ia'] = $prioridade;
                }

                /*
                |--------------------------------------------------------------------------
                | Finalização da triagem
                |--------------------------------------------------------------------------
                */

                if (
                    ($resultado['triagem_concluida'] ?? false) === true
                ) {
                    $dadosAtualizacao['status'] = 'concluida';
                    $dadosAtualizacao['finalizada_em'] = now();
                }

                $triagem->update($dadosAtualizacao);
            });
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors([
                'mensagem' =>
                    'A resposta da IA foi recebida, mas não foi possível salvar o resultado. Tente novamente.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Volta para a tela da triagem
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('triagens.show', $triagem);
    }
}


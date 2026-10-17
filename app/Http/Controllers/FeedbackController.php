<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Triagem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FeedbackController extends Controller
{
    /**
     * Exibe o formulário de feedback de uma triagem concluída.
     */
    public function create(Triagem $triagem): Response|RedirectResponse
    {
        $usuario = auth()->user();

        abort_unless(
            $usuario && $usuario->hasRole('Paciente'),
            403
        );

        $paciente = $usuario->paciente;

        abort_unless(
            $paciente && $triagem->paciente_id === $paciente->id,
            403
        );

        if ($triagem->status !== 'concluida') {
            return redirect()
                ->route('triagens.show', $triagem)
                ->with(
                    'error',
                    'Você só pode avaliar uma triagem concluída.'
                );
        }

        $feedbackExistente = Feedback::where('user_id', $usuario->id)
            ->where('triagem_id', $triagem->id)
            ->exists();

        if ($feedbackExistente) {
            return redirect()
                ->route('triagens.show', $triagem)
                ->with(
                    'info',
                    'Você já enviou seu feedback para esta triagem.'
                );
        }

        return Inertia::render('Feedback/Create', [
            'triagem' => [
                'id' => $triagem->id,
                'finalizada_em' => $triagem->finalizada_em,
            ],
        ]);
    }

    /**
     * Salva o feedback do paciente.
     */
    public function store(
        Request $request,
        Triagem $triagem
    ): RedirectResponse {
        $usuario = $request->user();

        abort_unless(
            $usuario && $usuario->hasRole('Paciente'),
            403
        );

        $paciente = $usuario->paciente;

        abort_unless(
            $paciente && $triagem->paciente_id === $paciente->id,
            403
        );

        if ($triagem->status !== 'concluida') {
            return redirect()
                ->route('triagens.show', $triagem)
                ->with(
                    'error',
                    'Você só pode avaliar uma triagem concluída.'
                );
        }

        $jaExiste = Feedback::where('user_id', $usuario->id)
            ->where('triagem_id', $triagem->id)
            ->exists();

        if ($jaExiste) {
            return redirect()
                ->route('triagens.show', $triagem)
                ->with(
                    'info',
                    'Você já enviou seu feedback para esta triagem.'
                );
        }

        $dados = $request->validate([
            'nota' => [
                'required',
                'integer',
                'between:1,5',
            ],
            'facilidade' => [
                'required',
                'in:muito_facil,facil,regular,dificil,muito_dificil',
            ],
            'clareza' => [
                'required',
                'in:muito_clara,clara,regular,pouco_clara,nada_clara',
            ],
            'comentario' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'nota.required' => 'Selecione uma nota de 1 a 5 estrelas.',
            'nota.between' => 'A nota deve estar entre 1 e 5 estrelas.',
            'facilidade.required' => 'Informe como foi a facilidade de uso.',
            'facilidade.in' => 'A opção de facilidade selecionada é inválida.',
            'clareza.required' => 'Informe como você avalia a clareza.',
            'clareza.in' => 'A opção de clareza selecionada é inválida.',
            'comentario.max' => 'O comentário deve ter no máximo 1000 caracteres.',
        ]);

        Feedback::create([
            'user_id' => $usuario->id,
            'triagem_id' => $triagem->id,
            'nota' => $dados['nota'],
            'facilidade' => $dados['facilidade'],
            'clareza' => $dados['clareza'],
            'comentario' => $dados['comentario'] ?? null,
        ]);

        return redirect()
            ->route('triagens.show', $triagem)
            ->with(
                'success',
                'Obrigado! Seu feedback foi registrado.'
            );
    }
}

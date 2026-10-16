<?php

namespace App\Http\Controllers;

use App\Models\AvaliacaoMedica;
use App\Models\Triagem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AvaliacaoController extends Controller
{
    private const AVALIADORES = [
        'Estudante',
        'Residente',
        'Medico',
    ];

    private const PRIORIDADES = [
        'emergencial',
        'muito_urgente',
        'urgente',
        'pouco_urgente',
        'autocuidado',
    ];

    /**
     * Lista as triagens concluídas que o avaliador ainda não avaliou.
     */
    public function index(Request $request): Response
    {
        $usuario = $request->user();

        abort_unless(
            $usuario->hasAnyRole(self::AVALIADORES),
            403,
            'Você não possui permissão para avaliar triagens.'
        );

        $triagens = Triagem::query()
            ->where('status', 'concluida')
            ->whereNotNull('prioridade_ia')
            ->whereDoesntHave(
                'avaliacoesMedicas',
                fn ($query) => $query->where(
                    'avaliador_id',
                    $usuario->id
                )
            )
            ->with([
                'mensagens' => fn ($query) => $query->orderBy('ordem'),
            ])
            ->orderByDesc('finalizada_em')
            ->get();

        return Inertia::render('Avaliacao/Index', [
            'triagens' => $triagens,
        ]);
    }

    /**
     * Salva a avaliação humana de uma triagem concluída.
     */
    public function store(
        Request $request,
        Triagem $triagem
    ): RedirectResponse {
        $usuario = $request->user();

        abort_unless(
            $usuario->hasAnyRole(self::AVALIADORES),
            403,
            'Você não possui permissão para avaliar triagens.'
        );

        abort_unless(
            $triagem->status === 'concluida'
                && $triagem->prioridade_ia !== null,
            422,
            'Esta triagem ainda não está disponível para avaliação.'
        );

        $dados = $request->validate([
            'prioridade_avaliador' => [
                'required',
                'string',
                Rule::in(self::PRIORIDADES),
            ],
            'justificativa' => [
                'required',
                'string',
                'min:10',
                'max:3000',
            ],
            'observacoes' => [
                'nullable',
                'string',
                'max:3000',
            ],
            'nota_qualidade' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],
            'comentario_qualidade' => [
                'nullable',
                'string',
                'max:1500',
            ],
        ]);

        $jaAvaliou = AvaliacaoMedica::query()
            ->where('triagem_id', $triagem->id)
            ->where('avaliador_id', $usuario->id)
            ->exists();

        if ($jaAvaliou) {
            return redirect()
                ->route('avaliacao.index')
                ->with('error', 'Você já avaliou esta triagem.');
        }

        DB::transaction(function () use (
            $dados,
            $triagem,
            $usuario
        ) {
            AvaliacaoMedica::create([
                'triagem_id' => $triagem->id,
                'avaliador_id' => $usuario->id,
                'prioridade_avaliador' => $dados['prioridade_avaliador'],
                'justificativa' => $dados['justificativa'],
                'observacoes' => $dados['observacoes'] ?? null,
                'nota_qualidade' => $dados['nota_qualidade'],
                'comentario_qualidade' => $dados['comentario_qualidade'] ?? null,
            ]);
        });

        return redirect()
            ->route('avaliacao.index')
            ->with('success', 'Avaliação registrada com sucesso.');
    }
}
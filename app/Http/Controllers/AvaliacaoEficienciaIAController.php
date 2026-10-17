<?php

namespace App\Http\Controllers;

use App\Models\AvaliacaoEficienciaIA;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AvaliacaoEficienciaIAController extends Controller
{
    private const AVALIADORES = [
        'Estudante',
        'Residente',
        'Medico',
    ];

    public function index(Request $request): Response
    {
        $usuario = $request->user();

        abort_unless(
            $usuario->hasAnyRole(self::AVALIADORES),
            403,
            'Você não possui permissão para avaliar a eficiência da IA.'
        );

        $avaliacao = AvaliacaoEficienciaIA::query()
            ->where('avaliador_id', $usuario->id)
            ->first();

        return Inertia::render('Avaliacao/Eficiencia', [
            'avaliacao' => $avaliacao,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $usuario = $request->user();

        abort_unless(
            $usuario->hasAnyRole(self::AVALIADORES),
            403,
            'Você não possui permissão para avaliar a eficiência da IA.'
        );

        $dados = $request->validate([
            'clareza_perguntas' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],
            'relevancia_perguntas' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],
            'coleta_dados' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],
            'qualidade_respostas' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],
            'utilidade_resumo' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],
            'eficiencia_geral' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],
            'pontos_melhoria' => [
                'nullable',
                'string',
                'max:3000',
            ],
            'comentarios' => [
                'nullable',
                'string',
                'max:3000',
            ],
        ], [
            '*.required' => 'Preencha todas as notas obrigatórias.',
            '*.min' => 'A nota mínima permitida é 1.',
            '*.max' => 'A nota deve ser de 1 a 5.',
        ]);

        AvaliacaoEficienciaIA::updateOrCreate(
            ['avaliador_id' => $usuario->id],
            $dados
        );

        return redirect()
            ->route('avaliacao.eficiencia')
            ->with(
                'success',
                'Sua avaliação geral da IA foi salva com sucesso.'
            );
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AvaliacaoMedica;
use App\Models\Convite;
use App\Models\Triagem;
use App\Models\User;
use App\Services\Avaliacao\ConviteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function index(Request $request): Response
    {
        abort_unless(
            $request->user()->hasRole('Administrador'),
            403,
            'Acesso restrito ao administrador.'
        );

        $dados = $request->validate([
            'perfil' => [
                'nullable',
                'string',
                Rule::in([
                    'todos',
                    'Estudante',
                    'Medico',
                    'Residente',
                ]),
            ],
        ]);

        $perfilSelecionado = $dados['perfil'] ?? 'todos';

        /*
         * Identifica os avaliadores do perfil selecionado.
         */
        $avaliadores = null;

        if ($perfilSelecionado !== 'todos') {
            $avaliadores = User::role($perfilSelecionado)
                ->select('users.id');
        }

        /*
         * Consulta-base das avaliações.
         */
        $avaliacoesBase = AvaliacaoMedica::query();

        if ($avaliadores !== null) {
            $avaliacoesBase->whereIn(
                'avaliacao_medicas.avaliador_id',
                $avaliadores
            );
        }

        /*
         * Total de avaliações registradas.
         */
        $totalAvaliacoes = (clone $avaliacoesBase)->count();

        /*
         * Avaliações em que a prioridade do avaliador
         * corresponde à prioridade registrada pela IA.
         */
        $concordantes = (clone $avaliacoesBase)
            ->join(
                'triagems',
                'avaliacao_medicas.triagem_id',
                '=',
                'triagems.id'
            )
            ->whereNotNull('avaliacao_medicas.prioridade_avaliador')
            ->whereNotNull('triagems.prioridade_ia')
            ->whereColumn(
                'avaliacao_medicas.prioridade_avaliador',
                'triagems.prioridade_ia'
            )
            ->count('avaliacao_medicas.id');

        /*
         * Avaliações comparáveis para o cálculo
         * de concordância e discordância.
         */
        $avaliacoesComparaveis = (clone $avaliacoesBase)
            ->join(
                'triagems',
                'avaliacao_medicas.triagem_id',
                '=',
                'triagems.id'
            )
            ->whereNotNull('avaliacao_medicas.prioridade_avaliador')
            ->whereNotNull('triagems.prioridade_ia');

        $totalComparacoes = (clone $avaliacoesComparaveis)->count();

        $discordantes = (clone $avaliacoesComparaveis)
            ->whereColumn(
                'avaliacao_medicas.prioridade_avaliador',
                '!=',
                'triagems.prioridade_ia'
            )
            ->count('avaliacao_medicas.id');

        /*
         * Quantidade de triagens distintas avaliadas
         * pelo perfil selecionado.
         */
        $triagensAvaliadas = (clone $avaliacoesBase)
            ->whereNotNull('avaliacao_medicas.triagem_id')
            ->distinct()
            ->count('avaliacao_medicas.triagem_id');

        /*
         * Qualidade das avaliações.
         */
        $avaliacoesComNota = (clone $avaliacoesBase)
            ->whereNotNull('avaliacao_medicas.nota_qualidade');

        $quantidadeNotas = (clone $avaliacoesComNota)->count();

        $mediaQualidade = (clone $avaliacoesComNota)
            ->avg('avaliacao_medicas.nota_qualidade');

        $distribuicaoQualidade = (clone $avaliacoesComNota)
            ->selectRaw(
                'avaliacao_medicas.nota_qualidade, COUNT(*) as total'
            )
            ->groupBy('avaliacao_medicas.nota_qualidade')
            ->orderBy('avaliacao_medicas.nota_qualidade')
            ->pluck('total', 'nota_qualidade');

        $notasQualidade = collect(range(1, 5))
            ->map(fn (int $nota) => [
                'nota' => $nota,
                'total' => (int) (
                    $distribuicaoQualidade[$nota] ?? 0
                ),
            ])
            ->values()
            ->all();

        /*
         * Listagem das avaliações registradas.
         */
        $avaliacoesQuery = AvaliacaoMedica::query()
            ->join(
                'users',
                'avaliacao_medicas.avaliador_id',
                '=',
                'users.id'
            )
            ->leftJoin(
                'triagems',
                'avaliacao_medicas.triagem_id',
                '=',
                'triagems.id'
            )
            ->select([
                'avaliacao_medicas.id',
                'avaliacao_medicas.triagem_id',
                'avaliacao_medicas.avaliador_id',
                'avaliacao_medicas.prioridade_avaliador',
                'avaliacao_medicas.justificativa',
                'avaliacao_medicas.observacoes',
                'avaliacao_medicas.nota_qualidade',
                'avaliacao_medicas.comentario_qualidade',
                'avaliacao_medicas.created_at',
                'users.name as avaliador_nome',
                'triagems.prioridade_ia',
                'triagems.resumo_ia',
            ]);

        if ($perfilSelecionado !== 'todos') {
            $avaliadoresDaLista = User::role($perfilSelecionado)
                ->select('users.id');

            $avaliacoesQuery->whereIn(
                'avaliacao_medicas.avaliador_id',
                $avaliadoresDaLista
            );
        }

        $avaliacoes = $avaliacoesQuery
            ->orderByDesc('avaliacao_medicas.created_at')
            ->get();

        /*
         * Convites recentes.
         */
        $convites = Convite::query()
            ->with('criador:id,name')
            ->latest()
            ->take(20)
            ->get();

        /*
         * Quantidade de avaliadores únicos por perfil.
         * Cada usuário é contado uma única vez,
         * mesmo que tenha realizado várias avaliações.
         */
        $idsAvaliadores = AvaliacaoMedica::query()
            ->whereNotNull('avaliador_id')
            ->select('avaliador_id')
            ->distinct();

        $avaliadoresPorPerfil = collect([
            [
                'perfil' => 'Médicos',
                'papel' => 'Medico',
            ],
            [
                'perfil' => 'Estudantes',
                'papel' => 'Estudante',
            ],
            [
                'perfil' => 'Residentes',
                'papel' => 'Residente',
            ],
        ])->map(function ($item) use ($idsAvaliadores) {
            $total = User::role($item['papel'])
                ->whereIn(
                    'users.id',
                    (clone $idsAvaliadores)->select('avaliador_id')
                )
                ->count();

            return [
                'perfil' => $item['perfil'],
                'total' => $total,
            ];
        })->values()->all();

        /*
         * Dados enviados para o painel administrativo.
         */
        return Inertia::render('Admin/Index', [
            'filtroPerfil' => $perfilSelecionado,

            'opcoesPerfil' => [
                [
                    'label' => 'Todas as avaliações',
                    'value' => 'todos',
                ],
                [
                    'label' => 'Estudantes',
                    'value' => 'Estudante',
                ],
                [
                    'label' => 'Médicos',
                    'value' => 'Medico',
                ],
                [
                    'label' => 'Residentes',
                    'value' => 'Residente',
                ],
            ],

            'avaliacoes' => $avaliacoes,

            'avaliadoresPorPerfil' => $avaliadoresPorPerfil,

            'indicadores' => [
                'triagens_concluidas' => Triagem::where(
                    'status',
                    'concluida'
                )->count(),

                'triagens_avaliadas' => $triagensAvaliadas,

                'total_avaliacoes' => $totalAvaliacoes,

                'concordantes' => $concordantes,

                'discordancias' => $discordantes,

                'concordancia_percentual' => $totalComparacoes > 0
                    ? round(
                        ($concordantes / $totalComparacoes) * 100,
                        1
                    )
                    : 0,
            ],

            'qualidade' => [
                'media' => $mediaQualidade !== null
                    ? round((float) $mediaQualidade, 2)
                    : null,

                'total_avaliacoes' => $quantidadeNotas,

                'distribuicao' => $notasQualidade,
            ],

            'convites' => $convites,
        ]);
    }

    public function storeConvite(
        Request $request,
        ConviteService $conviteService
    ): RedirectResponse {
        abort_unless(
            $request->user()->hasRole('Administrador'),
            403,
            'Apenas o administrador pode gerar convites.'
        );

        $dados = $request->validate([
            'perfil' => [
                'required',
                'string',
                Rule::in(ConviteService::PERFIS_PERMITIDOS),
            ],

            'expira_em' => [
                'nullable',
                'date',
                'after:now',
            ],
        ]);

        $convite = $conviteService->gerar(
            $request->user(),
            $dados['perfil'],
            $dados['expira_em'] ?? null
        );

        return redirect()
            ->route('admin.index')
            ->with(
                'success',
                'Convite criado: ' . $convite->codigo
            );
    }
}

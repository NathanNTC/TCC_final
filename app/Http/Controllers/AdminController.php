<?php

namespace App\Http\Controllers;

use App\Models\AvaliacaoMedica;
use App\Models\Convite;
use App\Models\LogIA;
use App\Models\Triagem;
use App\Models\User;
use App\Services\Avaliacao\ConviteService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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

        /*
         * FILTRO DE PERFIL.
         */
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
         * CONSULTA-BASE DAS AVALIAÇÕES.
         */
        $avaliadores = null;

        if ($perfilSelecionado !== 'todos') {
            $avaliadores = User::role($perfilSelecionado)
                ->select('users.id');
        }

        $avaliacoesBase = AvaliacaoMedica::query();

        if ($avaliadores !== null) {
            $avaliacoesBase->whereIn(
                'avaliacao_medicas.avaliador_id',
                $avaliadores
            );
        }

        /*
         * TOTAL DE AVALIAÇÕES.
         */
        $totalAvaliacoes = (clone $avaliacoesBase)->count();

        /*
         * AVALIAÇÕES CONCORDANTES COM A PRIORIDADE DA IA.
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
         * COMPARAÇÕES VÁLIDAS ENTRE A PRIORIDADE HUMANA E A IA.
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
         * QUANTIDADE DE TRIAGENS DISTINTAS AVALIADAS.
         */
        $triagensAvaliadas = (clone $avaliacoesBase)
            ->whereNotNull('avaliacao_medicas.triagem_id')
            ->distinct()
            ->count('avaliacao_medicas.triagem_id');

        /*
         * QUALIDADE DAS AVALIAÇÕES.
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
                'total' => (int) ($distribuicaoQualidade[$nota] ?? 0),
            ])
            ->values()
            ->all();

        /*
         * LISTAGEM DAS AVALIAÇÕES.
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
            $avaliacoesQuery->whereIn(
                'avaliacao_medicas.avaliador_id',
                User::role($perfilSelecionado)->select('users.id')
            );
        }

        $avaliacoes = $avaliacoesQuery
            ->orderByDesc('avaliacao_medicas.created_at')
            ->get();

        /*
         * CONVITES RECENTES.
         */
        $convites = Convite::query()
            ->with('criador:id,name')
            ->latest()
            ->take(20)
            ->get();

        /*
         * AVALIADORES ÚNICOS POR PERFIL.
         */
        $idsAvaliadores = AvaliacaoMedica::query()
            ->whereNotNull('avaliador_id')
            ->select('avaliador_id')
            ->distinct();

        $avaliadoresPorPerfil = collect([
            ['perfil' => 'Médicos', 'papel' => 'Medico'],
            ['perfil' => 'Estudantes', 'papel' => 'Estudante'],
            ['perfil' => 'Residentes', 'papel' => 'Residente'],
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
         * CONSUMO DA API GEMINI.
         */
        $usoGeminiBase = LogIA::query();

        $usoGemini30Dias = (clone $usoGeminiBase)
            ->where('created_at', '>=', now()->subDays(30));

        $resumirUsoGemini = function ($query): array {
            $totais = (clone $query)
                ->selectRaw('COUNT(*) as chamadas')
                ->selectRaw(
                    "COUNT(*) FILTER (WHERE status = 'sucesso') as chamadas_sucesso"
                )
                ->selectRaw(
                    "COUNT(*) FILTER (WHERE status != 'sucesso') as chamadas_com_problema"
                )
                ->selectRaw(
                    'COALESCE(SUM(tokens_entrada), 0) as tokens_entrada'
                )
                ->selectRaw(
                    'COALESCE(SUM(tokens_saida), 0) as tokens_saida'
                )
                ->selectRaw(
                    'COALESCE(SUM(tokens_raciocinio), 0) as tokens_raciocinio'
                )
                ->selectRaw(
                    'COALESCE(SUM(tokens_cache), 0) as tokens_cache'
                )
                ->selectRaw(
                    'COALESCE(SUM(tokens_total), 0) as tokens_total'
                )
                ->selectRaw(
                    'AVG(tempo_processamento_ms) as tempo_medio_ms'
                )
                ->selectRaw('SUM(custo_usd) as custo_usd')
                ->selectRaw('SUM(custo_brl) as custo_brl')
                ->selectRaw('COUNT(custo_usd) as registros_com_custo')
                ->first();

            $custosDisponiveis = (int) $totais->registros_com_custo > 0;

            return [
                'chamadas' => (int) $totais->chamadas,
                'chamadas_sucesso' => (int) $totais->chamadas_sucesso,
                'chamadas_com_problema' => (int) $totais->chamadas_com_problema,

                'tokens_entrada' => (int) $totais->tokens_entrada,
                'tokens_saida' => (int) $totais->tokens_saida,
                'tokens_raciocinio' => (int) $totais->tokens_raciocinio,
                'tokens_cache' => (int) $totais->tokens_cache,
                'tokens_total' => (int) $totais->tokens_total,

                'tempo_medio_ms' => $totais->tempo_medio_ms !== null
                    ? (int) round((float) $totais->tempo_medio_ms)
                    : null,

                'custos_disponiveis' => $custosDisponiveis,

                'custo_usd' => $custosDisponiveis
                    ? round((float) $totais->custo_usd, 6)
                    : null,

                'custo_brl' => $totais->custo_brl !== null
                    ? round((float) $totais->custo_brl, 4)
                    : null,
            ];
        };

        $consumoGemini = [
            'geral' => $resumirUsoGemini($usoGeminiBase),
            'ultimos_30_dias' => $resumirUsoGemini($usoGemini30Dias),
        ];

        /*
         * CONSUMO POR MODELO.
         */
        $consumoPorModelo = (clone $usoGeminiBase)
            ->select('modelo')
            ->selectRaw('COUNT(*) as chamadas')
            ->selectRaw(
                'COALESCE(SUM(tokens_total), 0) as tokens_total'
            )
            ->selectRaw('SUM(custo_usd) as custo_usd')
            ->selectRaw('SUM(custo_brl) as custo_brl')
            ->groupBy('modelo')
            ->orderByDesc('chamadas')
            ->get()
            ->map(fn ($item) => [
                'modelo' => $item->modelo,
                'chamadas' => (int) $item->chamadas,
                'tokens_total' => (int) $item->tokens_total,

                'custo_usd' => $item->custo_usd !== null
                    ? round((float) $item->custo_usd, 6)
                    : null,

                'custo_brl' => $item->custo_brl !== null
                    ? round((float) $item->custo_brl, 4)
                    : null,
            ])
            ->values();

        /*
         * ÚLTIMAS 20 CHAMADAS DA API GEMINI.
         */
        $registrosGemini = LogIA::query()
            ->select([
                'id',
                'triagem_id',
                'modelo',
                'tokens_entrada',
                'tokens_saida',
                'tokens_raciocinio',
                'tokens_cache',
                'tokens_total',
                'custo_usd',
                'custo_brl',
                'tempo_processamento_ms',
                'status',
                'erro',
                'created_at',
            ])
            ->latest()
            ->take(20)
            ->get();

        /*
         * GRÁFICO DE CUSTOS.
         *
         * Filtros aceitos:
         * - dia: últimas 24 horas, agrupadas por hora;
         * - semana: últimos 7 dias, agrupados por dia;
         * - mes: últimos 30 dias, agrupados por dia;
         * - historico: todo o histórico, agrupado por mês;
         * - personalizado + data_custos: dia específico;
         * - personalizado + mes_custos: mês específico.
         */
        $filtrosGrafico = $request->validate([
            'periodo_custos' => [
                'nullable',
                'string',
                Rule::in([
                    'dia',
                    'semana',
                    'mes',
                    'historico',
                    'personalizado',
                ]),
            ],
            'data_custos' => [
                'nullable',
                'date_format:Y-m-d',
            ],
            'mes_custos' => [
                'nullable',
                'date_format:Y-m',
            ],
        ]);

        $periodoCustosGrafico = $filtrosGrafico['periodo_custos'] ?? 'mes';

        $dataCustosGrafico = $filtrosGrafico['data_custos'] ?? null;
        $mesCustosGrafico = $filtrosGrafico['mes_custos'] ?? null;

        /*
         * No modo personalizado, deve ser informado exatamente
         * um filtro: uma data ou um mês, nunca os dois.
         */
        if ($periodoCustosGrafico === 'personalizado') {
            $temData = !empty($dataCustosGrafico);
            $temMes = !empty($mesCustosGrafico);

            if ($temData === $temMes) {
                throw ValidationException::withMessages([
                    'periodo_custos' => (
                        'Selecione uma data específica ou um mês para consultar os custos.'
                    ),
                ]);
            }
        }

        $inicioGrafico = null;
        $fimGrafico = null;

        switch ($periodoCustosGrafico) {
            case 'dia':
                // Últimas 24 horas, agrupadas por hora.
                $inicioGrafico = now()->subDay();
                $fimGrafico = now();
                $expressaoPeriodo = "date_trunc('hour', created_at)";
                break;

            case 'semana':
                // Últimos 7 dias, agrupados por dia.
                $inicioGrafico = now()->subDays(6)->startOfDay();
                $fimGrafico = now()->addSecond();
                $expressaoPeriodo = "date_trunc('day', created_at)";
                break;

            case 'historico':
                // Todo o histórico, agrupado por mês.
                $expressaoPeriodo = "date_trunc('month', created_at)";
                break;

            case 'personalizado':
                if (!empty($dataCustosGrafico)) {
                    // Data específica, agrupada por hora.
                    $inicioGrafico = Carbon::createFromFormat(
                        '!Y-m-d',
                        $dataCustosGrafico
                    );

                    $fimGrafico = $inicioGrafico->copy()->addDay();

                    $expressaoPeriodo = "date_trunc('hour', created_at)";
                } else {
                    // Mês específico, agrupado por dia.
                    $inicioGrafico = Carbon::createFromFormat(
                        '!Y-m',
                        $mesCustosGrafico
                    );

                    $fimGrafico = $inicioGrafico->copy()->addMonth();

                    $expressaoPeriodo = "date_trunc('day', created_at)";
                }
                break;

            case 'mes':
            default:
                // Últimos 30 dias, agrupados por dia.
                $inicioGrafico = now()->subDays(29)->startOfDay();
                $fimGrafico = now()->addSecond();
                $expressaoPeriodo = "date_trunc('day', created_at)";
                $periodoCustosGrafico = 'mes';
                break;
        }

        /*
         * A expressão de agrupamento vem exclusivamente do switch acima.
         * Os valores de data são enviados como parâmetros pelo Query Builder.
         */
        $consultaCustos = DB::table('log_ias')
            ->selectRaw("$expressaoPeriodo AS periodo")
            ->selectRaw('COALESCE(SUM(custo_brl), 0) AS custo_brl')
            ->selectRaw('COUNT(*) AS chamadas')
            ->whereNotNull('custo_brl');

        if ($inicioGrafico !== null) {
            $consultaCustos->where(
                'created_at',
                '>=',
                $inicioGrafico
            );
        }

        if ($fimGrafico !== null) {
            $consultaCustos->where(
                'created_at',
                '<',
                $fimGrafico
            );
        }

        $custosGrafico = $consultaCustos
            ->groupByRaw($expressaoPeriodo)
            ->orderByRaw($expressaoPeriodo)
            ->get()
            ->map(fn ($item) => [
                'periodo' => (string) $item->periodo,
                'custo_brl' => (float) $item->custo_brl,
                'chamadas' => (int) $item->chamadas,
            ])
            ->values();

        /*
         * DADOS ENVIADOS AO PAINEL ADMINISTRATIVO.
         */
        return Inertia::render('Admin/Index', [
            'filtroPerfil' => $perfilSelecionado,

            'custosGrafico' => $custosGrafico,
            'periodoCustosGrafico' => $periodoCustosGrafico,

            'opcoesPerfil' => [
                ['label' => 'Todas as avaliações', 'value' => 'todos'],
                ['label' => 'Estudantes', 'value' => 'Estudante'],
                ['label' => 'Médicos', 'value' => 'Medico'],
                ['label' => 'Residentes', 'value' => 'Residente'],
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

            'consumoGemini' => $consumoGemini,
            'consumoPorModelo' => $consumoPorModelo,
            'registrosGemini' => $registrosGemini,
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

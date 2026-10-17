<?php

namespace App\Http\Controllers;

use App\Models\AvaliacaoEficienciaIA;
use App\Models\Triagem;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminEficienciaController extends Controller
{
    public function index(Request $request): Response
    {
        abort_unless(
            $request->user()->hasRole('Administrador'),
            403,
            'Acesso restrito ao administrador.'
        );

        /*
         * CRITÉRIOS DA AVALIAÇÃO GERAL DA IA.
         */
        $camposEficiencia = [
            'clareza_perguntas' => 'Clareza das perguntas',
            'relevancia_perguntas' => 'Relevância das perguntas',
            'coleta_dados' => 'Coleta de dados',
            'qualidade_respostas' => 'Qualidade das respostas',
            'utilidade_resumo' => 'Utilidade do resumo',
            'eficiencia_geral' => 'Eficiência geral',
        ];

        $consultaEficiencia = AvaliacaoEficienciaIA::query();

        $totalAvaliacoes = (clone $consultaEficiencia)->count();

        /*
         * MÉDIAS DE CADA CRITÉRIO.
         */
        $mediasEficiencia = [];

        foreach ($camposEficiencia as $campo => $rotulo) {
            $media = (clone $consultaEficiencia)->avg($campo);

            $mediasEficiencia[] = [
                'campo' => $campo,
                'rotulo' => $rotulo,
                'media' => $media !== null
                    ? round((float) $media, 2)
                    : null,
            ];
        }

        $mediaGeral = (clone $consultaEficiencia)
            ->avg('eficiencia_geral');

        /*
         * COMPARAÇÃO ENTRE MÉDICOS, RESIDENTES E ESTUDANTES.
         */
        $eficienciaPorPerfil = collect([
            ['perfil' => 'Médicos', 'papel' => 'Medico'],
            ['perfil' => 'Residentes', 'papel' => 'Residente'],
            ['perfil' => 'Estudantes', 'papel' => 'Estudante'],
        ])->map(function ($item) use ($camposEficiencia) {
            $idsAvaliadores = User::role($item['papel'])
                ->select('users.id');

            $consulta = AvaliacaoEficienciaIA::query()
                ->whereIn('avaliador_id', $idsAvaliadores);

            $medias = [];

            foreach ($camposEficiencia as $campo => $rotulo) {
                $media = (clone $consulta)->avg($campo);

                $medias[] = [
                    'campo' => $campo,
                    'rotulo' => $rotulo,
                    'media' => $media !== null
                        ? round((float) $media, 2)
                        : null,
                ];
            }

            $mediaGeralPerfil = (clone $consulta)
                ->avg('eficiencia_geral');

            return [
                'perfil' => $item['perfil'],
                'total' => (clone $consulta)->count(),

                'media_geral' => $mediaGeralPerfil !== null
                    ? round((float) $mediaGeralPerfil, 2)
                    : null,

                'medias' => $medias,
            ];
        })->values()->all();

        /*
         * COMENTÁRIOS E SUGESTÕES DOS AVALIADORES.
         */
        $comentariosEficiencia = AvaliacaoEficienciaIA::query()
            ->with('avaliador')
            ->where(function ($query) {
                $query->whereNotNull('comentarios')
                    ->orWhereNotNull('pontos_melhoria');
            })
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($avaliacao) {
                $perfil = $avaliacao->avaliador
                    ? $avaliacao->avaliador->getRoleNames()->first()
                    : null;

                return [
                    'id' => $avaliacao->id,

                    'perfil' => match ($perfil) {
                        'Medico' => 'Médico',
                        'Residente' => 'Residente',
                        'Estudante' => 'Estudante',
                        default => 'Avaliador',
                    },

                    'comentarios' => $avaliacao->comentarios,
                    'pontos_melhoria' => $avaliacao->pontos_melhoria,

                    'created_at' => $avaliacao->created_at
                        ? $avaliacao->created_at->format('d/m/Y')
                        : null,
                ];
            })
            ->values()
            ->all();

        /*
         * TRIAGENS AGRUPADAS POR STATUS.
         */
        $triagensPorStatus = Triagem::query()
            ->selectRaw(
                "COALESCE(status, 'sem_status') AS categoria, COUNT(*) AS total"
            )
            ->groupBy('status')
            ->orderBy('status')
            ->get()
            ->map(fn ($item) => [
                'categoria' => ucfirst(
                    str_replace('_', ' ', $item->categoria)
                ),
                'total' => (int) $item->total,
            ])
            ->values()
            ->all();

        /*
         * TRIAGENS AGRUPADAS PELA PRIORIDADE DA IA.
         */
        $triagensPorPrioridade = Triagem::query()
            ->whereNotNull('prioridade_ia')
            ->selectRaw(
                'prioridade_ia AS categoria, COUNT(*) AS total'
            )
            ->groupBy('prioridade_ia')
            ->orderBy('prioridade_ia')
            ->get()
            ->map(fn ($item) => [
                'categoria' => ucfirst(
                    str_replace('_', ' ', $item->categoria)
                ),
                'total' => (int) $item->total,
            ])
            ->values()
            ->all();

        /*
         * INDICADORES DE UTILIZAÇÃO PELOS PACIENTES.
         *
         * São métricas operacionais, não notas de satisfação.
         */
        $totalPacientes = User::role('Paciente')->count();
        $totalTriagens = Triagem::count();

        $triagensConcluidas = Triagem::query()
            ->where('status', 'concluida')
            ->count();

        $indicadoresPaciente = [
            'total_pacientes' => $totalPacientes,
            'total_triagens' => $totalTriagens,
            'triagens_concluidas' => $triagensConcluidas,

            'percentual_concluidas' => $totalTriagens > 0
                ? round(($triagensConcluidas / $totalTriagens) * 100, 1)
                : 0,

            'triagens_por_status' => $triagensPorStatus,
            'triagens_por_prioridade' => $triagensPorPrioridade,
        ];

        /*
         * DADOS ENVIADOS À PÁGINA DE EFICIÊNCIA.
         */
        return Inertia::render('Admin/Eficiencia', [
            'eficienciaIA' => [
                'total_avaliacoes' => $totalAvaliacoes,

                'media_geral' => $mediaGeral !== null
                    ? round((float) $mediaGeral, 2)
                    : null,

                'medias' => $mediasEficiencia,
            ],

            'eficienciaPorPerfil' => $eficienciaPorPerfil,
            'comentariosEficiencia' => $comentariosEficiencia,
            'indicadoresPaciente' => $indicadoresPaciente,
        ]);
    }
}

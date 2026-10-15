<?php

namespace App\Services\IA;

use App\Models\MensagemTriagem;
use App\Models\PerfilSaude;
use App\Models\Triagem;
use App\Services\IA\Prompts\TriagemPrompt;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class GeminiService
{
    /**
     * Envia a conversa atual ao Gemini e retorna
     * uma resposta estruturada em JSON.
     */
    public function analisarTriagem(
        Triagem $triagem,
        PerfilSaude $perfilSaude
    ): array {
        $apiKey = config('services.gemini.api_key');

        $model = config(
            'services.gemini.model',
            'gemini-3.8-flash'
        );

        if (!$apiKey) {
            throw new RuntimeException(
                'A chave da API do Gemini não foi configurada. '
                . 'Confira GEMINI_API_KEY no arquivo .env.'
            );
        }

        $mensagens = $triagem->mensagens()
            ->orderBy('ordem')
            ->get();

        $historico = $this->montarHistorico($mensagens);

        $contextoPaciente = $this->montarContextoPaciente(
            $perfilSaude
        );

        $systemInstruction = TriagemPrompt::sistema();

        $prompt = TriagemPrompt::contexto(
            $contextoPaciente,
            $historico
        );

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

        $prioridadesPermitidas = [
            'emergencial',
            'muito_urgente',
            'urgente',
            'pouco_urgente',
            'autocuidado',
        ];

        $payload = [
            'systemInstruction' => [
                'parts' => [
                    [
                        'text' => $systemInstruction,
                    ],
                ],
            ],

            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        [
                            'text' => $prompt,
                        ],
                    ],
                ],
            ],

            'generationConfig' => [
                'thinkingConfig' => [
                    'thinkingLevel' => 'low',
                ],

                'responseMimeType' => 'application/json',

                'responseSchema' => [
                    'type' => 'OBJECT',

                    'properties' => [
                        'resposta' => [
                            'type' => 'STRING',
                        ],

                        'resumo' => [
                            'type' => 'STRING',
                        ],

                        'prioridade' => [
                            'type' => 'STRING',
                            'enum' => [
                                ...$prioridadesPermitidas,
                                'null',
                            ],
                        ],

                        'possiveis_causas' => [
                            'type' => 'ARRAY',
                            'items' => [
                                'type' => 'STRING',
                            ],
                        ],

                        'orientacao' => [
                            'type' => 'STRING',
                        ],

                        'triagem_concluida' => [
                            'type' => 'BOOLEAN',
                        ],

                        'proxima_pergunta' => [
                            'type' => 'STRING',
                        ],
                    ],

                    'required' => [
                        'resposta',
                        'resumo',
                        'prioridade',
                        'possiveis_causas',
                        'orientacao',
                        'triagem_concluida',
                        'proxima_pergunta',
                    ],
                ],
            ],
        ];

        $maxTentativas = 5;
        $inicio = microtime(true);
        $response = null;

        for (
            $tentativa = 1;
            $tentativa <= $maxTentativas;
            $tentativa++
        ) {
            try {
                Log::info(
                    'Enviando requisição para o Gemini.',
                    [
                        'triagem_id' => $triagem->id,
                        'modelo' => $model,
                        'tentativa' => $tentativa,
                        'max_tentativas' => $maxTentativas,
                    ]
                );

                $response = Http::timeout(90)
                    ->connectTimeout(15)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'x-goog-api-key' => $apiKey,
                    ])
                    ->post($url, $payload);

                if ($response->successful()) {
                    Log::info(
                        'Gemini respondeu com sucesso.',
                        [
                            'triagem_id' => $triagem->id,
                            'tentativa' => $tentativa,
                            'status' => $response->status(),
                        ]
                    );

                    break;
                }

                $status = $response->status();

                $erroTemporario = in_array(
                    $status,
                    [408, 429, 500, 502, 503, 504],
                    true
                );

                Log::warning(
                    'Gemini retornou erro HTTP.',
                    [
                        'triagem_id' => $triagem->id,
                        'tentativa' => $tentativa,
                        'status' => $status,
                        'body' => $response->body(),
                    ]
                );

                if (!$erroTemporario) {
                    throw new RuntimeException(
                        'Erro ao comunicar com o Gemini (HTTP '
                        . $status . '): ' . $response->body()
                    );
                }

                if ($tentativa === $maxTentativas) {
                    throw new RuntimeException(
                        'O Gemini continua temporariamente indisponível '
                        . 'após várias tentativas. Tente novamente.'
                    );
                }

                $espera = $tentativa * 5;

                Log::info(
                    'Aguardando antes de tentar novamente.',
                    [
                        'triagem_id' => $triagem->id,
                        'espera_segundos' => $espera,
                    ]
                );

                sleep($espera);
            } catch (ConnectionException $e) {
                Log::warning(
                    'Falha de conexão com o Gemini.',
                    [
                        'triagem_id' => $triagem->id,
                        'tentativa' => $tentativa,
                        'erro' => $e->getMessage(),
                    ]
                );

                if ($tentativa === $maxTentativas) {
                    throw new RuntimeException(
                        'Não foi possível conectar ao Gemini após '
                        . 'várias tentativas. Verifique a conexão.',
                        0,
                        $e
                    );
                }

                sleep($tentativa * 5);
            }
        }

        if (!$response || !$response->successful()) {
            throw new RuntimeException(
                'O Gemini não retornou uma resposta HTTP válida.'
            );
        }

        $tempoMs = (int) round(
            (microtime(true) - $inicio) * 1000
        );

        $json = $response->json();

        $usageMetadata = data_get($json, 'usageMetadata', []);

        Log::info(
            'Uso de tokens do Gemini.',
            [
                'triagem_id' => $triagem->id,
                'prompt_token_count' => data_get(
                    $usageMetadata,
                    'promptTokenCount'
                ),
                'candidates_token_count' => data_get(
                    $usageMetadata,
                    'candidatesTokenCount'
                ),
                'total_token_count' => data_get(
                    $usageMetadata,
                    'totalTokenCount'
                ),
                'cached_content_token_count' => data_get(
                    $usageMetadata,
                    'cachedContentTokenCount',
                    0
                ),
                'thoughts_token_count' => data_get(
                    $usageMetadata,
                    'thoughtsTokenCount',
                    0
                ),
            ]
        );

        $texto = data_get(
            $json,
            'candidates.0.content.parts.0.text'
        );

        if (!is_string($texto) || trim($texto) === '') {
            Log::error(
                'Gemini não retornou texto.',
                [
                    'triagem_id' => $triagem->id,
                    'resposta' => $json,
                ]
            );

            throw new RuntimeException(
                'O Gemini não retornou o conteúdo esperado.'
            );
        }

        $resultado = json_decode($texto, true);

        if (!is_array($resultado)) {
            Log::error(
                'Resposta do Gemini não possui JSON válido.',
                [
                    'triagem_id' => $triagem->id,
                    'texto' => $texto,
                    'erro_json' => json_last_error_msg(),
                ]
            );

            throw new RuntimeException(
                'A resposta do Gemini não possui um JSON válido.'
            );
        }

        $resultado['resposta'] =
            is_string($resultado['resposta'] ?? null)
                ? $resultado['resposta']
                : '';

        $resultado['resumo'] =
            is_string($resultado['resumo'] ?? null)
                ? $resultado['resumo']
                : '';

        $prioridade = $resultado['prioridade'] ?? null;

        if (
            $prioridade === 'null'
            || !in_array(
                $prioridade,
                $prioridadesPermitidas,
                true
            )
        ) {
            $prioridade = null;
        }

        $resultado['prioridade'] = $prioridade;

        $resultado['possiveis_causas'] =
            is_array($resultado['possiveis_causas'] ?? null)
                ? array_values(
                    array_filter(
                        $resultado['possiveis_causas'],
                        fn ($item) => is_string($item)
                    )
                )
                : [];

        $resultado['orientacao'] =
            is_string($resultado['orientacao'] ?? null)
                ? $resultado['orientacao']
                : '';

        $resultado['triagem_concluida'] =
            filter_var(
                $resultado['triagem_concluida'] ?? false,
                FILTER_VALIDATE_BOOLEAN
            );

        $resultado['proxima_pergunta'] =
            is_string($resultado['proxima_pergunta'] ?? null)
                ? $resultado['proxima_pergunta']
                : '';

        $resultado['tempo_processamento_ms'] = $tempoMs;

        Log::info(
            'Triagem processada pelo Gemini.',
            [
                'triagem_id' => $triagem->id,
                'tempo_processamento_ms' => $tempoMs,
                'triagem_concluida' =>
                    $resultado['triagem_concluida'],
                'prioridade' => $resultado['prioridade'],
                'total_token_count' => data_get(
                    $usageMetadata,
                    'totalTokenCount'
                ),
            ]
        );

        return $resultado;
    }

    /**
     * Monta o histórico da conversa em ordem cronológica.
     */
    private function montarHistorico($mensagens): string
    {
        if ($mensagens->isEmpty()) {
            return 'Ainda não existem mensagens nesta triagem.';
        }

        return $mensagens
            ->map(function (MensagemTriagem $mensagem) {
                $remetente = $mensagem->remetente === 'paciente'
                    ? 'PACIENTE'
                    : 'ASSISTENTE';

                return "{$remetente}: {$mensagem->mensagem}";
            })
            ->implode("\n");
    }

    /**
     * Converte arrays e valores JSON em texto legível.
     */
    private function formatarLista(mixed $valor): string
    {
        if ($valor === null || $valor === '') {
            return '';
        }

        if (is_string($valor)) {
            $decodificado = json_decode($valor, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $valor = $decodificado;
            } else {
                return trim($valor);
            }
        }

        if (is_array($valor)) {
            return collect($valor)
                ->flatten()
                ->filter(
                    fn ($item) => is_scalar($item)
                        && trim((string) $item) !== ''
                )
                ->map(fn ($item) => (string) $item)
                ->implode(', ');
        }

        if (is_scalar($valor)) {
            return trim((string) $valor);
        }

        return '';
    }

    /**
     * Diferencia valores negativos de campos não informados.
     */
    private function formatarRespostaBooleana(mixed $valor): string
    {
        if ($valor === null || $valor === '') {
            return 'Não informado';
        }

        if (is_bool($valor)) {
            return $valor ? 'Sim' : 'Não';
        }

        if (is_numeric($valor)) {
            return (int) $valor === 1 ? 'Sim' : 'Não';
        }

        $valorNormalizado = strtolower(trim((string) $valor));

        if (
            in_array(
                $valorNormalizado,
                ['true', 'yes', 'sim'],
                true
            )
        ) {
            return 'Sim';
        }

        if (
            in_array(
                $valorNormalizado,
                ['false', 'no', 'não', 'nao'],
                true
            )
        ) {
            return 'Não';
        }

        return 'Não informado';
    }

    /**
     * Monta o contexto de saúde do paciente.
     */
    private function montarContextoPaciente(
        PerfilSaude $perfilSaude
    ): string {
        $dados = [];

        if ($perfilSaude->tipo_sanguineo) {
            $dados[] = 'Tipo sanguíneo: '
                . $perfilSaude->tipo_sanguineo;
        }

        $listas = [
            'problemas_saude_previos' => 'Problemas de saúde prévios',
            'historico_familiar' => 'Histórico familiar',
            'alergias' => 'Alergias',
        ];

        foreach ($listas as $campo => $rotulo) {
            $valor = $this->formatarLista(
                $perfilSaude->getAttribute($campo)
            );

            if ($valor !== '') {
                $dados[] = "{$rotulo}: {$valor}";
            }
        }

        $dados[] = 'Fumante: '
            . $this->formatarRespostaBooleana(
                $perfilSaude->fumante
            );

        $dados[] = 'Consumo de álcool: '
            . $this->formatarRespostaBooleana(
                $perfilSaude->alcoolismo
            );

        $dados[] = 'Uso de drogas: '
            . $this->formatarRespostaBooleana(
                $perfilSaude->uso_drogas
            );

        $dados[] = 'Atividade física regular: '
            . $this->formatarRespostaBooleana(
                $perfilSaude->atividade_fisica_regular
            );

        if ($perfilSaude->altura !== null) {
            $dados[] = "Altura: {$perfilSaude->altura} m";
        }

        if ($perfilSaude->peso !== null) {
            $dados[] = "Peso: {$perfilSaude->peso} kg";
        }

        if (empty($dados)) {
            return 'Nenhuma informação adicional de saúde foi cadastrada.';
        }

        return implode("\n", $dados);
    }
}


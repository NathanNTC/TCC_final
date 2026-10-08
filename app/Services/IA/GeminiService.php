<?php

namespace App\Services\IA;

use App\Models\MensagemTriagem;
use App\Models\PerfilSaude;
use App\Models\Triagem;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GeminiService
{
    /**
     * Envia a conversa atual para o Gemini e retorna
     * uma resposta estruturada.
     */
    public function analisarTriagem(
        Triagem $triagem,
        PerfilSaude $perfilSaude
    ): array {
        $apiKey = config('services.gemini.api_key');

        $model = config(
            'services.gemini.model',
            'gemini-2.5-flash'
        );

        if (!$apiKey) {
            throw new RuntimeException(
                'A chave da API do Gemini não foi configurada.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Histórico da conversa
        |--------------------------------------------------------------------------
        */

        $mensagens = $triagem->mensagens()
            ->orderBy('ordem')
            ->get();

        $historico = $this->montarHistorico($mensagens);

        /*
        |--------------------------------------------------------------------------
        | Contexto de saúde
        |--------------------------------------------------------------------------
        */

        $contextoPaciente = $this->montarContextoPaciente(
            $perfilSaude
        );

        /*
        |--------------------------------------------------------------------------
        | Prompt
        |--------------------------------------------------------------------------
        */

        $prompt = TriagemPrompt::montar(
            $contextoPaciente,
            $historico
        );

        /*
        |--------------------------------------------------------------------------
        | Chamada para o Gemini
        |--------------------------------------------------------------------------
        */

        $inicio = microtime(true);

        $response = Http::timeout(60)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'x-goog-api-key' => $apiKey,
            ])
            ->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent",
                [
                    'system_instruction' => [
                        'parts' => [
                            [
                                'text' =>
                                    'Você é um assistente de apoio à coleta '
                                    . 'e organização de informações clínicas '
                                    . 'em Atenção Primária à Saúde. '
                                    . 'Não realiza diagnóstico médico. '
                                    . 'Siga rigorosamente as instruções '
                                    . 'fornecidas no prompt da triagem.'
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
                        'temperature' => 0.2,

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
                                        'emergencial',
                                        'nao_emergencial',
                                        'autocuidado',
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
                ]
            );

        $tempoMs = (int) round(
            (microtime(true) - $inicio) * 1000
        );

        /*
        |--------------------------------------------------------------------------
        | Verifica erro da API
        |--------------------------------------------------------------------------
        */

        if ($response->failed()) {
            throw new RuntimeException(
                'Erro ao comunicar com o Gemini: '
                . $response->body()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Obtém resposta
        |--------------------------------------------------------------------------
        */

        $json = $response->json();

        $texto = data_get(
            $json,
            'candidates.0.content.parts.0.text'
        );

        if (!$texto) {
            throw new RuntimeException(
                'O Gemini não retornou uma resposta válida.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Converte JSON retornado pelo Gemini
        |--------------------------------------------------------------------------
        */

        $resultado = json_decode(
            $texto,
            true
        );

        if (!is_array($resultado)) {
            throw new RuntimeException(
                'A resposta do Gemini não possui um formato JSON válido.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Tempo de processamento
        |--------------------------------------------------------------------------
        */

        $resultado['tempo_processamento_ms'] = $tempoMs;

        return $resultado;
    }

    /**
     * Monta o histórico da conversa.
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
     * Monta o contexto de saúde do paciente.
     */
    private function montarContextoPaciente(
        PerfilSaude $perfilSaude
    ): string {
        $dados = [];

        if ($perfilSaude->tipo_sanguineo) {
            $dados[] =
                "Tipo sanguíneo: {$perfilSaude->tipo_sanguineo}";
        }

        if ($perfilSaude->problemas_saude_previos) {
            $dados[] =
                'Problemas de saúde prévios: '
                . implode(
                    ', ',
                    $perfilSaude->problemas_saude_previos
                );
        }

        if ($perfilSaude->historico_familiar) {
            $dados[] =
                'Histórico familiar: '
                . implode(
                    ', ',
                    $perfilSaude->historico_familiar
                );
        }

        if ($perfilSaude->alergias) {
            $dados[] =
                'Alergias: '
                . implode(
                    ', ',
                    $perfilSaude->alergias
                );
        }

        $dados[] =
            'Fumante: '
            . ($perfilSaude->fumante ? 'Sim' : 'Não');

        $dados[] =
            'Consumo de álcool: '
            . ($perfilSaude->alcoolismo ? 'Sim' : 'Não');

        $dados[] =
            'Uso de drogas: '
            . ($perfilSaude->uso_drogas ? 'Sim' : 'Não');

        $dados[] =
            'Atividade física regular: '
            . ($perfilSaude->atividade_fisica_regular
                ? 'Sim'
                : 'Não');

        if ($perfilSaude->altura) {
            $dados[] =
                "Altura: {$perfilSaude->altura} m";
        }

        if ($perfilSaude->peso) {
            $dados[] =
                "Peso: {$perfilSaude->peso} kg";
        }

        if (empty($dados)) {
            return 'Nenhuma informação adicional de saúde foi cadastrada.';
        }

        return implode("\n", $dados);
    }
}
<?php

namespace App\Console\Commands;

use App\Services\IA\Prompts\TriagemPrompt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CriarGeminiCache extends Command
{
    /**
     * Nome do comando.
     */
    protected $signature = 'gemini:cache';

    /**
     * Descrição do comando.
     */
    protected $description = 'Cria o Context Cache do prompt de triagem no Gemini';

    /**
     * Executa o comando.
     */
    public function handle(): int
    {
        $apiKey = config('services.gemini.api_key');

        $model = config(
            'services.gemini.model',
            'gemini-2.5-flash'
        );

        /*
        |--------------------------------------------------------------------------
        | Verifica API Key
        |--------------------------------------------------------------------------
        */

        if (!$apiKey) {
            $this->error(
                'GEMINI_API_KEY não foi configurada.'
            );

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | Obtém o prompt fixo
        |--------------------------------------------------------------------------
        |
        | Esse é o prompt de aproximadamente 500 linhas.
        |
        */

        $prompt = TriagemPrompt::sistema();

        if (trim($prompt) === '') {
            $this->error(
                'O prompt da triagem está vazio.'
            );

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | Informações
        |--------------------------------------------------------------------------
        */

        $this->info(
            'Criando Context Cache do Gemini...'
        );

        $this->line(
            "Modelo: {$model}"
        );

        $this->line(
            'Tamanho do prompt: '
            . strlen($prompt)
            . ' caracteres'
        );

        $this->newLine();

        /*
        |--------------------------------------------------------------------------
        | URL da API
        |--------------------------------------------------------------------------
        */

        $url =
            'https://generativelanguage.googleapis.com/v1beta/cachedContents';

        /*
        |--------------------------------------------------------------------------
        | Payload
        |--------------------------------------------------------------------------
        |
        | O prompt fixo será armazenado no cache como
        | systemInstruction.
        |
        | O TTL abaixo é de 24 horas.
        |
        */

        $payload = [
            'model' => "models/{$model}",

            'displayName' => 'triagem-ia-prompt-v1',

            'systemInstruction' => [
                'parts' => [
                    [
                        'text' => $prompt,
                    ],
                ],
            ],

            'ttl' => '86400s',
        ];

        /*
        |--------------------------------------------------------------------------
        | Envia para o Gemini
        |--------------------------------------------------------------------------
        */

        try {
            $response = Http::timeout(90)
                ->connectTimeout(15)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => $apiKey,
                ])
                ->post(
                    $url,
                    $payload
                );
        } catch (\Throwable $e) {
            $this->error(
                'Erro ao conectar com o Gemini: '
                . $e->getMessage()
            );

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | Verifica resposta
        |--------------------------------------------------------------------------
        */

        if (!$response->successful()) {
            $this->error(
                'O Gemini recusou a criação do cache.'
            );

            $this->newLine();

            $this->error(
                'HTTP Status: '
                . $response->status()
            );

            $this->newLine();

            $this->line(
                $response->body()
            );

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | Decodifica resposta
        |--------------------------------------------------------------------------
        */

        $data = $response->json();

        /*
        |--------------------------------------------------------------------------
        | Obtém nome do cache
        |--------------------------------------------------------------------------
        */

        $cacheName = data_get(
            $data,
            'name'
        );

        if (!$cacheName) {
            $this->error(
                'O Gemini criou uma resposta, mas não retornou o nome do cache.'
            );

            $this->newLine();

            $this->line(
                json_encode(
                    $data,
                    JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_UNICODE
                )
            );

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | Sucesso
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info(
            'Cache criado com sucesso!'
        );

        $this->newLine();

        $this->line(
            'Nome do cache:'
        );

        $this->info(
            $cacheName
        );

        $this->newLine();

        $this->line(
            'Agora coloque este valor no seu arquivo .env:'
        );

        $this->newLine();

        $this->info(
            "GEMINI_TRIAGEM_CACHE={$cacheName}"
        );

        $this->newLine();

        $this->line(
            'TTL: 24 horas'
        );

        return self::SUCCESS;
    }
}

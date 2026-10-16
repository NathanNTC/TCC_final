<?php

namespace App\Services\IA;

use App\Models\LogIA;
use App\Models\Triagem;
use Illuminate\Support\Facades\Log;
use Throwable;

class UsoGeminiService
{
    /**
     * Registra o consumo de tokens e calcula o custo estimado
     * de uma chamada à API do Gemini.
     *
     * Os preços são configurados em config/services.php.
     * Se os preços não estiverem configurados, os custos ficam null.
     */
    public function registrar(
        Triagem $triagem,
        string $modelo,
        array $usageMetadata,
        int $tempoMs
    ): void {
        try {
            $promptTokens = data_get(
                $usageMetadata,
                'promptTokenCount'
            );

            $outputTokens = data_get(
                $usageMetadata,
                'candidatesTokenCount'
            );

            $thoughtTokens = data_get(
                $usageMetadata,
                'thoughtsTokenCount',
                0
            );

            $cachedTokens = data_get(
                $usageMetadata,
                'cachedContentTokenCount',
                0
            );

            $totalTokens = data_get(
                $usageMetadata,
                'totalTokenCount'
            );

            $usageDisponivel = is_numeric($promptTokens)
                && is_numeric($outputTokens);

            $promptTokens = $usageDisponivel
                ? max(0, (int) $promptTokens)
                : 0;

            $outputTokens = $usageDisponivel
                ? max(0, (int) $outputTokens)
                : 0;

            $thoughtTokens = is_numeric($thoughtTokens)
                ? max(0, (int) $thoughtTokens)
                : 0;

            $cachedTokens = is_numeric($cachedTokens)
                ? max(0, (int) $cachedTokens)
                : 0;

            // Tokens em cache fazem parte dos tokens de entrada.
            $cachedTokens = min($cachedTokens, $promptTokens);

            $entradaNormal = max(
                0,
                $promptTokens - $cachedTokens
            );

            $precoEntrada = config(
                'services.gemini.pricing.input_per_million_usd'
            );

            $precoSaida = config(
                'services.gemini.pricing.output_per_million_usd'
            );

            $precoCache = config(
                'services.gemini.pricing.cached_input_per_million_usd'
            );

            $cotacaoDolar = config(
                'services.gemini.usd_brl_rate'
            );

            $precosConfigurados = is_numeric($precoEntrada)
                && is_numeric($precoSaida)
                && (
                    $cachedTokens === 0
                    || is_numeric($precoCache)
                );

            $custoUsd = null;
            $custoBrl = null;

            if ($usageDisponivel && $precosConfigurados) {
                /*
                 * Tokens de raciocínio são contabilizados como
                 * saída faturável quando o modelo os reporta
                 * separadamente em thoughtsTokenCount.
                 */
                $tokensSaidaFaturaveis = (
                    $outputTokens + $thoughtTokens
                );

                $custoEntrada = (
                    $entradaNormal * (float) $precoEntrada
                );

                $custoCache = (
                    $cachedTokens * (
                        $cachedTokens > 0
                            ? (float) $precoCache
                            : 0
                    )
                );

                $custoSaida = (
                    $tokensSaidaFaturaveis * (float) $precoSaida
                );

                $custoUsd = (
                    $custoEntrada
                    + $custoCache
                    + $custoSaida
                ) / 1_000_000;

                if (is_numeric($cotacaoDolar)) {
                    $custoBrl = $custoUsd * (float) $cotacaoDolar;
                }
            }

            LogIA::create([
                'triagem_id' => $triagem->id,
                'modelo' => $modelo,
                'tokens_entrada' => $entradaNormal,
                'tokens_saida' => $outputTokens,
                'tokens_raciocinio' => $thoughtTokens,
                'tokens_cache' => $cachedTokens,

                'tokens_total' => is_numeric($totalTokens)
                    ? (int) $totalTokens
                    : (
                        $promptTokens
                        + $outputTokens
                        + $thoughtTokens
                    ),

                'custo_usd' => $custoUsd,
                'custo_brl' => $custoBrl,
                'tempo_processamento_ms' => max(0, $tempoMs),

                'status' => $usageDisponivel
                    ? 'sucesso'
                    : 'uso_indisponivel',
            ]);

            Log::info(
                'Consumo do Gemini registrado.',
                [
                    'triagem_id' => $triagem->id,
                    'modelo' => $modelo,
                    'tokens_total' => $totalTokens,
                    'custo_usd' => $custoUsd,
                    'custo_brl' => $custoBrl,
                ]
            );
        } catch (Throwable $e) {
            /*
             * Falhas no registro das métricas não devem impedir
             * o funcionamento da triagem.
             */
            Log::error(
                'Não foi possível registrar o uso do Gemini.',
                [
                    'triagem_id' => $triagem->id,
                    'modelo' => $modelo,
                    'erro' => $e->getMessage(),
                ]
            );
        }
    }
}
<script setup>
import { computed } from 'vue'
import {
    formatarNumero,
    formatarPercentual,
} from './formatadoresCustos.js'

const props = defineProps({
    consumoGemini: {
        type: Object,
        default: () => ({}),
    },
})

// No controller, os totais estão em consumoGemini.geral.
const dados = computed(() => (
    props.consumoGemini?.geral
    ?? props.consumoGemini
    ?? {}
))

const entrada = computed(() =>
    Number(dados.value.tokens_entrada ?? 0)
)

const saida = computed(() =>
    Number(dados.value.tokens_saida ?? 0)
)

const pensamento = computed(() =>
    Number(dados.value.tokens_raciocinio ?? 0)
)

const cache = computed(() =>
    Number(dados.value.tokens_cache ?? 0)
)

const total = computed(() =>
    Number(
        dados.value.tokens_total
        ?? (
            entrada.value
            + saida.value
            + pensamento.value
        )
    )
)

function percentual(valor) {
    if (total.value <= 0) return 0

    return Math.min(100, (valor / total.value) * 100)
}
</script>

<template>
    <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900">
        <!-- CABEÇALHO DA SEÇÃO -->
        <div class="mb-6">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-slate-100">
                Detalhamento de tokens
            </h2>

            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                Distribuição dos tokens registrados nas chamadas da API.
            </p>
        </div>

        <!-- CARDS DE ENTRADA, SAÍDA E RACIOCÍNIO -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <!-- Tokens de entrada -->
            <div class="rounded-xl border border-blue-100 bg-blue-50/50 p-4 dark:border-blue-900/40 dark:bg-blue-950/20">
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-700 dark:text-blue-400">
                    Tokens de entrada
                </p>

                <p class="mt-2 text-2xl font-black tracking-tight text-slate-900 dark:text-slate-100">
                    {{ formatarNumero(entrada) }}
                </p>

                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                    {{ formatarPercentual(percentual(entrada)) }} do total
                </p>

                <div class="mt-3 h-2 overflow-hidden rounded-full bg-blue-100 dark:bg-blue-950/80">
                    <div
                        class="h-full rounded-full bg-blue-500 transition-all duration-500 dark:bg-blue-400"
                        :style="{ width: `${percentual(entrada)}%` }"
                    />
                </div>
            </div>

            <!-- Tokens de saída -->
            <div class="rounded-xl border border-purple-100 bg-purple-50/50 p-4 dark:border-purple-900/40 dark:bg-purple-950/20">
                <p class="text-xs font-semibold uppercase tracking-wider text-purple-700 dark:text-purple-400">
                    Tokens de saída
                </p>

                <p class="mt-2 text-2xl font-black tracking-tight text-slate-900 dark:text-slate-100">
                    {{ formatarNumero(saida) }}
                </p>

                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                    {{ formatarPercentual(percentual(saida)) }} do total
                </p>

                <div class="mt-3 h-2 overflow-hidden rounded-full bg-purple-100 dark:bg-purple-950/80">
                    <div
                        class="h-full rounded-full bg-purple-500 transition-all duration-500 dark:bg-purple-400"
                        :style="{ width: `${percentual(saida)}%` }"
                    />
                </div>
            </div>

            <!-- Tokens de raciocínio -->
            <div class="rounded-xl border border-amber-100 bg-amber-50/50 p-4 dark:border-amber-900/40 dark:bg-amber-950/20">
                <p class="text-xs font-semibold uppercase tracking-wider text-amber-800 dark:text-amber-400">
                    Tokens de raciocínio
                </p>

                <p class="mt-2 text-2xl font-black tracking-tight text-slate-900 dark:text-slate-100">
                    {{ formatarNumero(pensamento) }}
                </p>

                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                    {{ formatarPercentual(percentual(pensamento)) }} do total
                </p>

                <div class="mt-3 h-2 overflow-hidden rounded-full bg-amber-100 dark:bg-amber-950/80">
                    <div
                        class="h-full rounded-full bg-amber-500 transition-all duration-500 dark:bg-amber-400"
                        :style="{ width: `${percentual(pensamento)}%` }"
                    />
                </div>
            </div>
        </div>

        <!-- TOKENS DE CACHE -->
        <div class="mt-4 flex flex-col gap-2 rounded-xl border border-slate-100 bg-slate-50/50 p-4 dark:border-slate-800/80 dark:bg-slate-800/40 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold text-slate-800 dark:text-slate-200">
                    Tokens de cache
                </p>

                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                    Tokens de entrada associados ao cache, conforme registrado pela API.
                </p>
            </div>

            <span class="font-mono text-base font-bold text-slate-900 dark:text-slate-100">
                {{ formatarNumero(cache) }}
            </span>
        </div>

        <!-- TOTAL DE TOKENS -->
        <div class="mt-3 flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/80 p-4 dark:border-slate-800/80 dark:bg-slate-800/60">
            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">
                Total de tokens
            </span>

            <span class="font-mono text-lg font-black text-slate-900 dark:text-slate-100">
                {{ formatarNumero(total) }}
            </span>
        </div>
    </section>
</template>
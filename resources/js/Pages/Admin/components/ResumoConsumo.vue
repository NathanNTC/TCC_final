<script setup>
import { computed } from 'vue'
import {
    formatarMoeda,
    formatarNumero,
    formatarDuracao,
} from './formatadoresCustos.js'

const props = defineProps({
    consumoGemini: {
        type: Object,
        default: () => ({}),
    },
})

const geral = computed(() => props.consumoGemini?.geral ?? {})

const chamadas = computed(() => geral.value.chamadas ?? 0)

const tokens = computed(() => geral.value.tokens_total ?? 0)

const custo = computed(() => geral.value.custo_brl)

const tempoMedio = computed(() => geral.value.tempo_medio_ms)

const custoDisponivel = computed(() => geral.value.custos_disponiveis === true)
</script>

<template>
    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <!-- TOTAL DE CHAMADAS -->
        <article class="flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all hover:border-slate-300 hover:shadow-xs dark:border-slate-800 dark:bg-slate-900 dark:hover:border-slate-700">
            <div>
                <div class="flex items-start justify-between gap-3">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        Total de chamadas
                    </p>

                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-700 ring-1 ring-inset ring-slate-500/10 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700">
                        API
                    </span>
                </div>

                <p class="mt-3 font-mono text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">
                    {{ formatarNumero(chamadas) }}
                </p>
            </div>

            <p class="mt-4 border-t border-slate-100 pt-3 text-xs leading-relaxed text-slate-500 dark:border-slate-800 dark:text-slate-400">
                Chamadas registradas
            </p>
        </article>

        <!-- TOKENS CONSUMIDOS -->
        <article class="flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all hover:border-slate-300 hover:shadow-xs dark:border-slate-800 dark:bg-slate-900 dark:hover:border-slate-700">
            <div>
                <div class="flex items-start justify-between gap-3">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        Tokens consumidos
                    </p>

                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-700 ring-1 ring-inset ring-slate-500/10 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700">
                        Volume
                    </span>
                </div>

                <p class="mt-3 font-mono text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">
                    {{ formatarNumero(tokens) }}
                </p>
            </div>

            <p class="mt-4 border-t border-slate-100 pt-3 text-xs leading-relaxed text-slate-500 dark:border-slate-800 dark:text-slate-400">
                Total informado pela API
            </p>
        </article>

        <!-- CUSTO ACUMULADO -->
        <article class="flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all hover:border-slate-300 hover:shadow-xs dark:border-slate-800 dark:bg-slate-900 dark:hover:border-slate-700">
            <div>
                <div class="flex items-start justify-between gap-3">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        Custo acumulado
                    </p>

                    <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-800 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-950/50 dark:text-emerald-400 dark:ring-emerald-500/30">
                        Estimado
                    </span>
                </div>

                <p class="mt-3 font-mono text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">
                    {{ custoDisponivel && custo !== null ? formatarMoeda(custo) : 'Não disponível' }}
                </p>
            </div>

            <p class="mt-4 border-t border-slate-100 pt-3 text-xs leading-relaxed text-slate-500 dark:border-slate-800 dark:text-slate-400">
                Custo estimado em reais
            </p>
        </article>

        <!-- TEMPO MÉDIO -->
        <article class="flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all hover:border-slate-300 hover:shadow-xs dark:border-slate-800 dark:bg-slate-900 dark:hover:border-slate-700">
            <div>
                <div class="flex items-start justify-between gap-3">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        Tempo médio
                    </p>

                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-700 ring-1 ring-inset ring-slate-500/10 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700">
                        Latência
                    </span>
                </div>

                <p class="mt-3 font-mono text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">
                    {{ tempoMedio !== null && tempoMedio !== undefined
                        ? formatarDuracao(tempoMedio)
                        : '—' }}
                </p>
            </div>

            <p class="mt-4 border-t border-slate-100 pt-3 text-xs leading-relaxed text-slate-500 dark:border-slate-800 dark:text-slate-400">
                Tempo de processamento por chamada
            </p>
        </article>
    </section>
</template>
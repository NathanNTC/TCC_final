<script setup>
import { computed } from 'vue'
import { formatarMoeda, formatarNumero } from './formatadoresCustos.js'

const props = defineProps({
    consumoGemini: {
        type: Object,
        default: () => ({}),
    },
})

const periodo = computed(() =>
    props.consumoGemini?.ultimos_30_dias ?? {}
)
</script>

<template>
    <section class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs sm:p-8 dark:border-slate-800 dark:bg-slate-900">
        <!-- CABEÇALHO DA SEÇÃO -->
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 ring-1 ring-emerald-600/10 dark:bg-emerald-950/50 dark:text-emerald-400 dark:ring-emerald-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                    </div>

                    <h2 class="text-base font-bold text-slate-900 dark:text-slate-100">
                        Resumo dos últimos 30 dias
                    </h2>
                </div>

                <p class="mt-1 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                    Consumo e métricas consolidadas de uso nos últimos 30 dias.
                </p>
            </div>
        </div>

        <!-- GRID DE MÉTRICAS -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <!-- CHAMADAS -->
            <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4 dark:border-slate-800/80 dark:bg-slate-800/40">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        Chamadas
                    </p>
                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                        Total
                    </span>
                </div>

                <p class="mt-3 font-mono text-2xl font-extrabold text-slate-900 dark:text-slate-100">
                    {{ formatarNumero(periodo.chamadas ?? 0) }}
                </p>
            </div>

            <!-- CHAMADAS COM SUCESSO -->
            <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4 dark:border-slate-800/80 dark:bg-slate-800/40">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        Chamadas com sucesso
                    </p>
                    <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-0.5 text-[11px] font-semibold text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400">
                        Sucesso
                    </span>
                </div>

                <p class="mt-3 font-mono text-2xl font-extrabold text-slate-900 dark:text-slate-100">
                    {{ formatarNumero(periodo.chamadas_sucesso ?? 0) }}
                </p>
            </div>

            <!-- TOKENS UTILIZADOS -->
            <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4 dark:border-slate-800/80 dark:bg-slate-800/40">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        Tokens utilizados
                    </p>
                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                        Volume
                    </span>
                </div>

                <p class="mt-3 font-mono text-2xl font-extrabold text-slate-900 dark:text-slate-100">
                    {{ formatarNumero(periodo.tokens_total ?? 0) }}
                </p>
            </div>

            <!-- CUSTO ESTIMADO -->
            <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4 dark:border-slate-800/80 dark:bg-slate-800/40">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        Custo estimado
                    </p>
                    <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-0.5 text-[11px] font-semibold text-emerald-800 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-950/50 dark:text-emerald-400 dark:ring-emerald-500/30">
                        BRL
                    </span>
                </div>

                <p class="mt-3 font-mono text-2xl font-extrabold text-slate-900 dark:text-slate-100">
                    {{ periodo.custos_disponiveis && periodo.custo_brl !== null
                        ? formatarMoeda(periodo.custo_brl)
                        : 'Não disponível' }}
                </p>
            </div>
        </div>
    </section>
</template>
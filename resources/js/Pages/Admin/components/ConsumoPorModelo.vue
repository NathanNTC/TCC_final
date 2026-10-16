<script setup>
import { computed } from 'vue'
import { formatarMoeda, formatarNumero } from './formatadoresCustos.js'

const props = defineProps({
    consumoPorModelo: {
        type: Array,
        default: () => [],
    },
})

const modelos = computed(() => props.consumoPorModelo)
</script>

<template>
    <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs dark:border-slate-800 dark:bg-slate-900">
        
        <!-- CABEÇALHO -->
        <div class="border-b border-slate-100 p-6 dark:border-slate-800">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-slate-100">
                Consumo por modelo
            </h2>
            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                Quantidade de chamadas, tokens e custos discriminados por modelo.
            </p>
        </div>

        <!-- TABELA -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-xs dark:divide-slate-800">
                <thead class="bg-slate-50/70 text-left font-semibold uppercase tracking-wider text-slate-500 dark:bg-slate-800/50 dark:text-slate-400">
                    <tr>
                        <th class="px-6 py-3.5">Modelo</th>
                        <th class="px-6 py-3.5 text-right">Chamadas</th>
                        <th class="px-6 py-3.5 text-right">Tokens</th>
                        <th class="px-6 py-3.5 text-right">Custo (BRL)</th>
                        <th class="px-6 py-3.5 text-right">Custo (USD)</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <tr
                        v-for="(modelo, indice) in modelos"
                        :key="modelo.modelo ?? indice"
                        class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40"
                    >
                        <td class="whitespace-nowrap px-6 py-4 font-mono font-bold text-slate-800 dark:text-slate-200">
                            {{ modelo.modelo ?? 'Não informado' }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right font-mono text-slate-600 dark:text-slate-400">
                            {{ formatarNumero(modelo.chamadas ?? 0) }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right font-mono text-slate-600 dark:text-slate-400">
                            {{ formatarNumero(modelo.tokens_total ?? 0) }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right font-mono font-bold text-slate-900 dark:text-slate-100">
                            {{ modelo.custo_brl !== null && modelo.custo_brl !== undefined
                                ? formatarMoeda(modelo.custo_brl)
                                : '—' }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right font-mono text-slate-600 dark:text-slate-400">
                            {{ modelo.custo_usd !== null && modelo.custo_usd !== undefined
                                ? formatarMoeda(modelo.custo_usd, 'USD')
                                : '—' }}
                        </td>
                    </tr>

                    <!-- ESTADO VAZIO -->
                    <tr v-if="modelos.length === 0">
                        <td colspan="5" class="p-8 text-center text-xs text-slate-400 dark:text-slate-500">
                            Nenhum consumo por modelo encontrado.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>
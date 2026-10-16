<script setup>
import { computed, ref, watch } from 'vue'
import {
    formatarMoeda,
    formatarNumero,
    formatarData,
    formatarDuracao,
} from './formatadoresCustos.js'

const props = defineProps({
    registrosGemini: {
        type: Array,
        default: () => [],
    },
})

const porPagina = 10
const paginaAtual = ref(1)
const recolhido = ref(false)

const registros = computed(() => props.registrosGemini ?? [])

const totalPaginas = computed(() =>
    Math.max(1, Math.ceil(registros.value.length / porPagina))
)

const registrosPaginados = computed(() => {
    const inicio = (paginaAtual.value - 1) * porPagina

    return registros.value.slice(inicio, inicio + porPagina)
})

const primeiroRegistro = computed(() =>
    registros.value.length === 0
        ? 0
        : (paginaAtual.value - 1) * porPagina + 1
)

const ultimoRegistro = computed(() =>
    Math.min(paginaAtual.value * porPagina, registros.value.length)
)

watch(
    () => props.registrosGemini.length,
    () => {
        if (paginaAtual.value > totalPaginas.value) {
            paginaAtual.value = totalPaginas.value
        }
    }
)

function irParaPagina(pagina) {
    if (pagina >= 1 && pagina <= totalPaginas.value) {
        paginaAtual.value = pagina
    }
}

function statusLegivel(status) {
    if (status === 'sucesso') return 'Sucesso'
    if (status === 'erro' || status === 'falha') return 'Erro'

    return status || 'Não informado'
}

function classeStatus(status) {
    if (status === 'sucesso') {
        return 'bg-emerald-50 text-emerald-800 ring-emerald-600/20 dark:bg-emerald-950/50 dark:text-emerald-400 dark:ring-emerald-500/30'
    }

    if (status === 'erro' || status === 'falha') {
        return 'bg-rose-50 text-rose-700 ring-rose-600/20 dark:bg-rose-950/50 dark:text-rose-400 dark:ring-rose-500/30'
    }

    return 'bg-slate-100 text-slate-700 ring-slate-500/10 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700'
}
</script>

<template>
    <section
        class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs dark:border-slate-800 dark:bg-slate-900"
    >
        <!-- CABEÇALHO RECOLHÍVEL -->
        <button
            type="button"
            class="flex w-full items-center justify-between gap-4 p-6 text-left transition hover:bg-slate-50/70 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-indigo-500 dark:hover:bg-slate-800/40"
            :aria-expanded="!recolhido"
            @click="recolhido = !recolhido"
        >
            <div>
                <h2
                    class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-slate-100"
                >
                    Histórico de chamadas
                </h2>

                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                    Últimas chamadas registradas à API Gemini.
                    <span class="ml-1">
                        ({{ registros.length }} registros)
                    </span>
                </p>
            </div>

            <!-- SETA -->
            <svg
                class="h-5 w-5 shrink-0 text-slate-500 transition-transform duration-200 dark:text-slate-400"
                :class="{ 'rotate-180': !recolhido }"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m19 9-7 7-7-7"
                />
            </svg>
        </button>

        <!-- CONTEÚDO -->
        <div v-if="!recolhido">
            <div class="border-t border-slate-100 dark:border-slate-800">
                <!-- TABELA -->
                <div class="overflow-x-auto">
                    <table
                        class="min-w-full divide-y divide-slate-100 text-xs dark:divide-slate-800"
                    >
                        <thead
                            class="bg-slate-50/70 text-left font-semibold uppercase tracking-wider text-slate-500 dark:bg-slate-800/50 dark:text-slate-400"
                        >
                            <tr>
                                <th class="px-5 py-3.5">Data</th>
                                <th class="px-5 py-3.5">Modelo</th>
                                <th class="px-5 py-3.5 text-right">Entrada</th>
                                <th class="px-5 py-3.5 text-right">Saída</th>
                                <th class="px-5 py-3.5 text-right">Total</th>
                                <th class="px-5 py-3.5 text-right">Duração</th>
                                <th class="px-5 py-3.5 text-right">Custo (BRL)</th>
                                <th class="px-5 py-3.5 text-left">Status</th>
                            </tr>
                        </thead>

                        <tbody
                            class="divide-y divide-slate-100 dark:divide-slate-800"
                        >
                            <tr
                                v-for="registro in registrosPaginados"
                                :key="registro.id"
                                class="transition hover:bg-slate-50/50 dark:hover:bg-slate-800/40"
                            >
                                <td
                                    class="whitespace-nowrap px-5 py-4 text-slate-600 dark:text-slate-400"
                                >
                                    {{ formatarData(registro.created_at) }}
                                </td>

                                <td
                                    class="whitespace-nowrap px-5 py-4 font-mono font-bold text-slate-800 dark:text-slate-200"
                                >
                                    {{ registro.modelo ?? '—' }}
                                </td>

                                <td
                                    class="whitespace-nowrap px-5 py-4 text-right font-mono text-slate-600 dark:text-slate-400"
                                >
                                    {{ formatarNumero(registro.tokens_entrada) }}
                                </td>

                                <td
                                    class="whitespace-nowrap px-5 py-4 text-right font-mono text-slate-600 dark:text-slate-400"
                                >
                                    {{ formatarNumero(registro.tokens_saida) }}
                                </td>

                                <td
                                    class="whitespace-nowrap px-5 py-4 text-right font-mono text-slate-600 dark:text-slate-400"
                                >
                                    {{ formatarNumero(registro.tokens_total) }}
                                </td>

                                <td
                                    class="whitespace-nowrap px-5 py-4 text-right font-mono text-slate-600 dark:text-slate-400"
                                >
                                    {{
                                        registro.tempo_processamento_ms !== null
                                        && registro.tempo_processamento_ms !== undefined
                                            ? formatarDuracao(registro.tempo_processamento_ms)
                                            : '—'
                                    }}
                                </td>

                                <td
                                    class="whitespace-nowrap px-5 py-4 text-right font-mono font-bold text-slate-900 dark:text-slate-100"
                                >
                                    {{
                                        registro.custo_brl !== null
                                        && registro.custo_brl !== undefined
                                            ? formatarMoeda(registro.custo_brl)
                                            : '—'
                                    }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    <span
                                        :class="[
                                            'inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset',
                                            classeStatus(registro.status),
                                        ]"
                                    >
                                        {{ statusLegivel(registro.status) }}
                                    </span>
                                </td>
                            </tr>

                            <!-- ESTADO VAZIO -->
                            <tr v-if="registros.length === 0">
                                <td
                                    colspan="8"
                                    class="p-8 text-center text-xs text-slate-400 dark:text-slate-500"
                                >
                                    Nenhuma chamada registrada.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- PAGINAÇÃO -->
                <div
                    v-if="registros.length > porPagina"
                    class="flex flex-col gap-3 border-t border-slate-100 px-5 py-4 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between"
                >
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Exibindo
                        <span class="font-semibold text-slate-700 dark:text-slate-200">
                            {{ primeiroRegistro }}–{{ ultimoRegistro }}
                        </span>
                        de
                        <span class="font-semibold text-slate-700 dark:text-slate-200">
                            {{ registros.length }}
                        </span>
                        registros
                    </p>

                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                            :disabled="paginaAtual === 1"
                            @click="irParaPagina(paginaAtual - 1)"
                        >
                            Anterior
                        </button>

                        <span
                            class="whitespace-nowrap px-2 text-xs text-slate-600 dark:text-slate-400"
                        >
                            Página {{ paginaAtual }} de {{ totalPaginas }}
                        </span>

                        <button
                            type="button"
                            class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                            :disabled="paginaAtual === totalPaginas"
                            @click="irParaPagina(paginaAtual + 1)"
                        >
                            Próxima
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

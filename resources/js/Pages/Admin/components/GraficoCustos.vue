<script setup>
import { computed, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import {
    formatarMoeda,
} from './formatadoresCustos.js'

const props = defineProps({
    custosGrafico: {
        type: Array,
        default: () => [],
    },
    periodoCustosGrafico: {
        type: String,
        default: 'mes',
    },
})

const filtro = ref(
    ['dia', 'semana', 'mes', 'historico'].includes(
        props.periodoCustosGrafico
    )
        ? props.periodoCustosGrafico
        : 'personalizado'
)

const dataInicio = ref('')
const mesSelecionado = ref('')
const carregandoPeriodo = ref(false)

let versaoRequisicao = 0

const itensGrafico = computed(() => {
    return props.custosGrafico.map((item, indice) => {
        const data = item.periodo
            ? new Date(item.periodo)
            : null

        let legenda = item.periodo ?? ''

        if (data && !Number.isNaN(data.getTime())) {
            if (filtro.value === 'dia' || (
                filtro.value === 'personalizado' && dataInicio.value
            )) {
                legenda = data.toLocaleTimeString('pt-BR', {
                    hour: '2-digit',
                    minute: '2-digit',
                })
            } else if (
                filtro.value === 'historico' ||
                (filtro.value === 'personalizado' && mesSelecionado.value)
            ) {
                legenda = data.toLocaleDateString('pt-BR', {
                    month: 'short',
                    year: '2-digit',
                })
            } else {
                legenda = data.toLocaleDateString('pt-BR', {
                    day: '2-digit',
                    month: '2-digit',
                })
            }
        }

        return {
            identificador: `${item.periodo ?? 'periodo'}-${indice}`,
            legenda,
            valor: Number(item.custo_brl ?? 0),
            chamadas: Number(item.chamadas ?? 0),
        }
    })
})

const maiorCusto = computed(() => {
    return Math.max(
        0,
        ...itensGrafico.value.map(item => item.valor)
    )
})

const totalPeriodo = computed(() => {
    return itensGrafico.value.reduce(
        (total, item) => total + item.valor,
        0
    )
})

function alturaBarra(valor) {
    if (maiorCusto.value <= 0) return '0%'

    return `${Math.max(4, (valor / maiorCusto.value) * 100)}%`
}

function carregarPeriodo(parametros, filtroSelecionado) {
    const versaoAtual = ++versaoRequisicao

    filtro.value = filtroSelecionado
    carregandoPeriodo.value = true

    router.get(
        window.location.pathname,
        parametros,
        {
            only: ['custosGrafico', 'periodoCustosGrafico'],
            preserveScroll: true,
            preserveState: true,
            replace: true,

            onSuccess: () => {
                if (versaoAtual === versaoRequisicao) {
                    filtro.value = filtroSelecionado
                }
            },

            onFinish: () => {
                if (versaoAtual === versaoRequisicao) {
                    carregandoPeriodo.value = false
                }
            },
        }
    )
}

function aplicarFiltro(valor) {
    if (carregandoPeriodo.value) return

    if (valor === 'personalizado') {
        filtro.value = valor
        return
    }

    carregarPeriodo(
        { periodo_custos: valor },
        valor
    )
}

function aplicarDia() {
    if (!dataInicio.value || carregandoPeriodo.value) return

    carregarPeriodo(
        {
            periodo_custos: 'personalizado',
            data_custos: dataInicio.value,
        },
        'personalizado'
    )
}

function aplicarMes() {
    if (!mesSelecionado.value || carregandoPeriodo.value) return

    carregarPeriodo(
        {
            periodo_custos: 'personalizado',
            mes_custos: mesSelecionado.value,
        },
        'personalizado'
    )
}
</script>

<template>
    <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900">
        <!-- CABEÇALHO DA SEÇÃO -->
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
            <div>
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-slate-100">
                    Custos da API Gemini
                </h2>

                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                    Acompanhe a evolução do consumo e gastos por período.
                </p>
            </div>

            <div class="text-left sm:text-right">
                <p class="text-xs text-slate-400 dark:text-slate-500">
                    Total exibido
                </p>

                <p class="font-mono text-xl font-black text-slate-900 dark:text-slate-100">
                    {{ formatarMoeda(totalPeriodo) }}
                </p>
            </div>
        </div>

        <!-- FILTROS RÁPIDOS -->
        <div class="mt-5 flex flex-wrap gap-2">
            <button
                v-for="opcao in [
                    { valor: 'dia', texto: 'Últimas 24 horas' },
                    { valor: 'semana', texto: 'Semana' },
                    { valor: 'mes', texto: 'Últimos 30 dias' },
                    { valor: 'historico', texto: 'Histórico' },
                ]"
                :key="opcao.valor"
                type="button"
                :disabled="carregandoPeriodo"
                class="cursor-pointer rounded-xl border px-3.5 py-1.5 text-xs font-semibold transition disabled:cursor-not-allowed disabled:opacity-50"
                :class="filtro === opcao.valor
                    ? 'border-emerald-600 bg-emerald-600 text-white shadow-xs dark:border-emerald-500 dark:bg-emerald-600'
                    : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800'"
                @click="aplicarFiltro(opcao.valor)"
            >
                {{ opcao.texto }}
            </button>
        </div>

        <!-- SELEÇÃO PERSONALIZADA -->
        <div class="mt-4 grid grid-cols-1 gap-4 rounded-xl border border-slate-100 bg-slate-50/50 p-4 dark:border-slate-800/80 dark:bg-slate-800/40 sm:grid-cols-2">
            <div>
                <label
                    for="data-custos"
                    class="mb-1.5 block text-xs font-semibold text-slate-700 dark:text-slate-300"
                >
                    Consultar um dia específico
                </label>

                <div class="flex flex-wrap gap-2">
                    <input
                        id="data-custos"
                        v-model="dataInicio"
                        type="date"
                        :disabled="carregandoPeriodo"
                        class="min-w-0 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-emerald-500 [color-scheme:light] dark:[color-scheme:dark]"
                    />

                    <button
                        type="button"
                        class="cursor-pointer rounded-xl bg-emerald-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-xs transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-emerald-600 dark:hover:bg-emerald-500"
                        :disabled="!dataInicio || carregandoPeriodo"
                        @click="aplicarDia"
                    >
                        Aplicar dia
                    </button>
                </div>
            </div>

            <div>
                <label
                    for="mes-custos"
                    class="mb-1.5 block text-xs font-semibold text-slate-700 dark:text-slate-300"
                >
                    Consultar um mês específico
                </label>

                <div class="flex flex-wrap gap-2">
                    <input
                        id="mes-custos"
                        v-model="mesSelecionado"
                        type="month"
                        :disabled="carregandoPeriodo"
                        class="min-w-0 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-emerald-500 [color-scheme:light] dark:[color-scheme:dark]"
                    />

                    <button
                        type="button"
                        class="cursor-pointer rounded-xl border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
                        :disabled="!mesSelecionado || carregandoPeriodo"
                        @click="aplicarMes"
                    >
                        Aplicar mês
                    </button>
                </div>
            </div>
        </div>

        <!-- INDICADOR DE CARREGAMENTO -->
        <p
            v-if="carregandoPeriodo"
            class="mt-4 text-xs font-semibold text-emerald-600 dark:text-emerald-400"
            role="status"
        >
            Atualizando gráfico...
        </p>

        <!-- GRÁFICO DE BARRAS -->
        <div
            v-if="itensGrafico.length"
            class="mt-8"
        >
            <div
                class="flex h-64 items-end gap-2 overflow-x-auto border-b border-l border-slate-200 px-3 pt-6 dark:border-slate-800"
            >
                <div
                    v-for="item in itensGrafico"
                    :key="item.identificador"
                    class="group flex h-full min-w-8 flex-1 flex-col items-center justify-end"
                >
                    <!-- TOOLTIP VISÍVEL NO HOVER -->
                    <div
                        class="mb-2 hidden font-mono text-[11px] font-bold text-slate-700 group-hover:block dark:text-slate-300"
                    >
                        {{ formatarMoeda(item.valor) }}
                    </div>

                    <!-- BARRA DO GRÁFICO -->
                    <div
                        class="w-full max-w-12 rounded-t-md bg-emerald-600 transition-all duration-300 hover:bg-emerald-500 dark:bg-emerald-500 dark:hover:bg-emerald-400"
                        :style="{ height: alturaBarra(item.valor) }"
                        :title="`${item.legenda}: ${formatarMoeda(item.valor)} — ${item.chamadas} chamadas`"
                    ></div>

                    <!-- LEGENDA DA BARRA -->
                    <span
                        class="mt-2 max-w-16 truncate text-center text-[11px] font-medium text-slate-400 dark:text-slate-500"
                    >
                        {{ item.legenda }}
                    </span>
                </div>
            </div>
        </div>

        <!-- ESTADO VAZIO -->
        <div
            v-else
            class="mt-8 rounded-xl border border-dashed border-slate-200 p-8 text-center text-xs text-slate-400 dark:border-slate-800 dark:text-slate-500"
        >
            Não há dados de custos para o período selecionado.
        </div>
    </section>
</template>
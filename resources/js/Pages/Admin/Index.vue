<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useDarkMode } from '@/Composables/useDarkMode'

import IndicadoresAdmin from './components/IndicadoresAdmin.vue'
import ListaAvaliacoes from './components/ListaAvaliacoes.vue'
import QualidadeTriagens from './components/QualidadeTriagens.vue'
import GerenciarConvites from './components/GerenciarConvites.vue'
import Custos from './components/Custos.vue'

// Controle de modo escuro
const { isDark, alternarTema } = useDarkMode()

// Controle de logout
const logoutForm = useForm({})

function logout() {
    if (logoutForm.processing) return

    logoutForm.post('/logout')
}

// Controle da aba ativa
// Valores: todos, indicadores, avaliacoes, qualidade, custos, convites
const abaAtiva = ref('todos')

defineProps({
    filtroPerfil: {
        type: String,
        default: 'todos',
    },

    opcoesPerfil: {
        type: Array,
        default: () => [],
    },

    avaliacoes: {
        type: Array,
        default: () => [],
    },

    indicadores: {
        type: Object,
        default: () => ({}),
    },

    qualidade: {
        type: Object,
        default: () => ({
            media: null,
            total_avaliacoes: 0,
            distribuicao: [],
        }),
    },

    convites: {
        type: Array,
        default: () => [],
    },

    avaliadoresPorPerfil: {
        type: Array,
        default: () => [],
    },

    // Consumo agregado da API Gemini
    consumoGemini: {
        type: Object,
        default: () => ({
            geral: {},
            ultimos_30_dias: {},
        }),
    },

    // Consumo agrupado por modelo
    consumoPorModelo: {
        type: Array,
        default: () => [],
    },

    // Últimos registros de utilização da API
    registrosGemini: {
        type: Array,
        default: () => [],
    },

        // Dados do gráfico de custos
    custosGrafico: {
        type: Array,
        default: () => [],
    },

    periodoCustosGrafico: {
        type: String,
        default: 'mes',
    },
})
</script>

<template>
    <div class="min-h-screen bg-slate-50/50 px-4 py-8 sm:px-6 lg:px-8 dark:bg-slate-950">
        <div class="mx-auto max-w-7xl space-y-8">

            <!-- CABEÇALHO DO PAINEL -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-600/10 dark:bg-emerald-950/50 dark:text-emerald-400 dark:ring-emerald-500/20">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-5 w-5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z"
                                />
                            </svg>
                        </div>

                        <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl dark:text-slate-100">
                            Painel administrativo
                        </h1>
                    </div>

                    <p class="mt-1 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                        Acompanhe indicadores gerais, avaliações médicas,
                        qualidade, consumo da IA e gestão de convites.
                    </p>
                </div>

                <!-- STATUS, BOTÃO DE MODO ESCURO E BOTÃO DE LOGOUT -->
                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-600/20 dark:bg-emerald-950/50 dark:text-emerald-400 dark:ring-emerald-500/30">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Modo Administrador
                    </span>

                    <!-- BOTÃO ALTERNAR MODO ESCURO -->
                    <button
                        type="button"
                        @click="alternarTema"
                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 cursor-pointer"
                        :title="isDark ? 'Mudar para modo claro' : 'Mudar para modo escuro'"
                    >
                        <svg v-if="isDark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-amber-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>

                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                        </svg>
                    </button>

                    <button
                        type="button"
                        @click="logout"
                        :disabled="logoutForm.processing"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 shadow-sm transition hover:border-red-300 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:border-red-900/50 dark:bg-slate-900 dark:text-red-400 dark:hover:bg-red-950/30 cursor-pointer"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M16 17l5-5-5-5M21 12H9"
                            />
                        </svg>

                        {{ logoutForm.processing ? 'Saindo...' : 'Sair' }}
                    </button>
                </div>
            </div>

            <!-- NAVEGAÇÃO DE ABAS -->
            <div class="border-b border-slate-200 dark:border-slate-800">
                <nav
                    class="-mb-px flex space-x-6 overflow-x-auto pb-1"
                    aria-label="Abas de navegação"
                >
                    <!-- VISÃO GERAL -->
                    <button
                        type="button"
                        @click="abaAtiva = 'todos'"
                        :class="[
                            abaAtiva === 'todos'
                                ? 'border-emerald-600 text-emerald-600 dark:border-emerald-500 dark:text-emerald-400'
                                : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200',
                            'flex shrink-0 cursor-pointer items-center gap-2 border-b-2 px-1 pb-3 text-sm font-semibold transition-all'
                        ]"
                        :aria-current="abaAtiva === 'todos' ? 'page' : undefined"
                    >
                        Visão Geral
                    </button>

                    <!-- INDICADORES -->
                    <button
                        type="button"
                        @click="abaAtiva = 'indicadores'"
                        :class="[
                            abaAtiva === 'indicadores'
                                ? 'border-emerald-600 text-emerald-600 dark:border-emerald-500 dark:text-emerald-400'
                                : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200',
                            'flex shrink-0 cursor-pointer items-center gap-2 border-b-2 px-1 pb-3 text-sm font-semibold transition-all'
                        ]"
                        :aria-current="abaAtiva === 'indicadores' ? 'page' : undefined"
                    >
                        Indicadores
                    </button>

                    <!-- AVALIAÇÕES -->
                    <button
                        type="button"
                        @click="abaAtiva = 'avaliacoes'"
                        :class="[
                            abaAtiva === 'avaliacoes'
                                ? 'border-emerald-600 text-emerald-600 dark:border-emerald-500 dark:text-emerald-400'
                                : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200',
                            'flex shrink-0 cursor-pointer items-center gap-2 border-b-2 px-1 pb-3 text-sm font-semibold transition-all'
                        ]"
                        :aria-current="abaAtiva === 'avaliacoes' ? 'page' : undefined"
                    >
                        Avaliações

                        <span
                            v-if="avaliacoes.length"
                            class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-600 dark:bg-slate-800 dark:text-slate-300"
                        >
                            {{ avaliacoes.length }}
                        </span>
                    </button>

                    <!-- QUALIDADE -->
                    <button
                        type="button"
                        @click="abaAtiva = 'qualidade'"
                        :class="[
                            abaAtiva === 'qualidade'
                                ? 'border-emerald-600 text-emerald-600 dark:border-emerald-500 dark:text-emerald-400'
                                : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200',
                            'flex shrink-0 cursor-pointer items-center gap-2 border-b-2 px-1 pb-3 text-sm font-semibold transition-all'
                        ]"
                        :aria-current="abaAtiva === 'qualidade' ? 'page' : undefined"
                    >
                        Qualidade
                    </button>

                    <!-- CUSTOS DA IA -->
                    <button
                        type="button"
                        @click="abaAtiva = 'custos'"
                        :class="[
                            abaAtiva === 'custos'
                                ? 'border-emerald-600 text-emerald-600 dark:border-emerald-500 dark:text-emerald-400'
                                : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200',
                            'flex shrink-0 cursor-pointer items-center gap-2 border-b-2 px-1 pb-3 text-sm font-semibold transition-all'
                        ]"
                        :aria-current="abaAtiva === 'custos' ? 'page' : undefined"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 3v18m5-14H9a3 3 0 0 0 0 6h6a3 3 0 0 1 0 6H6"
                            />
                        </svg>

                        Custos IA
                    </button>

                    <!-- CONVITES -->
                    <button
                        type="button"
                        @click="abaAtiva = 'convites'"
                        :class="[
                            abaAtiva === 'convites'
                                ? 'border-emerald-600 text-emerald-600 dark:border-emerald-500 dark:text-emerald-400'
                                : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200',
                            'flex shrink-0 cursor-pointer items-center gap-2 border-b-2 px-1 pb-3 text-sm font-semibold transition-all'
                        ]"
                        :aria-current="abaAtiva === 'convites' ? 'page' : undefined"
                    >
                        Convites

                        <span
                            v-if="convites.length"
                            class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-600 dark:bg-slate-800 dark:text-slate-300"
                        >
                            {{ convites.length }}
                        </span>
                    </button>
                </nav>
            </div>

            <!-- CONTEÚDO DAS ABAS -->
            <div class="space-y-8">

                <!-- SEÇÃO: INDICADORES -->
                <section
                    v-if="abaAtiva === 'todos' || abaAtiva === 'indicadores'"
                >
                    <IndicadoresAdmin
                        :indicadores="indicadores"
                        :qualidade="qualidade"
                        :filtro-perfil="filtroPerfil"
                        :opcoes-perfil="opcoesPerfil"
                        :avaliadores-por-perfil="avaliadoresPorPerfil"
                    />
                </section>

                <!-- SEÇÃO: AVALIAÇÕES -->
                <section
                    v-if="abaAtiva === 'todos' || abaAtiva === 'avaliacoes'"
                >
                    <ListaAvaliacoes
                        :avaliacoes="avaliacoes"
                        :filtro-perfil="filtroPerfil"
                        :opcoes-perfil="opcoesPerfil"
                    />
                </section>

                <!-- SEÇÃO: QUALIDADE -->
                <section
                    v-if="abaAtiva === 'todos' || abaAtiva === 'qualidade'"
                >
                    <QualidadeTriagens
                        :qualidade="qualidade"
                    />
                </section>

                <!-- SEÇÃO: CUSTOS E CONSUMO DO GEMINI -->
                <section
                    v-if="abaAtiva === 'todos' || abaAtiva === 'custos'"
                >
                    <Custos
                        :consumo-gemini="consumoGemini"
                        :consumo-por-modelo="consumoPorModelo"
                        :registros-gemini="registrosGemini"
                        :custos-grafico="custosGrafico"
                        :periodo-custos-grafico="periodoCustosGrafico"
                    />
                </section>

                <!-- SEÇÃO: CONVITES -->
                <section
                    v-if="abaAtiva === 'todos' || abaAtiva === 'convites'"
                >
                    <GerenciarConvites
                        :convites="convites"
                    />
                </section>

            </div>
        </div>
    </div>
</template>
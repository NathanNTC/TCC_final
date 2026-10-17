
<script setup>
import { computed, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

import { useDarkMode } from '@/Composables/useDarkMode'

import IndicadoresAdmin from './components/IndicadoresAdmin.vue'
import ListaAvaliacoes from './components/ListaAvaliacoes.vue'
import QualidadeTriagens from './components/QualidadeTriagens.vue'
import GerenciarConvites from './components/GerenciarConvites.vue'
import Custos from './components/Custos.vue'
import EficienciaIA from './components/EficienciaIA.vue'

const props = defineProps({
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

    avaliadoresPorPerfil: {
        type: Array,
        default: () => [],
    },

    indicadores: {
        type: Object,
        default: () => ({
            triagens_concluidas: 0,
            triagens_avaliadas: 0,
            total_avaliacoes: 0,
            concordantes: 0,
            discordancias: 0,
            concordancia_percentual: 0,
        }),
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

    consumoGemini: {
        type: Object,
        default: () => ({
            geral: {},
            ultimos_30_dias: {},
        }),
    },

    consumoPorModelo: {
        type: Array,
        default: () => [],
    },

    registrosGemini: {
        type: Array,
        default: () => [],
    },

    custosGrafico: {
        type: Array,
        default: () => [],
    },

    periodoCustosGrafico: {
        type: String,
        default: 'mes',
    },

    eficienciaIA: {
        type: Object,
        default: () => ({
            total_avaliacoes: 0,
            media_geral: null,
            medias: [],
        }),
    },

    eficienciaPorPerfil: {
        type: Array,
        default: () => [],
    },

    comentariosEficiencia: {
        type: Array,
        default: () => [],
    },

    indicadoresPaciente: {
        type: Object,
        default: () => ({
            total_pacientes: 0,
            total_triagens: 0,
            triagens_concluidas: 0,
            percentual_concluidas: 0,
            total_feedbacks: 0,
            media_nota_pacientes: null,
            facilidade: [],
            clareza: [],
            triagens_por_status: [],
            triagens_por_prioridade: [],
        }),
    },

    indicadoresAvaliacoesMedicas: {
        type: Object,
        default: () => ({
            total_com_nota: 0,
            media_qualidade: null,
            comentarios: [],
        }),
    },
})

const { isDark, alternarTema } = useDarkMode()

const abaAtiva = ref('todos')

const logoutForm = useForm({})

const abas = [
    { id: 'todos', nome: 'Visão geral' },
    { id: 'indicadores', nome: 'Indicadores' },
    { id: 'avaliacoes', nome: 'Avaliações médicas' },
    { id: 'qualidade', nome: 'Qualidade' },
    { id: 'eficiencia', nome: 'Eficiência da IA' },
    { id: 'custos', nome: 'Custos da IA' },
    { id: 'convites', nome: 'Convites' },
]

const tituloAba = computed(() => {
    return abas.find((aba) => aba.id === abaAtiva.value)?.nome
        ?? 'Painel administrativo'
})

function logout() {
    logoutForm.post(route('logout'))
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-900 transition-colors dark:bg-slate-950 dark:text-slate-100">
        <!-- CABEÇALHO -->
        <header class="border-b border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
            <div class="mx-auto flex max-w-screen-2xl flex-wrap items-center justify-between gap-4 px-4 py-5 sm:px-6 lg:px-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-400">
                        Administração
                    </p>

                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Painel administrativo
                    </h1>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Indicadores, avaliações, eficiência e utilização da IA.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button
                        type="button"
                        @click="alternarTema"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                        :aria-label="isDark ? 'Ativar tema claro' : 'Ativar tema escuro'"
                    >
                        <span>{{ isDark ? '☀️' : '🌙' }}</span>
                        <span>{{ isDark ? 'Tema claro' : 'Tema escuro' }}</span>
                    </button>

                    <button
                        type="button"
                        @click="logout"
                        :disabled="logoutForm.processing"
                        class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        {{ logoutForm.processing ? 'Saindo...' : 'Sair' }}
                    </button>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-screen-2xl px-4 py-6 sm:px-6 lg:px-8">
            <!-- NAVEGAÇÃO ENTRE ABAS -->
            <nav
                aria-label="Seções do painel administrativo"
                class="mb-6 overflow-x-auto rounded-2xl border border-slate-200 bg-white p-2 shadow-sm dark:border-slate-800 dark:bg-slate-900"
            >
                <div class="flex min-w-max gap-2">
                    <button
                        v-for="aba in abas"
                        :key="aba.id"
                        type="button"
                        @click="abaAtiva = aba.id"
                        :aria-current="abaAtiva === aba.id ? 'page' : undefined"
                        :class="[
                            'rounded-xl px-4 py-2.5 text-sm font-semibold transition',
                            abaAtiva === aba.id
                                ? 'bg-emerald-600 text-white shadow-sm'
                                : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800',
                        ]"
                    >
                        {{ aba.nome }}
                    </button>
                </div>
            </nav>

            <!-- TÍTULO DA SEÇÃO -->
            <div class="mb-6">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                    {{ tituloAba }}
                </h2>
            </div>

            <!-- VISÃO GERAL -->
            <div v-if="abaAtiva === 'todos'" class="space-y-8">
                <IndicadoresAdmin
                    :indicadores="indicadores"
                    :avaliadores-por-perfil="avaliadoresPorPerfil"
                />

                <section>
                    <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">
                        Avaliações médicas recentes
                    </h3>

                    <ListaAvaliacoes
                        :avaliacoes="avaliacoes"
                        :filtro-perfil="filtroPerfil"
                        :opcoes-perfil="opcoesPerfil"
                    />
                </section>

                <section>
                    <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">
                        Qualidade das avaliações
                    </h3>

                    <QualidadeTriagens :qualidade="qualidade" />
                </section>

                <section>
                    <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">
                        Consumo e custos da IA
                    </h3>

                    <Custos
                        :consumo-gemini="consumoGemini"
                        :consumo-por-modelo="consumoPorModelo"
                        :registros-gemini="registrosGemini"
                        :custos-grafico="custosGrafico"
                        :periodo-custos-grafico="periodoCustosGrafico"
                    />
                </section>

                <section>
                    <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">
                        Gerenciamento de convites
                    </h3>

                    <GerenciarConvites :convites="convites" />
                </section>
            </div>

            <!-- INDICADORES -->
            <section v-else-if="abaAtiva === 'indicadores'">
                <IndicadoresAdmin
                    :indicadores="indicadores"
                    :avaliadores-por-perfil="avaliadoresPorPerfil"
                />
            </section>

            <!-- AVALIAÇÕES MÉDICAS -->
            <section v-else-if="abaAtiva === 'avaliacoes'">
                <ListaAvaliacoes
                    :avaliacoes="avaliacoes"
                    :filtro-perfil="filtroPerfil"
                    :opcoes-perfil="opcoesPerfil"
                />
            </section>

            <!-- QUALIDADE DAS TRIAGENS -->
            <section v-else-if="abaAtiva === 'qualidade'">
                <QualidadeTriagens :qualidade="qualidade" />
            </section>

            <!-- EFICIÊNCIA DA IA -->
            <section v-else-if="abaAtiva === 'eficiencia'">
                <EficienciaIA
                    :eficiencia-i-a="eficienciaIA"
                    :eficiencia-por-perfil="eficienciaPorPerfil"
                    :comentarios-eficiencia="comentariosEficiencia"
                    :indicadores-paciente="indicadoresPaciente"
                />

                <!-- AVALIAÇÃO PROFISSIONAL DAS TRIAGENS -->
                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                            Avaliações médicas com nota
                        </p>

                        <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">
                            {{ indicadoresAvaliacoesMedicas.total_com_nota }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                            Nota média de qualidade das triagens
                        </p>

                        <p class="mt-3 text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                            {{
                                indicadoresAvaliacoesMedicas.media_qualidade !== null
                                    ? `${Number(indicadoresAvaliacoesMedicas.media_qualidade).toFixed(2)} / 5`
                                    : '—'
                            }}
                        </p>
                    </div>
                </div>
            </section>

            <!-- CUSTOS DA IA -->
            <section v-else-if="abaAtiva === 'custos'">
                <Custos
                    :consumo-gemini="consumoGemini"
                    :consumo-por-modelo="consumoPorModelo"
                    :registros-gemini="registrosGemini"
                    :custos-grafico="custosGrafico"
                    :periodo-custos-grafico="periodoCustosGrafico"
                />
            </section>

            <!-- CONVITES -->
            <section v-else-if="abaAtiva === 'convites'">
                <GerenciarConvites :convites="convites" />
            </section>
        </main>
    </div>
</template>

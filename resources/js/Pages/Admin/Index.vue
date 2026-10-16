<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

import IndicadoresAdmin from './components/IndicadoresAdmin.vue'
import ListaAvaliacoes from './components/ListaAvaliacoes.vue'
import QualidadeTriagens from './components/QualidadeTriagens.vue'
import GerenciarConvites from './components/GerenciarConvites.vue'

// Controle de logout
const logoutForm = useForm({})

function logout() {
    if (logoutForm.processing) return

    logoutForm.post('/logout')
}

// Controle de Aba Ativa ('todos', 'indicadores', 'avaliacoes', 'qualidade', 'convites')
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
            media: 0,
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
})
</script>

<template>
    <div class="min-h-screen bg-slate-50/50 px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl space-y-8">

            <!-- CABEÇALHO DO PAINEL -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-600/10">
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

                        <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                            Painel administrativo
                        </h1>
                    </div>

                    <p class="mt-1 text-sm leading-relaxed text-slate-500">
                        Acompanhe indicadores gerais, avaliações médicas, métricas de qualidade e gestão de convites.
                    </p>
                </div>

                <!-- STATUS E BOTÃO DE LOGOUT -->
                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-600/20">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Modo Administrador
                    </span>

                    <button
                        type="button"
                        @click="logout"
                        :disabled="logoutForm.processing"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 shadow-sm transition hover:border-red-300 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
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

            <!-- BARRA DE NAVEGAÇÃO / NAVEGAÇÃO DE ABAS -->
            <div class="border-b border-slate-200">
                <nav
                    class="-mb-px flex space-x-6 overflow-x-auto pb-1"
                    aria-label="Abas de navegação"
                >
                    <button
                        type="button"
                        @click="abaAtiva = 'todos'"
                        :class="[
                            abaAtiva === 'todos'
                                ? 'border-emerald-600 text-emerald-600'
                                : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700',
                            'flex shrink-0 items-center gap-2 border-b-2 px-1 pb-3 text-sm font-semibold transition-all cursor-pointer'
                        ]"
                    >
                        Visão Geral
                    </button>

                    <button
                        type="button"
                        @click="abaAtiva = 'indicadores'"
                        :class="[
                            abaAtiva === 'indicadores'
                                ? 'border-emerald-600 text-emerald-600'
                                : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700',
                            'flex shrink-0 items-center gap-2 border-b-2 px-1 pb-3 text-sm font-semibold transition-all cursor-pointer'
                        ]"
                    >
                        Indicadores
                    </button>

                    <button
                        type="button"
                        @click="abaAtiva = 'avaliacoes'"
                        :class="[
                            abaAtiva === 'avaliacoes'
                                ? 'border-emerald-600 text-emerald-600'
                                : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700',
                            'flex shrink-0 items-center gap-2 border-b-2 px-1 pb-3 text-sm font-semibold transition-all cursor-pointer'
                        ]"
                    >
                        Avaliações

                        <span
                            v-if="avaliacoes?.length"
                            class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-600"
                        >
                            {{ avaliacoes.length }}
                        </span>
                    </button>

                    <button
                        type="button"
                        @click="abaAtiva = 'qualidade'"
                        :class="[
                            abaAtiva === 'qualidade'
                                ? 'border-emerald-600 text-emerald-600'
                                : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700',
                            'flex shrink-0 items-center gap-2 border-b-2 px-1 pb-3 text-sm font-semibold transition-all cursor-pointer'
                        ]"
                    >
                        Qualidade
                    </button>

                    <button
                        type="button"
                        @click="abaAtiva = 'convites'"
                        :class="[
                            abaAtiva === 'convites'
                                ? 'border-emerald-600 text-emerald-600'
                                : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700',
                            'flex shrink-0 items-center gap-2 border-b-2 px-1 pb-3 text-sm font-semibold transition-all cursor-pointer'
                        ]"
                    >
                        Convites

                        <span
                            v-if="convites?.length"
                            class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-600"
                        >
                            {{ convites.length }}
                        </span>
                    </button>
                </nav>
            </div>

            <!-- CONTEÚDO DOS COMPONENTES -->
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

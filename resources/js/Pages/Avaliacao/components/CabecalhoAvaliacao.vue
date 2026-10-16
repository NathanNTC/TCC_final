
<script setup>
import { ref } from 'vue'

defineProps({
    total: {
        type: Number,
        default: 0,
    },
    darkMode: {
        type: Boolean,
        default: false,
    },
    logoutProcessing: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits([
    'alternar-tema',
    'logout',
])

const recolhido = ref(false)
</script>

<template>
    <header
        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-900 sm:p-6"
    >
        <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">
                    Avaliação de triagens
                </h1>

                <p
                    v-if="!recolhido"
                    class="mt-2 max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-400"
                >
                    Analise as triagens concluídas pela IA e registre sua
                    avaliação médica independente.
                </p>
            </div>

            <button
                type="button"
                class="inline-flex shrink-0 items-center justify-center rounded-lg border border-slate-200 p-2.5 text-slate-600 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                :aria-label="recolhido ? 'Expandir cabeçalho' : 'Recolher cabeçalho'"
                :title="recolhido ? 'Expandir cabeçalho' : 'Recolher cabeçalho'"
                @click="recolhido = !recolhido"
            >
                <svg
                    class="h-5 w-5 transition-transform"
                    :class="{ 'rotate-180': recolhido }"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m6 9 6 6 6-6"
                    />
                </svg>
            </button>
        </div>

        <div
            v-if="!recolhido"
            class="mt-5 flex flex-col gap-4 border-t border-slate-100 pt-5 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between"
        >
            <div
                class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/70 p-3 dark:border-emerald-900/70 dark:bg-emerald-950/30"
            >
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300"
                >
                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m16 0v-2a4 4 0 0 0-3-3.87M14 3.13a4 4 0 0 1 0 7.75M14 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"
                        />
                    </svg>
                </div>

                <div>
                    <p class="text-xs font-medium text-emerald-800 dark:text-emerald-300">
                        Disponíveis para avaliação
                    </p>

                    <p class="text-xl font-bold text-emerald-950 dark:text-emerald-100">
                        {{ total }}
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
                    :aria-label="darkMode ? 'Ativar tema claro' : 'Ativar tema escuro'"
                    :title="darkMode ? 'Ativar tema claro' : 'Ativar tema escuro'"
                    @click="emit('alternar-tema')"
                >
                    <svg
                        v-if="darkMode"
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <circle cx="12" cy="12" r="4" />
                        <path
                            stroke-linecap="round"
                            d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42"
                        />
                    </svg>

                    <svg
                        v-else
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M20.9 13A9 9 0 0 1 11 3.1 9 9 0 1 0 20.9 13Z"
                        />
                    </svg>

                    <span>{{ darkMode ? 'Tema claro' : 'Tema escuro' }}</span>
                </button>

                <button
                    type="button"
                    :disabled="logoutProcessing"
                    class="inline-flex items-center gap-2 rounded-lg border border-rose-200 bg-white px-3.5 py-2.5 text-sm font-medium text-rose-700 transition hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-60 focus:outline-none focus:ring-2 focus:ring-rose-500 dark:border-rose-900 dark:bg-slate-900 dark:text-rose-300 dark:hover:bg-rose-950/40"
                    @click="emit('logout')"
                >
                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M10 17l5-5-5-5m5 5H3m9-9h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"
                        />
                    </svg>

                    {{ logoutProcessing ? 'Saindo...' : 'Sair' }}
                </button>
            </div>
        </div>
    </header>
</template>

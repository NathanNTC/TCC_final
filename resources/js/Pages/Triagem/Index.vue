<script setup>
import { Head, Link } from '@inertiajs/vue3'

defineProps({
    triagens: {
        type: Array,
        default: () => [],
    },
})

function formatarData(data) {
    if (!data) {
        return '—'
    }

    return new Date(data).toLocaleString('pt-BR', {
        dateStyle: 'medium',
        timeStyle: 'short',
    })
}

function formatarPrioridade(prioridade) {
    const prioridades = {
        emergencial: {
            label: 'Emergencial',
            badgeClass: 'bg-rose-100 text-rose-800 ring-rose-600/20',
        },

        nao_emergencial: {
            label: 'Não emergencial',
            badgeClass: 'bg-amber-100 text-amber-800 ring-amber-600/20',
        },

        autocuidado: {
            label: 'Autocuidado',
            badgeClass: 'bg-emerald-100 text-emerald-800 ring-emerald-600/20',
        },
    }

    return prioridades[prioridade] ?? {
        label: prioridade,
        badgeClass: 'bg-slate-100 text-slate-700 ring-slate-500/10',
    }
}
</script>

<template>
    <Head title="Minhas Triagens" />

    <div class="min-h-screen bg-slate-50/50 px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl">

            <!-- CABEÇALHO -->
            <div class="mb-8">

                <!-- NAVEGAÇÃO -->
                <div class="mb-5 flex flex-wrap items-center gap-3">

                    <!-- VOLTAR PARA DASHBOARD -->
                    <Link
                        href="/dashboard"
                        class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-600 shadow-sm transition-all hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="h-4 w-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"
                            />
                        </svg>

                        <span>Dashboard</span>
                    </Link>

                </div>

                <!-- TÍTULO + BOTÃO -->
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                            Minhas triagens
                        </h1>

                        <p class="mt-1 text-sm font-medium text-slate-500">
                            Consulte suas triagens anteriores ou inicie uma nova triagem.
                        </p>
                    </div>

                    <!-- NOVA TRIAGEM -->
                    <Link
                        href="/triagens/nova"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-emerald-600/20 transition-all hover:bg-emerald-700 hover:shadow-lg active:scale-95"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2.5"
                            stroke="currentColor"
                            class="h-4 w-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 4.5v15m7.5-7.5h-15"
                            />
                        </svg>

                        <span>Nova triagem</span>
                    </Link>

                </div>
            </div>

            <!-- ESTADO VAZIO -->
            <div
                v-if="triagens.length === 0"
                class="rounded-2xl border border-slate-200/80 bg-white p-10 text-center shadow-sm"
            >

                <div
                    class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-600/10"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-8 w-8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z"
                        />
                    </svg>
                </div>

                <h2 class="text-lg font-bold text-slate-900">
                    Nenhuma triagem registrada
                </h2>

                <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">
                    Você ainda não possui nenhuma triagem no histórico.
                    Inicie uma nova para começar.
                </p>

                <!-- PRIMEIRA TRIAGEM -->
                <Link
                    href="/triagens/nova"
                    class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-emerald-600/20 transition-all hover:bg-emerald-700 hover:shadow-lg active:scale-95"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        class="h-4 w-4"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 4.5v15m7.5-7.5h-15"
                        />
                    </svg>

                    <span>Iniciar primeira triagem</span>
                </Link>

            </div>

            <!-- LISTA DE TRIAGENS -->
            <div
                v-else
                class="space-y-4"
            >

                <div
                    v-for="triagem in triagens"
                    :key="triagem.id"
                    class="group relative rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition-all duration-200 hover:border-emerald-300 hover:shadow-md"
                >

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                        <!-- INFORMAÇÕES -->
                        <div class="space-y-2">

                            <div class="flex flex-wrap items-center gap-2.5">

                                <h2 class="text-base font-bold text-slate-900">
                                    Triagem #{{ triagem.id }}
                                </h2>

                                <!-- STATUS -->
                                <span
                                    class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset"
                                    :class="{
                                        'bg-amber-50 text-amber-800 ring-amber-600/20':
                                            triagem.status === 'em_andamento',

                                        'bg-emerald-50 text-emerald-800 ring-emerald-600/20':
                                            triagem.status === 'concluida',

                                        'bg-rose-50 text-rose-800 ring-rose-600/20':
                                            triagem.status === 'cancelada',
                                    }"
                                >
                                    {{
                                        triagem.status === 'em_andamento'
                                            ? 'Em andamento'
                                            : triagem.status === 'concluida'
                                                ? 'Concluída'
                                                : triagem.status === 'cancelada'
                                                    ? 'Cancelada'
                                                    : triagem.status
                                    }}
                                </span>

                                <!-- PRIORIDADE IA -->
                                <span
                                    v-if="triagem.prioridade_ia"
                                    :class="[
                                        'inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset',
                                        formatarPrioridade(triagem.prioridade_ia).badgeClass
                                    ]"
                                >
                                    {{ formatarPrioridade(triagem.prioridade_ia).label }}
                                </span>

                            </div>

                            <!-- DATA -->
                            <p class="flex items-center gap-1.5 text-xs text-slate-400">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    class="h-3.5 w-3.5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                    />
                                </svg>

                                <span>
                                    Iniciada em: {{ formatarData(triagem.iniciada_em) }}
                                </span>

                            </p>

                        </div>

                        <!-- VISUALIZAR -->
                        <div class="flex items-center">

                            <Link
                                :href="`/triagens/${triagem.id}`"
                                class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 transition-all group-hover:border-emerald-200 group-hover:bg-emerald-50 group-hover:text-emerald-700 sm:w-auto"
                            >
                                <span>Visualizar</span>

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    class="h-4 w-4 transition-transform group-hover:translate-x-0.5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"
                                    />
                                </svg>
                            </Link>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</template>


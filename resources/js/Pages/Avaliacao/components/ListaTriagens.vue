
<script setup>
import { computed } from 'vue'

const props = defineProps({
    triagens: {
        type: Array,
        default: () => [],
    },
})

const emit = defineEmits(['selecionar'])

const lista = computed(() => props.triagens ?? [])

function formatarData(data) {
    if (!data) return 'Data não informada'

    const dataConvertida = new Date(data)

    if (Number.isNaN(dataConvertida.getTime())) {
        return 'Data não informada'
    }

    return new Intl.DateTimeFormat('pt-BR', {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(dataConvertida)
}

function badgePrioridadeClass(prioridade) {
    const classes = {
        emergencial:
            'bg-rose-100 text-rose-800 ring-rose-600/20 dark:bg-rose-950/60 dark:text-rose-300 dark:ring-rose-500/30',
        muito_urgente:
            'bg-rose-50 text-rose-700 ring-rose-500/20 dark:bg-rose-950/40 dark:text-rose-300 dark:ring-rose-500/30',
        urgente:
            'bg-amber-100 text-amber-800 ring-amber-600/20 dark:bg-amber-950/50 dark:text-amber-300 dark:ring-amber-500/30',
        pouco_urgente:
            'bg-amber-50 text-amber-700 ring-amber-500/20 dark:bg-amber-950/30 dark:text-amber-300 dark:ring-amber-500/30',
        nao_emergencial:
            'bg-emerald-50 text-emerald-800 ring-emerald-600/20 dark:bg-emerald-950/40 dark:text-emerald-300 dark:ring-emerald-500/30',
        autocuidado:
            'bg-emerald-100 text-emerald-800 ring-emerald-600/20 dark:bg-emerald-950/60 dark:text-emerald-300 dark:ring-emerald-500/30',
    }

    return classes[prioridade] ??
        'bg-slate-100 text-slate-700 ring-slate-500/20 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-600/30'
}

function rotuloPrioridade(prioridade) {
    const rotulos = {
        emergencial: 'Emergencial',
        muito_urgente: 'Muito urgente',
        urgente: 'Urgente',
        pouco_urgente: 'Pouco urgente',
        nao_emergencial: 'Não emergencial',
        autocuidado: 'Autocuidado',
    }

    return rotulos[prioridade] ?? 'Não informada'
}
</script>

<template>
    <section class="space-y-4">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                    Triagens pendentes
                </h2>

                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                    Selecione uma triagem para consultar os dados e registrar
                    sua avaliação.
                </p>
            </div>

            <span class="text-sm text-slate-500 dark:text-slate-400">
                {{ lista.length }}
                {{ lista.length === 1 ? 'triagem disponível' : 'triagens disponíveis' }}
            </span>
        </div>

        <div
            v-if="lista.length === 0"
            class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center dark:border-slate-700 dark:bg-slate-900"
        >
            <div
                class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300"
            >
                <svg
                    class="h-7 w-7"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.7"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m5 12 4 4L19 6"
                    />
                </svg>
            </div>

            <h3 class="mt-4 text-base font-semibold text-slate-900 dark:text-white">
                Todas as triagens foram avaliadas!
            </h3>

            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-600 dark:text-slate-400">
                Não existem triagens disponíveis para avaliação no momento.
                Volte mais tarde para conferir novas triagens.
            </p>
        </div>

        <div v-else class="space-y-3">
            <article
                v-for="triagem in lista"
                :key="triagem.id"
                class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-emerald-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900 dark:hover:border-emerald-800 sm:p-6"
            >
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="font-semibold text-slate-900 dark:text-white">
                                Triagem #{{ triagem.id }}
                            </h3>

                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset"
                                :class="badgePrioridadeClass(triagem.prioridade_ia)"
                            >
                                {{ rotuloPrioridade(triagem.prioridade_ia) }}
                            </span>
                        </div>

                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                            Finalizada em:
                            {{ formatarData(triagem.finalizada_em) }}
                        </p>

                        <div
                            v-if="triagem.resumo_ia"
                            class="mt-4 rounded-xl bg-slate-50 p-4 dark:bg-slate-800/70"
                        >
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                Resumo da IA
                            </p>

                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700 dark:text-slate-300">
                                {{ triagem.resumo_ia }}
                            </p>
                        </div>

                        <p
                            v-else
                            class="mt-4 text-sm italic text-slate-500 dark:text-slate-400"
                        >
                            Esta triagem não possui resumo disponível.
                        </p>
                    </div>

                    <button
                        type="button"
                        class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
                        @click="emit('selecionar', triagem)"
                    >
                        Analisar triagem

                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 12h14m-7-7 7 7-7 7"
                            />
                        </svg>
                    </button>
                </div>
            </article>
        </div>
    </section>
</template>

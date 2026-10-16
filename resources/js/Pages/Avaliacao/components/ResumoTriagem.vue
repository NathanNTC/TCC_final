
<script setup>
defineProps({
    triagem: {
        type: Object,
        required: true,
    },
})

const emit = defineEmits(['voltar'])

function formatarData(data) {
    if (!data) return 'Data não informada'

    const convertida = new Date(data)

    if (Number.isNaN(convertida.getTime())) {
        return 'Data não informada'
    }

    return new Intl.DateTimeFormat('pt-BR', {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(convertida)
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

function causasComoLista(causas) {
    if (Array.isArray(causas)) {
        return causas
    }

    if (typeof causas === 'string' && causas.trim()) {
        try {
            const resultado = JSON.parse(causas)

            return Array.isArray(resultado) ? resultado : [causas]
        } catch {
            return [causas]
        }
    }

    return []
}
</script>

<template>
    <section
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900"
    >
        <div class="flex flex-col gap-4 border-b border-slate-100 p-5 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between sm:p-6">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">
                    Avaliação independente
                </p>

                <h2 class="mt-1 text-xl font-bold text-slate-900 dark:text-white">
                    Triagem #{{ triagem.id }}
                </h2>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Finalizada em {{ formatarData(triagem.finalizada_em) }}
                </p>
            </div>

            <button
                type="button"
                class="inline-flex items-center justify-center gap-2 self-start rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700 sm:self-auto"
                @click="emit('voltar')"
            >
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
                        d="m15 18-6-6 6-6"
                    />
                </svg>

                Voltar à lista
            </button>
        </div>

        <div class="space-y-5 p-5 sm:p-6">
            <div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                    Prioridade atribuída pela IA
                </h3>

                <div class="mt-2">
                    <span
                        class="inline-flex items-center rounded-full px-3 py-1.5 text-sm font-semibold ring-1 ring-inset"
                        :class="badgePrioridadeClass(triagem.prioridade_ia)"
                    >
                        {{ rotuloPrioridade(triagem.prioridade_ia) }}
                    </span>
                </div>
            </div>

            <div
                v-if="causasComoLista(triagem.possiveis_causas_ia).length"
                class="border-t border-slate-100 pt-5 dark:border-slate-800"
            >
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                    Possíveis causas indicadas pela IA
                </h3>

                <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">
                    São possibilidades sugeridas pela IA, não diagnósticos
                    confirmados.
                </p>

                <div class="mt-3 flex flex-wrap gap-2">
                    <span
                        v-for="(causa, index) in causasComoLista(triagem.possiveis_causas_ia)"
                        :key="`${index}-${causa}`"
                        class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300"
                    >
                        {{ causa }}
                    </span>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-5 dark:border-slate-800">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                    Resumo clínico da IA
                </h3>

                <p
                    v-if="triagem.resumo_ia"
                    class="mt-2 whitespace-pre-line text-sm leading-7 text-slate-700 dark:text-slate-300"
                >
                    {{ triagem.resumo_ia }}
                </p>

                <p
                    v-else
                    class="mt-2 text-sm italic text-slate-500 dark:text-slate-400"
                >
                    Não foi disponibilizado um resumo para esta triagem.
                </p>
            </div>

            <div class="border-t border-slate-100 pt-5 dark:border-slate-800">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                    Orientação fornecida pela IA
                </h3>

                <p
                    v-if="triagem.orientacao_ia"
                    class="mt-2 whitespace-pre-line text-sm leading-7 text-slate-700 dark:text-slate-300"
                >
                    {{ triagem.orientacao_ia }}
                </p>

                <p
                    v-else
                    class="mt-2 text-sm italic text-slate-500 dark:text-slate-400"
                >
                    Não foi disponibilizada uma orientação para esta triagem.
                </p>
            </div>
        </div>
    </section>
</template>

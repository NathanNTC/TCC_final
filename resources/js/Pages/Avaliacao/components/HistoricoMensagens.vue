
<script setup>
import { computed } from 'vue'

const props = defineProps({
    mensagens: {
        type: Array,
        default: () => [],
    },
})

const lista = computed(() => props.mensagens ?? [])

function formatarData(data) {
    if (!data) return ''

    const convertida = new Date(data)

    if (Number.isNaN(convertida.getTime())) {
        return ''
    }

    return new Intl.DateTimeFormat('pt-BR', {
        dateStyle: 'short',
        timeStyle: 'short',
    }).format(convertida)
}

function ehPaciente(mensagem) {
    return mensagem?.remetente === 'paciente'
}
</script>

<template>
    <section
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900"
    >
        <div class="border-b border-slate-100 px-5 py-4 dark:border-slate-800 sm:px-6">
            <h2 class="font-semibold text-slate-900 dark:text-white">
                Histórico da conversa
            </h2>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Mensagens trocadas durante a triagem.
            </p>
        </div>

        <div
            v-if="lista.length === 0"
            class="px-5 py-10 text-center sm:px-6"
        >
            <svg
                class="mx-auto h-9 w-9 text-slate-400 dark:text-slate-500"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.6"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M8 10h8m-8 4h5m-8 7 3.5-3H18a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3H6a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h.5L4 21Z"
                />
            </svg>

            <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">
                Nenhuma mensagem encontrada nesta triagem.
            </p>
        </div>

        <div
            v-else
            class="max-h-[600px] space-y-5 overflow-y-auto bg-slate-50/70 p-4 dark:bg-slate-950/40 sm:p-6"
        >
            <div
                v-for="(msg, index) in lista"
                :key="msg.id ?? index"
                class="flex"
                :class="ehPaciente(msg) ? 'justify-end' : 'justify-start'"
            >
                <div
                    class="max-w-[90%] sm:max-w-[80%]"
                    :class="ehPaciente(msg) ? 'items-end' : 'items-start'"
                >
                    <p
                        class="mb-1.5 px-1 text-xs font-semibold"
                        :class="ehPaciente(msg) ? 'text-right text-emerald-700 dark:text-emerald-400' : 'text-left text-slate-500 dark:text-slate-400'"
                    >
                        {{ ehPaciente(msg) ? 'Paciente' : 'Assistente de IA' }}
                    </p>

                    <div
                        class="rounded-2xl px-4 py-3 shadow-sm"
                        :class="ehPaciente(msg)
                            ? 'rounded-tr-sm bg-emerald-600 text-white'
                            : 'rounded-tl-sm border border-slate-200 bg-white text-slate-800 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200'"
                    >
                        <p class="whitespace-pre-wrap break-words text-sm leading-6">
                            {{ msg.mensagem }}
                        </p>
                    </div>

                    <p
                        v-if="msg.created_at"
                        class="mt-1.5 px-1 text-[11px] text-slate-400 dark:text-slate-500"
                        :class="ehPaciente(msg) ? 'text-right' : 'text-left'"
                    >
                        {{ formatarData(msg.created_at) }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

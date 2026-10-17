<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, onMounted, nextTick } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    triagem: {
        type: Object,
        required: true,
    },
    feedbackEnviado: {
        type: Boolean,
        default: false,
    },
})

const mensagem = ref('')
const enviando = ref(false)
const chatContainer = ref(null)

function scrollToBottom() {
    nextTick(() => {
        if (chatContainer.value) {
            chatContainer.value.scrollTop =
                chatContainer.value.scrollHeight
        }
    })
}

onMounted(() => {
    scrollToBottom()
})

function formatarData(data) {
    if (!data) return '—'

    return new Date(data).toLocaleString('pt-BR', {
        dateStyle: 'medium',
        timeStyle: 'short',
    })
}

function formatarStatus(status) {
    const statusMap = {
        pendente: {
            label: 'Pendente',
            class: 'bg-slate-100 text-slate-800 ring-slate-500/20 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700',
        },
        em_andamento: {
            label: 'Em andamento',
            class: 'bg-amber-50 text-amber-800 ring-amber-600/20 dark:bg-amber-950/60 dark:text-amber-300 dark:ring-amber-500/30',
        },
        concluida: {
            label: 'Concluída',
            class: 'bg-emerald-50 text-emerald-800 ring-emerald-600/20 dark:bg-emerald-950/60 dark:text-emerald-300 dark:ring-emerald-500/30',
        },
        cancelada: {
            label: 'Cancelada',
            class: 'bg-rose-50 text-rose-800 ring-rose-600/20 dark:bg-rose-950/60 dark:text-rose-300 dark:ring-rose-500/30',
        },
    }

    return statusMap[status] ?? {
        label: status ?? '—',
        class: 'bg-slate-100 text-slate-700 ring-slate-500/10 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700',
    }
}

function formatarPrioridade(prioridade) {
    const prioridades = {
        emergencial: {
            label: 'Emergencial',
            badgeClass: 'bg-rose-100 text-rose-800 ring-rose-600/20 dark:bg-rose-950/60 dark:text-rose-300 dark:ring-rose-500/30',
        },
        nao_emergencial: {
            label: 'Não emergencial',
            badgeClass: 'bg-amber-100 text-amber-800 ring-amber-600/20 dark:bg-amber-950/60 dark:text-amber-300 dark:ring-amber-500/30',
        },
        autocuidado: {
            label: 'Autocuidado',
            badgeClass: 'bg-emerald-100 text-emerald-800 ring-emerald-600/20 dark:bg-emerald-950/60 dark:text-emerald-300 dark:ring-emerald-500/30',
        },
    }

    return prioridades[prioridade] ?? {
        label: prioridade ?? 'Não definida',
        badgeClass: 'bg-slate-100 text-slate-700 ring-slate-500/10 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700',
    }
}

function handleKeydown(event) {
    if (event.key === 'Enter' && (event.ctrlKey || event.metaKey)) {
        enviarMensagem()
    }
}

function enviarMensagem() {
    const texto = mensagem.value.trim()

    if (!texto || enviando.value) return

    enviando.value = true

    router.post(
        `/triagens/${props.triagem.id}/mensagens`,
        { mensagem: texto },
        {
            preserveScroll: true,

            onSuccess: () => {
                mensagem.value = ''
                scrollToBottom()
            },

            onError: (errors) => {
                console.error('Erro ao enviar mensagem:', errors)
            },

            onFinish: () => {
                enviando.value = false
            },
        }
    )
}
</script>

<template>
    <Head :title="`Triagem #${triagem.id}`" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-slate-50/50 px-4 py-8 transition-colors duration-200 sm:px-6 lg:px-8 dark:bg-slate-950">
            <div class="mx-auto max-w-4xl">

                

                <!-- CABEÇALHO -->
                <div class="mb-8">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <Link
                            href="/triagens"
                            class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 transition-colors hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="2.5"
                                stroke="currentColor"
                                class="h-4 w-4"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                            </svg>
                            Voltar para minhas triagens
                        </Link>

                        <Link
                            href="/dashboard"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-100"
                        >
                            Dashboard
                        </Link>
                    </div>

                    <div class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl dark:text-slate-100">
                                Triagem #{{ triagem.id }}
                            </h1>

                            <p class="mt-1 flex items-center gap-1.5 text-xs text-slate-400 dark:text-slate-500">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    class="h-3.5 w-3.5"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0" />
                                </svg>
                                <span>Iniciada em: {{ formatarData(triagem.iniciada_em) }}</span>
                            </p>
                        </div>

                        <!-- STATUS E PRIORIDADE -->
                        <div class="flex flex-wrap items-center gap-2">
                            <span
                                class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset"
                                :class="formatarStatus(triagem.status).class"
                            >
                                {{ formatarStatus(triagem.status).label }}
                            </span>

                            <span
                                v-if="triagem.prioridade_ia"
                                :class="[
                                    'inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset',
                                    formatarPrioridade(triagem.prioridade_ia).badgeClass,
                                ]"
                            >
                                {{ formatarPrioridade(triagem.prioridade_ia).label }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- CARD DA CONVERSA -->
                <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

                    <!-- TÍTULO DO CARD -->
                    <div class="border-b border-slate-100 px-6 py-5 sm:px-8 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-950/80 dark:text-emerald-300">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.8"
                                    stroke="currentColor"
                                    class="h-5 w-5"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 0-.75 0 .375.375 0 0 1 0 .75Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 0 .75 0 .375.375 0 0 1 0-.75Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a.75.75 0 0 1-.816-.816 6.008 6.008 0 0 1 1.057-2.836A7.476 7.476 0 0 1 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-slate-900 dark:text-slate-100">
                                    Conversa da triagem2
                                </h2>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    Responda às perguntas para fornecer as informações necessárias.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- ÁREA DE MENSAGENS -->
                    <div
                        ref="chatContainer"
                        class="max-h-[500px] space-y-4 overflow-y-auto p-6 sm:p-8"
                    >
                        <div
                            v-if="!triagem.mensagens || triagem.mensagens.length === 0"
                            class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center dark:border-slate-800 dark:bg-slate-950/50"
                        >
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.8"
                                    stroke="currentColor"
                                    class="h-6 w-6"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm4.125 0a.375.375 0 0 1 0 .75Zm0 0h-.375" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12c0 4.556-4.03 8.25-9 8.25s-9-3.694-9-8.25 4.03-8.25 9-8.25 9 3.694 9 8.25Z" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-sm font-bold text-slate-900 dark:text-slate-100">
                                Vamos começar
                            </h3>
                            <p class="mx-auto mt-1 max-w-md text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                                Descreva o principal sintoma ou motivo pelo qual você está realizando esta triagem.
                            </p>
                        </div>

                        <!-- LISTA DE MENSAGENS -->
                        <div
                            v-for="item in triagem.mensagens"
                            :key="item.id"
                            class="flex"
                            :class="item.remetente === 'paciente' ? 'justify-end' : 'justify-start'"
                        >
                            <div
                                class="max-w-[85%] rounded-2xl px-4 py-3 text-sm leading-relaxed"
                                :class="item.remetente === 'paciente'
                                    ? 'rounded-br-md bg-emerald-600 text-white dark:bg-emerald-600'
                                    : 'rounded-bl-md bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-200'"
                            >
                                <p class="whitespace-pre-wrap">{{ item.mensagem }}</p>
                                <p
                                    class="mt-1.5 text-[11px]"
                                    :class="item.remetente === 'paciente'
                                        ? 'text-emerald-100 dark:text-emerald-200'
                                        : 'text-slate-400 dark:text-slate-500'"
                                >
                                    {{ formatarData(item.enviada_em) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- CAMPO DE ENVIO OU AVISO DE FINALIZAÇÃO -->
                    <div
                        v-if="triagem.status === 'em_andamento'"
                        class="border-t border-slate-100 bg-slate-50/70 p-5 sm:p-6 dark:border-slate-800 dark:bg-slate-900/60"
                    >
                        <form
                            class="flex flex-col gap-3 sm:flex-row"
                            @submit.prevent="enviarMensagem"
                        >
                            <textarea
                                v-model="mensagem"
                                rows="3"
                                maxlength="5000"
                                placeholder="Digite sua resposta... (Ctrl + Enter para enviar)"
                                class="min-h-[90px] flex-1 resize-none rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-emerald-500 dark:focus:ring-emerald-500/20"
                                :disabled="enviando"
                                @keydown="handleKeydown"
                            />

                            <button
                                type="submit"
                                :disabled="enviando || !mensagem.trim()"
                                class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50 sm:self-end dark:bg-emerald-600 dark:hover:bg-emerald-500"
                            >
                                <svg
                                    v-if="enviando"
                                    class="h-4 w-4 animate-spin"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                </svg>
                                <span>
                                    {{ enviando ? 'Enviando...' : 'Enviar resposta' }}
                                </span>
                            </button>
                        </form>

                        <p class="mt-2 text-xs text-slate-400 dark:text-slate-500">
                            Não compartilhe informações que não sejam necessárias para esta triagem.
                        </p>
                    </div>

                    <div
                        v-else
                        class="border-t border-slate-100 bg-slate-50 px-6 py-5 text-center dark:border-slate-800 dark:bg-slate-950/40"
                    >
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">
                            Esta triagem não está mais em andamento.
                        </p>
                    </div>

                    <!-- FEEDBACK DA TRIAGEM CONCLUÍDA -->
                    <div
                        v-if="triagem.status === 'concluida'"
                        class="border-t border-slate-100 bg-white px-6 py-5 dark:border-slate-800 dark:bg-slate-900"
                    >
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">
                                    Como foi sua experiência?
                                </h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                    Seu feedback nos ajuda a melhorar o sistema.
                                </p>
                            </div>

                            <Link
                                v-if="!feedbackEnviado"
                                :href="`/triagens/${triagem.id}/feedback`"
                                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
                            >
                                <span aria-hidden="true">★</span>
                                Avaliar minha experiência
                            </Link>

                            <span
                                v-else
                                class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300"
                            >
                                <span aria-hidden="true">✓</span>
                                Feedback enviado
                            </span>
                        </div>
                    </div>
                </div>

                <!-- RESULTADO DA IA -->
                <div
                    v-if="triagem.prioridade_ia || triagem.resumo_ia || triagem.orientacao_ia"
                    class="mt-6 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm sm:p-8 dark:border-slate-800 dark:bg-slate-900"
                >
                    <h2 class="text-base font-bold text-slate-900 dark:text-slate-100">
                        Resultado da triagem
                    </h2>

                    <div v-if="triagem.prioridade_ia" class="mt-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                            Prioridade
                        </p>
                        <span
                            :class="[
                                'mt-2 inline-flex items-center rounded-lg px-3 py-1.5 text-sm font-bold ring-1 ring-inset',
                                formatarPrioridade(triagem.prioridade_ia).badgeClass,
                            ]"
                        >
                            {{ formatarPrioridade(triagem.prioridade_ia).label }}
                        </span>
                    </div>

                    <div v-if="triagem.resumo_ia" class="mt-6">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">
                            Resumo
                        </h3>
                        <p class="mt-2 whitespace-pre-wrap text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                            {{ triagem.resumo_ia }}
                        </p>
                    </div>

                    <div v-if="triagem.orientacao_ia" class="mt-6">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">
                            Orientação
                        </h3>
                        <p class="mt-2 whitespace-pre-wrap text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                            {{ triagem.orientacao_ia }}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

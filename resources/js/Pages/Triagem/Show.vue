<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, nextTick } from 'vue'

const props = defineProps({
    triagem: {
        type: Object,
        required: true,
    },
})

const mensagem = ref('')
const enviando = ref(false)
const chatContainer = ref(null)

function formatarData(data) {
    if (!data) {
        return '—'
    }

    return new Date(data).toLocaleString('pt-BR', {
        dateStyle: 'short',
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

function rolarParaBaixo() {
    nextTick(() => {
        if (chatContainer.value) {
            chatContainer.value.scrollTop = chatContainer.value.scrollHeight
        }
    })
}

function enviarMensagem() {
    const texto = mensagem.value.trim()

    if (!texto || enviando.value) {
        return
    }

    enviando.value = true

    router.post(
        `/triagens/${props.triagem.id}/mensagens`,
        {
            mensagem: texto,
        },
        {
            preserveScroll: true,

            onSuccess: () => {
                mensagem.value = ''
                rolarParaBaixo()
            },

            onFinish: () => {
                enviando.value = false
            },
        }
    )
}

function enviarComEnter(event) {
    if (event.shiftKey) {
        return
    }

    event.preventDefault()

    enviarMensagem()
}

rolarParaBaixo()
</script>

<template>
    <Head :title="`Triagem #${triagem.id}`" />

    <div class="min-h-screen bg-slate-50 px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">

            <!-- CABEÇALHO -->
            <div class="mb-6">
                <div class="flex flex-wrap items-center justify-between gap-3">

                    <Link
                        href="/triagens"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 transition hover:text-emerald-700"
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
                                d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"
                            />
                        </svg>

                        Minhas triagens
                    </Link>

                    <span
                        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset"
                        :class="{
                            'bg-amber-50 text-amber-800 ring-amber-600/20':
                                triagem.status === 'em_andamento',

                            'bg-emerald-50 text-emerald-800 ring-emerald-600/20':
                                triagem.status === 'concluida',

                            'bg-rose-50 text-rose-800 ring-rose-600/20':
                                triagem.status === 'cancelada',

                            'bg-slate-50 text-slate-700 ring-slate-500/10':
                                !['em_andamento', 'concluida', 'cancelada'].includes(
                                    triagem.status
                                ),
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
                </div>

                <div class="mt-4">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                        Triagem #{{ triagem.id }}
                    </h1>

                    <p class="mt-1 text-xs text-slate-400">
                        Iniciada em {{ formatarData(triagem.iniciada_em) }}
                    </p>
                </div>
            </div>

            <!-- CHAT -->
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <!-- CABEÇALHO DO CHAT -->
                <div class="border-b border-slate-100 bg-white px-5 py-4 sm:px-6">
                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700"
                        >
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
                                    d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a.75.75 0 0 1-.816-.816 6.008 6.008 0 0 1 1.057-2.836A7.476 7.476 0 0 1 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z"
                                />
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-sm font-bold text-slate-900">
                                Assistente de triagem
                            </h2>

                            <p class="text-xs text-slate-500">
                                Coleta de informações clínicas
                            </p>
                        </div>
                    </div>
                </div>

                <!-- MENSAGENS -->
                <div
                    ref="chatContainer"
                    class="h-[500px] space-y-4 overflow-y-auto bg-slate-50/70 p-4 sm:p-6"
                >

                    <!-- ESTADO INICIAL -->
                    <div
                        v-if="!triagem.mensagens || triagem.mensagens.length === 0"
                        class="flex h-full items-center justify-center"
                    >
                        <div class="max-w-md text-center">

                            <div
                                class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.8"
                                    stroke="currentColor"
                                    class="h-7 w-7"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25s-9 3.694-9 8.25"
                                    />
                                </svg>
                            </div>

                            <h3 class="mt-4 text-base font-bold text-slate-900">
                                Vamos começar
                            </h3>

                            <p class="mt-1 text-sm leading-relaxed text-slate-500">
                                Descreva o principal sintoma ou motivo que
                                levou você a iniciar esta triagem.
                            </p>
                        </div>
                    </div>

                    <!-- MENSAGENS -->
                    <template
                        v-for="item in triagem.mensagens"
                        :key="item.id"
                    >
                        <!-- MENSAGEM DA IA -->
                        <div
                            v-if="item.remetente === 'ia'"
                            class="flex justify-start"
                        >
                            <div class="max-w-[85%] sm:max-w-[75%]">

                                <div class="mb-1 ml-1 text-[11px] font-semibold text-slate-400">
                                    Assistente
                                </div>

                                <div
                                    class="rounded-2xl rounded-tl-md border border-slate-200 bg-white px-4 py-3 text-sm leading-relaxed text-slate-700 shadow-sm"
                                >
                                    {{ item.mensagem }}
                                </div>

                                <div class="mt-1 ml-1 text-[10px] text-slate-400">
                                    {{ formatarData(item.enviada_em) }}
                                </div>
                            </div>
                        </div>

                        <!-- MENSAGEM DO PACIENTE -->
                        <div
                            v-else
                            class="flex justify-end"
                        >
                            <div class="max-w-[85%] sm:max-w-[75%]">

                                <div class="mb-1 mr-1 text-right text-[11px] font-semibold text-slate-400">
                                    Você
                                </div>

                                <div
                                    class="rounded-2xl rounded-tr-md bg-emerald-600 px-4 py-3 text-sm leading-relaxed text-white shadow-sm"
                                >
                                    {{ item.mensagem }}
                                </div>

                                <div class="mt-1 mr-1 text-right text-[10px] text-slate-400">
                                    {{ formatarData(item.enviada_em) }}
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- INDICADOR DE ENVIO -->
                    <div
                        v-if="enviando"
                        class="flex justify-start"
                    >
                        <div class="rounded-2xl rounded-tl-md bg-white px-4 py-3 shadow-sm ring-1 ring-slate-200">
                            <div class="flex items-center gap-1.5">
                                <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-slate-400"></span>
                                <span
                                    class="h-1.5 w-1.5 animate-bounce rounded-full bg-slate-400"
                                    style="animation-delay: 120ms"
                                ></span>
                                <span
                                    class="h-1.5 w-1.5 animate-bounce rounded-full bg-slate-400"
                                    style="animation-delay: 240ms"
                                ></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AVISO -->
                <div class="border-t border-slate-100 bg-amber-50/70 px-4 py-3">
                    <p class="text-center text-xs leading-relaxed text-amber-800">
                        Esta ferramenta é apenas um apoio à organização de
                        informações e não substitui uma avaliação médica
                        profissional.
                    </p>
                </div>

                <!-- CAMPO DE MENSAGEM -->
                <div
                    v-if="triagem.status === 'em_andamento'"
                    class="border-t border-slate-200 bg-white p-4 sm:p-5"
                >
                    <form
                        class="flex items-end gap-3"
                        @submit.prevent="enviarMensagem"
                    >
                        <textarea
                            v-model="mensagem"
                            rows="2"
                            maxlength="5000"
                            placeholder="Digite sua resposta..."
                            class="min-h-[52px] flex-1 resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-emerald-400 focus:bg-white focus:ring-2 focus:ring-emerald-100"
                            :disabled="enviando"
                            @keydown.enter="enviarComEnter"
                        ></textarea>

                        <button
                            type="submit"
                            :disabled="enviando || !mensagem.trim()"
                            class="flex h-[52px] shrink-0 items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50"
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
                                    d="M6 12 3.269 3.125A59.77 59.77 0 0 1 21.485 12 59.77 59.77 0 0 1 3.27 20.875L6 12Zm0 0h7.5"
                                />
                            </svg>

                            <span class="hidden sm:inline">
                                Enviar
                            </span>
                        </button>
                    </form>

                    <p class="mt-2 text-[11px] text-slate-400">
                        Pressione Enter para enviar. Use Shift + Enter para
                        quebrar linha.
                    </p>
                </div>

                <!-- TRIAGEM FINALIZADA -->
                <div
                    v-else
                    class="border-t border-slate-200 bg-slate-50 px-5 py-4 text-center"
                >
                    <p class="text-sm font-medium text-slate-600">
                        Esta triagem não está mais disponível para novas
                        mensagens.
                    </p>
                </div>
            </div>

            <!-- RESULTADO DA IA -->
            <div
                v-if="triagem.prioridade_ia"
                class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6"
            >
                <h2 class="text-base font-bold text-slate-900">
                    Resultado da triagem
                </h2>

                <div class="mt-4">
                    <span
                        :class="[
                            'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset',
                            formatarPrioridade(triagem.prioridade_ia).badgeClass,
                        ]"
                    >
                        {{ formatarPrioridade(triagem.prioridade_ia).label }}
                    </span>
                </div>

                <div
                    v-if="triagem.resumo_ia"
                    class="mt-4"
                >
                    <h3 class="text-sm font-semibold text-slate-800">
                        Resumo
                    </h3>

                    <p class="mt-1 text-sm leading-relaxed text-slate-600">
                        {{ triagem.resumo_ia }}
                    </p>
                </div>

                <div
                    v-if="triagem.orientacao_ia"
                    class="mt-4"
                >
                    <h3 class="text-sm font-semibold text-slate-800">
                        Orientação
                    </h3>

                    <p class="mt-1 text-sm leading-relaxed text-slate-600">
                        {{ triagem.orientacao_ia }}
                    </p>
                </div>
            </div>

        </div>
    </div>
</template>

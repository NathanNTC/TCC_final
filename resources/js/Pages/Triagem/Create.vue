<script setup>
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const iniciando = ref(false)

function iniciarTriagem() {
    if (iniciando.value) {
        return
    }

    iniciando.value = true

    router.post('/triagens', {}, {
        onFinish: () => {
            iniciando.value = false
        },
    })
}
</script>

<template>
    <Head title="Nova Triagem" />

    <div class="min-h-screen bg-slate-50/50 px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl">

            <!-- CABEÇALHO -->
            <div class="mb-8">
                <a
                    href="/triagens"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 transition-colors hover:text-emerald-700"
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

                    <span>Voltar para minhas triagens</span>
                </a>

                <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Nova triagem
                </h1>

                <p class="mt-2 text-sm leading-relaxed text-slate-500">
                    A triagem irá coletar informações sobre os sintomas apresentados
                    para gerar uma orientação inicial baseada nas informações fornecidas.
                </p>
            </div>

            <!-- AVISO -->
            <div class="mb-6 rounded-2xl border border-amber-200/80 bg-amber-50/60 p-5 shadow-sm">
                <div class="flex gap-3.5">

                    <div
                        class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-700"
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
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"
                            />
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-sm font-bold text-amber-900">
                            Aviso importante
                        </h2>

                        <p class="mt-1 text-sm leading-relaxed text-amber-800/90">
                            Esta ferramenta tem finalidade exclusivamente de apoio à
                            organização de informações e não substitui uma avaliação
                            ou consulta médica profissional.
                        </p>
                    </div>

                </div>
            </div>

            <!-- CARD PRINCIPAL -->
            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm sm:p-8">

                <h2 class="text-base font-bold text-slate-900">
                    Como funciona o processo?
                </h2>

                <div class="mt-6 space-y-6">

                    <!-- PASSO 1 -->
                    <div class="flex gap-4">

                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-sm font-bold text-emerald-700 ring-1 ring-emerald-600/10"
                        >
                            1
                        </div>

                        <div class="pt-1">
                            <h3 class="text-sm font-bold text-slate-900">
                                Descreva seus sintomas
                            </h3>

                            <p class="mt-1 text-sm leading-relaxed text-slate-500">
                                Você responderá a perguntas direcionadas sobre o que
                                está sentindo no momento.
                            </p>
                        </div>

                    </div>

                    <!-- PASSO 2 -->
                    <div class="flex gap-4">

                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-sm font-bold text-emerald-700 ring-1 ring-emerald-600/10"
                        >
                            2
                        </div>

                        <div class="pt-1">
                            <h3 class="text-sm font-bold text-slate-900">
                                Contextualize seu histórico
                            </h3>

                            <p class="mt-1 text-sm leading-relaxed text-slate-500">
                                As informações salvas no seu perfil de saúde serão
                                integradas para complementar as informações da triagem.
                            </p>
                        </div>

                    </div>

                    <!-- PASSO 3 -->
                    <div class="flex gap-4">

                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-sm font-bold text-emerald-700 ring-1 ring-emerald-600/10"
                        >
                            3
                        </div>

                        <div class="pt-1">
                            <h3 class="text-sm font-bold text-slate-900">
                                Receba a orientação inicial
                            </h3>

                            <p class="mt-1 text-sm leading-relaxed text-slate-500">
                                Ao final, a IA apresentará um resumo estruturado e uma
                                recomendação do nível de prioridade para a situação.
                            </p>
                        </div>

                    </div>

                </div>

                <!-- AÇÃO -->
                <div class="mt-8 border-t border-slate-100 pt-6">

                    <button
                        type="button"
                        :disabled="iniciando"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-md shadow-emerald-600/20 transition-all hover:bg-emerald-700 hover:shadow-lg active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-60 disabled:shadow-none"
                        @click="iniciarTriagem"
                    >

                        <svg
                            v-if="iniciando"
                            class="h-4 w-4 animate-spin"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            />

                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            />
                        </svg>

                        <svg
                            v-else
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
                                d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z"
                            />
                        </svg>

                        <span>
                            {{ iniciando ? 'Iniciando triagem...' : 'Iniciar triagem' }}
                        </span>

                    </button>

                </div>

            </div>

        </div>
    </div>
</template>


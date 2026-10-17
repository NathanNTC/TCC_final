
<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'

import CabecalhoAvaliacao from './components/CabecalhoAvaliacao.vue'
import ListaTriagens from './components/ListaTriagens.vue'
import ResumoTriagem from './components/ResumoTriagem.vue'
import HistoricoMensagens from './components/HistoricoMensagens.vue'
import FormularioAvaliacao from './components/FormularioAvaliacao.vue'

const props = defineProps({
    triagens: {
        type: Array,
        default: () => [],
    },
})

const page = usePage()
const selecionada = ref(null)
const darkMode = ref(false)

const triagensDisponiveis = computed(() => props.triagens ?? [])

const form = useForm({
    prioridade_avaliador: '',
    justificativa: '',
    observacoes: '',
    nota_qualidade: '',
    comentario_qualidade: '',
})

const logoutForm = useForm({})

onMounted(() => {
    const temaSalvo = localStorage.getItem('avaliacao-triagens-tema')

    darkMode.value = temaSalvo === 'dark'

    document.documentElement.classList.toggle('dark', darkMode.value)
})

watch(darkMode, (ativo) => {
    document.documentElement.classList.toggle('dark', ativo)

    localStorage.setItem(
        'avaliacao-triagens-tema',
        ativo ? 'dark' : 'light',
    )
})

function alternarTema() {
    darkMode.value = !darkMode.value
}

function iniciarAvaliacao(triagem) {
    selecionada.value = triagem

    form.reset()
    form.clearErrors()
}

function cancelarAvaliacao() {
    selecionada.value = null

    form.reset()
    form.clearErrors()
}

function enviarAvaliacao() {
    if (!selecionada.value || form.processing) {
        return
    }

    form.post(`/avaliacao/${selecionada.value.id}`, {
        preserveScroll: true,

        onSuccess: () => {
            selecionada.value = null

            form.reset()
            form.clearErrors()
        },
    })
}

function logout() {
    if (logoutForm.processing) {
        return
    }

    logoutForm.post('/logout')
}
</script>

<template>
    <Head title="Avaliação de triagens" />

    <main
        class="min-h-screen bg-slate-50 px-4 py-8 text-slate-900 transition-colors duration-200 dark:bg-slate-950 dark:text-slate-100 sm:px-6 lg:px-8"
    >
        <div class="mx-auto max-w-5xl space-y-6">
            <CabecalhoAvaliacao
                :total="triagensDisponiveis.length"
                :dark-mode="darkMode"
                :logout-processing="logoutForm.processing"
                @alternar-tema="alternarTema"
                @logout="logout"
            />

            <!-- Mensagem de sucesso -->
            <div
                v-if="page.props.flash?.success"
                role="status"
                class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-300"
            >
                {{ page.props.flash.success }}
            </div>

            <!-- Mensagem de erro -->
            <div
                v-if="page.props.flash?.error"
                role="alert"
                class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 dark:border-rose-900 dark:bg-rose-950/40 dark:text-rose-300"
            >
                {{ page.props.flash.error }}
            </div>

            <!-- Lista de triagens e acesso à avaliação geral -->
            <div v-if="!selecionada" class="space-y-5">
                <Link
                    href="/avaliacao/eficiencia"
                    class="group block rounded-2xl border border-emerald-200 bg-white p-5 shadow-sm transition hover:border-emerald-400 hover:shadow-md dark:border-emerald-900 dark:bg-slate-900 dark:hover:border-emerald-700 sm:p-6"
                >
                    <div class="flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 12l2 2 4-4"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 3l7 4v5c0 5-3.5 7.5-7 9-3.5-1.5-7-4-7-9V7l7-4z"
                                />
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div
                                class="flex flex-wrap items-center gap-2"
                            >
                                <h2
                                    class="text-lg font-semibold text-slate-900 dark:text-white"
                                >
                                    Avaliação geral da eficiência da IA
                                </h2>

                                <span
                                    class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300"
                                >
                                    Avaliação geral
                                </span>
                            </div>

                            <p
                                class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300"
                            >
                                Avalie a clareza das perguntas, a relevância
                                das informações coletadas, a qualidade das
                                respostas e a utilidade dos resumos gerados
                                pela inteligência artificial.
                            </p>

                            <span
                                class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 group-hover:text-emerald-800 dark:text-emerald-400 dark:group-hover:text-emerald-300"
                            >
                                Acessar avaliação geral

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 transition-transform group-hover:translate-x-1"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5 12h14m-6-6l6 6-6 6"
                                    />
                                </svg>
                            </span>
                        </div>
                    </div>
                </Link>

                <!-- Avaliação individual de cada triagem -->
                <ListaTriagens
                    :triagens="triagensDisponiveis"
                    @selecionar="iniciarAvaliacao"
                />
            </div>

            <!-- Formulário de avaliação individual -->
            <template v-else>
                <ResumoTriagem
                    :triagem="selecionada"
                    @voltar="cancelarAvaliacao"
                />

                <HistoricoMensagens
                    :mensagens="selecionada.mensagens ?? []"
                />

                <FormularioAvaliacao
                    :form="form"
                    @submit="enviarAvaliacao"
                    @cancelar="cancelarAvaliacao"
                />
            </template>
        </div>
    </main>
</template>


<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'

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

            <div
                v-if="page.props.flash?.success"
                role="status"
                class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-300"
            >
                {{ page.props.flash.success }}
            </div>

            <div
                v-if="page.props.flash?.error"
                role="alert"
                class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 dark:border-rose-900 dark:bg-rose-950/40 dark:text-rose-300"
            >
                {{ page.props.flash.error }}
            </div>

            <ListaTriagens
                v-if="!selecionada"
                :triagens="triagensDisponiveis"
                @selecionar="iniciarAvaliacao"
            />

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

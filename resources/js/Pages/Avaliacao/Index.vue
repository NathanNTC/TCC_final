<script setup>
import { computed, ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'

const props = defineProps({
    triagens: {
        type: Array,
        default: () => [],
    },
})

const selecionada = ref(null)

const form = useForm({
    prioridade_avaliador: '',
    justificativa: '',
    observacoes: '',
    nota_qualidade: '',
    comentario_qualidade: '',
})

const logoutForm = useForm({})

const prioridades = [
    { value: 'emergencial', label: 'Emergencial', desc: 'Risco iminente', badgeClass: 'border-rose-200 bg-rose-50 text-rose-800 hover:bg-rose-100' },
    { value: 'muito_urgente', label: 'Muito urgente', desc: 'Atendimento prioritário', badgeClass: 'border-rose-200 bg-rose-50/70 text-rose-700 hover:bg-rose-100' },
    { value: 'urgente', label: 'Urgente', desc: 'Avaliação em poucas horas', badgeClass: 'border-amber-200 bg-amber-50 text-amber-800 hover:bg-amber-100' },
    { value: 'pouco_urgente', label: 'Pouco urgente', desc: 'Pode aguardar', badgeClass: 'border-amber-200 bg-amber-50/70 text-amber-700 hover:bg-amber-100' },
    { value: 'autocuidado', label: 'Autocuidado', desc: 'Orientações e cuidados básicos', badgeClass: 'border-emerald-200 bg-emerald-50 text-emerald-800 hover:bg-emerald-100' },
]

const notasQualidade = [
    { value: 1, label: 'Muito ruim' },
    { value: 2, label: 'Ruim' },
    { value: 3, label: 'Regular' },
    { value: 4, label: 'Boa' },
    { value: 5, label: 'Excelente' },
]

const rotulos = Object.fromEntries(
    prioridades.map((item) => [item.value, item.label])
)

const triagensDisponiveis = computed(() => props.triagens)

function formatarData(data) {
    if (!data) return 'Não informada'

    return new Intl.DateTimeFormat('pt-BR', {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(new Date(data))
}

function badgePrioridadeClass(prioridade) {
    const estilos = {
        emergencial: 'bg-rose-100 text-rose-800 ring-rose-600/20',
        muito_urgente: 'bg-rose-50 text-rose-700 ring-rose-500/20',
        urgente: 'bg-amber-100 text-amber-800 ring-amber-600/20',
        pouco_urgente: 'bg-amber-50 text-amber-700 ring-amber-500/20',
        nao_emergencial: 'bg-emerald-50 text-emerald-800 ring-emerald-600/20',
        autocuidado: 'bg-emerald-100 text-emerald-800 ring-emerald-600/20',
    }

    return estilos[prioridade] ?? 'bg-slate-100 text-slate-700 ring-slate-500/10'
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
    if (!selecionada.value || form.processing) return

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
    if (logoutForm.processing) return

    logoutForm.post('/logout')
}
</script>

<template>
    <Head title="Avaliação de triagens" />

    <main class="min-h-screen bg-slate-50/50 px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl space-y-6">

            <!-- CABEÇALHO -->
            <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        Avaliação de triagens
                    </h1>

                    <p class="mt-1 text-sm leading-relaxed text-slate-500">
                        Analise as triagens concluídas pela IA e registre sua avaliação médica independente.
                    </p>
                </div>

                <!-- CONTADOR E BOTÃO DE LOGOUT -->
                <div v-if="!selecionada" class="flex flex-wrap items-center gap-3">
                    <div class="flex shrink-0 items-center gap-3 rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-600/10">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.25-2.142V8.25" />
                            </svg>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Disponíveis
                            </p>
                            <p class="text-xl font-black text-slate-900">
                                {{ triagensDisponiveis.length }}
                            </p>
                        </div>
                    </div>

                    <button
                        type="button"
                        :disabled="logoutForm.processing"
                        @click="logout"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-rose-200 bg-white px-4 py-3 text-sm font-semibold text-rose-600 shadow-xs transition hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 15l3-3m0 0-3-3m3 3H9" />
                        </svg>

                        <span>{{ logoutForm.processing ? 'Saindo...' : 'Sair' }}</span>
                    </button>
                </div>
            </header>

            <!-- MENSAGENS DE FLASH -->
            <div
                v-if="$page.props.flash?.success"
                class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900 shadow-xs"
                role="status"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 shrink-0 text-emerald-600">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                </svg>
                <span>{{ $page.props.flash.success }}</span>
            </div>

            <div
                v-if="$page.props.flash?.error"
                class="flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-900 shadow-xs"
                role="alert"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 shrink-0 text-rose-600">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 0 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 0 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 0 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                </svg>
                <span>{{ $page.props.flash.error }}</span>
            </div>

            <!-- LISTA DE TRIAGENS -->
            <div v-if="!selecionada" class="space-y-4">

                <!-- ESTADO VAZIO -->
                <div
                    v-if="triagensDisponiveis.length === 0"
                    class="rounded-2xl border border-slate-200/80 bg-white p-12 text-center shadow-xs"
                >
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-600/10">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-7 w-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>

                    <h2 class="mt-4 text-base font-bold text-slate-900">
                        Todas as triagens foram avaliadas!
                    </h2>

                    <p class="mx-auto mt-1 max-w-sm text-sm leading-relaxed text-slate-500">
                        Não há novas triagens pendentes de avaliação no momento. Assim que os pacientes concluírem novas triagens, elas aparecerão aqui.
                    </p>
                </div>

                <!-- CARDS DE TRIAGEM -->
                <article
                    v-for="triagem in triagensDisponiveis"
                    :key="triagem.id"
                    class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs transition-all hover:border-slate-300 hover:shadow-sm"
                >
                    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <h2 class="text-base font-bold text-slate-900">
                                    Triagem #{{ triagem.id }}
                                </h2>
                                <span class="text-xs text-slate-400">•</span>
                                <p class="text-xs text-slate-400">
                                    Finalizada em {{ formatarData(triagem.finalizada_em) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-slate-400">IA:</span>
                            <span
                                :class="[
                                    'inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset',
                                    badgePrioridadeClass(triagem.prioridade_ia)
                                ]"
                            >
                                {{ rotulos[triagem.prioridade_ia] ?? triagem.prioridade_ia }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Resumo Clínico da IA
                        </h3>
                        <p class="mt-1.5 whitespace-pre-line text-sm leading-relaxed text-slate-700">
                            {{ triagem.resumo_ia || 'Nenhum resumo disponibilizado pela IA.' }}
                        </p>
                    </div>

                    <div class="mt-5 flex justify-end">
                        <button
                            type="button"
                            class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-emerald-600/20 transition hover:bg-emerald-700 hover:shadow-lg active:scale-98"
                            @click="iniciarAvaliacao(triagem)"
                        >
                            <span>Analisar triagem</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </div>
                </article>
            </div>

            <!-- FORMULÁRIO DE AVALIAÇÃO -->
            <section v-else class="space-y-6">

                <!-- RESUMO E DADOS DA IA -->
                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs sm:p-8">
                    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-600/10">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                            </div>

                            <div>
                                <h2 class="text-lg font-bold text-slate-900">
                                    Avaliando Triagem #{{ selecionada.id }}
                                </h2>
                                <p class="text-xs text-slate-400">
                                    Concluída em {{ formatarData(selecionada.finalizada_em) }}
                                </p>
                            </div>
                        </div>

                        <button
                            type="button"
                            class="cursor-pointer rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 shadow-xs transition hover:bg-slate-50"
                            @click="cancelarAvaliacao"
                        >
                            Cancelar
                        </button>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Prioridade definida pela IA
                            </p>
                            <span
                                :class="[
                                    'mt-2 inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset',
                                    badgePrioridadeClass(selecionada.prioridade_ia)
                                ]"
                            >
                                {{ rotulos[selecionada.prioridade_ia] ?? selecionada.prioridade_ia }}
                            </span>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Possíveis Causas Mencionadas
                            </p>
                            <div class="mt-2 flex flex-wrap gap-1.5">
                                <span
                                    v-for="(causa, idx) in selecionada.possiveis_causas_ia"
                                    :key="idx"
                                    class="inline-flex items-center rounded-md bg-white px-2 py-0.5 text-xs font-medium text-slate-700 ring-1 ring-slate-200"
                                >
                                    {{ causa }}
                                </span>
                                <span v-if="!selecionada.possiveis_causas_ia?.length" class="text-xs text-slate-400">
                                    Nenhuma causa informada.
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 space-y-4">
                        <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Resumo da IA
                            </h3>
                            <p class="mt-1.5 whitespace-pre-line text-sm leading-relaxed text-slate-700">
                                {{ selecionada.resumo_ia || 'Não informado.' }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Orientação Gerada
                            </h3>
                            <p class="mt-1.5 whitespace-pre-line text-sm leading-relaxed text-slate-700">
                                {{ selecionada.orientacao_ia || 'Não informada.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- HISTÓRICO DA CONVERSA -->
                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs sm:p-8">
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 ring-1 ring-emerald-600/10">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 0-.75 0 .375.375 0 0 1 0 .75Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 0 .75 0 .375.375 0 0 1 0-.75Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a.75.75 0 0 1-.816-.816 6.008 6.008 0 0 1 1.057-2.836A7.476 7.476 0 0 1 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">
                            Histórico de mensagens
                        </h3>
                    </div>

                    <p class="mt-1 text-xs text-slate-500">
                        Examine as interações reais entre o paciente e a IA para fundamentar seu parecer.
                    </p>

                    <div
                        v-if="selecionada.mensagens?.length"
                        class="mt-6 max-h-[400px] space-y-3 overflow-y-auto pr-2"
                    >
                        <div
                            v-for="msg in selecionada.mensagens"
                            :key="msg.id"
                            class="flex"
                            :class="msg.remetente === 'paciente' ? 'justify-end' : 'justify-start'"
                        >
                            <div
                                class="max-w-[85%] rounded-2xl px-4 py-3 text-xs leading-relaxed"
                                :class="msg.remetente === 'paciente'
                                    ? 'rounded-br-xs bg-emerald-600 text-white'
                                    : 'rounded-bl-xs bg-slate-100 text-slate-800'"
                            >
                                <p class="mb-1 text-[10px] font-bold uppercase opacity-75">
                                    {{ msg.remetente === 'paciente' ? 'Paciente' : 'Assistente IA' }}
                                </p>
                                <p class="whitespace-pre-line">{{ msg.mensagem }}</p>
                            </div>
                        </div>
                    </div>

                    <p v-else class="mt-4 py-6 text-center text-xs text-slate-400">
                        Nenhuma mensagem registrada nesta triagem.
                    </p>
                </div>

                <!-- FORMULÁRIO DE AVALIAÇÃO HUMANA -->
                <form
                    class="space-y-6 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs sm:p-8"
                    @submit.prevent="enviarAvaliacao"
                >
                    <div class="border-b border-slate-100 pb-4">
                        <h3 class="text-base font-bold text-slate-900">
                            Sua Avaliação Independente
                        </h3>
                        <p class="mt-0.5 text-xs text-slate-500">
                            Insira os dados da sua análise médica para comparar com a triagem automatizada.
                        </p>
                    </div>

                    <!-- PRIORIDADE ATRIBUÍDA -->
                    <div>
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-700">
                            Qual prioridade você atribui a esta triagem? <span class="text-rose-500">*</span>
                        </label>

                        <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2 lg:grid-cols-3">
                            <button
                                v-for="item in prioridades"
                                :key="item.value"
                                type="button"
                                class="flex cursor-pointer flex-col items-start rounded-xl border p-3.5 text-left transition-all"
                                :class="form.prioridade_avaliador === item.value
                                    ? 'border-emerald-600 bg-emerald-50/80 ring-2 ring-emerald-500/20'
                                    : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50'"
                                @click="form.prioridade_avaliador = item.value"
                            >
                                <div class="flex w-full items-center justify-between">
                                    <span class="text-xs font-bold text-slate-900">{{ item.label }}</span>
                                    <span
                                        class="h-2 w-2 rounded-full"
                                        :class="form.prioridade_avaliador === item.value ? 'bg-emerald-600' : 'bg-transparent'"
                                    />
                                </div>
                                <span class="mt-1 text-[11px] text-slate-500">{{ item.desc }}</span>
                            </button>
                        </div>

                        <p v-if="form.errors.prioridade_avaliador" class="mt-1.5 text-xs font-medium text-rose-600">
                            {{ form.errors.prioridade_avaliador }}
                        </p>
                    </div>

                    <!-- JUSTIFICATIVA -->
                    <div>
                        <label for="justificativa" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-700">
                            Justificativa Clínica <span class="text-rose-500">*</span>
                        </label>
                        <textarea
                            id="justificativa"
                            v-model="form.justificativa"
                            required
                            minlength="10"
                            maxlength="3000"
                            rows="4"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/10"
                            placeholder="Explique detalhadamente os aspectos clínicos que fundamentam sua avaliação..."
                        />
                        <p v-if="form.errors.justificativa" class="mt-1.5 text-xs font-medium text-rose-600">
                            {{ form.errors.justificativa }}
                        </p>
                    </div>

                    <!-- OBSERVAÇÕES OPCIONAIS -->
                    <div>
                        <label for="observacoes" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-700">
                            Observações Adicionais (opcional)
                        </label>
                        <textarea
                            id="observacoes"
                            v-model="form.observacoes"
                            maxlength="3000"
                            rows="2"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/10"
                            placeholder="Anotações secundárias ou complementares..."
                        />
                    </div>

                    <!-- NOTA DE QUALIDADE DA IA -->
                    <div>
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-700">
                            Como você avalia a qualidade geral da triagem realizada pela IA? <span class="text-rose-500">*</span>
                        </label>

                        <div class="flex flex-wrap items-center gap-2">
                            <button
                                v-for="nota in notasQualidade"
                                :key="nota.value"
                                type="button"
                                class="flex cursor-pointer items-center gap-1.5 rounded-xl border px-3.5 py-2 text-xs font-bold transition"
                                :class="form.nota_qualidade === nota.value
                                    ? 'border-amber-400 bg-amber-50 text-amber-900 shadow-xs'
                                    : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'"
                                @click="form.nota_qualidade = nota.value"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-amber-400">
                                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ nota.value }} — {{ nota.label }}</span>
                            </button>
                        </div>

                        <p v-if="form.errors.nota_qualidade" class="mt-1.5 text-xs font-medium text-rose-600">
                            {{ form.errors.nota_qualidade }}
                        </p>
                    </div>

                    <!-- COMENTÁRIO OPCIONAL -->
                    <div>
                        <label for="comentario_qualidade" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-700">
                            Comentários sobre a Qualidade da IA (opcional)
                        </label>
                        <textarea
                            id="comentario_qualidade"
                            v-model="form.comentario_qualidade"
                            maxlength="1500"
                            rows="2"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/10"
                            placeholder="Comente sobre a clareza das perguntas, precisão das hipóteses ou conduta gerada..."
                        />
                    </div>

                    <p v-if="form.errors.error" class="text-xs font-medium text-rose-600">
                        {{ form.errors.error }}
                    </p>

                    <!-- BOTÕES DE SUBMISSÃO -->
                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <button
                            type="submit"
                            :disabled="form.processing || !form.prioridade_avaliador || !form.nota_qualidade"
                            class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-emerald-600/20 transition hover:bg-emerald-700 hover:shadow-lg active:scale-98 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <svg
                                v-if="form.processing"
                                class="h-4 w-4 animate-spin text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>
                            <span>{{ form.processing ? 'Salvando...' : 'Salvar avaliação' }}</span>
                        </button>

                        <button
                            type="button"
                            :disabled="form.processing"
                            class="cursor-pointer rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-xs transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
                            @click="cancelarAvaliacao"
                        >
                            Cancelar
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </main>
</template>

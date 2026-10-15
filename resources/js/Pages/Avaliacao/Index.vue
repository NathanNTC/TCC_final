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

const prioridades = [
    { value: 'emergencial', label: 'Emergencial' },
    { value: 'muito_urgente', label: 'Muito urgente' },
    { value: 'urgente', label: 'Urgente' },
    { value: 'pouco_urgente', label: 'Pouco urgente' },
    { value: 'autocuidado', label: 'Autocuidado' },
]

const notasQualidade = [
    { value: 1, label: '1 — Muito ruim' },
    { value: 2, label: '2 — Ruim' },
    { value: 3, label: '3 — Regular' },
    { value: 4, label: '4 — Boa' },
    { value: 5, label: '5 — Excelente' },
]

const rotulos = Object.fromEntries(
    prioridades.map((item) => [item.value, item.label])
)

const triagensDisponiveis = computed(() => props.triagens)

function formatarData(data) {
    if (!data) return 'Não informada'

    return new Intl.DateTimeFormat('pt-BR', {
        dateStyle: 'short',
        timeStyle: 'short',
    }).format(new Date(data))
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
</script>

<template>
    <Head title="Avaliação de triagens" />

    <main class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-6xl">
            <header class="mb-8">
                <a
                    href="/dashboard"
                    class="text-sm font-medium text-blue-700 hover:underline"
                >
                    ← Voltar ao painel
                </a>

                <h1 class="mt-4 text-3xl font-bold text-slate-900">
                    Avaliação de triagens
                </h1>

                <p class="mt-2 text-slate-600">
                    Analise as triagens concluídas pela IA e registre sua
                    avaliação independente.
                </p>
            </header>

            <!-- Mensagens de retorno -->
            <div
                v-if="$page.props.flash?.success"
                class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800"
                role="status"
            >
                {{ $page.props.flash.success }}
            </div>

            <div
                v-if="$page.props.flash?.error"
                class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800"
                role="alert"
            >
                {{ $page.props.flash.error }}
            </div>

            <!-- Lista de triagens -->
            <div v-if="!selecionada" class="space-y-5">
                <div
                    class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"
                >
                    <p class="text-sm text-slate-500">
                        Triagens disponíveis para avaliação
                    </p>

                    <p class="mt-1 text-3xl font-bold text-slate-900">
                        {{ triagensDisponiveis.length }}
                    </p>
                </div>

                <div
                    v-if="triagensDisponiveis.length === 0"
                    class="rounded-xl border border-slate-200 bg-white p-8 text-center"
                >
                    <h2 class="text-lg font-semibold text-slate-800">
                        Nenhuma triagem disponível
                    </h2>

                    <p class="mt-2 text-sm text-slate-500">
                        Quando houver triagens concluídas e ainda não avaliadas
                        por você, elas aparecerão aqui.
                    </p>
                </div>

                <article
                    v-for="triagem in triagensDisponiveis"
                    :key="triagem.id"
                    class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
                >
                    <div
                        class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start"
                    >
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">
                                Triagem #{{ triagem.id }}
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Concluída em
                                {{ formatarData(triagem.finalizada_em) }}
                            </p>
                        </div>

                        <span
                            class="inline-flex w-fit rounded-full bg-blue-50 px-3 py-1 text-sm font-semibold text-blue-800"
                        >
                            IA:
                            {{
                                rotulos[triagem.prioridade_ia]
                                ?? triagem.prioridade_ia
                            }}
                        </span>
                    </div>

                    <div class="mt-5">
                        <h3 class="text-sm font-semibold text-slate-700">
                            Resumo gerado pela IA
                        </h3>

                        <p
                            class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600"
                        >
                            {{ triagem.resumo_ia || 'Resumo não informado.' }}
                        </p>
                    </div>

                    <button
                        type="button"
                        class="mt-5 rounded-lg bg-blue-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-800"
                        @click="iniciarAvaliacao(triagem)"
                    >
                        Analisar triagem
                    </button>
                </article>
            </div>

            <!-- Formulário de avaliação -->
            <section v-else class="space-y-6">
                <!-- Informações da triagem -->
                <div
                    class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
                >
                    <div
                        class="flex flex-wrap items-center justify-between gap-3"
                    >
                        <h2 class="text-xl font-bold text-slate-900">
                            Triagem #{{ selecionada.id }}
                        </h2>

                        <button
                            type="button"
                            class="text-sm font-medium text-slate-600 hover:text-slate-900"
                            @click="cancelarAvaliacao"
                        >
                            Cancelar
                        </button>
                    </div>

                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        <div class="rounded-lg bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">
                                Prioridade definida pela IA
                            </p>

                            <p class="mt-1 font-semibold text-slate-900">
                                {{
                                    rotulos[selecionada.prioridade_ia]
                                    ?? selecionada.prioridade_ia
                                }}
                            </p>
                        </div>

                        <div class="rounded-lg bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">
                                Data de conclusão
                            </p>

                            <p class="mt-1 font-semibold text-slate-900">
                                {{ formatarData(selecionada.finalizada_em) }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h3 class="font-semibold text-slate-800">
                            Resumo da IA
                        </h3>

                        <p
                            class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600"
                        >
                            {{ selecionada.resumo_ia || 'Não informado.' }}
                        </p>
                    </div>

                    <div class="mt-5">
                        <h3 class="font-semibold text-slate-800">
                            Orientação da IA
                        </h3>

                        <p
                            class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600"
                        >
                            {{ selecionada.orientacao_ia || 'Não informada.' }}
                        </p>
                    </div>

                    <div class="mt-5">
                        <h3 class="font-semibold text-slate-800">
                            Possíveis causas citadas pela IA
                        </h3>

                        <ul
                            v-if="selecionada.possiveis_causas_ia?.length"
                            class="mt-2 list-inside list-disc space-y-1 text-sm text-slate-600"
                        >
                            <li
                                v-for="(causa, indice) in selecionada.possiveis_causas_ia"
                                :key="indice"
                            >
                                {{ causa }}
                            </li>
                        </ul>

                        <p v-else class="mt-2 text-sm text-slate-500">
                            Nenhuma causa informada.
                        </p>
                    </div>
                </div>

                <!-- Histórico da conversa -->
                <div
                    class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
                >
                    <h3 class="text-lg font-bold text-slate-900">
                        Histórico da conversa
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Use o contexto da conversa para fundamentar sua análise.
                    </p>

                    <div
                        v-if="selecionada.mensagens?.length"
                        class="mt-5 space-y-4"
                    >
                        <div
                            v-for="mensagem in selecionada.mensagens"
                            :key="mensagem.id"
                            class="rounded-lg p-4"
                            :class="
                                mensagem.remetente === 'paciente'
                                    ? 'bg-blue-50'
                                    : 'bg-slate-100'
                            "
                        >
                            <p
                                class="text-xs font-bold uppercase tracking-wide text-slate-500"
                            >
                                {{
                                    mensagem.remetente === 'paciente'
                                        ? 'Paciente'
                                        : 'Assistente de IA'
                                }}
                            </p>

                            <p
                                class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700"
                            >
                                {{ mensagem.mensagem }}
                            </p>
                        </div>
                    </div>

                    <p v-else class="mt-4 text-sm text-slate-500">
                        Não há mensagens registradas nesta triagem.
                    </p>
                </div>

                <!-- Formulário -->
                <form
                    class="space-y-5 rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
                    @submit.prevent="enviarAvaliacao"
                >
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">
                            Sua avaliação independente
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Registre sua própria avaliação antes de comparar
                            os resultados.
                        </p>
                    </div>

                    <!-- Prioridade atribuída pelo avaliador -->
                    <div>
                        <label
                            for="prioridade"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Qual prioridade você atribui a esta triagem?
                        </label>

                        <select
                            id="prioridade"
                            v-model="form.prioridade_avaliador"
                            required
                            class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >
                            <option value="" disabled>
                                Selecione uma prioridade
                            </option>

                            <option
                                v-for="prioridade in prioridades"
                                :key="prioridade.value"
                                :value="prioridade.value"
                            >
                                {{ prioridade.label }}
                            </option>
                        </select>

                        <p
                            v-if="form.errors.prioridade_avaliador"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.prioridade_avaliador }}
                        </p>
                    </div>

                    <!-- Justificativa -->
                    <div>
                        <label
                            for="justificativa"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Justificativa
                        </label>

                        <textarea
                            id="justificativa"
                            v-model="form.justificativa"
                            required
                            minlength="10"
                            maxlength="3000"
                            rows="5"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            placeholder="Explique os aspectos clínicos que fundamentam sua avaliação."
                        />

                        <p
                            v-if="form.errors.justificativa"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.justificativa }}
                        </p>
                    </div>

                    <!-- Observações opcionais -->
                    <div>
                        <label
                            for="observacoes"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Observações adicionais (opcional)
                        </label>

                        <textarea
                            id="observacoes"
                            v-model="form.observacoes"
                            maxlength="3000"
                            rows="3"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            placeholder="Registre observações relevantes."
                        />

                        <p
                            v-if="form.errors.observacoes"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.observacoes }}
                        </p>
                    </div>

                    <!-- Nota de qualidade obrigatória -->
                    <div>
                        <label
                            for="nota_qualidade"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Como você avalia a qualidade da triagem realizada
                            pela IA?
                        </label>

                        <select
                            id="nota_qualidade"
                            v-model="form.nota_qualidade"
                            required
                            class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >
                            <option value="" disabled>
                                Selecione uma nota
                            </option>

                            <option
                                v-for="nota in notasQualidade"
                                :key="nota.value"
                                :value="nota.value"
                            >
                                {{ nota.label }}
                            </option>
                        </select>

                        <p
                            v-if="form.errors.nota_qualidade"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.nota_qualidade }}
                        </p>
                    </div>

                    <!-- Comentário de qualidade opcional -->
                    <div>
                        <label
                            for="comentario_qualidade"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Comentário sobre a qualidade (opcional)
                        </label>

                        <textarea
                            id="comentario_qualidade"
                            v-model="form.comentario_qualidade"
                            maxlength="1500"
                            rows="3"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            placeholder="Comente sobre a clareza das perguntas, a coleta de informações ou a orientação gerada pela IA."
                        />

                        <p
                            v-if="form.errors.comentario_qualidade"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.comentario_qualidade }}
                        </p>
                    </div>

                    <!-- Erro geral, se houver -->
                    <div
                        v-if="form.errors.error"
                        class="rounded-lg bg-red-50 p-3 text-sm text-red-700"
                    >
                        {{ form.errors.error }}
                    </div>

                    <!-- Botões -->
                    <div class="flex flex-wrap gap-3">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-lg bg-blue-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-800 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            {{
                                form.processing
                                    ? 'Salvando...'
                                    : 'Salvar avaliação'
                            }}
                        </button>

                        <button
                            type="button"
                            :disabled="form.processing"
                            class="rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 disabled:opacity-60"
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

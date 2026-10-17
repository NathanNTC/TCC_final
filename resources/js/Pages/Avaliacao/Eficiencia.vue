
<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'

const props = defineProps({
    avaliacao: {
        type: Object,
        default: null,
    },
})

const page = usePage()

const perguntas = [
    {
        campo: 'clareza_perguntas',
        titulo: '1. Clareza das perguntas',
        descricao: 'As perguntas da IA são claras e fáceis de entender?',
    },
    {
        campo: 'relevancia_perguntas',
        titulo: '2. Relevância das perguntas',
        descricao: 'As perguntas são pertinentes para compreender a queixa do paciente?',
    },
    {
        campo: 'coleta_dados',
        titulo: '3. Coleta de dados clínicos',
        descricao: 'A IA coleta informações suficientes e relevantes para a avaliação inicial?',
    },
    {
        campo: 'qualidade_respostas',
        titulo: '4. Qualidade das respostas',
        descricao: 'As respostas da IA são coerentes, claras e adequadas ao contexto informado?',
    },
    {
        campo: 'utilidade_resumo',
        titulo: '5. Utilidade do resumo clínico',
        descricao: 'O resumo gerado ajuda a compreender as informações relatadas pelo paciente?',
    },
    {
        campo: 'eficiencia_geral',
        titulo: '6. Eficiência geral',
        descricao: 'Considerando o processo como um todo, quão eficiente é a IA na coleta inicial de dados clínicos?',
    },
]

const form = useForm({
    clareza_perguntas: props.avaliacao?.clareza_perguntas ?? '',
    relevancia_perguntas: props.avaliacao?.relevancia_perguntas ?? '',
    coleta_dados: props.avaliacao?.coleta_dados ?? '',
    qualidade_respostas: props.avaliacao?.qualidade_respostas ?? '',
    utilidade_resumo: props.avaliacao?.utilidade_resumo ?? '',
    eficiencia_geral: props.avaliacao?.eficiencia_geral ?? '',
    pontos_melhoria: props.avaliacao?.pontos_melhoria ?? '',
    comentarios: props.avaliacao?.comentarios ?? '',
})

function enviar() {
    if (form.processing) return

    form.transform((dados) => ({
        ...dados,
        clareza_perguntas: Number(dados.clareza_perguntas),
        relevancia_perguntas: Number(dados.relevancia_perguntas),
        coleta_dados: Number(dados.coleta_dados),
        qualidade_respostas: Number(dados.qualidade_respostas),
        utilidade_resumo: Number(dados.utilidade_resumo),
        eficiencia_geral: Number(dados.eficiencia_geral),
    })).post('/avaliacao/eficiencia', {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Avaliação geral da eficiência da IA" />

    <main class="min-h-screen bg-slate-50 px-4 py-8 text-slate-900 dark:bg-slate-950 dark:text-slate-100 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl space-y-6">
            <header class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <Link
                    href="/avaliacao"
                    class="text-sm font-medium text-emerald-700 hover:underline dark:text-emerald-400"
                >
                    ← Voltar às avaliações de triagens
                </Link>

                <h1 class="mt-4 text-2xl font-bold tracking-tight sm:text-3xl">
                    Avaliação geral da eficiência da IA
                </h1>

                <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-400">
                    Avalie o funcionamento geral do sistema de triagem,
                    considerando a coleta inicial de dados clínicos.
                    Esta avaliação não substitui nem altera as avaliações
                    individuais das triagens.
                </p>

                <div class="mt-5 rounded-xl border border-sky-200 bg-sky-50 p-4 text-sm leading-6 text-sky-900 dark:border-sky-900 dark:bg-sky-950/40 dark:text-sky-200">
                    <strong>Escala de avaliação</strong>

                    <p>1 — Muito ruim</p>
                    <p>2 — Ruim</p>
                    <p>3 — Regular</p>
                    <p>4 — Bom</p>
                    <p>5 — Excelente</p>
                </div>

                <p
                    v-if="props.avaliacao"
                    class="mt-4 text-sm text-emerald-700 dark:text-emerald-400"
                >
                    Você já enviou uma avaliação. Ao salvar novamente,
                    suas respostas serão atualizadas.
                </p>
            </header>

            <div
                v-if="page.props.flash?.success"
                role="status"
                class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-300"
            >
                {{ page.props.flash.success }}
            </div>

            <form
                class="space-y-6"
                @submit.prevent="enviar"
            >
                <section
                    v-for="pergunta in perguntas"
                    :key="pergunta.campo"
                    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6"
                >
                    <h2 class="font-semibold">
                        {{ pergunta.titulo }}
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">
                        {{ pergunta.descricao }}
                    </p>

                    <fieldset class="mt-5">
                        <legend class="mb-3 text-sm font-medium">
                            Selecione uma nota:
                        </legend>

                        <div class="grid grid-cols-5 gap-2">
                            <label
                                v-for="nota in [1, 2, 3, 4, 5]"
                                :key="nota"
                                class="cursor-pointer"
                            >
                                <input
                                    v-model="form[pergunta.campo]"
                                    type="radio"
                                    :name="pergunta.campo"
                                    :value="nota"
                                    class="peer sr-only"
                                />

                                <span
                                    class="flex min-h-12 items-center justify-center rounded-lg border border-slate-300 bg-white font-semibold transition hover:bg-slate-50 peer-checked:border-emerald-600 peer-checked:bg-emerald-600 peer-checked:text-white peer-focus-visible:ring-2 peer-focus-visible:ring-emerald-500 dark:border-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:peer-checked:border-emerald-500 dark:peer-checked:bg-emerald-600"
                                >
                                    {{ nota }}
                                </span>
                            </label>
                        </div>
                    </fieldset>

                    <p
                        v-if="form.errors[pergunta.campo]"
                        class="mt-2 text-sm text-rose-600 dark:text-rose-400"
                    >
                        {{ form.errors[pergunta.campo] }}
                    </p>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                    <label
                        for="pontos_melhoria"
                        class="block font-semibold"
                    >
                        Quais melhorias você sugere?
                    </label>

                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                        Campo opcional. Descreva dificuldades ou pontos que
                        poderiam ser aprimorados.
                    </p>

                    <textarea
                        id="pontos_melhoria"
                        v-model="form.pontos_melhoria"
                        rows="4"
                        maxlength="3000"
                        class="mt-3 w-full rounded-xl border border-slate-300 bg-white p-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-700 dark:bg-slate-950"
                        placeholder="Ex.: perguntas repetitivas, informações que poderiam ser coletadas melhor..."
                    />

                    <p
                        v-if="form.errors.pontos_melhoria"
                        class="mt-2 text-sm text-rose-600"
                    >
                        {{ form.errors.pontos_melhoria }}
                    </p>

                    <label
                        for="comentarios"
                        class="mt-6 block font-semibold"
                    >
                        Comentários adicionais
                    </label>

                    <textarea
                        id="comentarios"
                        v-model="form.comentarios"
                        rows="4"
                        maxlength="3000"
                        class="mt-3 w-full rounded-xl border border-slate-300 bg-white p-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-700 dark:bg-slate-950"
                        placeholder="Outras observações sobre a eficiência geral do sistema..."
                    />

                    <p
                        v-if="form.errors.comentarios"
                        class="mt-2 text-sm text-rose-600"
                    >
                        {{ form.errors.comentarios }}
                    </p>
                </section>

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-between">
                    <Link
                        href="/avaliacao"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold transition hover:bg-slate-100 dark:border-slate-700 dark:hover:bg-slate-800"
                    >
                        Cancelar
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        {{
                            form.processing
                                ? 'Salvando...'
                                : props.avaliacao
                                    ? 'Atualizar avaliação'
                                    : 'Salvar avaliação'
                        }}
                    </button>
                </div>
            </form>
        </div>
    </main>
</template>

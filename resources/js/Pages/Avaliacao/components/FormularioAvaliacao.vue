
<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
})

const emit = defineEmits(['submit', 'cancelar'])

const prioridades = [
    {
        value: 'emergencial',
        label: 'Emergencial',
        descricao: 'Risco iminente',
        classes: 'border-rose-300 bg-rose-50 text-rose-800 dark:border-rose-800 dark:bg-rose-950/40 dark:text-rose-300',
        ponto: 'bg-rose-500',
    },
    {
        value: 'muito_urgente',
        label: 'Muito urgente',
        descricao: 'Atendimento prioritário',
        classes: 'border-orange-300 bg-orange-50 text-orange-800 dark:border-orange-800 dark:bg-orange-950/40 dark:text-orange-300',
        ponto: 'bg-orange-500',
    },
    {
        value: 'urgente',
        label: 'Urgente',
        descricao: 'Avaliação em poucas horas',
        classes: 'border-amber-300 bg-amber-50 text-amber-800 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-300',
        ponto: 'bg-amber-500',
    },
    {
        value: 'pouco_urgente',
        label: 'Pouco urgente',
        descricao: 'Pode aguardar',
        classes: 'border-yellow-300 bg-yellow-50 text-yellow-800 dark:border-yellow-800 dark:bg-yellow-950/40 dark:text-yellow-300',
        ponto: 'bg-yellow-500',
    },
    {
        value: 'autocuidado',
        label: 'Autocuidado',
        descricao: 'Orientações e cuidados básicos',
        classes: 'border-emerald-300 bg-emerald-50 text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300',
        ponto: 'bg-emerald-500',
    },
]

const notasQualidade = [
    { value: 1, label: 'Muito ruim' },
    { value: 2, label: 'Ruim' },
    { value: 3, label: 'Regular' },
    { value: 4, label: 'Boa' },
    { value: 5, label: 'Excelente' },
]
</script>

<template>
    <section
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900"
    >
        <div class="border-b border-slate-100 px-5 py-5 dark:border-slate-800 sm:px-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">
                Sua avaliação
            </h2>

            <p class="mt-1 text-sm leading-6 text-slate-600 dark:text-slate-400">
                Registre sua análise independente da prioridade e da qualidade
                da triagem realizada pela IA.
            </p>
        </div>

        <form
            class="space-y-7 p-5 sm:p-6"
            @submit.prevent="emit('submit')"
        >
            <!-- Prioridade do avaliador -->
            <fieldset>
                <legend class="text-sm font-semibold text-slate-900 dark:text-white">
                    1. Qual é a prioridade que você atribui a esta triagem?
                    <span class="text-rose-500">*</span>
                </legend>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Selecione a prioridade com base nas informações apresentadas.
                </p>

                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    <button
                        v-for="prioridade in prioridades"
                        :key="prioridade.value"
                        type="button"
                        class="rounded-xl border p-4 text-left transition focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        :class="form.prioridade_avaliador === prioridade.value
                            ? `${prioridade.classes} ring-2 ring-emerald-500/40`
                            : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:hover:border-slate-600 dark:hover:bg-slate-800'"
                        :aria-pressed="form.prioridade_avaliador === prioridade.value"
                        @click="form.prioridade_avaliador = prioridade.value"
                    >
                        <span class="flex items-start gap-3">
                            <span
                                class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full"
                                :class="prioridade.ponto"
                            />

                            <span class="min-w-0 flex-1">
                                <span class="block text-sm font-semibold">
                                    {{ prioridade.label }}
                                </span>

                                <span
                                    class="mt-1 block text-xs leading-5 opacity-80"
                                >
                                    {{ prioridade.descricao }}
                                </span>
                            </span>

                            <svg
                                v-if="form.prioridade_avaliador === prioridade.value"
                                class="h-5 w-5 shrink-0"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m5 12 4 4L19 6"
                                />
                            </svg>
                        </span>
                    </button>
                </div>

                <p
                    v-if="form.errors.prioridade_avaliador"
                    class="mt-2 text-sm text-rose-600 dark:text-rose-400"
                >
                    {{ form.errors.prioridade_avaliador }}
                </p>
            </fieldset>

            <!-- Justificativa -->
            <div class="border-t border-slate-100 pt-6 dark:border-slate-800">
                <label
                    for="justificativa"
                    class="block text-sm font-semibold text-slate-900 dark:text-white"
                >
                    2. Justificativa da prioridade
                    <span class="text-rose-500">*</span>
                </label>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Explique os elementos clínicos que sustentam sua avaliação.
                </p>

                <textarea
                    id="justificativa"
                    v-model="form.justificativa"
                    rows="4"
                    minlength="10"
                    maxlength="3000"
                    required
                    placeholder="Descreva os motivos da prioridade selecionada..."
                    class="mt-3 block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm leading-6 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:bg-slate-800"
                />

                <div class="mt-1 flex items-center justify-between gap-3">
                    <p
                        v-if="form.errors.justificativa"
                        class="text-sm text-rose-600 dark:text-rose-400"
                    >
                        {{ form.errors.justificativa }}
                    </p>

                    <span
                        class="ml-auto text-xs text-slate-400 dark:text-slate-500"
                    >
                        {{ (form.justificativa ?? '').length }}/3000
                    </span>
                </div>
            </div>

            <!-- Observações -->
            <div class="border-t border-slate-100 pt-6 dark:border-slate-800">
                <label
                    for="observacoes"
                    class="block text-sm font-semibold text-slate-900 dark:text-white"
                >
                    3. Observações adicionais
                    <span class="font-normal text-slate-500 dark:text-slate-400">
                        (opcional)
                    </span>
                </label>

                <textarea
                    id="observacoes"
                    v-model="form.observacoes"
                    rows="3"
                    maxlength="3000"
                    placeholder="Adicione comentários relevantes, se necessário..."
                    class="mt-3 block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm leading-6 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:bg-slate-800"
                />

                <div class="mt-1 flex items-center justify-between gap-3">
                    <p
                        v-if="form.errors.observacoes"
                        class="text-sm text-rose-600 dark:text-rose-400"
                    >
                        {{ form.errors.observacoes }}
                    </p>

                    <span class="ml-auto text-xs text-slate-400 dark:text-slate-500">
                        {{ (form.observacoes ?? '').length }}/3000
                    </span>
                </div>
            </div>

            <!-- Qualidade da IA -->
            <fieldset class="border-t border-slate-100 pt-6 dark:border-slate-800">
                <legend class="text-sm font-semibold text-slate-900 dark:text-white">
                    4. Como você avalia a qualidade da triagem realizada pela IA?
                    <span class="text-rose-500">*</span>
                </legend>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Considere a coleta de informações, a coerência e a
                    adequação das orientações fornecidas.
                </p>

                <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-5">
                    <button
                        v-for="nota in notasQualidade"
                        :key="nota.value"
                        type="button"
                        class="flex items-center gap-3 rounded-xl border p-3 text-left transition focus:outline-none focus:ring-2 focus:ring-emerald-500 sm:flex-col sm:gap-2 sm:text-center"
                        :class="Number(form.nota_qualidade) === nota.value
                            ? 'border-emerald-500 bg-emerald-50 text-emerald-800 ring-2 ring-emerald-500/20 dark:border-emerald-500 dark:bg-emerald-950/40 dark:text-emerald-300'
                            : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800'"
                        :aria-pressed="Number(form.nota_qualidade) === nota.value"
                        @click="form.nota_qualidade = nota.value"
                    >
                        <span
                            class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold"
                            :class="Number(form.nota_qualidade) === nota.value
                                ? 'bg-emerald-600 text-white'
                                : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200'"
                        >
                            {{ nota.value }}
                        </span>

                        <span class="text-sm font-medium">
                            {{ nota.label }}
                        </span>
                    </button>
                </div>

                <p
                    v-if="form.errors.nota_qualidade"
                    class="mt-2 text-sm text-rose-600 dark:text-rose-400"
                >
                    {{ form.errors.nota_qualidade }}
                </p>
            </fieldset>

            <!-- Comentário sobre qualidade -->
            <div class="border-t border-slate-100 pt-6 dark:border-slate-800">
                <label
                    for="comentario_qualidade"
                    class="block text-sm font-semibold text-slate-900 dark:text-white"
                >
                    5. Comentário sobre a qualidade
                    <span class="font-normal text-slate-500 dark:text-slate-400">
                        (opcional)
                    </span>
                </label>

                <textarea
                    id="comentario_qualidade"
                    v-model="form.comentario_qualidade"
                    rows="3"
                    maxlength="1500"
                    placeholder="O que a IA fez bem? O que poderia melhorar?"
                    class="mt-3 block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm leading-6 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:bg-slate-800"
                />

                <div class="mt-1 flex items-center justify-between gap-3">
                    <p
                        v-if="form.errors.comentario_qualidade"
                        class="text-sm text-rose-600 dark:text-rose-400"
                    >
                        {{ form.errors.comentario_qualidade }}
                    </p>

                    <span class="ml-auto text-xs text-slate-400 dark:text-slate-500">
                        {{ (form.comentario_qualidade ?? '').length }}/1500
                    </span>
                </div>
            </div>

            <div
                v-if="form.errors.error"
                role="alert"
                class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 dark:border-rose-900 dark:bg-rose-950/40 dark:text-rose-300"
            >
                {{ form.errors.error }}
            </div>

            <!-- Ações -->
            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 dark:border-slate-800 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    :disabled="form.processing"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-60 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
                    @click="emit('cancelar')"
                >
                    Cancelar
                </button>

                <button
                    type="submit"
                    :disabled="form.processing || !form.prioridade_avaliador || !form.nota_qualidade"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:focus:ring-offset-slate-900"
                >
                    <svg
                        v-if="form.processing"
                        class="h-4 w-4 animate-spin"
                        viewBox="0 0 24 24"
                        fill="none"
                        aria-hidden="true"
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
                            d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4Z"
                        />
                    </svg>

                    {{
                        form.processing
                            ? 'Salvando avaliação...'
                            : 'Salvar avaliação'
                    }}
                </button>
            </div>
        </form>
    </section>
</template>

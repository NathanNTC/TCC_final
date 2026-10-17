
<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    triagem: {
        type: Object,
        required: true,
    },
})

const form = useForm({
    nota: 0,
    facilidade: '',
    clareza: '',
    comentario: '',
})

const estrelas = [1, 2, 3, 4, 5]

const opcoesFacilidade = [
    { value: 'muito_facil', label: 'Muito fácil' },
    { value: 'facil', label: 'Fácil' },
    { value: 'regular', label: 'Regular' },
    { value: 'dificil', label: 'Difícil' },
    { value: 'muito_dificil', label: 'Muito difícil' },
]

const opcoesClareza = [
    { value: 'muito_clara', label: 'Muito claras' },
    { value: 'clara', label: 'Claras' },
    { value: 'regular', label: 'Razoáveis' },
    { value: 'pouco_clara', label: 'Pouco claras' },
    { value: 'nada_clara', label: 'Nada claras' },
]

function enviarFeedback() {
    form.post(`/triagens/${props.triagem.id}/feedback`, {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Avaliar triagem" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-slate-50/70 px-4 py-8 dark:bg-slate-950 sm:px-6 lg:px-8">
            <main class="mx-auto max-w-3xl">
                <Link
                    :href="`/triagens/${triagem.id}`"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 transition hover:text-indigo-600 dark:text-slate-300 dark:hover:text-indigo-400"
                >
                    <span aria-hidden="true">←</span>
                    Voltar para a triagem
                </Link>

                <section class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="border-b border-slate-200 px-6 py-6 dark:border-slate-800 sm:px-8">
                        <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                            Sua experiência importa
                        </p>

                        <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">
                            Como foi sua experiência?
                        </h1>

                        <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">
                            Queremos entender como foi utilizar o sistema de
                            triagem para melhorar a experiência dos pacientes.
                            Leva apenas alguns minutos.
                        </p>

                        <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">
                            Triagem #{{ triagem.id }}
                        </p>
                    </div>

                    <form
                        class="space-y-8 px-6 py-7 sm:px-8"
                        @submit.prevent="enviarFeedback"
                    >
                        <div>
                            <fieldset>
                                <legend class="text-base font-semibold text-slate-900 dark:text-white">
                                    1. Como você avalia sua experiência geral?
                                </legend>

                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                    Selecione de 1 a 5 estrelas.
                                </p>

                                <div class="mt-4 flex flex-wrap items-center gap-2">
                                    <button
                                        v-for="estrela in estrelas"
                                        :key="estrela"
                                        type="button"
                                        :aria-label="`${estrela} ${estrela === 1 ? 'estrela' : 'estrelas'}`"
                                        :aria-pressed="form.nota === estrela"
                                        class="rounded-lg p-1 text-4xl transition hover:scale-110 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        :class="estrela <= form.nota
                                            ? 'text-amber-400'
                                            : 'text-slate-300 dark:text-slate-600'"
                                        @click="form.nota = estrela"
                                    >
                                        {{ estrela <= form.nota ? '★' : '☆' }}
                                    </button>

                                    <span
                                        v-if="form.nota"
                                        class="ml-2 text-sm font-medium text-slate-600 dark:text-slate-300"
                                    >
                                        {{ form.nota }} de 5
                                    </span>
                                </div>

                                <p
                                    v-if="form.errors.nota"
                                    class="mt-2 text-sm text-red-600"
                                >
                                    {{ form.errors.nota }}
                                </p>
                            </fieldset>
                        </div>

                        <div>
                            <fieldset>
                                <legend class="text-base font-semibold text-slate-900 dark:text-white">
                                    2. Como foi a facilidade de uso?
                                </legend>

                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                    Considere a navegação e o preenchimento das informações.
                                </p>

                                <div class="mt-4 space-y-3">
                                    <label
                                        v-for="opcao in opcoesFacilidade"
                                        :key="opcao.value"
                                        class="flex cursor-pointer items-center gap-3 rounded-xl border p-3 transition"
                                        :class="form.facilidade === opcao.value
                                            ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-950/40'
                                            : 'border-slate-200 hover:border-slate-300 dark:border-slate-700 dark:hover:border-slate-600'"
                                    >
                                        <input
                                            v-model="form.facilidade"
                                            type="radio"
                                            name="facilidade"
                                            :value="opcao.value"
                                            class="h-4 w-4 accent-indigo-600"
                                        />

                                        <span class="text-sm text-slate-700 dark:text-slate-200">
                                            {{ opcao.label }}
                                        </span>
                                    </label>
                                </div>

                                <p
                                    v-if="form.errors.facilidade"
                                    class="mt-2 text-sm text-red-600"
                                >
                                    {{ form.errors.facilidade }}
                                </p>
                            </fieldset>
                        </div>

                        <div>
                            <fieldset>
                                <legend class="text-base font-semibold text-slate-900 dark:text-white">
                                    3. Como você avalia a clareza das perguntas e orientações?
                                </legend>

                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                    As mensagens foram fáceis de entender?
                                </p>

                                <div class="mt-4 space-y-3">
                                    <label
                                        v-for="opcao in opcoesClareza"
                                        :key="opcao.value"
                                        class="flex cursor-pointer items-center gap-3 rounded-xl border p-3 transition"
                                        :class="form.clareza === opcao.value
                                            ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-950/40'
                                            : 'border-slate-200 hover:border-slate-300 dark:border-slate-700 dark:hover:border-slate-600'"
                                    >
                                        <input
                                            v-model="form.clareza"
                                            type="radio"
                                            name="clareza"
                                            :value="opcao.value"
                                            class="h-4 w-4 accent-indigo-600"
                                        />

                                        <span class="text-sm text-slate-700 dark:text-slate-200">
                                            {{ opcao.label }}
                                        </span>
                                    </label>
                                </div>

                                <p
                                    v-if="form.errors.clareza"
                                    class="mt-2 text-sm text-red-600"
                                >
                                    {{ form.errors.clareza }}
                                </p>
                            </fieldset>
                        </div>

                        <div>
                            <label
                                for="comentario"
                                class="block text-base font-semibold text-slate-900 dark:text-white"
                            >
                                4. Quer deixar algum comentário?
                                <span class="font-normal text-slate-500">(opcional)</span>
                            </label>

                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                Conte o que funcionou bem ou o que poderia melhorar.
                                Evite incluir dados pessoais ou informações médicas.
                            </p>

                            <textarea
                                id="comentario"
                                v-model="form.comentario"
                                rows="4"
                                maxlength="1000"
                                placeholder="Escreva seu comentário..."
                                class="mt-4 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                            />

                            <div class="mt-1 flex justify-between gap-3">
                                <p
                                    v-if="form.errors.comentario"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.comentario }}
                                </p>

                                <span class="ml-auto text-xs text-slate-500">
                                    {{ form.comentario.length }}/1000
                                </span>
                            </div>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800/60">
                            <p class="text-xs leading-5 text-slate-600 dark:text-slate-300">
                                Este formulário avalia sua experiência de uso.
                                Sua resposta não representa uma avaliação da
                                precisão médica da inteligência artificial e
                                não substitui uma avaliação profissional.
                            </p>
                        </div>

                        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 dark:border-slate-800 sm:flex-row sm:justify-end">
                            <Link
                                :href="`/triagens/${triagem.id}`"
                                class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                            >
                                Cancelar
                            </Link>

                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex min-h-11 items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60"
                            >
                                {{
                                    form.processing
                                        ? 'Enviando...'
                                        : 'Enviar feedback'
                                }}
                            </button>
                        </div>
                    </form>
                </section>
            </main>
        </div>
    </AuthenticatedLayout>
</template>

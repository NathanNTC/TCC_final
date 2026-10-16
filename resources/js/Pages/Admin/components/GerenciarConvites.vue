<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    convites: {
        type: Array,
        default: () => [],
    },
})

const form = useForm({
    perfil: 'Estudante',
    expira_em: '',
})

const codigoCopiadoId = ref(null)

function criarConvite() {
    form.post('/admin/convites', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            form.perfil = 'Estudante'
        },
    })
}

async function copiarCodigo(codigo, id) {
    try {
        await navigator.clipboard.writeText(codigo)
        codigoCopiadoId.value = id

        setTimeout(() => {
            codigoCopiadoId.value = null
        }, 2000)
    } catch (erro) {
        console.error('Não foi possível copiar o código:', erro)
    }
}

function formatarData(data) {
    if (!data) return 'Sem expiração'

    const dataConvertida = new Date(data)

    if (Number.isNaN(dataConvertida.getTime())) {
        return data
    }

    return dataConvertida.toLocaleString('pt-BR', {
        dateStyle: 'short',
        timeStyle: 'short',
    })
}

function formatarPerfil(perfil) {
    const perfis = {
        Estudante: 'Estudante',
        Medico: 'Médico',
        Residente: 'Residente',
    }

    return perfis[perfil] ?? perfil ?? 'Não informado'
}

function codigoConvite(convite) {
    return convite.codigo ?? convite.code ?? convite.token ?? ''
}

/**
 * Verifica se o convite já foi utilizado.
 */
function conviteUtilizado(convite) {
    const camposBooleanos = [
        convite.utilizado,
        convite.usado,
        convite.is_used,
        convite.is_used_up,
    ]

    if (camposBooleanos.some(valor => valor === true || valor === 1)) {
        return true
    }

    const camposDeUso = [
        convite.utilizado_em,
        convite.usado_em,
        convite.used_at,
        convite.data_utilizacao,
        convite.utilizado_por,
        convite.usado_por,
        convite.used_by,
        convite.usuario_utilizador_id,
    ]

    return camposDeUso.some(valor =>
        valor !== null &&
        valor !== undefined &&
        valor !== '' &&
        valor !== false &&
        valor !== 0
    )
}

function conviteExpirado(convite) {
    if (!convite.expira_em && !convite.expires_at) {
        return false
    }

    const data = new Date(convite.expira_em ?? convite.expires_at)

    return !Number.isNaN(data.getTime()) && data < new Date()
}

function statusConvite(convite) {
    if (conviteUtilizado(convite)) {
        return 'Utilizado'
    }

    if (conviteExpirado(convite)) {
        return 'Expirado'
    }

    return 'Disponível'
}

function classesStatus(convite) {
    const status = statusConvite(convite)

    if (status === 'Utilizado') {
        return 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-950/50 dark:text-blue-400 dark:ring-blue-500/30'
    }

    if (status === 'Expirado') {
        return 'bg-rose-50 text-rose-700 ring-rose-600/20 dark:bg-rose-950/50 dark:text-rose-400 dark:ring-rose-500/30'
    }

    return 'bg-emerald-50 text-emerald-800 ring-emerald-600/20 dark:bg-emerald-950/50 dark:text-emerald-400 dark:ring-emerald-500/30'
}
</script>

<template>
    <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs dark:border-slate-800 dark:bg-slate-900">

        <!-- CABEÇALHO -->
        <div class="border-b border-slate-100 p-6 sm:p-8 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 ring-1 ring-emerald-600/10 dark:bg-emerald-950/50 dark:text-emerald-400 dark:ring-emerald-500/20">
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
                            d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z"
                        />
                    </svg>
                </div>

                <h2 class="text-base font-bold text-slate-900 dark:text-slate-100">
                    Gerenciar convites
                </h2>
            </div>

            <p class="mt-1 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                Gere e distribua códigos de acesso exclusivos para a avaliação de triagens.
            </p>
        </div>

        <!-- FORMULÁRIO -->
        <form
            class="space-y-5 bg-slate-50/50 p-6 sm:p-8 dark:bg-slate-800/30"
            @submit.prevent="criarConvite"
        >
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <div>
                    <label
                        for="perfil-convite"
                        class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300"
                    >
                        Perfil do avaliador
                    </label>

                    <select
                        id="perfil-convite"
                        v-model="form.perfil"
                        :disabled="form.processing"
                        class="w-full cursor-pointer rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 disabled:cursor-not-allowed disabled:opacity-60 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-emerald-500"
                        required
                    >
                        <option value="Estudante" class="dark:bg-slate-900">Estudante</option>
                        <option value="Residente" class="dark:bg-slate-900">Residente</option>
                        <option value="Medico" class="dark:bg-slate-900">Médico</option>
                    </select>

                    <p
                        v-if="form.errors.perfil"
                        class="mt-1 text-xs font-medium text-rose-600 dark:text-rose-400"
                    >
                        {{ form.errors.perfil }}
                    </p>
                </div>

                <div>
                    <label
                        for="expira-convite"
                        class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300"
                    >
                        Data de expiração (opcional)
                    </label>

                    <input
                        id="expira-convite"
                        v-model="form.expira_em"
                        type="datetime-local"
                        :disabled="form.processing"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 disabled:cursor-not-allowed disabled:opacity-60 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-emerald-500 [color-scheme:light] dark:[color-scheme:dark]"
                    />

                    <p
                        v-if="form.errors.expira_em"
                        class="mt-1 text-xs font-medium text-rose-600 dark:text-rose-400"
                    >
                        {{ form.errors.expira_em }}
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-emerald-600/20 transition hover:bg-emerald-700 hover:shadow-lg disabled:cursor-not-allowed disabled:opacity-50 dark:bg-emerald-600 dark:hover:bg-emerald-500"
                >
                    <svg
                        v-if="form.processing"
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
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                        />
                    </svg>

                    <span>
                        {{ form.processing ? 'Gerando...' : 'Gerar convite' }}
                    </span>
                </button>

                <p class="text-xs text-slate-400 dark:text-slate-500">
                    O código gerado será de uso único e vinculado ao perfil escolhido.
                </p>
            </div>

            <p
                v-if="form.errors.error"
                class="mt-2 text-xs font-medium text-rose-600 dark:text-rose-400"
            >
                {{ form.errors.error }}
            </p>
        </form>

        <!-- LISTA DE CONVITES -->
        <div class="border-t border-slate-100 p-6 sm:p-8 dark:border-slate-800">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">
                    Convites existentes
                </h3>

                <span class="text-xs font-medium text-slate-400 dark:text-slate-500">
                    {{ convites.length }}
                    {{ convites.length === 1 ? 'convite' : 'convites' }}
                </span>
            </div>

            <div
                v-if="convites.length"
                class="mt-4 grid grid-cols-1 gap-4 lg:grid-cols-2"
            >
                <article
                    v-for="(convite, indice) in convites"
                    :key="convite.id ?? indice"
                    class="flex flex-col justify-between rounded-xl border border-slate-200/80 bg-white p-4 transition hover:border-slate-300 hover:shadow-xs dark:border-slate-800 dark:bg-slate-900/80 dark:hover:border-slate-700"
                >
                    <div>
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <span class="text-sm font-bold text-slate-900 dark:text-slate-100">
                                    {{ formatarPerfil(convite.perfil) }}
                                </span>

                                <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">
                                    Expiração:
                                    {{ formatarData(convite.expira_em ?? convite.expires_at) }}
                                </p>
                            </div>

                            <span
                                :class="[
                                    'inline-flex shrink-0 items-center gap-1.5 rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset',
                                    classesStatus(convite)
                                ]"
                            >
                                <span
                                    class="h-1.5 w-1.5 rounded-full"
                                    :class="{
                                        'bg-blue-500': statusConvite(convite) === 'Utilizado',
                                        'bg-rose-500': statusConvite(convite) === 'Expirado',
                                        'bg-emerald-500': statusConvite(convite) === 'Disponível',
                                    }"
                                />

                                {{ statusConvite(convite) }}
                            </span>
                        </div>

                        <!-- CÓDIGO -->
                        <div class="mt-4 flex items-center gap-2">
                            <code class="min-w-0 flex-1 truncate rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 font-mono text-xs font-semibold text-slate-800 dark:border-slate-800 dark:bg-slate-800/60 dark:text-slate-200">
                                {{ codigoConvite(convite) || 'Código não informado' }}
                            </code>

                            <button
                                v-if="codigoConvite(convite)"
                                type="button"
                                :class="[
                                    'inline-flex shrink-0 cursor-pointer items-center gap-1.5 rounded-lg border px-3 py-2 text-xs font-semibold transition',
                                    codigoCopiadoId === (convite.id ?? indice)
                                        ? 'border-emerald-600 bg-emerald-600 text-white dark:border-emerald-500 dark:bg-emerald-500'
                                        : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800'
                                ]"
                                @click="copiarCodigo(codigoConvite(convite), convite.id ?? indice)"
                            >
                                <svg
                                    v-if="codigoCopiadoId === (convite.id ?? indice)"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="2.5"
                                    stroke="currentColor"
                                    class="h-3.5 w-3.5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="m4.5 12.75 6 6 9-13.5"
                                    />
                                </svg>

                                <svg
                                    v-else
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.8"
                                    stroke="currentColor"
                                    class="h-3.5 w-3.5 text-slate-500 dark:text-slate-400"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75"
                                    />
                                </svg>

                                <span>
                                    {{ codigoCopiadoId === (convite.id ?? indice) ? 'Copiado!' : 'Copiar' }}
                                </span>
                            </button>
                        </div>

                        <p
                            v-if="conviteUtilizado(convite)"
                            class="mt-3 text-xs text-blue-700 dark:text-blue-400"
                        >
                            Este código já foi utilizado e não deve ser usado novamente.
                        </p>

                        <p
                            v-else-if="conviteExpirado(convite)"
                            class="mt-3 text-xs text-rose-600 dark:text-rose-400"
                        >
                            A validade deste convite terminou.
                        </p>
                    </div>
                </article>
            </div>

            <!-- ESTADO VAZIO -->
            <div
                v-else
                class="py-8 text-center text-xs text-slate-400 dark:text-slate-500"
            >
                Nenhum convite gerado até o momento.
            </div>
        </div>
    </section>
</template>
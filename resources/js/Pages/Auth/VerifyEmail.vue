<script setup>
import { Head, useForm, router } from '@inertiajs/vue3'
import { useDarkMode } from '@/Composables/useDarkMode'

const { isDark, alternarTema } = useDarkMode()

const form = useForm({})

const reenviarEmail = () => {
    form.post('/email/verification-notification')
}

const sair = () => {
    router.post('/logout', {})
}
</script>

<template>
    <Head title="Verificar e-mail" />

    <div class="relative flex min-h-screen items-center justify-center bg-slate-50 px-4 dark:bg-slate-950">
        <button
            type="button"
            @click="alternarTema"
            class="absolute right-5 top-5 rounded-xl border border-slate-200 bg-white p-3 text-slate-600 transition hover:bg-slate-100 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
            :title="isDark ? 'Mudar para tema claro' : 'Mudar para tema escuro'"
            :aria-label="isDark ? 'Ativar tema claro' : 'Ativar tema escuro'"
        >
            <svg
                v-if="isDark"
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <circle cx="12" cy="12" r="4" />
                <path d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42" />
            </svg>

            <svg
                v-else
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <path d="M20.985 12.486a9 9 0 1 1-9.473-9.472c.405-.022.617.46.402.803a6.5 6.5 0 0 0 8.268 8.268c.344-.215.825-.003.803.401Z" />
            </svg>
        </button>

        <main class="w-full max-w-md rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/50 dark:border-slate-800 dark:bg-slate-900 dark:shadow-black/20 sm:p-10">
            <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-8 w-8"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <rect x="3" y="5" width="18" height="14" rx="2" />
                    <path d="m3 7 9 6 9-6" />
                </svg>
            </div>

            <h1 class="text-center text-2xl font-bold text-slate-900 dark:text-white">
                Verifique seu e-mail
            </h1>

            <p class="mt-4 text-center text-sm leading-6 text-slate-600 dark:text-slate-300">
                Antes de continuar, confirme seu endereço de e-mail pelo link que enviamos para você.
                Se não recebeu a mensagem, solicite um novo envio abaixo.
            </p>

            <div
                v-if="$page.props.status === 'verification-link-sent'"
                class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-300"
                role="status"
            >
                Um novo link de verificação foi enviado para o e-mail cadastrado.
            </div>

            <div
                v-if="form.errors.email"
                class="mt-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300"
                role="alert"
            >
                {{ form.errors.email }}
            </div>

            <form @submit.prevent="reenviarEmail" class="mt-7">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="flex w-full items-center justify-center rounded-xl bg-emerald-600 px-4 py-3 font-semibold text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-60"
                >
                    {{ form.processing ? 'Enviando...' : 'Reenviar e-mail de verificação' }}
                </button>
            </form>

            <form @submit.prevent="sair" class="mt-4">
                <button
                    type="submit"
                    class="w-full rounded-xl px-4 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800"
                >
                    Sair da conta
                </button>
            </form>
        </main>
    </div>
</template>

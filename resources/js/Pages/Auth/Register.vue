<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post('/register', {
        onFinish: () => {
            form.reset('password', 'password_confirmation')
        },
    })
}
</script>

<template>
    <Head title="Criar conta" />

    <div class="flex min-h-screen items-center justify-center bg-slate-50 px-4 transition-colors duration-200 dark:bg-slate-950">
        <div class="w-full max-w-md">

            <!-- LOGO / TÍTULO -->
            <div class="mb-8 text-center">
                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/20 dark:bg-emerald-600 dark:shadow-emerald-900/40"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-7 w-7"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 21s8-4.5 8-10.5V5.25L12 3 4 5.25v5.25C4 16.5 12 21 12 21Z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12l2 2 4-4"
                        />
                    </svg>
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100">
                    Triagem IA
                </h1>

                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    Crie sua conta para começar.
                </p>
            </div>

            <!-- CARD -->
            <div
                class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5 sm:p-8 dark:bg-slate-900 dark:ring-slate-800"
            >
                <form
                    @submit.prevent="submit"
                    class="space-y-5"
                >

                    <!-- NOME -->
                    <div>
                        <label
                            for="name"
                            class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300"
                        >
                            Nome completo
                        </label>

                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            autocomplete="name"
                            autofocus
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-emerald-500"
                            placeholder="Seu nome completo"
                        />

                        <p
                            v-if="form.errors.name"
                            class="mt-2 text-sm text-red-600 dark:text-rose-400"
                        >
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- E-MAIL -->
                    <div>
                        <label
                            for="email"
                            class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300"
                        >
                            E-mail
                        </label>

                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            autocomplete="username"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-emerald-500"
                            placeholder="seu@email.com"
                        />

                        <p
                            v-if="form.errors.email"
                            class="mt-2 text-sm text-red-600 dark:text-rose-400"
                        >
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- SENHA -->
                    <div>
                        <label
                            for="password"
                            class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300"
                        >
                            Senha
                        </label>

                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-emerald-500"
                            placeholder="••••••••"
                        />

                        <p
                            v-if="form.errors.password"
                            class="mt-2 text-sm text-red-600 dark:text-rose-400"
                        >
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- CONFIRMAR SENHA -->
                    <div>
                        <label
                            for="password_confirmation"
                            class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300"
                        >
                            Confirmar senha
                        </label>

                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-emerald-500"
                            placeholder="••••••••"
                        />

                        <p
                            v-if="form.errors.password_confirmation"
                            class="mt-2 text-sm text-red-600 dark:text-rose-400"
                        >
                            {{ form.errors.password_confirmation }}
                        </p>
                    </div>

                    <!-- BOTÃO -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 px-5 py-3 text-sm font-semibold text-white shadow-md shadow-emerald-500/20 transition-all duration-200 hover:-translate-y-0.5 hover:from-emerald-500 hover:to-teal-500 hover:shadow-lg hover:shadow-emerald-500/30 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0 dark:focus:ring-offset-slate-900"
                    >
                        <svg
                            v-if="form.processing"
                            class="h-4 w-4 animate-spin text-white"
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
                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4Z"
                            />
                        </svg>

                        {{ form.processing ? 'Criando conta...' : 'Criar conta' }}
                    </button>

                </form>

                <!-- LOGIN -->
                <div
                    class="mt-6 border-t border-slate-100 pt-6 text-center dark:border-slate-800"
                >
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Já possui uma conta?
                    </p>

                    <Link
                        href="/login"
                        class="mt-2 inline-block text-sm font-semibold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300"
                    >
                        Entrar na conta
                    </Link>
                </div>

            </div>

            <p class="mt-6 text-center text-xs text-slate-400 dark:text-slate-500">
                Sistema de apoio à coleta de dados clínicos.
            </p>

        </div>
    </div>
</template>
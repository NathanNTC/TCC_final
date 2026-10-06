<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({
    canResetPassword: {
        type: Boolean,
        default: true,
    },
    status: {
        type: String,
        default: null,
    },
})

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post('/login', {
        onFinish: () => {
            form.reset('password')
        },
    })
}
</script>

<template>
    <Head title="Entrar" />

    <div class="min-h-screen bg-slate-50 flex items-center justify-center px-4">
        <div class="w-full max-w-md">

            <!-- LOGO / TÍTULO -->

            <div class="mb-8 text-center">
                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center
                           justify-center rounded-2xl bg-emerald-600
                           text-white shadow-lg shadow-emerald-600/20"
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

                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    Triagem IA
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Entre na sua conta para continuar.
                </p>
            </div>

            <!-- CARD -->

            <div
                class="rounded-2xl bg-white p-6 shadow-sm
                       ring-1 ring-slate-900/5 sm:p-8"
            >

                <!-- STATUS -->

                <div
                    v-if="status"
                    class="mb-5 rounded-xl bg-emerald-50 px-4 py-3
                           text-sm font-medium text-emerald-700"
                >
                    {{ status }}
                </div>

                <form
                    @submit.prevent="submit"
                    class="space-y-5"
                >

                    <!-- E-MAIL -->

                    <div>
                        <label
                            for="email"
                            class="mb-2 block text-sm font-semibold
                                   text-slate-700"
                        >
                            E-mail
                        </label>

                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            autocomplete="username"
                            autofocus
                            required
                            class="w-full rounded-xl border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-900
                                   outline-none transition
                                   placeholder:text-slate-400
                                   focus:border-emerald-500
                                   focus:ring-4 focus:ring-emerald-500/10"
                            placeholder="seu@email.com"
                        />

                        <p
                            v-if="form.errors.email"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- SENHA -->

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label
                                for="password"
                                class="block text-sm font-semibold
                                       text-slate-700"
                            >
                                Senha
                            </label>

                            <Link
                                v-if="canResetPassword"
                                href="/forgot-password"
                                class="text-sm font-medium text-emerald-600
                                       hover:text-emerald-700"
                            >
                                Esqueci minha senha
                            </Link>
                        </div>

                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            autocomplete="current-password"
                            required
                            class="w-full rounded-xl border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-900
                                   outline-none transition
                                   placeholder:text-slate-400
                                   focus:border-emerald-500
                                   focus:ring-4 focus:ring-emerald-500/10"
                            placeholder="••••••••"
                        />

                        <p
                            v-if="form.errors.password"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- LEMBRAR -->

                    <label
                        class="flex cursor-pointer items-center gap-3"
                    >
                        <input
                            v-model="form.remember"
                            type="checkbox"
                            class="h-4 w-4 rounded border-slate-300
                                   text-emerald-600 focus:ring-emerald-500"
                        />

                        <span class="text-sm text-slate-600">
                            Lembrar de mim
                        </span>
                    </label>

                    <!-- BOTÃO -->

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex w-full items-center justify-center
                               gap-2 rounded-xl bg-gradient-to-r
                               from-emerald-600 to-teal-600 px-5 py-3
                               text-sm font-semibold text-white
                               shadow-md shadow-emerald-500/20
                               transition-all duration-200
                               hover:-translate-y-0.5
                               hover:from-emerald-500 hover:to-teal-500
                               hover:shadow-lg
                               hover:shadow-emerald-500/30
                               focus:outline-none focus:ring-2
                               focus:ring-emerald-500 focus:ring-offset-2
                               disabled:cursor-not-allowed
                               disabled:opacity-60
                               disabled:hover:translate-y-0"
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
                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4Z"
                            />
                        </svg>

                        {{ form.processing ? 'Entrando...' : 'Entrar' }}
                    </button>

                </form>

                <!-- CADASTRO -->

                <div
                    class="mt-6 border-t border-slate-100 pt-6
                           text-center"
                >
                    <p class="text-sm text-slate-500">
                        Ainda não possui uma conta?
                    </p>

                    <Link
                        href="/register"
                        class="mt-2 inline-block text-sm font-semibold
                               text-emerald-600 hover:text-emerald-700"
                    >
                        Criar uma conta
                    </Link>
                </div>

            </div>

            <p class="mt-6 text-center text-xs text-slate-400">
                Sistema de apoio à coleta de dados clínicos.
            </p>

        </div>
    </div>
</template>
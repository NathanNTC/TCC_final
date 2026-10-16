<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { useDarkMode } from '@/Composables/useDarkMode'

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

const { isDark, alternarTema } = useDarkMode()

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

    <div
        class="relative flex min-h-screen items-center justify-center
               bg-slate-50 px-4 dark:bg-slate-950"
    >
        <!-- BOTÃO DE ALTERNAR TEMA -->
        <div class="absolute right-4 top-4 sm:right-6 sm:top-6">
            <button
                type="button"
                @click="alternarTema"
                class="flex h-10 w-10 items-center justify-center
                       rounded-xl border border-slate-200 bg-white
                       text-slate-600 shadow-sm transition
                       hover:bg-slate-50 focus:outline-none
                       focus:ring-2 focus:ring-emerald-500
                       dark:border-slate-800 dark:bg-slate-900
                       dark:text-slate-300 dark:hover:bg-slate-800"
                :title="isDark ? 'Mudar para modo claro' : 'Mudar para modo escuro'"
                :aria-label="isDark ? 'Mudar para modo claro' : 'Mudar para modo escuro'"
            >
                <svg
                    v-if="isDark"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="h-5 w-5 text-amber-400"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 3v2.25m6.364.386-1.591 1.591
                           M21 12h-2.25m-.386 6.364-1.591-1.591
                           M12 18.75V21m-4.773-4.227-1.591 1.591
                           M5.25 12H3m4.227-4.773L5.636 5.636
                           M15.75 12a3.75 3.75 0 1 1-7.5 0
                           3.75 3.75 0 0 1 7.5 0Z"
                    />
                </svg>

                <svg
                    v-else
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="h-5 w-5 text-slate-600"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75
                           c-5.385 0-9.75-4.365-9.75-9.75
                           0-1.33.266-2.597.748-3.752
                           A9.753 9.753 0 0 0 3 11.25
                           C3 16.635 7.365 21 12.75 21
                           a9.753 9.753 0 0 0 9.002-5.998Z"
                    />
                </svg>
            </button>
        </div>

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
                            d="M12 21s8-4.5 8-10.5V5.25L12 3
                               4 5.25v5.25C4 16.5 12 21 12 21Z"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m9 12 2 2 4-4"
                        />
                    </svg>
                </div>

                <h1
                    class="text-2xl font-bold tracking-tight
                           text-slate-900 dark:text-slate-100"
                >
                    Triagem IA
                </h1>

                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    Entre na sua conta para continuar.
                </p>
            </div>

            <!-- CARD DE LOGIN -->
            <div
                class="rounded-2xl bg-white p-6 shadow-sm
                       ring-1 ring-slate-900/5 sm:p-8
                       dark:bg-slate-900 dark:ring-slate-800"
            >
                <!-- STATUS -->
                <div
                    v-if="status"
                    class="mb-5 rounded-xl bg-emerald-50 px-4 py-3
                           text-sm font-medium text-emerald-700
                           dark:bg-emerald-950/50 dark:text-emerald-400"
                >
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- E-MAIL -->
                    <div>
                        <label
                            for="email"
                            class="mb-2 block text-sm font-semibold
                                   text-slate-700 dark:text-slate-300"
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
                                   focus:ring-4 focus:ring-emerald-500/10
                                   dark:border-slate-800 dark:bg-slate-950
                                   dark:text-slate-100
                                   dark:placeholder:text-slate-600
                                   dark:focus:border-emerald-500
                                   dark:focus:ring-emerald-500/20"
                            placeholder="seu@email.com"
                        />

                        <p
                            v-if="form.errors.email"
                            class="mt-2 text-sm text-red-600 dark:text-red-400"
                        >
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- SENHA -->
                    <div>
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <label
                                for="password"
                                class="block text-sm font-semibold
                                       text-slate-700 dark:text-slate-300"
                            >
                                Senha
                            </label>

                            <Link
                                v-if="canResetPassword"
                                href="/forgot-password"
                                class="text-sm font-medium text-emerald-600
                                       hover:text-emerald-700
                                       dark:text-emerald-400
                                       dark:hover:text-emerald-300"
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
                                   focus:ring-4 focus:ring-emerald-500/10
                                   dark:border-slate-800 dark:bg-slate-950
                                   dark:text-slate-100
                                   dark:placeholder:text-slate-600
                                   dark:focus:border-emerald-500
                                   dark:focus:ring-emerald-500/20"
                            placeholder="••••••••"
                        />

                        <p
                            v-if="form.errors.password"
                            class="mt-2 text-sm text-red-600 dark:text-red-400"
                        >
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- LEMBRAR DE MIM -->
                    <label class="flex cursor-pointer items-center gap-3">
                        <input
                            v-model="form.remember"
                            type="checkbox"
                            class="h-4 w-4 rounded border-slate-300
                                   text-emerald-600 focus:ring-emerald-500
                                   dark:border-slate-800 dark:bg-slate-950"
                        />

                        <span class="text-sm text-slate-600 dark:text-slate-400">
                            Lembrar de mim
                        </span>
                    </label>

                    <!-- BOTÃO ENTRAR -->
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
                               hover:shadow-lg hover:shadow-emerald-500/30
                               focus:outline-none focus:ring-2
                               focus:ring-emerald-500 focus:ring-offset-2
                               disabled:cursor-not-allowed disabled:opacity-60
                               disabled:hover:translate-y-0
                               dark:focus:ring-offset-slate-900"
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
                                d="M4 12a8 8 0 0 1 8-8v4
                                   a4 4 0 0 0-4 4H4Z"
                            />
                        </svg>

                        {{ form.processing ? 'Entrando...' : 'Entrar' }}
                    </button>
                </form>

                <!-- OPÇÕES DE CADASTRO -->
                <div
                    class="mt-6 border-t border-slate-100 pt-6
                           dark:border-slate-800"
                >
                    <!-- CADASTRO DE PACIENTE -->
                    <div class="text-center">
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Ainda não possui uma conta?
                        </p>

                        <Link
                            href="/register"
                            class="mt-3 flex w-full items-center
                                   justify-center gap-2 rounded-xl
                                   bg-gradient-to-r from-emerald-600
                                   to-teal-600 px-5 py-3 text-sm
                                   font-semibold text-white
                                   shadow-md shadow-emerald-500/20
                                   transition-all duration-200
                                   hover:-translate-y-0.5
                                   hover:from-emerald-500 hover:to-teal-500
                                   hover:shadow-lg
                                   hover:shadow-emerald-500/30
                                   focus:outline-none focus:ring-2
                                   focus:ring-emerald-500 focus:ring-offset-2
                                   dark:focus:ring-offset-slate-900"
                        >
                            Criar uma conta
                        </Link>
                    </div>

                    <!-- CADASTRO DE AVALIADOR -->
                    <div
                        class="mt-5 border-t border-slate-100 pt-5
                               dark:border-slate-800"
                    >
                        <p class="text-center text-sm text-slate-500 dark:text-slate-400">
                            É estudante ou profissional da área médica?
                        </p>

                        <Link
                            href="/avaliador/cadastro"
                            class="mt-3 flex w-full items-center
                                   justify-center gap-2 rounded-xl
                                   border border-emerald-600 bg-white
                                   px-5 py-3 text-sm font-semibold
                                   text-emerald-700 shadow-sm
                                   transition-all duration-200
                                   hover:-translate-y-0.5 hover:bg-emerald-50
                                   hover:shadow-md
                                   focus:outline-none focus:ring-2
                                   focus:ring-emerald-500 focus:ring-offset-2
                                   dark:border-emerald-500 dark:bg-slate-900
                                   dark:text-emerald-400
                                   dark:hover:bg-slate-800
                                   dark:focus:ring-offset-slate-900"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-5 w-5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 0 0
                                       3.75.772 9.38 9.38 0 0 0
                                       3.75-.772m-7.5 0v-.003
                                       c0-1.113-.285-2.16-.786-3.07
                                       M15 19.128v.003A9.38 9.38 0 0 1
                                       12 19.5a9.38 9.38 0 0 1-3-.369
                                       m6-3.076a5.25 5.25 0 0 0-6 0
                                       M9 16.055a5.25 5.25 0 0 0-6 0
                                       M9 16.055A5.25 5.25 0 0 1 12 15
                                       c1.1 0 2.12.337 3 .93
                                       M9 16.055v.003c0 1.113.285 2.16.786 3.07
                                       M9 16.055v.003A9.38 9.38 0 0 1
                                       12 19.5m0-10.5a3 3 0 1 0 0-6
                                       3 3 0 0 0 0 6Z"
                                />
                            </svg>

                            Cadastrar como avaliador
                        </Link>
                    </div>
                </div>
            </div>

            <!-- RODAPÉ -->
            <p class="mt-6 text-center text-xs text-slate-400 dark:text-slate-500">
                Sistema de apoio à coleta de dados clínicos.
            </p>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { useDarkMode } from '@/Composables/useDarkMode'

const props = defineProps({
    email: {
        type: String,
        default: '',
    },
    token: {
        type: String,
        required: true,
    },
})

const { isDark, alternarTema } = useDarkMode()

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post('/reset-password', {
        onFinish: () => {
            form.reset('password', 'password_confirmation')
        },
    })
}
</script>

<template>
    <Head title="Definir nova senha" />

    <div class="relative flex min-h-screen items-center justify-center bg-slate-50 px-4 transition-colors duration-200 dark:bg-slate-950">
        <!-- ALTERNAR TEMA -->
        <div class="absolute right-4 top-4 sm:right-6 sm:top-6">
            <button
                type="button"
                @click="alternarTema"
                class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
                :title="isDark ? 'Mudar para modo claro' : 'Mudar para modo escuro'"
            >
                <svg v-if="isDark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-amber-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                </svg>

                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                </svg>
            </button>
        </div>

        <div class="w-full max-w-md">
            <!-- LOGO / TÍTULO -->
            <div class="mb-8 text-center">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/20 dark:shadow-emerald-900/40">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-7 w-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 0 0-9 0v3.75m-1.5 0h12a1.5 1.5 0 0 1 1.5 1.5v7.5a1.5 1.5 0 0 1-1.5 1.5h-12a1.5 1.5 0 0 1-1.5-1.5V12a1.5 1.5 0 0 1 1.5-1.5Z" />
                    </svg>
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100">
                    Definir nova senha
                </h1>

                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    Escolha uma nova senha para sua conta.
                </p>
            </div>

            <!-- CARD -->
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5 sm:p-8 dark:bg-slate-900 dark:ring-slate-800">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            E-mail
                        </label>

                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            autocomplete="email"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-emerald-500"
                        />

                        <p v-if="form.errors.email" class="mt-2 text-sm text-red-600 dark:text-rose-400">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Nova senha
                        </label>

                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-600 dark:focus:border-emerald-500"
                            placeholder="Digite sua nova senha"
                        />

                        <p v-if="form.errors.password" class="mt-2 text-sm text-red-600 dark:text-rose-400">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Confirmar nova senha
                        </label>

                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 dark:placeholder:text-slate-600 dark:focus:border-emerald-500"
                            placeholder="Digite novamente a nova senha"
                        />

                        <p v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-600 dark:text-rose-400">
                            {{ form.errors.password_confirmation }}
                        </p>
                    </div>

                    <p v-if="form.errors.token" class="text-sm text-red-600 dark:text-rose-400">
                        {{ form.errors.token }}
                    </p>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex w-full cursor-pointer items-center justify-center rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:from-emerald-500 hover:to-teal-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 disabled:cursor-not-allowed disabled:opacity-60 dark:focus:ring-offset-slate-900"
                    >
                        {{ form.processing ? 'Salvando...' : 'Alterar senha' }}
                    </button>
                </form>

                <div class="mt-6 border-t border-slate-100 pt-6 text-center dark:border-slate-800">
                    <Link href="/login" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300">
                        Voltar para o login
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
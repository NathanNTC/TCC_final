<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    email: '',
    codigo: '',
    password: '',
    password_confirmation: '',
})

function cadastrar() {
    if (form.processing) return

    form.post('/avaliador/cadastro', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template>
    <Head title="Cadastro de Avaliador" />

    <div class="flex min-h-screen items-center justify-center bg-slate-50/50 px-4 py-10 sm:px-6 lg:px-8">
        <div class="w-full max-w-lg rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs sm:p-8">
            
            <!-- CABEÇALHO -->
            <div class="mb-8 text-center">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-600/10">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-7 w-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"
                        />
                    </svg>
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Cadastro de avaliador
                </h1>

                <p class="mt-2 text-sm leading-relaxed text-slate-500">
                    Crie sua conta utilizando o convite fornecido pelo administrador.
                </p>
            </div>

            <!-- FORMULÁRIO -->
            <form class="space-y-5" @submit.prevent="cadastrar">
                
                <!-- NOME -->
                <div>
                    <label for="name" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-700">
                        Nome completo
                    </label>

                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        autocomplete="name"
                        required
                        maxlength="255"
                        :disabled="form.processing"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition-all placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/10 disabled:cursor-not-allowed disabled:opacity-60"
                        placeholder="Seu nome completo"
                    />

                    <p v-if="form.errors.name" class="mt-1 text-xs font-medium text-rose-600">
                        {{ form.errors.name }}
                    </p>
                </div>

                <!-- EMAIL -->
                <div>
                    <label for="email" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-700">
                        E-mail
                    </label>

                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        autocomplete="email"
                        required
                        :disabled="form.processing"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition-all placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/10 disabled:cursor-not-allowed disabled:opacity-60"
                        placeholder="voce@exemplo.com"
                    />

                    <p v-if="form.errors.email" class="mt-1 text-xs font-medium text-rose-600">
                        {{ form.errors.email }}
                    </p>
                </div>

                <!-- CÓDIGO DO CONVITE -->
                <div>
                    <label for="codigo" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-700">
                        Código do convite
                    </label>

                    <input
                        id="codigo"
                        v-model="form.codigo"
                        type="text"
                        autocomplete="off"
                        required
                        maxlength="64"
                        :disabled="form.processing"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 uppercase outline-none transition-all placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/10 disabled:cursor-not-allowed disabled:opacity-60"
                        placeholder="Ex.: AB12CD34"
                    />

                    <p class="mt-1.5 text-xs text-slate-400">
                        O convite é de uso único e determina seu perfil de acesso.
                    </p>

                    <p v-if="form.errors.codigo" class="mt-1 text-xs font-medium text-rose-600">
                        {{ form.errors.codigo }}
                    </p>
                </div>

                <!-- SENHA -->
                <div>
                    <label for="password" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-700">
                        Senha
                    </label>

                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        autocomplete="new-password"
                        required
                        :disabled="form.processing"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition-all placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/10 disabled:cursor-not-allowed disabled:opacity-60"
                        placeholder="Crie uma senha"
                    />

                    <p v-if="form.errors.password" class="mt-1 text-xs font-medium text-rose-600">
                        {{ form.errors.password }}
                    </p>
                </div>

                <!-- CONFIRMAÇÃO DE SENHA -->
                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-700">
                        Confirmar senha
                    </label>

                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        required
                        :disabled="form.processing"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition-all placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/10 disabled:cursor-not-allowed disabled:opacity-60"
                        placeholder="Digite a senha novamente"
                    />
                </div>

                <!-- BOTÃO DE CADASTRO -->
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-md shadow-emerald-600/20 transition-all hover:bg-emerald-700 hover:shadow-lg active:scale-98 disabled:cursor-not-allowed disabled:opacity-60 disabled:shadow-none cursor-pointer"
                >
                    <svg
                        v-if="form.processing"
                        class="h-4 w-4 animate-spin text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>

                    <span>{{ form.processing ? 'Criando conta...' : 'Criar conta de avaliador' }}</span>
                </button>
            </form>

            <!-- FOOTER -->
            <div class="mt-6 text-center text-sm text-slate-500">
                Já possui uma conta?
                <Link
                    href="/login"
                    class="font-semibold text-emerald-600 transition-colors hover:text-emerald-700"
                >
                    Entrar
                </Link>
            </div>

        </div>
    </div>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, usePage } from '@inertiajs/vue3'

const page = usePage()

const user = page.props.auth?.user
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>

        <!-- Cabeçalho -->
        <div class="mb-8">

            <p class="text-sm font-medium text-blue-600">
                Dashboard
            </p>

            <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">
                Olá, {{ user?.name }}! 👋
            </h1>

            <p class="mt-2 text-slate-500">
                Acompanhe suas informações de saúde e suas triagens.
            </p>

        </div>


        <!-- Cards -->
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">

            <!-- Perfil -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Perfil de saúde
                        </p>

                        <p class="mt-2 text-3xl font-bold text-slate-900">
                            85%
                        </p>
                    </div>

                    <div
                        class="flex h-12 w-12 items-center justify-center
                               rounded-xl bg-blue-50 text-xl"
                    >
                        👤
                    </div>

                </div>

                <div class="mt-5 h-2 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full w-[85%] rounded-full bg-blue-600"></div>
                </div>

                <Link
                    href="/perfil"
                    class="mt-4 inline-block text-sm font-semibold text-blue-600 hover:text-blue-700"
                >
                    Completar perfil →
                </Link>

            </div>


            <!-- Triagens -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Triagens realizadas
                        </p>

                        <p class="mt-2 text-3xl font-bold text-slate-900">
                            0
                        </p>
                    </div>

                    <div
                        class="flex h-12 w-12 items-center justify-center
                               rounded-xl bg-emerald-50 text-xl"
                    >
                        🩺
                    </div>

                </div>

                <p class="mt-5 text-sm text-slate-500">
                    Nenhuma triagem realizada ainda.
                </p>

            </div>


            <!-- Status -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Sistema
                        </p>

                        <p class="mt-2 text-xl font-bold text-emerald-600">
                            Operacional
                        </p>
                    </div>

                    <div
                        class="flex h-12 w-12 items-center justify-center
                               rounded-xl bg-emerald-50"
                    >
                        ✓
                    </div>

                </div>

                <p class="mt-5 text-sm text-slate-500">
                    Todos os serviços estão funcionando normalmente.
                </p>

            </div>

        </div>


        <!-- Nova triagem -->
        <div
            class="mt-8 overflow-hidden rounded-2xl
                   border border-blue-100 bg-gradient-to-r
                   from-blue-600 to-blue-700 p-8 text-white shadow-sm"
        >

            <div class="max-w-2xl">

                <div class="mb-4 text-3xl">
                    🩺
                </div>

                <h2 class="text-2xl font-bold">
                    Precisa realizar uma triagem?
                </h2>

                <p class="mt-2 leading-6 text-blue-100">
                    Informe seus sintomas e responda às perguntas.
                    O sistema utilizará inteligência artificial para
                    auxiliar na classificação da sua situação.
                </p>

                <Link
                    href="/triagem"
                    class="mt-6 inline-flex items-center rounded-xl
                           bg-white px-5 py-3 text-sm font-semibold
                           text-blue-700 shadow-sm transition
                           hover:bg-blue-50"
                >
                    Iniciar triagem
                    <span class="ml-2">→</span>
                </Link>

            </div>

        </div>


        <!-- Informações -->
        <div class="mt-8 grid gap-6 lg:grid-cols-2">

            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <h2 class="text-lg font-bold text-slate-900">
                    Sobre a triagem
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    A triagem tem como objetivo coletar informações
                    sobre os sintomas apresentados e auxiliar na
                    classificação da prioridade de atendimento.
                </p>

            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <h2 class="text-lg font-bold text-slate-900">
                    Seus dados
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Mantenha seu perfil de saúde atualizado para que
                    as informações relevantes possam ser consideradas
                    durante a triagem.
                </p>

                <Link
                    href="/perfil"
                    class="mt-4 inline-block text-sm font-semibold text-blue-600"
                >
                    Ver meu perfil →
                </Link>

            </div>

        </div>

    </AuthenticatedLayout>

</template>
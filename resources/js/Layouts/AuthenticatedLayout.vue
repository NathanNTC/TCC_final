<script setup>
import { ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const sidebarOpen = ref(false)

const page = usePage()

const user = page.props.auth?.user
</script>

<template>
    <div class="min-h-screen bg-slate-50">

        <!-- Sidebar mobile -->
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 z-40 bg-black/40 lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col
                   border-r border-slate-200 bg-white
                   transition-transform duration-300
                   lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >

            <!-- Logo -->
            <div class="flex h-20 items-center gap-3 border-b border-slate-200 px-6">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600">
                    <span class="text-xl text-white">
                        🩺
                    </span>
                </div>

                <div>
                    <h1 class="text-lg font-bold text-slate-900">
                        TR-IA
                    </h1>

                    <p class="text-xs text-slate-500">
                        Triagem Inteligente
                    </p>
                </div>

            </div>

            <!-- Menu -->
            <nav class="flex-1 space-y-1 px-4 py-6">

                <Link
                    href="/dashboard"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm
                           font-medium text-slate-600 transition
                           hover:bg-blue-50 hover:text-blue-600"
                >
                    <span>⌂</span>
                    Dashboard
                </Link>

                <Link
                    href="/perfil"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm
                           font-medium text-slate-600 transition
                           hover:bg-blue-50 hover:text-blue-600"
                >
                    <span>👤</span>
                    Meu perfil
                </Link>

                <Link
                    href="/triagem"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm
                           font-medium text-slate-600 transition
                           hover:bg-blue-50 hover:text-blue-600"
                >
                    <span>🩺</span>
                    Nova triagem
                </Link>

                <Link
                    href="/triagens"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm
                           font-medium text-slate-600 transition
                           hover:bg-blue-50 hover:text-blue-600"
                >
                    <span>📋</span>
                    Histórico
                </Link>

            </nav>

            <!-- Usuário -->
            <div class="border-t border-slate-200 p-4">

                <div class="flex items-center gap-3 rounded-xl bg-slate-50 p-3">

                    <div
                        class="flex h-10 w-10 items-center justify-center
                               rounded-full bg-blue-100 font-semibold text-blue-700"
                    >
                        {{ user?.name?.charAt(0)?.toUpperCase() }}
                    </div>

                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-slate-800">
                            {{ user?.name }}
                        </p>

                        <p class="truncate text-xs text-slate-500">
                            Paciente
                        </p>
                    </div>

                </div>

            </div>

        </aside>


        <!-- Conteúdo -->
        <div class="lg:pl-64">

            <!-- Topbar -->
            <header
                class="sticky top-0 z-30 flex h-20 items-center
                       justify-between border-b border-slate-200
                       bg-white/90 px-6 backdrop-blur"
            >

                <button
                    type="button"
                    class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden"
                    @click="sidebarOpen = true"
                >
                    ☰
                </button>

                <div class="hidden lg:block">
                    <p class="text-sm text-slate-500">
                        Sistema de Triagem Médica
                    </p>
                </div>

                <div class="flex items-center gap-4">

                    <button
                        type="button"
                        class="relative rounded-xl p-2.5 text-slate-500
                               hover:bg-slate-100"
                    >
                        🔔
                    </button>

                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-semibold text-slate-800">
                            {{ user?.name }}
                        </p>

                        <p class="text-xs text-slate-500">
                            Paciente
                        </p>
                    </div>

                </div>

            </header>


            <!-- Página -->
            <main class="p-6 lg:p-8">
                <slot />
            </main>

        </div>

    </div>
</template>
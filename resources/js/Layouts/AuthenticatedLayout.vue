<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Sidebar from '@/Components/Sidebar.vue'

const page = usePage()

const user = computed(() => page.props.auth?.user)

// Tipo de usuário normalizado
const tipoUsuario = computed(() => {
    return (user.value?.tipo_usuario ?? user.value?.tipo ?? '').toLowerCase()
})

// Rótulo e estilo dinâmico do perfil
const perfilInfo = computed(() => {
    if (tipoUsuario.value === 'admin' || tipoUsuario.value === 'administrador') {
        return { label: 'Administrador', badgeClass: 'bg-amber-100 text-amber-800 ring-amber-600/20' }
    }
    if (tipoUsuario.value === 'medico' || tipoUsuario.value === 'médico') {
        return { label: 'Corpo Médico', badgeClass: 'bg-emerald-100 text-emerald-800 ring-emerald-600/20' }
    }
    return { label: 'Paciente', badgeClass: 'bg-slate-100 text-slate-700 ring-slate-500/10' }
})

const inicialNome = computed(() => user.value?.name?.charAt(0)?.toUpperCase() || 'U')
</script>

<template>
    <div class="min-h-screen bg-slate-50 font-sans antialiased">
        <!-- ============================================================ -->
        <!-- SIDEBAR -->
        <!-- ============================================================ -->
        <Sidebar :user="user" />

        <!-- ============================================================ -->
        <!-- CONTEÚDO PRINCIPAL -->
        <!-- ============================================================ -->
        <div class="flex flex-col min-h-screen">
            <!-- ======================================================== -->
            <!-- TOPBAR / CABEÇALHO SUPERIOR -->
            <!-- ======================================================== -->
            <header
                class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-slate-200/80 bg-white/90 px-4 shadow-xs backdrop-blur-md sm:px-8"
            >
                <!-- Lado esquerdo: Espaço reservado e Identificação -->
                <div class="flex items-center gap-4">
                    <!-- Espaço reservado para casar com o botão flutuante da Sidebar -->
                    <div class="h-11 w-11 shrink-0"></div>

                    <div class="hidden sm:block">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Sistema de Triagem Médica
                        </p>
                        <h1 class="text-lg font-bold tracking-tight text-slate-900">
                            IAAM-Inteligência Artificial de Auxilio Médico
                        </h1>
                    </div>
                </div>

                <!-- Lado direito: Notificações e Perfil do Usuário -->
                <div class="flex items-center gap-3">
                    <!-- Botão de Notificações -->
                    <button
                        type="button"
                        class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-xs transition hover:border-emerald-200 hover:bg-emerald-50/50 hover:text-emerald-600 active:scale-95"
                        aria-label="Notificações"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 0 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 0-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                        <!-- Indicador de Notificação Não Lida -->
                        <span class="absolute top-2 right-2 h-2 w-2 rounded-full bg-emerald-500 ring-2 ring-white"></span>
                    </button>

                    <!-- Cartão do Usuário -->
                    <div class="hidden items-center gap-3 rounded-xl border border-slate-200/80 bg-slate-50/80 px-3 py-1.5 sm:flex">
                        <!-- Avatar com Inicial -->
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100 font-bold text-emerald-800 ring-2 ring-emerald-600/20">
                            {{ inicialNome }}
                        </div>

                        <!-- Nome e Perfil -->
                        <div class="max-w-44 pr-1">
                            <p class="truncate text-sm font-semibold text-slate-800">
                                {{ user?.name || 'Usuário' }}
                            </p>
                            <span :class="['inline-flex items-center rounded-md px-1.5 py-0.2 text-[10px] font-medium ring-1 ring-inset', perfilInfo.badgeClass]">
                                {{ perfilInfo.label }}
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- ======================================================== -->
            <!-- CONTEÚDO PRINCIPAL DA PÁGINA -->
            <!-- ======================================================== -->
            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
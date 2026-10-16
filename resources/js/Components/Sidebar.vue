<script setup>
import { ref, computed, onMounted } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
})

const aberta = ref(false)
const isDark = ref(false)
const page = usePage()

// Inicializa e verifica preferência de Tema (Dark / Light)
onMounted(() => {
    if (
        localStorage.theme === 'dark' ||
        (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
        isDark.value = true
        document.documentElement.classList.add('dark')
    } else {
        isDark.value = false
        document.documentElement.classList.remove('dark')
    }
})

function toggleDarkMode() {
    isDark.value = !isDark.value
    if (isDark.value) {
        document.documentElement.classList.add('dark')
        localStorage.theme = 'dark'
    } else {
        document.documentElement.classList.remove('dark')
        localStorage.theme = 'light'
    }
}

function abrir() {
    aberta.value = true
}

function fechar() {
    aberta.value = false
}

function logout() {
    router.post('/logout', {}, {
        preserveState: false,
        preserveScroll: false,
        onSuccess: () => {
            window.location.href = '/login'
        },
    })
}

// Tipo de usuário normalizado
const tipoUsuario = computed(() => {
    return (props.user?.tipo_usuario ?? props.user?.tipo ?? '').toLowerCase()
})

// Aponta para a rota definida no seu web.php: Route::get('/dashboard', ...)
const dashboardRota = computed(() => '/dashboard')

// Rota atual para destacar o item ativo no menu
const urlAtual = computed(() => page.url)

function isAtivo(rota) {
    return urlAtual.value.startsWith(rota)
}

/*
|--------------------------------------------------------------------------
| Definição dos Menus por Perfil
|--------------------------------------------------------------------------
*/
const menuPaciente = [
    {
        nome: 'Meu perfil',
        rota: '/perfil',
        icon: 'user',
    },
    {
        nome: 'Nova triagem',
        rota: '/triagens/nova',
        icon: 'stethoscope',
    },
    {
        nome: 'Histórico',
        rota: '/triagens',
        icon: 'clipboard-list',
    },
]

const menuMedico = [
    {
        nome: 'Dashboard',
        rota: '/dashboard',
        icon: 'home',
    },
    {
        nome: 'Triagens',
        rota: '/medico/triagens',
        icon: 'stethoscope',
    },
    {
        nome: 'Histórico',
        rota: '/medico/historico',
        icon: 'clipboard-list',
    },
    {
        nome: 'Meu perfil',
        rota: '/medico/perfil',
        icon: 'user',
    },
    {
        nome: 'Gerar convite',
        rota: '/medico/convites',
        icon: 'key',
    },
]

const menuAdmin = [
    {
        nome: 'Dashboard',
        rota: '/dashboard',
        icon: 'home',
    },
    {
        nome: 'Pacientes',
        rota: '/admin/pacientes',
        icon: 'users',
    },
    {
        nome: 'Médicos',
        rota: '/admin/medicos',
        icon: 'user-doctor',
    },
    {
        nome: 'Triagens',
        rota: '/admin/triagens',
        icon: 'clipboard-list',
    },
    {
        nome: 'Avaliações',
        rota: '/admin/avaliacoes',
        icon: 'check-circle',
    },
    {
        nome: 'Convites',
        rota: '/admin/convites',
        icon: 'key',
    },
    {
        nome: 'Relatórios',
        rota: '/admin/relatorios',
        icon: 'chart-bar',
    },
    {
        nome: 'Meu perfil',
        rota: '/admin/perfil',
        icon: 'user',
    },
]

const menu = computed(() => {
    if (tipoUsuario.value === 'admin' || tipoUsuario.value === 'administrador') {
        return menuAdmin
    }
    if (tipoUsuario.value === 'medico' || tipoUsuario.value === 'médico') {
        return menuMedico
    }
    return menuPaciente
})

// Texto e cores amigáveis para o cargo
const perfilInfo = computed(() => {
    if (tipoUsuario.value === 'admin' || tipoUsuario.value === 'administrador') {
        return { label: 'Administrador', badgeClass: 'bg-amber-100 text-amber-800 ring-amber-600/20 dark:bg-amber-950/60 dark:text-amber-300 dark:ring-amber-500/30' }
    }
    if (tipoUsuario.value === 'medico' || tipoUsuario.value === 'médico') {
        return { label: 'Corpo Médico', badgeClass: 'bg-emerald-100 text-emerald-800 ring-emerald-600/20 dark:bg-emerald-950/60 dark:text-emerald-300 dark:ring-emerald-500/30' }
    }
    return { label: 'Paciente', badgeClass: 'bg-slate-100 text-slate-700 ring-slate-500/10 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700' }
})

const inicialNome = computed(() => props.user?.name?.charAt(0)?.toUpperCase() || 'U')

function navegar(rota) {
    fechar()
    router.visit(rota, {
        preserveScroll: false,
        preserveState: false,
    })
}
</script>

<template>
    <!-- BOTÃO FLUTUANTE PARA ABRIR -->
    <button
        v-if="!aberta"
        type="button"
        @click="abrir"
        class="fixed left-5 top-5 z-40 flex h-11 w-11 items-center justify-center rounded-xl bg-white text-slate-700 shadow-md ring-1 ring-slate-900/10 transition-all duration-200 hover:bg-emerald-50 hover:text-emerald-600 hover:shadow-lg active:scale-95 dark:bg-slate-900 dark:text-slate-200 dark:ring-slate-800 dark:hover:bg-slate-800 dark:hover:text-emerald-400"
        aria-label="Abrir menu de navegação"
    >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
    </button>

    <!-- OVERLAY ESCURO -->
    <Transition name="fade">
        <div
            v-if="aberta"
            class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm transition-opacity dark:bg-slate-950/60"
            @click="fechar"
        ></div>
    </Transition>

    <!-- SIDEBAR -->
    <Transition name="sidebar">
        <aside
            v-if="aberta"
            class="fixed left-0 top-0 z-50 flex h-screen w-80 flex-col bg-white shadow-2xl ring-1 ring-slate-900/10 dark:bg-slate-900 dark:ring-slate-800"
        >
            <!-- CABEÇALHO DA SIDEBAR -->
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5 dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-md shadow-emerald-600/30 dark:bg-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold tracking-tight text-slate-900 dark:text-slate-100">
                            TR-IA
                        </h2>
                        <p class="text-xs font-medium text-slate-400 dark:text-slate-500">
                            Triagem Inteligente
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-1">
                    <!-- BOTÃO MODO ESCURO (CABEÇALHO) -->
                    <button
                        type="button"
                        @click="toggleDarkMode"
                        :title="isDark ? 'Mudar para Modo Claro' : 'Mudar para Modo Escuro'"
                        class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-amber-300"
                        aria-label="Alternar modo escuro"
                    >
                        <!-- Ícone Sol (quando escuro) -->
                        <svg v-if="isDark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 13.5V21m8.966-8.966h-2.25m-13.5 0h-2.25m15.356-6.364l-1.591 1.591M6.75 17.25l-1.591 1.591M19.071 19.071l-1.591-1.591M6.75 6.75L5.159 5.159M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <!-- Ícone Lua (quando claro) -->
                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                        </svg>
                    </button>

                    <!-- BOTÃO VOLTAR PARA DASHBOARD -->
                    <button
                        type="button"
                        @click="navegar(dashboardRota)"
                        title="Voltar ao Dashboard"
                        class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition hover:bg-emerald-50 hover:text-emerald-600 dark:text-slate-500 dark:hover:bg-slate-800 dark:hover:text-emerald-400"
                        aria-label="Voltar para o Dashboard"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21.00v-6a2.25 2.25 0 0 1 2.25-2.25h3a2.25 2.25 0 0 1 2.25 2.25v6" />
                        </svg>
                    </button>

                    <!-- BOTÃO FECHAR MENU -->
                    <button
                        type="button"
                        @click="fechar"
                        class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:text-slate-500 dark:hover:bg-slate-800 dark:hover:text-slate-300"
                        aria-label="Fechar menu"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- PERFIL DO USUÁRIO -->
            <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4 dark:border-slate-800 dark:bg-slate-800/40">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 font-bold text-emerald-800 ring-2 ring-emerald-600/20 dark:bg-emerald-950/80 dark:text-emerald-300 dark:ring-emerald-500/30">
                        {{ inicialNome }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-slate-900 dark:text-slate-100">
                            {{ user?.name || 'Usuário' }}
                        </p>
                        <span :class="['mt-1 inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium ring-1 ring-inset', perfilInfo.badgeClass]">
                            {{ perfilInfo.label }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- NAVEGAÇÃO -->
            <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-4">
                <button
                    v-for="item in menu"
                    :key="item.rota"
                    type="button"
                    @click="navegar(item.rota)"
                    :class="[
                        'group relative flex w-full items-center gap-3.5 rounded-xl px-3.5 py-3 text-left text-sm font-medium transition-all duration-150',
                        isAtivo(item.rota)
                            ? 'bg-emerald-50 text-emerald-800 font-semibold shadow-xs dark:bg-emerald-950/50 dark:text-emerald-300'
                            : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-slate-100'
                    ]"
                >
                    <!-- Indicador visual da rota ativa -->
                    <span
                        v-if="isAtivo(item.rota)"
                        class="absolute left-0 top-2 bottom-2 w-1 rounded-r-full bg-emerald-600 dark:bg-emerald-400"
                    ></span>

                    <!-- Ícones Vetoriais SVG -->
                    <span :class="['flex h-6 w-6 shrink-0 items-center justify-center transition-colors', isAtivo(item.rota) ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300']">
                        <!-- Dashboard -->
                        <svg v-if="item.icon === 'home'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21.00v-6a2.25 2.25 0 0 1 2.25-2.25h3a2.25 2.25 0 0 1 2.25 2.25v6" /></svg>

                        <!-- Perfil -->
                        <svg v-else-if="item.icon === 'user'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a8.25 8.25 0 0 1 15 0" /></svg>

                        <!-- Triagem / Estetoscópio -->
                        <svg v-else-if="item.icon === 'stethoscope'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 0a3.75 3.75 0 1 0 3.75 3.75H12Zm0 0V21m-6.75-9a3.75 3.75 0 0 1 3.75-3.75h6a3.75 3.75 0 0 1 3.75 3.75v3.75" /></svg>

                        <!-- Histórico / Relatório -->
                        <svg v-else-if="item.icon === 'clipboard-list'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z" /></svg>

                        <!-- Pacientes / Grupos -->
                        <svg v-else-if="item.icon === 'users'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>

                        <!-- Médico -->
                        <svg v-else-if="item.icon === 'user-doctor'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a8.25 8.25 0 0 1 15 0M12 11.25v3.75m0 0h1.875M12 15h-1.875" /></svg>

                        <!-- Key / Convite -->
                        <svg v-else-if="item.icon === 'key'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" /></svg>

                        <!-- Avaliações -->
                        <svg v-else-if="item.icon === 'check-circle'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>

                        <!-- Relatórios -->
                        <svg v-else-if="item.icon === 'chart-bar'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>
                    </span>

                    <span>{{ item.nome }}</span>
                </button>
            </nav>

            <!-- RODAPÉ DA SIDEBAR (MUDAR TEMA & SAIR) -->
            <div class="space-y-1 border-t border-slate-100 p-4 dark:border-slate-800">
                <!-- ITEM DE ALTERNAR MODO ESCURO/CLARO -->
                <button
                    type="button"
                    @click="toggleDarkMode"
                    class="group flex w-full items-center gap-3.5 rounded-xl px-3.5 py-2.5 text-left text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-slate-100"
                >
                    <span class="flex h-6 w-6 items-center justify-center text-slate-400 transition-colors group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-amber-300">
                        <svg v-if="isDark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 13.5V21m8.966-8.966h-2.25m-13.5 0h-2.25m15.356-6.364l-1.591 1.591M6.75 17.25l-1.591 1.591M19.071 19.071l-1.591-1.591M6.75 6.75L5.159 5.159M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                        </svg>
                    </span>
                    <span>{{ isDark ? 'Modo claro' : 'Modo escuro' }}</span>
                </button>

                <!-- BOTÃO DE SAIR -->
                <button
                    type="button"
                    @click.prevent.stop="logout"
                    class="group flex w-full items-center gap-3.5 rounded-xl px-3.5 py-2.5 text-left text-sm font-medium text-rose-600 transition hover:bg-rose-50 hover:text-rose-700 dark:text-rose-400 dark:hover:bg-rose-950/40 dark:hover:text-rose-300"
                >
                    <span class="flex h-6 w-6 items-center justify-center text-rose-500 transition-transform group-hover:-translate-x-0.5 dark:text-rose-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12" />
                        </svg>
                    </span>
                    <span>Sair da conta</span>
                </button>
            </div>
        </aside>
    </Transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.sidebar-enter-active,
.sidebar-leave-active {
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.sidebar-enter-from,
.sidebar-leave-to {
    transform: translateX(-100%);
}
</style>
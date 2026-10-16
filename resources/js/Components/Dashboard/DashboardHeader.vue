<script setup>
import { computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { useDarkMode } from '@/Composables/useDarkMode'

const page = usePage()

// Controle de modo escuro
const { isDark, alternarTema } = useDarkMode()

const usuario = computed(() => page.props.auth?.user ?? {})

const perfis = computed(() => {
    const roles = usuario.value.roles

    if (Array.isArray(roles)) {
        return roles.map(role =>
            typeof role === 'string' ? role : role.name
        )
    }

    if (typeof usuario.value.role === 'string') {
        return [usuario.value.role]
    }

    if (usuario.value.role?.name) {
        return [usuario.value.role.name]
    }

    return []
})

const ehAdministrador = computed(() =>
    perfis.value.includes('Administrador')
)

const ehAvaliador = computed(() =>
    perfis.value.some(perfil =>
        ['Medico', 'Médico', 'Residente', 'Estudante'].includes(perfil)
    )
)

const logout = () => {
    router.post('/logout', {}, {
        preserveScroll: false,
        onFinish: () => {
            window.location.href = '/login'
        },
    })
}
</script>

<template>
    <header class="relative z-0 border-b border-emerald-100/60 bg-white dark:border-slate-800/80 dark:bg-slate-900">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-6 py-4">

            <!-- IDENTIDADE / TÍTULO -->
            <div class="flex min-w-0 items-center gap-4">

                <!-- ÍCONE DA SAÚDE -->
                <div class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/20 ring-4 ring-emerald-50 dark:ring-emerald-950/40">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        class="h-6 w-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 21s8-4.5 8-10.5V5.5L12 3 4 5.5v5C4 16.5 12 21 12 21Z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12h6M12 9v6"
                        />
                    </svg>
                </div>

                <!-- TÍTULO -->
                <div class="min-w-0">
                    <h1 class="flex flex-wrap items-center gap-2 text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100">
                        Dashboard

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-950/50 dark:text-emerald-400 dark:ring-emerald-500/30">
                            <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-500"></span>
                            Ativo
                        </span>
                    </h1>

                    <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                        Acompanhe suas informações de saúde.
                    </p>
                </div>
            </div>

            <!-- AÇÕES -->
            <div class="flex shrink-0 items-center gap-2 sm:gap-3">

                <!-- ALTERNAR TEMA (CLARO / ESCURO) -->
                <button
                    type="button"
                    @click="alternarTema"
                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-xs transition-all duration-200 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 cursor-pointer"
                    :title="isDark ? 'Mudar para modo claro' : 'Mudar para modo escuro'"
                >
                    <svg v-if="isDark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-amber-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>

                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5 text-slate-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>
                </button>

                <!-- DASHBOARD ADMINISTRATIVO -->
                <Link
                    v-if="ehAdministrador"
                    href="/admin"
                    class="group inline-flex items-center gap-2 rounded-xl border border-violet-200 bg-violet-50 px-3 py-2.5 text-sm font-semibold text-violet-800 shadow-xs transition-all duration-200 hover:border-violet-300 hover:bg-violet-100 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 active:scale-95 sm:px-4 dark:border-violet-900/50 dark:bg-violet-950/40 dark:text-violet-300 dark:hover:bg-violet-900/60"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-4 w-4 shrink-0 transition-transform duration-200 group-hover:scale-110"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3.75 3.75h6.5v6.5h-6.5v-6.5Zm10 0h6.5v6.5h-6.5v-6.5Zm-10 10h6.5v6.5h-6.5v-6.5Zm10 0h6.5v6.5h-6.5v-6.5Z"
                        />
                    </svg>

                    <span class="hidden sm:inline">Dashboard administrativo</span>
                    <span class="sm:hidden">Admin</span>
                </Link>

                <!-- AVALIAÇÕES MÉDICAS -->
                <Link
                    v-else-if="ehAvaliador"
                    href="/avaliacao"
                    class="group inline-flex items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-3 py-2.5 text-sm font-semibold text-blue-800 shadow-xs transition-all duration-200 hover:border-blue-300 hover:bg-blue-100 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:scale-95 sm:px-4 dark:border-blue-900/50 dark:bg-blue-950/40 dark:text-blue-300 dark:hover:bg-blue-900/60"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-4 w-4 shrink-0 transition-transform duration-200 group-hover:scale-110"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M12 3.75l7.5 3v5.25c0 4.5-3.75 7.5-7.5 8.25-3.75-.75-7.5-3.75-7.5-8.25V6.75l7.5-3Z"
                        />
                    </svg>

                    <span>Avaliações médicas</span>
                </Link>

                <!-- MEU PERFIL -->
                <Link
                    href="/perfil"
                    class="group inline-flex items-center gap-2 rounded-xl border border-emerald-200/80 bg-emerald-50/50 px-3 py-2.5 text-sm font-semibold text-emerald-800 shadow-xs transition-all duration-200 hover:border-emerald-300 hover:bg-emerald-100/70 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 active:scale-95 sm:px-4 dark:border-emerald-800/80 dark:bg-emerald-950/40 dark:text-emerald-300 dark:hover:bg-emerald-900/60"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-4 w-4 text-emerald-600 transition-transform duration-200 group-hover:scale-110 dark:text-emerald-400"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a8.25 8.25 0 0 1 15 0"
                        />
                    </svg>

                    <span class="hidden sm:inline">Meu perfil</span>
                    <span class="sm:hidden">Perfil</span>
                </Link>

                <!-- DIVISOR -->
                <div class="mx-1 hidden h-6 w-px bg-slate-200 sm:block dark:bg-slate-800"></div>

                <!-- SAIR -->
                <button
                    type="button"
                    @click="logout"
                    class="group inline-flex cursor-pointer items-center gap-2 rounded-xl border border-transparent px-2.5 py-2.5 text-sm font-semibold text-slate-500 transition-all duration-200 hover:border-rose-100 hover:bg-rose-50 hover:text-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:ring-offset-2 active:scale-95 sm:px-3.5 dark:text-slate-400 dark:hover:border-rose-900/50 dark:hover:bg-rose-950/30 dark:hover:text-rose-400"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M18 15l3-3m0 0-3-3m3 3H9"
                        />
                    </svg>

                    <span class="hidden sm:inline">Sair</span>
                </button>
            </div>
        </div>
    </header>
</template>
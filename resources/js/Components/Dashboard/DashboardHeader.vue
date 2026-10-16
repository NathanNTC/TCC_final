<script setup>
import { computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'

const page = usePage()

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
    <header class="sticky top-0 z-50 border-b border-emerald-100/60 bg-white/80 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-6 py-4">

            <!-- IDENTIDADE / TÍTULO -->
            <div class="flex min-w-0 items-center gap-4">

                <!-- ÍCONE DA SAÚDE -->
                <div class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/20 ring-4 ring-emerald-50">
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
                    <h1 class="flex flex-wrap items-center gap-2 text-xl font-bold tracking-tight text-slate-800">
                        Dashboard

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                            <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-500"></span>
                            Ativo
                        </span>
                    </h1>

                    <p class="mt-0.5 text-sm text-slate-500">
                        Acompanhe suas informações de saúde.
                    </p>
                </div>
            </div>

            <!-- AÇÕES -->
            <div class="flex shrink-0 items-center gap-2 sm:gap-3">

                <!-- DASHBOARD ADMINISTRATIVO -->
                <Link
                    v-if="ehAdministrador"
                    href="/admin"
                    class="group inline-flex items-center gap-2 rounded-xl border border-violet-200 bg-violet-50 px-3 py-2.5 text-sm font-semibold text-violet-800 shadow-sm transition-all duration-200 hover:border-violet-300 hover:bg-violet-100 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 active:scale-95 sm:px-4"
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
                    class="group inline-flex items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-3 py-2.5 text-sm font-semibold text-blue-800 shadow-sm transition-all duration-200 hover:border-blue-300 hover:bg-blue-100 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:scale-95 sm:px-4"
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
                    class="group inline-flex items-center gap-2 rounded-xl border border-emerald-200/80 bg-emerald-50/50 px-3 py-2.5 text-sm font-semibold text-emerald-800 shadow-sm transition-all duration-200 hover:border-emerald-300 hover:bg-emerald-100/70 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 active:scale-95 sm:px-4"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-4 w-4 text-emerald-600 transition-transform duration-200 group-hover:scale-110"
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
                <div class="mx-1 hidden h-6 w-px bg-slate-200 sm:block"></div>

                <!-- SAIR -->
                <button
                    type="button"
                    @click="logout"
                    class="group inline-flex items-center gap-2 rounded-xl border border-transparent px-2.5 py-2.5 text-sm font-semibold text-slate-500 transition-all duration-200 hover:border-rose-100 hover:bg-rose-50 hover:text-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:ring-offset-2 active:scale-95 sm:px-3.5"
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

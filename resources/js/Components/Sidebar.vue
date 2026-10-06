<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
})

const aberta = ref(false)

/*
|--------------------------------------------------------------------------
| Abrir / fechar sidebar
|--------------------------------------------------------------------------
*/

function abrir() {
    aberta.value = true
}

function fechar() {
    aberta.value = false
}

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

function logout() {
    router.post('/logout')
}

/*
|--------------------------------------------------------------------------
| Tipo de usuário
|--------------------------------------------------------------------------
*/

const tipoUsuario = computed(() => {
    return props.user?.tipo_usuario
        ?? props.user?.tipo
        ?? ''
})

/*
|--------------------------------------------------------------------------
| Menus
|--------------------------------------------------------------------------
*/

const menuPaciente = [
    {
        nome: 'Meu perfil',
        rota: '/perfil',
        icone: '👤',
    },
    {
        nome: 'Nova triagem',
        rota: '/triagem/nova',
        icone: '🩺',
    },
    {
        nome: 'Histórico',
        rota: '/triagem/historico',
        icone: '📋',
    },
]

const menuMedico = [
    {
        nome: 'Dashboard',
        rota: '/medico/dashboard',
        icone: '🏠',
    },
    {
        nome: 'Triagens',
        rota: '/medico/triagens',
        icone: '🩺',
    },
    {
        nome: 'Histórico',
        rota: '/medico/historico',
        icone: '📋',
    },
    {
        nome: 'Meu perfil',
        rota: '/medico/perfil',
        icone: '👤',
    },
    {
        nome: 'Gerar convite',
        rota: '/medico/convites',
        icone: '🔑',
    },
]

const menuAdmin = [
    {
        nome: 'Dashboard',
        rota: '/admin/dashboard',
        icone: '🏠',
    },
    {
        nome: 'Pacientes',
        rota: '/admin/pacientes',
        icone: '👥',
    },
    {
        nome: 'Médicos',
        rota: '/admin/medicos',
        icone: '🩺',
    },
    {
        nome: 'Triagens',
        rota: '/admin/triagens',
        icone: '📋',
    },
    {
        nome: 'Avaliações',
        rota: '/admin/avaliacoes',
        icone: '✅',
    },
    {
        nome: 'Convites',
        rota: '/admin/convites',
        icone: '🔑',
    },
    {
        nome: 'Relatórios',
        rota: '/admin/relatorios',
        icone: '📊',
    },
    {
        nome: 'Meu perfil',
        rota: '/admin/perfil',
        icone: '👤',
    },
]

/*
|--------------------------------------------------------------------------
| Menu atual
|--------------------------------------------------------------------------
*/

const menu = computed(() => {
    if (
        tipoUsuario.value === 'admin' ||
        tipoUsuario.value === 'administrador'
    ) {
        return menuAdmin
    }

    if (
        tipoUsuario.value === 'medico' ||
        tipoUsuario.value === 'médico'
    ) {
        return menuMedico
    }

    return menuPaciente
})

/*
|--------------------------------------------------------------------------
| Navegação
|--------------------------------------------------------------------------
*/

function navegar(rota) {
    fechar()
    router.visit(rota)
}
</script>

<template>

    <!-- ================================================================ -->
    <!-- BOTÃO PARA ABRIR -->
    <!-- ================================================================ -->

    <button
        v-if="!aberta"
        type="button"
        @click="abrir"
        class="fixed left-4 top-4 z-40 flex h-11 w-11 items-center justify-center rounded-xl border border-gray-200 bg-white text-2xl font-bold text-gray-700 shadow-md transition hover:bg-gray-50"
        aria-label="Abrir menu"
    >
        ⋮
    </button>


    <!-- ================================================================ -->
    <!-- OVERLAY -->
    <!-- ================================================================ -->

    <Transition name="fade">

        <div
            v-if="aberta"
            class="fixed inset-0 z-40 bg-black/30"
            @click="fechar"
        ></div>

    </Transition>


    <!-- ================================================================ -->
    <!-- SIDEBAR -->
    <!-- ================================================================ -->

    <Transition name="sidebar">

        <aside
            v-if="aberta"
            class="fixed left-0 top-0 z-50 flex h-screen w-72 flex-col border-r border-gray-200 bg-white shadow-2xl"
        >

            <!-- ======================================================== -->
            <!-- CABEÇALHO -->
            <!-- ======================================================== -->

            <div
                class="flex items-center justify-between border-b border-gray-200 px-5 py-5"
            >

                <div>

                    <h2 class="text-xl font-bold text-gray-900">
                        TR-IA
                    </h2>

                    <p class="mt-0.5 text-xs text-gray-500">
                        Triagem Inteligente
                    </p>

                </div>


                <button
                    type="button"
                    @click="fechar"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-xl text-gray-500 transition hover:bg-gray-100 hover:text-gray-800"
                    aria-label="Fechar menu"
                >
                    ×
                </button>

            </div>


            <!-- ======================================================== -->
            <!-- USUÁRIO -->
            <!-- ======================================================== -->

            <div class="border-b border-gray-200 px-5 py-4">

                <p class="truncate text-sm font-semibold text-gray-900">
                    {{ user?.name || 'Usuário' }}
                </p>

                <p class="mt-0.5 text-xs text-gray-500">
                    {{
                        tipoUsuario === 'admin' ||
                        tipoUsuario === 'administrador'
                            ? 'Administrador'
                            : tipoUsuario === 'medico' ||
                              tipoUsuario === 'médico'
                                ? 'Médico'
                                : 'Paciente'
                    }}
                </p>

            </div>


            <!-- ======================================================== -->
            <!-- MENU -->
            <!-- ======================================================== -->

            <nav class="flex-1 overflow-y-auto px-3 py-5">

                <div class="space-y-1">

                    <button
                        v-for="item in menu"
                        :key="item.rota"
                        type="button"
                        @click="navegar(item.rota)"
                        class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left text-sm font-medium text-gray-700 transition hover:bg-gray-100 hover:text-gray-900"
                    >

                        <span
                            class="flex h-8 w-8 items-center justify-center text-lg"
                        >
                            {{ item.icone }}
                        </span>

                        <span>
                            {{ item.nome }}
                        </span>

                    </button>

                </div>

            </nav>


            <!-- ======================================================== -->
            <!-- SAIR -->
            <!-- ======================================================== -->

            <div class="border-t border-gray-200 p-3">

                <button
                    type="button"
                    @click="logout"
                    class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left text-sm font-medium text-red-600 transition hover:bg-red-50 hover:text-red-700"
                >

                    <span
                        class="flex h-8 w-8 items-center justify-center text-lg"
                    >
                        🚪
                    </span>

                    <span>
                        Sair
                    </span>

                </button>

            </div>

        </aside>

    </Transition>

</template>


<style scoped>

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}


.sidebar-enter-active,
.sidebar-leave-active {
    transition: transform 0.25s ease;
}

.sidebar-enter-from,
.sidebar-leave-to {
    transform: translateX(-100%);
}

</style>
<script setup>
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import DashboardHeader from '@/Components/Dashboard/DashboardHeader.vue'
import PerfilResumoCard from '@/Components/Dashboard/PerfilResumoCard.vue'
import PerfilSecoes from '@/Components/Dashboard/PerfilSecoes.vue'
import TriagemCard from '@/Components/Dashboard/TriagemCard.vue'

defineProps({
    auth: {
        type: Object,
        default: () => ({}),
    },

    perfilCompletude: {
        type: Number,
        default: 0,
    },

    perfilSecoes: {
        type: Object,
        default: () => ({}),
    },
})
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-slate-50/70 transition-colors duration-200 dark:bg-slate-950">

            <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

                <DashboardHeader />

                <div class="mt-6 flex flex-col gap-6">

                    <PerfilResumoCard
                        :perfil-completude="perfilCompletude"
                    />

                    <PerfilSecoes
                        :perfil-secoes="perfilSecoes"
                    />

                    <TriagemCard />

                </div>

            </main>

        </div>
    </AuthenticatedLayout>
</template>
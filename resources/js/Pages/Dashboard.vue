<script setup>
import DashboardHeader from '@/Components/Dashboard/DashboardHeader.vue'
import PerfilResumoCard from '@/Components/Dashboard/PerfilResumoCard.vue'
import PerfilSecoes from '@/Components/Dashboard/PerfilSecoes.vue'
import TriagemCard from '@/Components/Dashboard/TriagemCard.vue'

defineProps({
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
    <div class="min-h-screen bg-slate-50">

        <DashboardHeader />

        <main class="mx-auto max-w-7xl px-6 py-8">

            <PerfilResumoCard
                :perfil-completude="perfilCompletude"
            />

            <PerfilSecoes
                :perfil-secoes="perfilSecoes"
            />

            <TriagemCard />

        </main>

    </div>
</template>
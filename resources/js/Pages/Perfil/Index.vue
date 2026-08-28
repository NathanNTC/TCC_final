<script setup>
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import DadosPessoaisCard from '@/Components/Perfil/DadosPessoaisCard.vue'
import DeficienciasCard from '@/Components/Perfil/DeficienciasCard.vue'
import ProblemasSaudeCard from '@/Components/Perfil/ProblemasSaudeCard.vue'
import HistoricoFamiliarCard from '@/Components/Perfil/HistoricoFamiliarCard.vue'
import AlergiasCard from '@/Components/Perfil/AlergiasCard.vue'
import MedicamentosCard from '@/Components/Perfil/MedicamentosCard.vue'
import CirurgiasCard from '@/Components/Perfil/CirurgiasCard.vue'
import HabitosCard from '@/Components/Perfil/HabitosCard.vue'

defineProps({
    paciente: {
        type: Object,
        required: true,
    },

    perfilSaude: {
        type: Object,
        default: null,
    },

    medicamentos: {
        type: Array,
        default: () => [],
    },
})
</script>

<template>

    <Head title="Perfil de Saúde" />

    <AuthenticatedLayout>

        <div class="mx-auto max-w-5xl px-4 py-8">

            <!-- CABEÇALHO -->

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">
                    Perfil de Saúde
                </h1>

                <p class="mt-2 text-gray-600">
                    Visualize e atualize suas informações de saúde.
                </p>
            </div>


            <!-- CARDS -->

            <div class="space-y-6">

                <DadosPessoaisCard
                    :paciente="paciente"
                    :perfil-saude="perfilSaude"
                />

                <DeficienciasCard
                    :perfil-saude="perfilSaude"
                />

                <ProblemasSaudeCard
                    :perfil-saude="perfilSaude"
                />

                <HistoricoFamiliarCard
                    :perfil-saude="perfilSaude"
                />

                <AlergiasCard
                    :perfil-saude="perfilSaude"
                />

                <MedicamentosCard
                    :medicamentos="medicamentos"
                />

                <CirurgiasCard
                    :perfil-saude="perfilSaude"
                />

                <HabitosCard
                    :perfil-saude="perfilSaude"
                />

            </div>

        </div>

    </AuthenticatedLayout>

</template>
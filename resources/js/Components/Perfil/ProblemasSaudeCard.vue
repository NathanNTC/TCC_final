<script setup>
import { router } from '@inertiajs/vue3'
import PerfilCard from './PerfilCard.vue'

defineProps({
    perfilSaude: {
        type: Object,
        default: null,
    },
})

function editar() {
    router.visit('/perfil/editar/problemas-saude')
}

const nomes = {
    hipertensao: 'Pressão alta (Hipertensão)',
    diabetes: 'Diabetes',
    dislipidemia: 'Colesterol ou triglicerídeos altos',
    asma_dpoc: 'Asma / DPOC',
    infarto: 'Infarto prévio',
    arritmia: 'Arritmias',
    insuficiencia_cardiaca: 'Insuficiência cardíaca',
    epilepsia: 'Epilepsia',
    avc: 'AVC prévio',
    enxaqueca: 'Enxaqueca crônica',
    hipotireoidismo: 'Hipotireoidismo',
    cancer: 'Histórico de câncer',
    cirurgias: 'Cirurgias prévias',
    internacoes: 'Internações recentes',
    outros: 'Outra condição',
}
</script>

<template>
    <PerfilCard
        titulo="Condições de saúde"
        descricao="Problemas de saúde e histórico médico"
    >

        <template #acao>
            <button
                type="button"
                @click="editar"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            >
                Editar
            </button>
        </template>

        <!-- CONDIÇÕES -->
        <div
            v-if="perfilSaude?.problemas_saude_previos?.length"
            class="flex flex-wrap gap-2"
        >
            <span
                v-for="item in perfilSaude.problemas_saude_previos"
                :key="item"
                class="rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-700"
            >
                {{ nomes[item] || item }}
            </span>
        </div>

        <!-- NENHUMA CONDIÇÃO -->
        <p
            v-else
            class="text-gray-600"
        >
            Nenhuma condição informada.
        </p>

        <!-- DETALHES -->
        <div
            v-if="perfilSaude?.problemas_saude_detalhes"
            class="mt-5"
        >
            <p class="text-sm text-gray-500">
                Detalhes
            </p>

            <p class="mt-1 whitespace-pre-line text-gray-900">
                {{ perfilSaude.problemas_saude_detalhes }}
            </p>
        </div>

    </PerfilCard>
</template>
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
    router.visit('/perfil/editar/historico-familiar')
}

const nomes = {
    infarto: 'Infarto ou doença cardíaca',
    morte_subita: 'Morte súbita',
    diabetes: 'Diabetes',
    hipertensao: 'Hipertensão',
    cancer_mama: 'Câncer de mama',
    cancer_colon: 'Câncer de intestino',
    cancer_prostata: 'Câncer de próstata',
    doencas_geneticas: 'Doença genética ou rara',
    outros: 'Outro',
    nao_possui: 'Não possui',
}
</script>

<template>
    <PerfilCard
        titulo="Histórico familiar"
        descricao="Condições de saúde na família próxima"
    >

        <template #acao>
            <button
                type="button"
                @click="editar"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50"
            >
                Editar
            </button>
        </template>

        <!-- CONDIÇÕES -->
        <div
            v-if="perfilSaude?.historico_familiar?.length"
            class="flex flex-wrap gap-2"
        >
            <span
                v-for="item in perfilSaude.historico_familiar"
                :key="item"
                class="rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-700"
            >
                {{ nomes[item] || item }}
            </span>
        </div>

        <p
            v-else
            class="text-gray-600"
        >
            Nenhum histórico familiar informado.
        </p>

        <!-- DETALHES -->
        <div
            v-if="perfilSaude?.historico_familiar_detalhes"
            class="mt-5"
        >
            <p class="text-sm text-gray-500">
                Detalhes
            </p>

            <p class="mt-1 whitespace-pre-line text-gray-900">
                {{ perfilSaude.historico_familiar_detalhes }}
            </p>
        </div>

    </PerfilCard>
</template>
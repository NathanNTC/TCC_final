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
    router.visit(route('perfil.editar', {
        secao: 'alergias',
    }))
}

const nomes = {
    medicamentosa: 'Medicamentos',
    alimentar: 'Alimentos',
    ambiental: 'Ambientais',
    latex: 'Látex',
    contraste: 'Contraste de exames',
    insetos: 'Picadas de insetos',
    outros: 'Outros',
    nao_possui: 'Não possui',
}
</script>

<template>
    <PerfilCard
        titulo="Alergias"
        descricao="Alergias conhecidas e reações"
    >

        <template #acao>
            <button
                type="button"
                @click="editar"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Editar
            </button>
        </template>

        <div
            v-if="perfilSaude?.alergias?.length"
            class="flex flex-wrap gap-2"
        >
            <span
                v-for="item in perfilSaude.alergias"
                :key="item"
                class="rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-700"
            >
                {{ nomes[item] || item }}
            </span>
        </div>

        <p v-else class="text-gray-600">
            Nenhuma alergia informada.
        </p>

        <div
            v-if="perfilSaude?.alergias_detalhes"
            class="mt-5"
        >
            <p class="text-sm text-gray-500">
                Reações e detalhes
            </p>

            <p class="mt-1 whitespace-pre-line text-gray-900">
                {{ perfilSaude.alergias_detalhes }}
            </p>
        </div>

    </PerfilCard>
</template>
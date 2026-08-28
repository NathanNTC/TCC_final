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
        secao: 'cirurgias',
    }))
}
</script>

<template>
    <PerfilCard
        titulo="Cirurgias anteriores"
        descricao="Histórico cirúrgico e anestésico"
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

        <p
            v-if="perfilSaude?.cirurgias_previas"
            class="whitespace-pre-line text-gray-900"
        >
            {{ perfilSaude.cirurgias_previas }}
        </p>

        <p
            v-else
            class="text-gray-600"
        >
            Nenhuma cirurgia informada.
        </p>

    </PerfilCard>
</template>
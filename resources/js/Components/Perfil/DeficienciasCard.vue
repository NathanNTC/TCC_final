<script setup>
import { router } from '@inertiajs/vue3'
import PerfilCard from './PerfilCard.vue'

const props = defineProps({
    perfilSaude: {
        type: Object,
        default: null,
    },
})

function editar() {
    router.visit(route('perfil.editar', {
        secao: 'deficiencias',
    }))
}

const nomes = {
    fisica: 'Mobilidade',
    visual: 'Cegueira ou baixa visão',
    auditiva: 'Surdez ou perda auditiva',
    intelectual_cognitiva: 'Limitação intelectual ou cognitiva',
    psicossocial: 'Condição psicossocial',
    outros: 'Outra',
    nao_possui: 'Não possui',
}
</script>

<template>
    <PerfilCard
        titulo="Necessidades de acessibilidade"
        descricao="Informações importantes para o atendimento"
    >

        <template #acao>
            <button
                type="button"
                @click="editar"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
            >
                Editar
            </button>
        </template>

        <div
            v-if="perfilSaude?.deficiencias?.length"
            class="flex flex-wrap gap-2"
        >
            <span
                v-for="item in perfilSaude.deficiencias"
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
            Nenhuma necessidade informada.
        </p>

        <div
            v-if="perfilSaude?.deficiencia_outros_descricao"
            class="mt-5"
        >
            <p class="text-sm text-gray-500">
                Outras informações
            </p>

            <p class="mt-1 whitespace-pre-line text-gray-900">
                {{ perfilSaude.deficiencia_outros_descricao }}
            </p>
        </div>

        <div
            v-if="perfilSaude?.necessidades_acessibilidade"
            class="mt-5"
        >
            <p class="text-sm text-gray-500">
                Recursos de acessibilidade
            </p>

            <p class="mt-1 whitespace-pre-line text-gray-900">
                {{ perfilSaude.necessidades_acessibilidade }}
            </p>
        </div>

    </PerfilCard>
</template>
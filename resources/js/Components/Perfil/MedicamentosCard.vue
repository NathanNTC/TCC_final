<script setup>
import { router } from '@inertiajs/vue3'
import PerfilCard from './PerfilCard.vue'

defineProps({
    medicamentos: {
        type: Array,
        default: () => [],
    },
})

function editar() {
    router.visit(route('perfil.editar', {
        secao: 'medicamentos',
    }))
}
</script>

<template>
    <PerfilCard
        titulo="Medicamentos e suplementos"
        descricao="Medicamentos utilizados regularmente"
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
            v-if="medicamentos.length"
            class="space-y-4"
        >

            <div
                v-for="medicamento in medicamentos"
                :key="medicamento.id"
                class="rounded-xl border border-gray-200 p-4"
            >

                <h3 class="font-semibold text-gray-900">
                    {{ medicamento.nome }}
                </h3>

                <div class="mt-2 grid grid-cols-1 gap-3 text-sm md:grid-cols-3">

                    <div>
                        <span class="text-gray-500">
                            Dose
                        </span>

                        <p class="font-medium text-gray-900">
                            {{ medicamento.dosagem || 'Não informado' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-500">
                            Frequência
                        </span>

                        <p class="font-medium text-gray-900">
                            {{ medicamento.frequencia || 'Não informado' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-500">
                            Motivo
                        </span>

                        <p class="font-medium text-gray-900">
                            {{ medicamento.indicacao || 'Não informado' }}
                        </p>
                    </div>

                </div>

            </div>

        </div>

        <p v-else class="text-gray-600">
            Nenhum medicamento informado.
        </p>

    </PerfilCard>
</template>
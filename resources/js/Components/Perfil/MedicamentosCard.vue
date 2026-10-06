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
    router.visit('/perfil/editar/medicamentos')
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

        <!-- Nenhum medicamento -->
        <div
            v-if="!medicamentos.length"
            class="rounded-xl border border-dashed border-gray-300 p-6 text-center"
        >
            <p class="text-gray-600">
                Nenhum medicamento informado.
            </p>
        </div>

        <!-- Lista de medicamentos -->
        <div
            v-else
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

                <div class="mt-3 grid grid-cols-1 gap-4 text-sm md:grid-cols-3">

                    <!-- Dosagem -->
                    <div>
                        <p class="text-gray-500">
                            Dosagem
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ medicamento.dosagem || 'Não informado' }}
                        </p>
                    </div>

                    <!-- Frequência -->
                    <div>
                        <p class="text-gray-500">
                            Frequência
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ medicamento.frequencia || 'Não informado' }}
                        </p>
                    </div>

                    <!-- Indicação -->
                    <div>
                        <p class="text-gray-500">
                            Indicação
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ medicamento.indicacao || 'Não informado' }}
                        </p>
                    </div>

                </div>

            </div>
        </div>

    </PerfilCard>
</template>
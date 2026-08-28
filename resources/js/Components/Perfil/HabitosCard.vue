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
        secao: 'habitos',
    }))
}
</script>

<template>
    <PerfilCard
        titulo="Hábitos e medidas"
        descricao="Hábitos de vida e medidas corporais"
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

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            <div>
                <p class="text-sm text-gray-500">
                    Tabagismo
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ perfilSaude?.fumante ? 'Sim' : 'Não' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Consumo de álcool
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ perfilSaude?.alcoolismo ? 'Sim' : 'Não' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Uso de drogas
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ perfilSaude?.uso_drogas ? 'Sim' : 'Não' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Atividade física regular
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ perfilSaude?.atividade_fisica_regular ? 'Sim' : 'Não' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Altura
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{
                        perfilSaude?.altura
                            ? `${perfilSaude.altura} m`
                            : 'Não informado'
                    }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Peso
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{
                        perfilSaude?.peso
                            ? `${perfilSaude.peso} kg`
                            : 'Não informado'
                    }}
                </p>
            </div>

        </div>

    </PerfilCard>
</template>
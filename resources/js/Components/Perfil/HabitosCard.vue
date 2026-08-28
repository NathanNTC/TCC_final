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
    router.visite('/perfil/editar/habitos')
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
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50"
            >
                Editar
            </button>
        </template>


        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            <!-- TABAGISMO -->
            <div>
                <p class="text-sm text-gray-500">
                    Tabagismo
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{
                        perfilSaude?.fumante === true
                            ? 'Sim'
                            : perfilSaude?.fumante === false
                                ? 'Não'
                                : 'Não informado'
                    }}
                </p>
            </div>


            <!-- ÁLCOOL -->
            <div>
                <p class="text-sm text-gray-500">
                    Consumo de álcool
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{
                        perfilSaude?.alcoolismo === true
                            ? 'Sim'
                            : perfilSaude?.alcoolismo === false
                                ? 'Não'
                                : 'Não informado'
                    }}
                </p>
            </div>


            <!-- DROGAS -->
            <div>
                <p class="text-sm text-gray-500">
                    Uso de drogas
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{
                        perfilSaude?.uso_drogas === true
                            ? 'Sim'
                            : perfilSaude?.uso_drogas === false
                                ? 'Não'
                                : 'Não informado'
                    }}
                </p>
            </div>


            <!-- ATIVIDADE FÍSICA -->
            <div>
                <p class="text-sm text-gray-500">
                    Atividade física regular
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{
                        perfilSaude?.atividade_fisica_regular === true
                            ? 'Sim'
                            : perfilSaude?.atividade_fisica_regular === false
                                ? 'Não'
                                : 'Não informado'
                    }}
                </p>
            </div>


            <!-- ALTURA -->
            <div>
                <p class="text-sm text-gray-500">
                    Altura
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{
                        perfilSaude?.altura !== null &&
                        perfilSaude?.altura !== undefined &&
                        perfilSaude?.altura !== ''
                            ? `${perfilSaude.altura} m`
                            : 'Não informado'
                    }}
                </p>
            </div>


            <!-- PESO -->
            <div>
                <p class="text-sm text-gray-500">
                    Peso
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{
                        perfilSaude?.peso !== null &&
                        perfilSaude?.peso !== undefined &&
                        perfilSaude?.peso !== ''
                            ? `${perfilSaude.peso} kg`
                            : 'Não informado'
                    }}
                </p>
            </div>

        </div>

    </PerfilCard>
</template>
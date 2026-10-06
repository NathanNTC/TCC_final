<script setup>
import { router } from '@inertiajs/vue3'
import PerfilCard from './PerfilCard.vue'

defineProps({
    perfilSaude: {
        type: Object,
        default: null,
    },
})

/*
|--------------------------------------------------------------------------
| Editar
|--------------------------------------------------------------------------
*/

function editar() {
    router.visit('/perfil/editar/alergias')
}

/*
|--------------------------------------------------------------------------
| Nomes dos tipos de alergia
|--------------------------------------------------------------------------
*/

const nomesAlergia = {
    medicamentosa: 'Medicamentos',
    alimentar: 'Alimentos',
    outros: 'Outros',
    nao_possui: 'Não possui',
}

/*
|--------------------------------------------------------------------------
| Nomes das reações
|--------------------------------------------------------------------------
*/

const nomesReacao = {
    urticaria: 'Manchas na pele / Coceira',
    inchaco: 'Inchaço nos lábios, olhos ou garganta',
    dificuldade_respirar: 'Dificuldade grave para respirar',
    tontura_desmaio: 'Tontura forte ou desmaio',
}
</script>

<template>

    <PerfilCard
        titulo="Alergias"
        descricao="Alergias conhecidas e reações"
    >

        <!-- ============================================================= -->
        <!-- BOTÃO EDITAR -->
        <!-- ============================================================= -->

        <template #acao>

            <button
                type="button"
                @click="editar"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50"
            >
                Editar
            </button>

        </template>


        <!-- ============================================================= -->
        <!-- SEM INFORMAÇÃO -->
        <!-- ============================================================= -->

        <div
            v-if="
                !perfilSaude?.alergias?.length ||
                perfilSaude.alergias.includes('nao_possui')
            "
        >

            <div
                v-if="perfilSaude?.alergias?.includes('nao_possui')"
                class="rounded-xl border border-gray-200 bg-gray-50 p-4"
            >

                <p class="font-medium text-gray-900">
                    Não possui alergias conhecidas.
                </p>

            </div>

            <p
                v-else
                class="text-gray-600"
            >
                Nenhuma alergia informada.
            </p>

        </div>


        <!-- ============================================================= -->
        <!-- ALERGIAS -->
        <!-- ============================================================= -->

        <div
            v-else
            class="space-y-6"
        >

            <!-- TIPOS -->

            <div>

                <p class="mb-3 text-sm font-medium text-gray-500">
                    Tipos de alergia
                </p>

                <div class="flex flex-wrap gap-2">

                    <span
                        v-for="item in perfilSaude.alergias"
                        :key="item"
                        class="rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-700"
                    >
                        {{ nomesAlergia[item] || item }}
                    </span>

                </div>

            </div>


            <!-- SUBSTÂNCIAS -->

            <div
                v-if="perfilSaude.alergias_detalhes"
            >

                <p class="text-sm font-medium text-gray-500">
                    Substâncias que causam alergia
                </p>

                <p class="mt-1 whitespace-pre-line text-gray-900">
                    {{ perfilSaude.alergias_detalhes }}
                </p>

            </div>


            <!-- REAÇÕES -->

            <div
                v-if="perfilSaude.alergias_reacoes?.length"
            >

                <p class="text-sm font-medium text-gray-500">
                    Reações conhecidas
                </p>

                <div class="mt-2 space-y-2">

                    <div
                        v-for="reacao in perfilSaude.alergias_reacoes"
                        :key="reacao"
                        class="flex items-center gap-2"
                    >

                        <span class="text-gray-500">
                            •
                        </span>

                        <span class="text-gray-900">
                            {{ nomesReacao[reacao] || reacao }}
                        </span>

                    </div>

                </div>

            </div>


            <!-- OBSERVAÇÕES -->

            <div
                v-if="perfilSaude.alergias_observacoes"
            >

                <p class="text-sm font-medium text-gray-500">
                    Outras informações
                </p>

                <p class="mt-1 whitespace-pre-line text-gray-900">
                    {{ perfilSaude.alergias_observacoes }}
                </p>

            </div>

        </div>

    </PerfilCard>

</template>
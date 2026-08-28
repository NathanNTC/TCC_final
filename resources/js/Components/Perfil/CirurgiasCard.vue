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
    router.visit(route('perfil.editar.cirurgias'))
}

const anestesias = {
    geral: 'Geral',
    raqui_peridural: 'Nas costas (raqui ou peridural)',
    local_sedacao: 'Local ou sedação leve',
    nao_lembra: 'Não lembra',
}

const complicacoes = {
    uti: 'Precisou ir para a UTI',
    infeccao: 'Infecção no local da cirurgia',
    sangramento_transfusao: 'Sangramento forte ou transfusão de sangue',
    demora_acordar: 'Mal-estar grave ou demora para acordar da anestesia',
    nenhuma: 'Nenhuma complicação',
}

const implantes = {
    placas_parafusos_pinos: 'Placas, parafusos ou pinos de metal',
    protese: 'Prótese',
    marcapasso: 'Marcapasso ou desfibrilador',
    nenhum: 'Não possui implantes',
}
</script>


<template>

    <PerfilCard
        titulo="Cirurgias anteriores"
        descricao="Histórico cirúrgico e anestésico"
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
        <!-- NENHUMA CIRURGIA -->
        <!-- ============================================================= -->

        <div
            v-if="!perfilSaude?.cirurgias?.length"
            class="text-gray-600"
        >
            Nenhuma cirurgia informada.
        </div>


        <!-- ============================================================= -->
        <!-- CIRURGIAS -->
        <!-- ============================================================= -->

        <div
            v-else
            class="space-y-6"
        >

            <div
                v-for="(cirurgia, index) in perfilSaude.cirurgias"
                :key="index"
                class="rounded-xl border border-gray-200 bg-gray-50 p-5"
            >

                <!-- TÍTULO -->

                <div class="mb-4 flex items-center justify-between">

                    <h3 class="font-semibold text-gray-900">
                        Cirurgia {{ index + 1 }}
                    </h3>

                    <span
                        v-if="cirurgia.ano"
                        class="text-sm text-gray-500"
                    >
                        {{ cirurgia.ano }}
                    </span>

                </div>


                <!-- PROCEDIMENTO -->

                <div
                    v-if="cirurgia.procedimento"
                    class="mb-4"
                >

                    <p class="text-sm text-gray-500">
                        Procedimento
                    </p>

                    <p class="mt-1 font-medium text-gray-900">
                        {{ cirurgia.procedimento }}
                    </p>

                </div>


                <!-- ANESTESIA -->

                <div
                    v-if="cirurgia.anestesia"
                    class="mb-4"
                >

                    <p class="text-sm text-gray-500">
                        Tipo de anestesia
                    </p>

                    <p class="mt-1 text-gray-900">
                        {{
                            anestesias[cirurgia.anestesia]
                            || cirurgia.anestesia
                        }}
                    </p>

                </div>


                <!-- COMPLICAÇÕES -->

                <div
                    v-if="cirurgia.complicacoes?.length"
                    class="mb-4"
                >

                    <p class="text-sm text-gray-500">
                        Complicações
                    </p>

                    <ul class="mt-2 space-y-1">

                        <li
                            v-for="item in cirurgia.complicacoes"
                            :key="item"
                            class="text-sm text-gray-900"
                        >
                            •
                            {{
                                complicacoes[item]
                                || item
                            }}
                        </li>

                    </ul>

                </div>


                <!-- IMPLANTES -->

                <div
                    v-if="cirurgia.implantes?.length"
                    class="mb-4"
                >

                    <p class="text-sm text-gray-500">
                        Implantes ou materiais
                    </p>

                    <ul class="mt-2 space-y-1">

                        <li
                            v-for="item in cirurgia.implantes"
                            :key="item"
                            class="text-sm text-gray-900"
                        >
                            •
                            {{
                                implantes[item]
                                || item
                            }}
                        </li>

                    </ul>

                </div>


                <!-- OBSERVAÇÕES -->

                <div
                    v-if="cirurgia.observacoes"
                >

                    <p class="text-sm text-gray-500">
                        Observações
                    </p>

                    <p class="mt-1 whitespace-pre-line text-gray-900">
                        {{ cirurgia.observacoes }}
                    </p>

                </div>

            </div>

        </div>

    </PerfilCard>

</template>
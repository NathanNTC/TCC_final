<script setup>
import { computed } from 'vue'
import { Head, useForm, router } from '@inertiajs/vue3'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import DadosPessoais from '@/Components/Perfil/DadosPessoais.vue'
import Deficiencias from '@/Components/Perfil/Deficiencias.vue'
import ProblemasSaude from '@/Components/Perfil/ProblemasSaude.vue'
import HistoricoFamiliar from '@/Components/Perfil/HistoricoFamiliar.vue'
import Alergias from '@/Components/Perfil/Alergias.vue'
import Medicamentos from '@/Components/Perfil/Medicamentos.vue'
import Cirurgias from '@/Components/Perfil/Cirurgias.vue'
import Habitos from '@/Components/Perfil/Habitos.vue'

const props = defineProps({
    secao: {
        type: String,
        required: true,
    },

    paciente: {
        type: Object,
        required: true,
    },

    perfilSaude: {
        type: Object,
        default: null,
    },

    medicamentos: {
        type: Array,
        default: () => [],
    },
})


/*
|--------------------------------------------------------------------------
| Formulário
|--------------------------------------------------------------------------
*/

const form = useForm({
    cpf: props.paciente.cpf ?? '',
    sexo: props.paciente.sexo ?? '',
    data_nascimento: props.paciente.data_nascimento ?? '',
    telefone: props.paciente.telefone ?? '',
    estado: props.paciente.estado ?? '',
    cidade: props.paciente.cidade ?? '',
    profissao: props.paciente.profissao ?? '',

    tipo_sanguineo: props.perfilSaude?.tipo_sanguineo ?? '',

    deficiencias: props.perfilSaude?.deficiencias ?? [],
    deficiencia_outros_descricao:
        props.perfilSaude?.deficiencia_outros_descricao ?? '',

    necessidades_acessibilidade:
        props.perfilSaude?.necessidades_acessibilidade ?? '',

    problemas_saude_previos:
        props.perfilSaude?.problemas_saude_previos ?? [],

    problemas_saude_detalhes:
        props.perfilSaude?.problemas_saude_detalhes ?? '',

    historico_familiar:
        props.perfilSaude?.historico_familiar ?? [],

    historico_familiar_detalhes:
        props.perfilSaude?.historico_familiar_detalhes ?? '',

    alergias:
        props.perfilSaude?.alergias ?? [],

    alergias_detalhes:
        props.perfilSaude?.alergias_detalhes ?? '',

    medicamentos: props.medicamentos ?? [],

    cirurgias_previas:
        props.perfilSaude?.cirurgias_previas ?? '',

    fumante:
        props.perfilSaude?.fumante ?? false,

    alcoolismo:
        props.perfilSaude?.alcoolismo ?? false,

    uso_drogas:
        props.perfilSaude?.uso_drogas ?? false,

    atividade_fisica_regular:
        props.perfilSaude?.atividade_fisica_regular ?? false,

    altura:
        props.perfilSaude?.altura ?? '',

    peso:
        props.perfilSaude?.peso ?? '',
})


/*
|--------------------------------------------------------------------------
| Título da seção
|--------------------------------------------------------------------------
*/

const titulo = computed(() => {

    const titulos = {
        'dados-pessoais': 'Dados pessoais',
        'deficiencias': 'Necessidades de acessibilidade',
        'problemas-saude': 'Condições de saúde',
        'historico-familiar': 'Histórico familiar',
        'alergias': 'Alergias',
        'medicamentos': 'Medicamentos e suplementos',
        'cirurgias': 'Cirurgias anteriores',
        'habitos': 'Hábitos e atividade física',
    }

    return titulos[props.secao] ?? 'Editar perfil'
})


/*
|--------------------------------------------------------------------------
| Voltar
|--------------------------------------------------------------------------
*/

function voltar() {
    router.visit(route('perfil.index'))
}


/*
|--------------------------------------------------------------------------
| Salvar
|--------------------------------------------------------------------------
*/

function salvar() {

    form.put(
        route('perfil.atualizar', {
            secao: props.secao,
        }),
        {
            preserveScroll: true,
        }
    )
}
</script>


<template>

    <Head :title="`Editar - ${titulo}`" />

    <AuthenticatedLayout>

        <div class="mx-auto max-w-5xl px-4 py-8">


            <!-- CABEÇALHO -->

            <div class="mb-6">

                <button
                    type="button"
                    @click="voltar"
                    class="mb-4 text-sm font-medium text-gray-600 hover:text-gray-900"
                >
                    ← Voltar para o perfil
                </button>


                <h1 class="text-3xl font-bold text-gray-900">
                    {{ titulo }}
                </h1>


                <p class="mt-2 text-gray-600">
                    Atualize somente as informações desta seção.
                </p>

            </div>


            <!-- FORMULÁRIO -->

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">


                <!-- DADOS PESSOAIS -->

                <DadosPessoais
                    v-if="secao === 'dados-pessoais'"
                    :form="form"
                />


                <!-- DEFICIÊNCIAS -->

                <Deficiencias
                    v-else-if="secao === 'deficiencias'"
                    :form="form"
                />


                <!-- PROBLEMAS DE SAÚDE -->

                <ProblemasSaude
                    v-else-if="secao === 'problemas-saude'"
                    :form="form"
                />


                <!-- HISTÓRICO FAMILIAR -->

                <HistoricoFamiliar
                    v-else-if="secao === 'historico-familiar'"
                    :form="form"
                />


                <!-- ALERGIAS -->

                <Alergias
                    v-else-if="secao === 'alergias'"
                    :form="form"
                />


                <!-- MEDICAMENTOS -->

                <Medicamentos
                    v-else-if="secao === 'medicamentos'"
                    :form="form"
                />


                <!-- CIRURGIAS -->

                <Cirurgias
                    v-else-if="secao === 'cirurgias'"
                    :form="form"
                />


                <!-- HÁBITOS -->

                <Habitos
                    v-else-if="secao === 'habitos'"
                    :form="form"
                />


                <!-- BOTÕES -->

                <div
                    class="mt-8 flex items-center justify-between border-t border-gray-200 pt-6"
                >

                    <button
                        type="button"
                        @click="voltar"
                        class="rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50"
                    >
                        Cancelar
                    </button>


                    <button
                        type="button"
                        @click="salvar"
                        :disabled="form.processing"
                        class="rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        {{ form.processing ? 'Salvando...' : 'Salvar alterações' }}
                    </button>

                </div>

            </div>

        </div>

    </AuthenticatedLayout>

</template>
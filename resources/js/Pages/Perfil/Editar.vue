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


/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

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

    cirurgias: {
        type: Array,
        default: () => [],
    },
})


/*
|--------------------------------------------------------------------------
| FORMULÁRIO
|--------------------------------------------------------------------------
*/

const form = useForm({

    /*
    |--------------------------------------------------------------------------
    | Dados pessoais
    |--------------------------------------------------------------------------
    */

    cpf: props.paciente?.cpf ?? '',

    sexo: props.paciente?.sexo ?? '',

    data_nascimento:
        props.paciente?.data_nascimento ?? '',

    telefone:
        props.paciente?.telefone ?? '',

    estado:
        props.paciente?.estado ?? '',

    cidade:
        props.paciente?.cidade ?? '',

    profissao:
        props.paciente?.profissao ?? '',


    /*
    |--------------------------------------------------------------------------
    | Tipo sanguíneo
    |--------------------------------------------------------------------------
    */

    tipo_sanguineo:
        props.perfilSaude?.tipo_sanguineo ?? '',


    /*
    |--------------------------------------------------------------------------
    | Deficiências
    |--------------------------------------------------------------------------
    */

    deficiencias:
        props.perfilSaude?.deficiencias ?? [],

    deficiencia_outros_descricao:
        props.perfilSaude?.deficiencia_outros_descricao ?? '',

    necessidades_acessibilidade:
        props.perfilSaude?.necessidades_acessibilidade ?? '',


    /*
    |--------------------------------------------------------------------------
    | Problemas de saúde
    |--------------------------------------------------------------------------
    */

    problemas_saude_previos:
        props.perfilSaude?.problemas_saude_previos ?? [],

    problemas_saude_detalhes:
        props.perfilSaude?.problemas_saude_detalhes ?? '',


    /*
    |--------------------------------------------------------------------------
    | Histórico familiar
    |--------------------------------------------------------------------------
    */

    historico_familiar:
        props.perfilSaude?.historico_familiar ?? [],

    historico_familiar_detalhes:
        props.perfilSaude?.historico_familiar_detalhes ?? '',


    /*
    |--------------------------------------------------------------------------
    | Alergias
    |--------------------------------------------------------------------------
    */

    alergias:
        props.perfilSaude?.alergias ?? [],

    alergias_detalhes:
        props.perfilSaude?.alergias_detalhes ?? '',

    alergias_reacoes:
        props.perfilSaude?.alergias_reacoes ?? [],

    alergias_observacoes:
        props.perfilSaude?.alergias_observacoes ?? '',


    /*
    |--------------------------------------------------------------------------
    | Medicamentos
    |--------------------------------------------------------------------------
    */

    medicamentos:
        props.medicamentos ?? [],


    /*
    |--------------------------------------------------------------------------
    | Cirurgias
    |--------------------------------------------------------------------------
    */

    cirurgias:
        props.cirurgias ??
        props.perfilSaude?.cirurgias ??
        [],


    /*
    |--------------------------------------------------------------------------
    | Hábitos
    |--------------------------------------------------------------------------
    */

    fumante:
        props.perfilSaude?.fumante ?? false,

    alcoolismo:
        props.perfilSaude?.alcoolismo ?? false,

    uso_drogas:
        props.perfilSaude?.uso_drogas ?? false,

    atividade_fisica_regular:
        props.perfilSaude?.atividade_fisica_regular ?? false,


    /*
    |--------------------------------------------------------------------------
    | Medidas corporais
    |--------------------------------------------------------------------------
    */

    altura:
        props.perfilSaude?.altura ?? '',

    peso:
        props.perfilSaude?.peso ?? '',
})


/*
|--------------------------------------------------------------------------
| TÍTULOS
|--------------------------------------------------------------------------
*/

const titulos = {
    'dados-pessoais': 'Dados pessoais',

    'deficiencias':
        'Necessidades de acessibilidade',

    'problemas-saude':
        'Condições de saúde',

    'historico-familiar':
        'Histórico familiar',

    'alergias':
        'Alergias',

    'medicamentos':
        'Medicamentos e suplementos',

    'cirurgias':
        'Cirurgias anteriores',

    'habitos':
        'Hábitos e atividade física',
}


const titulo = computed(() => {
    return titulos[props.secao] ?? 'Editar perfil'
})


/*
|--------------------------------------------------------------------------
| ROTAS DE ATUALIZAÇÃO
|--------------------------------------------------------------------------
|
| Cada seção agora possui seu próprio controller.
|
*/

const rotasAtualizacao = {
    'dados-pessoais':
        'perfil.atualizar.dados-pessoais',

    'deficiencias':
        'perfil.atualizar.deficiencias',

    'problemas-saude':
        'perfil.atualizar.problemas-saude',

    'historico-familiar':
        'perfil.atualizar.historico-familiar',

    'alergias':
        'perfil.atualizar.alergias',

    'medicamentos':
        'perfil.atualizar.medicamentos',

    'cirurgias':
        'perfil.atualizar.cirurgias',

    'habitos':
        'perfil.atualizar.habitos',
}


/*
|--------------------------------------------------------------------------
| VOLTAR
|--------------------------------------------------------------------------
*/

function voltar() {
    router.visit(route('perfil.index'))
}


/*
|--------------------------------------------------------------------------
| SALVAR
|--------------------------------------------------------------------------
*/

function salvar() {

    const nomeRota =
        rotasAtualizacao[props.secao]

    if (!nomeRota) {

        console.error(
            'Seção de perfil inválida:',
            props.secao
        )

        return
    }


    form.put(
        route(nomeRota),
        {
            preserveScroll: true,

            onSuccess: () => {

                router.visit(
                    route('perfil.index')
                )
            },
        }
    )
}
</script>


<template>

    <Head :title="`Editar - ${titulo}`" />

    <AuthenticatedLayout>

        <div class="mx-auto max-w-5xl px-4 py-8">


            <!-- ========================================================= -->
            <!-- CABEÇALHO -->
            <!-- ========================================================= -->

            <div class="mb-6">

                <button
                    type="button"
                    @click="voltar"
                    class="mb-4 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50"
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


            <!-- ========================================================= -->
            <!-- FORMULÁRIO -->
            <!-- ========================================================= -->

            <div
                class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm"
            >


                <!-- ===================================================== -->
                <!-- DADOS PESSOAIS -->
                <!-- ===================================================== -->

                <DadosPessoais
                    v-if="secao === 'dados-pessoais'"
                    :form="form"
                />


                <!-- ===================================================== -->
                <!-- DEFICIÊNCIAS -->
                <!-- ===================================================== -->

                <Deficiencias
                    v-else-if="secao === 'deficiencias'"
                    :form="form"
                />


                <!-- ===================================================== -->
                <!-- PROBLEMAS DE SAÚDE -->
                <!-- ===================================================== -->

                <ProblemasSaude
                    v-else-if="secao === 'problemas-saude'"
                    :form="form"
                />


                <!-- ===================================================== -->
                <!-- HISTÓRICO FAMILIAR -->
                <!-- ===================================================== -->

                <HistoricoFamiliar
                    v-else-if="secao === 'historico-familiar'"
                    :form="form"
                />


                <!-- ===================================================== -->
                <!-- ALERGIAS -->
                <!-- ===================================================== -->

                <Alergias
                    v-else-if="secao === 'alergias'"
                    :form="form"
                />


                <!-- ===================================================== -->
                <!-- MEDICAMENTOS -->
                <!-- ===================================================== -->

                <Medicamentos
                    v-else-if="secao === 'medicamentos'"
                    :form="form"
                />


                <!-- ===================================================== -->
                <!-- CIRURGIAS -->
                <!-- ===================================================== -->

                <Cirurgias
                    v-else-if="secao === 'cirurgias'"
                    :form="form"
                />


                <!-- ===================================================== -->
                <!-- HÁBITOS -->
                <!-- ===================================================== -->

                <Habitos
                    v-else-if="secao === 'habitos'"
                    :form="form"
                />


                <!-- ===================================================== -->
                <!-- SEÇÃO INVÁLIDA -->
                <!-- ===================================================== -->

                <div
                    v-else
                    class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700"
                >
                    Seção de perfil inválida.
                </div>


                <!-- ===================================================== -->
                <!-- BOTÕES -->
                <!-- ===================================================== -->

                <div
                    class="mt-8 flex items-center justify-between border-t border-gray-200 pt-6"
                >

                    <!-- CANCELAR -->

                    <button
                        type="button"
                        @click="voltar"
                        class="rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50"
                    >
                        Cancelar
                    </button>


                    <!-- SALVAR -->

                    <button
                        type="button"
                        @click="salvar"
                        :disabled="
                            form.processing ||
                            !rotasAtualizacao[secao]
                        "
                        class="rounded-lg bg-indigo-600 px-6 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        {{
                            form.processing
                                ? 'Salvando...'
                                : 'Salvar alterações'
                        }}
                    </button>

                </div>

            </div>

        </div>

    </AuthenticatedLayout>

</template>
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

    // Dados pessoais
    cpf: props.paciente?.cpf ?? '',
    sexo: props.paciente?.sexo ?? '',
    data_nascimento: props.paciente?.data_nascimento ?? '',
    telefone: props.paciente?.telefone ?? '',
    estado: props.paciente?.estado ?? '',
    cidade: props.paciente?.cidade ?? '',
    profissao: props.paciente?.profissao ?? '',

    // Tipo sanguíneo
    tipo_sanguineo:
        props.perfilSaude?.tipo_sanguineo ?? '',

    // Deficiências
    deficiencias:
        Array.isArray(props.perfilSaude?.deficiencias)
            ? props.perfilSaude.deficiencias
            : [],

    deficiencia_outros_descricao:
        props.perfilSaude?.deficiencia_outros_descricao ?? '',

    necessidades_acessibilidade:
        props.perfilSaude?.necessidades_acessibilidade ?? '',

    // Problemas de saúde
    problemas_saude_previos:
        Array.isArray(props.perfilSaude?.problemas_saude_previos)
            ? props.perfilSaude.problemas_saude_previos
            : [],

    problemas_saude_detalhes:
        props.perfilSaude?.problemas_saude_detalhes ?? '',

    // Histórico familiar
    historico_familiar:
        Array.isArray(props.perfilSaude?.historico_familiar)
            ? props.perfilSaude.historico_familiar
            : [],

    historico_familiar_detalhes:
        props.perfilSaude?.historico_familiar_detalhes ?? '',

    // Alergias
    alergias:
        Array.isArray(props.perfilSaude?.alergias)
            ? props.perfilSaude.alergias
            : [],

    alergias_detalhes:
        props.perfilSaude?.alergias_detalhes ?? '',

    alergias_reacoes:
        Array.isArray(props.perfilSaude?.alergias_reacoes)
            ? props.perfilSaude.alergias_reacoes
            : [],

    alergias_observacoes:
        props.perfilSaude?.alergias_observacoes ?? '',

    // Medicamentos
    medicamentos:
        Array.isArray(props.medicamentos)
            ? props.medicamentos.map((medicamento) => ({
                id: medicamento.id ?? null,
                nome: medicamento.nome ?? '',
                dosagem: medicamento.dosagem ?? '',
                frequencia: medicamento.frequencia ?? '',
                indicacao: medicamento.indicacao ?? '',
            }))
            : [],

    // Cirurgias
    cirurgias:
        Array.isArray(props.cirurgias)
            ? props.cirurgias.map((cirurgia) => ({
                id: cirurgia.id ?? null,
                procedimento: cirurgia.procedimento ?? '',
                ano: cirurgia.ano ?? '',
                anestesia: cirurgia.anestesia ?? '',
                complicacoes:
                    Array.isArray(cirurgia.complicacoes)
                        ? cirurgia.complicacoes
                        : [],
                implantes:
                    Array.isArray(cirurgia.implantes)
                        ? cirurgia.implantes
                        : [],
                observacoes: cirurgia.observacoes ?? '',
            }))
            : [],

    // Hábitos
    fumante:
        props.perfilSaude?.fumante ?? false,

    alcoolismo:
        props.perfilSaude?.alcoolismo ?? false,

    uso_drogas:
        props.perfilSaude?.uso_drogas ?? false,

    atividade_fisica_regular:
        props.perfilSaude?.atividade_fisica_regular ?? false,

    // Medidas
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

    deficiencias:
        'Necessidades de acessibilidade',

    'problemas-saude':
        'Condições de saúde',

    'historico-familiar':
        'Histórico familiar',

    alergias:
        'Alergias',

    medicamentos:
        'Medicamentos e suplementos',

    cirurgias:
        'Cirurgias anteriores',

    habitos:
        'Hábitos e atividade física',
}

const titulo = computed(() => {
    return titulos[props.secao] ?? 'Editar perfil'
})

/*
|--------------------------------------------------------------------------
| ROTAS DE SALVAMENTO
|--------------------------------------------------------------------------
*/

const rotasSalvar = {
    'dados-pessoais':
        '/perfil/editar/dados-pessoais',

    deficiencias:
        '/perfil/editar/deficiencias',

    'problemas-saude':
        '/perfil/editar/problemas-saude',

    'historico-familiar':
        '/perfil/editar/historico-familiar',

    alergias:
        '/perfil/editar/alergias',

    medicamentos:
        '/perfil/editar/medicamentos',

    cirurgias:
        '/perfil/editar/cirurgias',

    habitos:
        '/perfil/editar/habitos',
}

/*
|--------------------------------------------------------------------------
| VOLTAR
|--------------------------------------------------------------------------
*/

function voltar() {
    router.visit('/perfil')
}

/*
|--------------------------------------------------------------------------
| SALVAR
|--------------------------------------------------------------------------
*/

function salvar() {
    const url = rotasSalvar[props.secao]

    if (!url) {
        console.error(
            'Seção de perfil inválida:',
            props.secao
        )

        return
    }

    console.log(
        'Salvando seção:',
        props.secao
    )

    console.log(
        'URL:',
        url
    )

    console.log(
        'Dados:',
        form.data()
    )

    form.put(url, {
        preserveScroll: true,

        onSuccess: () => {
            console.log(
                'Perfil salvo com sucesso!'
            )

            router.visit('/perfil')
        },

        onError: (errors) => {
            console.error(
                'Erro ao salvar perfil:',
                errors
            )
        },
    })
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

            <!-- FORMULÁRIO -->

            <div
                class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm"
            >

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

                <!-- SEÇÃO INVÁLIDA -->

                <div
                    v-else
                    class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700"
                >
                    Seção de perfil inválida.
                </div>

                <!-- BOTÕES -->

                <div
                    v-if="rotasSalvar[secao]"
                    class="mt-8 flex items-center justify-between border-t border-gray-200 pt-6"
                >

                    <!-- CANCELAR -->

                    <button
                        type="button"
                        @click="voltar"
                        :disabled="form.processing"
                        class="rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Cancelar
                    </button>

                    <!-- SALVAR -->

                    <button
                        type="button"
                        @click="salvar"
                        :disabled="form.processing"
                        class="rounded-lg bg-red-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <span v-if="form.processing">
                            Salvando...
                        </span>

                        <span v-else>
                            Salvar alterações
                        </span>
                    </button>

                </div>

            </div>

        </div>
    </AuthenticatedLayout>
</template>
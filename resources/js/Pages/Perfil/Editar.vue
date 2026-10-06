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
    secao: { type: String, required: true },
    paciente: { type: Object, required: true },
    perfilSaude: { type: Object, default: null },
    medicamentos: { type: Array, default: () => [] },
    cirurgias: { type: Array, default: () => [] },
})

const form = useForm({
    // Dados Pessoais
    cpf: props.paciente?.cpf ?? '',
    sexo: props.paciente?.sexo ?? '',
    data_nascimento: props.paciente?.data_nascimento ?? '',
    telefone: props.paciente?.telefone ?? '',
    estado: props.paciente?.estado ?? '',
    cidade: props.paciente?.cidade ?? '',
    profissao: props.paciente?.profissao ?? '',

    // Tipo Sanguíneo
    tipo_sanguineo: props.perfilSaude?.tipo_sanguineo ?? '',

    // Deficiências
    deficiencias: Array.isArray(props.perfilSaude?.deficiencias) ? props.perfilSaude.deficiencias : [],
    deficiencia_outros_descricao: props.perfilSaude?.deficiencia_outros_descricao ?? '',
    necessidades_acessibilidade: props.perfilSaude?.necessidades_acessibilidade ?? '',

    // Problemas de Saúde
    problemas_saude_previos: Array.isArray(props.perfilSaude?.problemas_saude_previos) ? props.perfilSaude.problemas_saude_previos : [],
    problemas_saude_detalhes: props.perfilSaude?.problemas_saude_detalhes ?? '',

    // Histórico Familiar
    historico_familiar: Array.isArray(props.perfilSaude?.historico_familiar) ? props.perfilSaude.historico_familiar : [],
    historico_familiar_detalhes: props.perfilSaude?.historico_familiar_detalhes ?? '',

    // Alergias
    alergias: Array.isArray(props.perfilSaude?.alergias) ? props.perfilSaude.alergias : [],
    alergias_detalhes: props.perfilSaude?.alergias_detalhes ?? '',
    alergias_reacoes: Array.isArray(props.perfilSaude?.alergias_reacoes) ? props.perfilSaude.alergias_reacoes : [],
    alergias_observacoes: props.perfilSaude?.alergias_observacoes ?? '',

    // Medicamentos
    medicamentos: Array.isArray(props.medicamentos)
        ? props.medicamentos.map((m) => ({
              id: m.id ?? null,
              nome: m.nome ?? '',
              dosagem: m.dosagem ?? '',
              frequencia: m.frequencia ?? '',
              indicacao: m.indicacao ?? '',
          }))
        : [],

    // Cirurgias
    cirurgias: Array.isArray(props.cirurgias)
        ? props.cirurgias.map((c) => ({
              id: c.id ?? null,
              tipo: c.tipo ?? c.procedimento ?? '',
              ano: c.ano ?? '',
              anestesia: c.anestesia ?? '',
              complicacoes: Array.isArray(c.complicacoes) ? c.complicacoes : [],
              implantes: Array.isArray(c.implantes) ? c.implantes : [],
              observacoes: c.observacoes ?? '',
          }))
        : [],

    // Hábitos
    fumante: props.perfilSaude?.fumante ?? false,
    alcoolismo: props.perfilSaude?.alcoolismo ?? false,
    uso_drogas: props.perfilSaude?.uso_drogas ?? false,
    atividade_fisica_regular: props.perfilSaude?.atividade_fisica_regular ?? false,

    // Medidas
    altura: props.perfilSaude?.altura ?? '',
    peso: props.perfilSaude?.peso ?? '',
})

const titulos = {
    'dados-pessoais': 'Dados pessoais',
    deficiencias: 'Necessidades de acessibilidade',
    'problemas-saude': 'Condições de saúde',
    'historico-familiar': 'Histórico familiar',
    alergias: 'Alergias',
    medicamentos: 'Medicamentos e suplementos',
    cirurgias: 'Cirurgias anteriores',
    habitos: 'Hábitos e atividade física',
}

const descricoes = {
    'dados-pessoais': 'Atualize suas informações básicas e dados de contato',
    deficiencias: 'Informe necessidades especiais e preferências de acessibilidade',
    'problemas-saude': 'Cadastre diagnósticos, doenças crônicas ou acompanhamentos médicos',
    'historico-familiar': 'Registre antecedentes de saúde de parentes de primeiro grau',
    alergias: 'Especifique medicamentos, alimentos ou substâncias que provocam reações',
    medicamentos: 'Mantenha atualizada a lista de medicamentos e suplementos em uso',
    cirurgias: 'Cadastre cirurgias anteriores, tipo de anestesia e observações',
    habitos: 'Informe hábitos cotidianos, estilo de vida e medidas corporais',
}

const rotasSalvar = {
    'dados-pessoais': '/perfil/editar/dados-pessoais',
    deficiencias: '/perfil/editar/deficiencias',
    'problemas-saude': '/perfil/editar/problemas-saude',
    'historico-familiar': '/perfil/editar/historico-familiar',
    alergias: '/perfil/editar/alergias',
    medicamentos: '/perfil/editar/medicamentos',
    cirurgias: '/perfil/editar/cirurgias',
    habitos: '/perfil/editar/habitos',
}

const titulo = computed(() => titulos[props.secao] ?? 'Editar perfil')
const descricao = computed(() => descricoes[props.secao] ?? 'Atualize as informações do seu perfil de saúde')

function voltar() {
    router.visit('/perfil')
}

function cirurgiaVazia(c) {
    const tipo = String(c.tipo ?? '').trim()
    const ano = String(c.ano ?? '').trim()
    const anestesia = String(c.anestesia ?? '').trim()
    const observacoes = String(c.observacoes ?? '').trim()
    const complicacoes = Array.isArray(c.complicacoes) ? c.complicacoes : []
    const implantes = Array.isArray(c.implantes) ? c.implantes : []

    return (
        tipo === '' &&
        ano === '' &&
        anestesia === '' &&
        observacoes === '' &&
        complicacoes.length === 0 &&
        implantes.length === 0
    )
}

function prepararCirurgias() {
    if (!Array.isArray(form.cirurgias)) return []

    return form.cirurgias
        .filter((c) => !cirurgiaVazia(c))
        .map((c) => ({
            id: c.id ?? null,
            tipo: String(c.tipo ?? c.procedimento ?? '').trim(),
            ano: c.ano === '' ? null : c.ano,
            anestesia: c.anestesia || null,
            complicacoes: Array.isArray(c.complicacoes) ? c.complicacoes : [],
            implantes: Array.isArray(c.implantes) ? c.implantes : [],
            observacoes: c.observacoes || null,
        }))
}

function salvar() {
    const url = rotasSalvar[props.secao]

    if (!url) {
        console.error('Seção de perfil inválida:', props.secao)
        return
    }

    form.transform((data) => ({
        ...data,
        cirurgias: props.secao === 'cirurgias' ? prepararCirurgias() : data.cirurgias,
    })).put(url, {
        preserveScroll: true,
        onSuccess: () => {
            router.visit('/perfil')
        },
        onError: (errors) => {
            console.error('Erro ao salvar:', errors)
        },
    })
}
</script>

<template>
    <Head :title="`Editar - ${titulo}`" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">

            <!-- BARRA SUPERIOR / HEADER -->
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        @click="voltar"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-xs transition hover:border-slate-300 hover:bg-slate-50 active:scale-95"
                        title="Voltar para o perfil"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                    </button>

                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-xl font-bold tracking-tight text-slate-800">
                                {{ titulo }}
                            </h1>
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                Edição
                            </span>
                        </div>
                        <p class="mt-0.5 text-xs text-slate-500">
                            {{ descricao }}
                        </p>
                    </div>
                </div>

                <!-- AÇÕES RÁPIDAS NO TOPO (OPCIONAL/RESPONSIVO) -->
                <div class="hidden sm:flex items-center gap-2">
                   
                </div>
            </div>

            <!-- CARD DE ERROS GERAIS -->
            <div v-if="Object.keys(form.errors).length > 0" class="mb-6 rounded-xl border border-rose-200 bg-rose-50/80 p-4 text-xs text-rose-800">
                <div class="flex items-center gap-2 font-semibold text-rose-900">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4 text-rose-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    Existem campos que precisam de sua atenção:
                </div>
                <ul class="mt-1.5 list-disc list-inside space-y-0.5 text-rose-700">
                    <li v-for="(erro, campo) in form.errors" :key="campo">
                        {{ erro }}
                    </li>
                </ul>
            </div>

            <!-- FORMULÁRIO DINÂMICO -->
            <form @submit.prevent="salvar" class="space-y-6">
                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs">

                    <DadosPessoais
                        v-if="secao === 'dados-pessoais'"
                        :form="form"
                    />

                    <Deficiencias
                        v-else-if="secao === 'deficiencias'"
                        :form="form"
                    />

                    <ProblemasSaude
                        v-else-if="secao === 'problemas-saude'"
                        :form="form"
                    />

                    <HistoricoFamiliar
                        v-else-if="secao === 'historico-familiar'"
                        :form="form"
                    />

                    <Alergias
                        v-else-if="secao === 'alergias'"
                        :form="form"
                    />

                    <Medicamentos
                        v-else-if="secao === 'medicamentos'"
                        :form="form"
                    />

                    <Cirurgias
                        v-else-if="secao === 'cirurgias'"
                        :form="form"
                    />

                    <Habitos
                        v-else-if="secao === 'habitos'"
                        :form="form"
                    />

                </div>

                <!-- RODAPÉ DE AÇÕES DA PÁGINA -->
                <div class="flex items-center justify-end gap-3 rounded-2xl border border-slate-200/80 bg-white px-6 py-4 shadow-xs">
                    <button
                        type="button"
                        @click="voltar"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-xs transition hover:bg-slate-50 hover:text-slate-900 active:scale-95"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-emerald-700 active:scale-95 disabled:opacity-50"
                    >
                        <svg v-if="form.processing" class="h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ form.processing ? 'Salvando...' : 'Salvar alterações' }}
                    </button>
                </div>
            </form>

        </div>
    </AuthenticatedLayout>
</template>
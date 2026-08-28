<script setup>
import { computed } from 'vue'

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
})


/*
|--------------------------------------------------------------------------
| TIPOS DE ALERGIA
|--------------------------------------------------------------------------
*/

const tiposAlergia = [
    {
        value: 'medicamentosa',
        label: 'Remédios',
        descricao: 'Ex.: Dipirona, Penicilina, anti-inflamatórios',
    },
    {
        value: 'alimentar',
        label: 'Alimentos',
        descricao: 'Ex.: Leite/lactose, glúten, frutos do mar, amendoim',
    },
    {
        value: 'outros',
        label: 'Outros',
        descricao: 'Ex.: Látex, contraste de exame, picada de inseto',
    },
]


/*
|--------------------------------------------------------------------------
| TIPOS DE REAÇÃO
|--------------------------------------------------------------------------
*/

const tiposReacao = [
    {
        value: 'urticaria',
        label: 'Manchas na pele / Coceira',
        descricao: 'Urticária',
    },
    {
        value: 'inchaco',
        label: 'Inchaço nos lábios, olhos ou garganta',
        descricao: '',
    },
    {
        value: 'dificuldade_respirar',
        label: 'Dificuldade grave para respirar',
        descricao: 'Falta de ar',
    },
    {
        value: 'tontura_desmaio',
        label: 'Tontura forte ou desmaio',
        descricao: '',
    },
]


/*
|--------------------------------------------------------------------------
| COMPUTADOS
|--------------------------------------------------------------------------
*/

const alergias = computed(() => {
    return Array.isArray(props.form.alergias)
        ? props.form.alergias
        : []
})


const reacoes = computed(() => {
    return Array.isArray(props.form.alergias_reacoes)
        ? props.form.alergias_reacoes
        : []
})


const possuiAlergia = computed(() => {
    return alergias.value.length > 0 &&
        !alergias.value.includes('nao_possui')
})


/*
|--------------------------------------------------------------------------
| NÃO POSSUI ALERGIA
|--------------------------------------------------------------------------
*/

function marcarNaoPossui(event) {

    if (event.target.checked) {

        props.form.alergias = ['nao_possui']

        props.form.alergias_reacoes = []

        props.form.alergias_detalhes = ''

        props.form.alergias_observacoes = ''

    } else {

        props.form.alergias = []
    }
}


/*
|--------------------------------------------------------------------------
| ALTERAR TIPO DE ALERGIA
|--------------------------------------------------------------------------
*/

function alterarAlergia(tipo) {

    if (!Array.isArray(props.form.alergias)) {
        props.form.alergias = []
    }

    const index = props.form.alergias.indexOf(tipo)

    if (index === -1) {

        props.form.alergias.push(tipo)

    } else {

        props.form.alergias.splice(index, 1)
    }

    // Caso tenha alguma alergia, remove "não possui"
    props.form.alergias =
        props.form.alergias.filter(
            item => item !== 'nao_possui'
        )
}


/*
|--------------------------------------------------------------------------
| LIMPAR
|--------------------------------------------------------------------------
*/

function limparAlergias() {

    props.form.alergias = []

    props.form.alergias_reacoes = []

    props.form.alergias_detalhes = ''

    props.form.alergias_observacoes = ''
}
</script>


<template>

    <div>


        <!-- ============================================================= -->
        <!-- CABEÇALHO -->
        <!-- ============================================================= -->

        <div class="mb-8">

            <h2 class="text-2xl font-bold text-gray-800">
                Alergias
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Informe alergias conhecidas e como seu corpo reage a elas.
                Essas informações ajudam a identificar possíveis riscos
                durante o atendimento.
            </p>

        </div>


        <!-- ============================================================= -->
        <!-- TIPO DE ALERGIA -->
        <!-- ============================================================= -->

        <div>

            <h3 class="mb-3 text-base font-semibold text-gray-800">
                Você possui alguma alergia conhecida?
            </h3>

            <p class="mb-4 text-sm text-gray-500">
                Selecione todos os tipos que se aplicam.
            </p>


            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                <label
                    v-for="tipo in tiposAlergia"
                    :key="tipo.value"
                    class="cursor-pointer rounded-xl border p-4 transition"
                    :class="
                        alergias.includes(tipo.value)
                            ? 'border-gray-500 bg-gray-50'
                            : 'border-gray-200 hover:bg-gray-50'
                    "
                >

                    <div class="flex items-start gap-3">

                        <input
                            type="checkbox"
                            :checked="alergias.includes(tipo.value)"
                            @change="alterarAlergia(tipo.value)"
                            class="mt-1 rounded border-gray-300 text-gray-700 focus:ring-gray-500"
                        >

                        <div>

                            <span
                                class="block text-sm font-semibold text-gray-800"
                            >
                                {{ tipo.label }}
                            </span>

                            <span
                                class="mt-1 block text-xs text-gray-500"
                            >
                                {{ tipo.descricao }}
                            </span>

                        </div>

                    </div>

                </label>

            </div>


            <!-- ========================================================= -->
            <!-- NÃO POSSUI -->
            <!-- ========================================================= -->

            <label
                class="mt-4 flex cursor-pointer items-center gap-3 rounded-xl border p-4 transition"
                :class="
                    alergias.includes('nao_possui')
                        ? 'border-gray-500 bg-gray-50'
                        : 'border-gray-200 hover:bg-gray-50'
                "
            >

                <input
                    type="checkbox"
                    :checked="alergias.includes('nao_possui')"
                    @change="marcarNaoPossui"
                    class="rounded border-gray-300 text-gray-700 focus:ring-gray-500"
                >

                <div>

                    <span
                        class="block text-sm font-semibold text-gray-800"
                    >
                        Não possuo alergias conhecidas
                    </span>

                    <span
                        class="block text-xs text-gray-500"
                    >
                        Marque esta opção se nunca teve uma alergia conhecida.
                    </span>

                </div>

            </label>

        </div>


        <!-- ============================================================= -->
        <!-- INFORMAÇÕES DA ALERGIA -->
        <!-- ============================================================= -->

        <div
            v-if="possuiAlergia"
            class="mt-8 rounded-xl border border-gray-200 bg-gray-50 p-5"
        >

            <h3 class="text-base font-semibold text-gray-800">
                Sobre suas alergias
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Informe quais substâncias causam alergia.
            </p>


            <div class="mt-5">

                <label
                    class="block text-sm font-medium text-gray-700"
                >
                    Quais são as substâncias?
                </label>

                <textarea
                    v-model="form.alergias_detalhes"
                    rows="4"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                    placeholder="Ex.: Dipirona, Penicilina, amendoim, látex..."
                ></textarea>

                <p
                    v-if="form.errors.alergias_detalhes"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ form.errors.alergias_detalhes }}
                </p>

            </div>

        </div>


        <!-- ============================================================= -->
        <!-- REAÇÕES -->
        <!-- ============================================================= -->

        <div
            v-if="possuiAlergia"
            class="mt-8"
        >

            <h3 class="text-base font-semibold text-gray-800">
                O que acontece quando você tem uma reação?
            </h3>

            <p class="mt-1 mb-4 text-sm text-gray-500">
                Marque todas as reações que já aconteceram.
            </p>


            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">

                <label
                    v-for="reacao in tiposReacao"
                    :key="reacao.value"
                    class="flex cursor-pointer items-start gap-3 rounded-xl border p-4 transition"
                    :class="
                        reacoes.includes(reacao.value)
                            ? 'border-gray-500 bg-gray-50'
                            : 'border-gray-200 hover:bg-gray-50'
                    "
                >

                    <input
                        v-model="form.alergias_reacoes"
                        type="checkbox"
                        :value="reacao.value"
                        class="mt-1 rounded border-gray-300 text-gray-700 focus:ring-gray-500"
                    >

                    <div>

                        <span
                            class="block text-sm font-medium text-gray-800"
                        >
                            {{ reacao.label }}
                        </span>

                        <span
                            v-if="reacao.descricao"
                            class="block text-xs text-gray-500"
                        >
                            {{ reacao.descricao }}
                        </span>

                    </div>

                </label>

            </div>


            <p
                v-if="form.errors.alergias_reacoes"
                class="mt-2 text-sm text-red-600"
            >
                {{ form.errors.alergias_reacoes }}
            </p>

        </div>


        <!-- ============================================================= -->
        <!-- OBSERVAÇÕES -->
        <!-- ============================================================= -->

        <div
            v-if="possuiAlergia"
            class="mt-8"
        >

            <label
                class="block text-sm font-medium text-gray-700"
            >
                Outras informações
                <span class="font-normal text-gray-400">
                    (opcional)
                </span>
            </label>

            <textarea
                v-model="form.alergias_observacoes"
                rows="3"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                placeholder="Ex.: precisei ir ao hospital, a reação aconteceu há alguns anos..."
            ></textarea>

        </div>


        <!-- ============================================================= -->
        <!-- LIMPAR -->
        <!-- ============================================================= -->

        <div
            v-if="possuiAlergia"
            class="mt-6"
        >

            <button
                type="button"
                @click="limparAlergias"
                class="text-sm font-medium text-gray-500 underline hover:text-gray-800"
            >
                Limpar informações de alergia
            </button>

        </div>


    </div>

</template>
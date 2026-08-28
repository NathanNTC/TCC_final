<script setup>
import { computed } from 'vue'

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
})

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

const possuiAlergia = computed(() => {
    return props.form.alergias.length > 0
})

function limparAlergias() {
    props.form.alergias = []
    props.form.alergias_detalhes = ''
    props.form.alergias_reacoes = []
}
</script>

<template>
    <div>

        <!-- CABEÇALHO -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">
                Alergias
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Informe se possui alguma alergia conhecida. Essas informações
                ajudam a evitar riscos durante o atendimento.
            </p>
        </div>


        <!-- TIPO DE ALERGIA -->
        <div>
            <h3 class="mb-3 text-base font-semibold text-gray-800">
                Você possui alguma alergia?
            </h3>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                <label
                    v-for="tipo in tiposAlergia"
                    :key="tipo.value"
                    class="cursor-pointer rounded-xl border p-4 transition hover:bg-gray-50"
                    :class="form.alergias.includes(tipo.value)
                        ? 'border-indigo-500 bg-indigo-50'
                        : 'border-gray-200'"
                >

                    <div class="flex items-start gap-3">

                        <input
                            v-model="form.alergias"
                            type="checkbox"
                            :value="tipo.value"
                            class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                        >

                        <div>
                            <span class="block text-sm font-semibold text-gray-800">
                                {{ tipo.label }}
                            </span>

                            <span class="mt-1 block text-xs text-gray-500">
                                {{ tipo.descricao }}
                            </span>
                        </div>

                    </div>

                </label>

            </div>


            <!-- NÃO POSSUI -->
            <label
                class="mt-4 flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 p-4 hover:bg-gray-50"
                :class="form.alergias.includes('nao_possui')
                    ? 'border-green-500 bg-green-50'
                    : ''"
            >

                <input
                    type="checkbox"
                    :checked="form.alergias.includes('nao_possui')"
                    class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                    @change="
                        $event.target.checked
                            ? (form.alergias = ['nao_possui'])
                            : (form.alergias = [])
                    "
                >

                <div>
                    <span class="block text-sm font-semibold text-gray-800">
                        Não possuo alergias conhecidas
                    </span>

                    <span class="block text-xs text-gray-500">
                        Marque esta opção se não tiver alergias conhecidas.
                    </span>
                </div>

            </label>

        </div>


        <!-- INFORMAÇÕES DA ALERGIA -->
        <div
            v-if="possuiAlergia && !form.alergias.includes('nao_possui')"
            class="mt-8 rounded-xl border border-gray-200 bg-gray-50 p-5"
        >

            <h3 class="text-base font-semibold text-gray-800">
                Sobre suas alergias
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Informe quais substâncias causam alergia.
            </p>


            <div class="mt-5">

                <label class="block text-sm font-medium text-gray-700">
                    Quais são as substâncias?
                </label>

                <textarea
                    v-model="form.alergias_detalhes"
                    rows="4"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Ex.: Dipirona, Penicilina, amendoim, látex..."
                ></textarea>

            </div>

        </div>


        <!-- REAÇÕES -->
        <div
            v-if="possuiAlergia && !form.alergias.includes('nao_possui')"
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
                    class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 p-4 hover:bg-gray-50"
                    :class="form.alergias_reacoes?.includes(reacao.value)
                        ? 'border-indigo-500 bg-indigo-50'
                        : ''"
                >

                    <input
                        v-model="form.alergias_reacoes"
                        type="checkbox"
                        :value="reacao.value"
                        class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    >

                    <div>
                        <span class="block text-sm font-medium text-gray-800">
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

        </div>


        <!-- OBSERVAÇÕES -->
        <div
            v-if="possuiAlergia && !form.alergias.includes('nao_possui')"
            class="mt-8"
        >

            <label class="block text-sm font-medium text-gray-700">
                Outras informações
                <span class="font-normal text-gray-400">
                    (opcional)
                </span>
            </label>

            <textarea
                v-model="form.alergias_observacoes"
                rows="3"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Informe qualquer outra informação importante sobre suas alergias."
            ></textarea>

        </div>

    </div>
</template>
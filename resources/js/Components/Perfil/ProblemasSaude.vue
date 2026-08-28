<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <div>

        <!-- CABEÇALHO -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                Problemas de saúde prévios
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Informe doenças, condições ou problemas de saúde já diagnosticados.
            </p>
        </div>


        <!-- CONDIÇÕES -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            <label
                v-for="item in [
                    ['hipertensao', 'Hipertensão'],
                    ['diabetes', 'Diabetes'],
                    ['dislipidemia', 'Dislipidemia'],
                    ['asma_dpoc', 'Asma / DPOC'],
                    ['infarto', 'Infarto prévio'],
                    ['arritmia', 'Arritmias'],
                    ['insuficiencia_cardiaca', 'Insuficiência cardíaca'],
                    ['epilepsia', 'Epilepsia'],
                    ['avc', 'AVC prévio'],
                    ['enxaqueca', 'Enxaqueca crônica'],
                    ['hipotireoidismo', 'Hipotireoidismo'],
                    ['cancer', 'Histórico de câncer'],
                    ['cirurgias', 'Cirurgias prévias'],
                    ['internacoes', 'Internações recentes'],
                    ['outros', 'Outros'],
                ]"
                :key="item[0]"
                class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 p-4 transition hover:bg-gray-50"
                :class="form.problemas_saude_previos.includes(item[0])
                    ? 'border-indigo-500 bg-indigo-50'
                    : ''"
            >

                <input
                    v-model="form.problemas_saude_previos"
                    type="checkbox"
                    :value="item[0]"
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                >

                <span class="text-sm font-medium text-gray-700">
                    {{ item[1] }}
                </span>

            </label>

        </div>


        <!-- DETALHES -->
        <div class="mt-6">

            <label class="block text-sm font-medium text-gray-700">
                Detalhes adicionais
                <span class="font-normal text-gray-400">
                    (opcional)
                </span>
            </label>

            <textarea
                v-model="form.problemas_saude_detalhes"
                rows="5"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Informe detalhes importantes sobre seus problemas de saúde."
            ></textarea>

            <p
                v-if="form.errors.problemas_saude_detalhes"
                class="mt-1 text-sm text-red-600"
            >
                {{ form.errors.problemas_saude_detalhes }}
            </p>

        </div>

    </div>
</template>
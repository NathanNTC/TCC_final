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

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                Problemas de saúde prévios
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Informe doenças, condições ou problemas de saúde já diagnosticados.
            </p>
        </div>

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
                class="flex cursor-pointer items-center gap-3 rounded-xl border p-4 hover:bg-gray-50"
            >
                <input
                    v-model="form.problemas_saude_previos"
                    type="checkbox"
                    :value="item[0]"
                    class="rounded border-gray-300"
                >

                <span class="text-sm font-medium text-gray-700">
                    {{ item[1] }}
                </span>
            </label>

        </div>

        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700">
                Detalhes adicionais
            </label>

            <textarea
                v-model="form.problemas_saude_detalhes"
                rows="5"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                placeholder="Informe detalhes importantes sobre seus problemas de saúde."
            ></textarea>
        </div>

    </div>
</template>
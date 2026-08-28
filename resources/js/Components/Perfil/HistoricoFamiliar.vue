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
                Histórico familiar
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Informe doenças importantes presentes em familiares próximos.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            <label
                v-for="item in [
                    ['infarto', 'Infarto ou doença cardíaca'],
                    ['morte_subita', 'Morte súbita'],
                    ['diabetes', 'Diabetes'],
                    ['hipertensao', 'Hipertensão'],
                    ['cancer_mama', 'Câncer de mama'],
                    ['cancer_colon', 'Câncer de cólon'],
                    ['cancer_prostata', 'Câncer de próstata'],
                    ['doencas_geneticas', 'Doenças genéticas ou raras'],
                    ['outros', 'Outros'],
                ]"
                :key="item[0]"
                class="flex cursor-pointer items-center gap-3 rounded-xl border p-4 hover:bg-gray-50"
            >
                <input
                    v-model="form.historico_familiar"
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
                Detalhes do histórico familiar
            </label>

            <textarea
                v-model="form.historico_familiar_detalhes"
                rows="5"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                placeholder="Informe quem apresentou a condição e outras informações relevantes."
            ></textarea>
        </div>

    </div>
</template>
<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
})

const opcoes = [
    ['infarto', 'Infarto ou doença cardíaca'],
    ['morte_subita', 'Morte súbita'],
    ['diabetes', 'Diabetes'],
    ['hipertensao', 'Hipertensão'],
    ['cancer_mama', 'Câncer de mama'],
    ['cancer_colon', 'Câncer de cólon'],
    ['cancer_prostata', 'Câncer de próstata'],
    ['doencas_geneticas', 'Doenças genéticas ou raras'],
    ['outros', 'Outros'],
]
</script>

<template>
    <div>

        <!-- CABEÇALHO -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">
                Histórico familiar
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Informe doenças importantes presentes em familiares próximos.
            </p>
        </div>


        <!-- CONDIÇÕES -->
        <div>

            <h3 class="mb-4 text-base font-semibold text-gray-800">
                Existem casos dessas condições na sua família?
            </h3>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                <label
                    v-for="item in opcoes"
                    :key="item[0]"
                    class="flex cursor-pointer items-center gap-3 rounded-xl border p-4 transition hover:bg-gray-50"
                    :class="form.historico_familiar?.includes(item[0])
                        ? 'border-indigo-500 bg-indigo-50'
                        : 'border-gray-200'"
                >

                    <input
                        v-model="form.historico_familiar"
                        type="checkbox"
                        :value="item[0]"
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    >

                    <span class="text-sm font-medium text-gray-700">
                        {{ item[1] }}
                    </span>

                </label>

            </div>

            <p
                v-if="form.errors.historico_familiar"
                class="mt-2 text-sm text-red-600"
            >
                {{ form.errors.historico_familiar }}
            </p>

        </div>


        <!-- DETALHES -->
        <div class="mt-8">

            <label class="block text-sm font-medium text-gray-700">
                Detalhes do histórico familiar
                <span class="font-normal text-gray-400">
                    (opcional)
                </span>
            </label>

            <p class="mt-1 text-sm text-gray-500">
                Informe quem apresentou a condição, o grau de parentesco e
                outras informações relevantes.
            </p>

            <textarea
                v-model="form.historico_familiar_detalhes"
                rows="5"
                class="mt-3 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Ex.: Minha mãe possui diabetes tipo 2. Meu avô teve um infarto aos 60 anos."
            ></textarea>

            <p
                v-if="form.errors.historico_familiar_detalhes"
                class="mt-1 text-sm text-red-600"
            >
                {{ form.errors.historico_familiar_detalhes }}
            </p>

        </div>


        <!-- AVISO -->
        <div class="mt-8 rounded-xl border border-gray-200 bg-gray-50 p-5">

            <p class="text-sm leading-relaxed text-gray-600">
                Essas informações ajudam a identificar possíveis fatores
                familiares relevantes durante a triagem.
            </p>

        </div>

    </div>
</template>
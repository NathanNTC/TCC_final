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
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">
                Hábitos e medidas
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Informe alguns hábitos e medidas importantes para sua avaliação.
            </p>
        </div>


        <!-- HÁBITOS -->
        <div>
            <h3 class="mb-4 text-lg font-semibold text-gray-800">
                Hábitos
            </h3>

            <div class="space-y-4">

                <!-- FUMANTE -->
                <label
                    class="flex cursor-pointer items-center justify-between rounded-xl border border-gray-200 p-5 transition hover:bg-gray-50"
                    :class="form.fumante
                        ? 'border-indigo-500 bg-indigo-50'
                        : ''"
                >

                    <div>
                        <p class="font-medium text-gray-800">
                            É fumante?
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Inclui cigarro e outros produtos derivados do tabaco.
                        </p>
                    </div>

                    <input
                        v-model="form.fumante"
                        type="checkbox"
                        class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    >

                </label>


                <!-- ÁLCOOL -->
                <label
                    class="flex cursor-pointer items-center justify-between rounded-xl border border-gray-200 p-5 transition hover:bg-gray-50"
                    :class="form.alcoolismo
                        ? 'border-indigo-500 bg-indigo-50'
                        : ''"
                >

                    <div>
                        <p class="font-medium text-gray-800">
                            Consome bebidas alcoólicas?
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Informe se possui consumo habitual de bebidas alcoólicas.
                        </p>
                    </div>

                    <input
                        v-model="form.alcoolismo"
                        type="checkbox"
                        class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    >

                </label>


                <!-- DROGAS -->
                <label
                    class="flex cursor-pointer items-center justify-between rounded-xl border border-gray-200 p-5 transition hover:bg-gray-50"
                    :class="form.uso_drogas
                        ? 'border-indigo-500 bg-indigo-50'
                        : ''"
                >

                    <div>
                        <p class="font-medium text-gray-800">
                            Faz uso de drogas?
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Informe caso faça uso de substâncias psicoativas.
                        </p>
                    </div>

                    <input
                        v-model="form.uso_drogas"
                        type="checkbox"
                        class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    >

                </label>


                <!-- ATIVIDADE FÍSICA -->
                <label
                    class="flex cursor-pointer items-center justify-between rounded-xl border border-gray-200 p-5 transition hover:bg-gray-50"
                    :class="form.atividade_fisica_regular
                        ? 'border-indigo-500 bg-indigo-50'
                        : ''"
                >

                    <div>
                        <p class="font-medium text-gray-800">
                            Pratica atividade física regularmente?
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Considere atividades físicas realizadas de forma habitual.
                        </p>
                    </div>

                    <input
                        v-model="form.atividade_fisica_regular"
                        type="checkbox"
                        class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    >

                </label>

            </div>
        </div>


        <!-- MEDIDAS -->
        <div class="mt-8">

            <h3 class="text-lg font-semibold text-gray-800">
                Medidas corporais
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Informe seus valores atuais, se souber.
            </p>


            <div class="mt-5 grid grid-cols-1 gap-5 md:grid-cols-2">

                <!-- ALTURA -->
                <div>

                    <label class="block text-sm font-medium text-gray-700">
                        Altura (metros)
                    </label>

                    <input
                        v-model="form.altura"
                        type="number"
                        min="0.5"
                        max="3"
                        step="0.01"
                        placeholder="Ex.: 1.75"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    <p
                        v-if="form.errors.altura"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ form.errors.altura }}
                    </p>

                </div>


                <!-- PESO -->
                <div>

                    <label class="block text-sm font-medium text-gray-700">
                        Peso (kg)
                    </label>

                    <input
                        v-model="form.peso"
                        type="number"
                        min="1"
                        max="500"
                        step="0.01"
                        placeholder="Ex.: 70"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    <p
                        v-if="form.errors.peso"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ form.errors.peso }}
                    </p>

                </div>

            </div>

        </div>


        <!-- AVISO -->
        <div class="mt-8 rounded-xl border border-gray-200 bg-gray-50 p-5">

            <p class="text-sm leading-relaxed text-gray-600">
                Essas informações serão utilizadas para complementar seu perfil
                e auxiliar no processo de triagem.
            </p>

        </div>

    </div>
</template>
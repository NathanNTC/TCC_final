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
                Hábitos e medidas
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Informe alguns hábitos e medidas importantes para sua avaliação.
            </p>
        </div>

        <!-- Hábitos -->
        <div class="space-y-4">

            <label class="flex cursor-pointer items-center justify-between rounded-xl border p-5">

                <div>
                    <p class="font-medium text-gray-800">
                        É fumante?
                    </p>

                    <p class="text-sm text-gray-500">
                        Inclui cigarro e outros produtos derivados do tabaco.
                    </p>
                </div>

                <input
                    v-model="form.fumante"
                    type="checkbox"
                    class="h-5 w-5 rounded border-gray-300"
                >

            </label>

            <label class="flex cursor-pointer items-center justify-between rounded-xl border p-5">

                <div>
                    <p class="font-medium text-gray-800">
                        Consome bebidas alcoólicas?
                    </p>
                </div>

                <input
                    v-model="form.alcoolismo"
                    type="checkbox"
                    class="h-5 w-5 rounded border-gray-300"
                >

            </label>

            <label class="flex cursor-pointer items-center justify-between rounded-xl border p-5">

                <div>
                    <p class="font-medium text-gray-800">
                        Faz uso de drogas?
                    </p>
                </div>

                <input
                    v-model="form.uso_drogas"
                    type="checkbox"
                    class="h-5 w-5 rounded border-gray-300"
                >

            </label>

            <label class="flex cursor-pointer items-center justify-between rounded-xl border p-5">

                <div>
                    <p class="font-medium text-gray-800">
                        Pratica atividade física regularmente?
                    </p>
                </div>

                <input
                    v-model="form.atividade_fisica_regular"
                    type="checkbox"
                    class="h-5 w-5 rounded border-gray-300"
                >

            </label>

        </div>

        <!-- Medidas -->
        <div class="mt-8">

            <h3 class="text-lg font-semibold text-gray-800">
                Medidas corporais
            </h3>

            <div class="mt-4 grid grid-cols-1 gap-5 md:grid-cols-2">

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
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                    >
                </div>

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
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                    >
                </div>

            </div>

        </div>

        <!-- Aviso -->
        <div class="mt-8 rounded-xl bg-gray-50 p-5">

            <p class="text-sm text-gray-600">
                Essas informações serão utilizadas para complementar seu perfil
                e auxiliar no processo de triagem.
            </p>

        </div>

    </div>
</template>
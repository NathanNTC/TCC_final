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
                Deficiências
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Selecione todas as opções que se aplicam.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            <label
                v-for="item in [
                    ['fisica', 'Física'],
                    ['visual', 'Visual'],
                    ['auditiva', 'Auditiva'],
                    ['intelectual', 'Intelectual/Cognitiva'],
                    ['psicossocial', 'Psicossocial'],
                    ['outros', 'Outros'],
                ]"
                :key="item[0]"
                class="flex cursor-pointer items-center gap-3 rounded-xl border p-4 transition hover:bg-gray-50"
            >
                <input
                    v-model="form.deficiencias"
                    type="checkbox"
                    :value="item[0]"
                    class="rounded border-gray-300"
                >

                <span class="text-sm font-medium text-gray-700">
                    {{ item[1] }}
                </span>
            </label>

        </div>

        <div
            v-if="form.deficiencias.includes('outros')"
            class="mt-6"
        >
            <label class="block text-sm font-medium text-gray-700">
                Especifique
            </label>

            <textarea
                v-model="form.deficiencia_outros_descricao"
                rows="3"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                placeholder="Descreva a deficiência."
            ></textarea>
        </div>

        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700">
                Necessidades de acessibilidade
            </label>

            <textarea
                v-model="form.necessidades_acessibilidade"
                rows="3"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                placeholder="Informe alguma necessidade específica."
            ></textarea>
        </div>

    </div>
</template>
<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
})

const opcoesDeficiencias = [
    {
        value: 'fisica',
        label: 'Física',
    },
    {
        value: 'visual',
        label: 'Visual',
    },
    {
        value: 'auditiva',
        label: 'Auditiva',
    },
    {
        value: 'intelectual',
        label: 'Intelectual/Cognitiva',
    },
    {
        value: 'psicossocial',
        label: 'Psicossocial',
    },
    {
        value: 'outros',
        label: 'Outros',
    },
]
</script>

<template>
    <div>

        <!-- CABEÇALHO -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">
                Deficiências
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Selecione todas as opções que se aplicam.
            </p>
        </div>


        <!-- OPÇÕES -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            <label
                v-for="item in opcoesDeficiencias"
                :key="item.value"
                class="flex cursor-pointer items-center gap-3 rounded-xl border p-4 transition hover:bg-gray-50"
                :class="
                    form.deficiencias?.includes(item.value)
                        ? 'border-indigo-500 bg-indigo-50'
                        : 'border-gray-200'
                "
            >

                <input
                    v-model="form.deficiencias"
                    type="checkbox"
                    :value="item.value"
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                >

                <span class="text-sm font-medium text-gray-700">
                    {{ item.label }}
                </span>

            </label>

        </div>


        <!-- ERRO -->
        <p
            v-if="form.errors.deficiencias"
            class="mt-2 text-sm text-red-600"
        >
            {{ form.errors.deficiencias }}
        </p>


        <!-- OUTROS -->
        <div
            v-if="form.deficiencias?.includes('outros')"
            class="mt-6"
        >

            <label class="block text-sm font-medium text-gray-700">
                Especifique
            </label>

            <textarea
                v-model="form.deficiencia_outros_descricao"
                rows="3"
                maxlength="1000"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Descreva a deficiência."
            ></textarea>

            <p
                v-if="form.errors.deficiencia_outros_descricao"
                class="mt-1 text-sm text-red-600"
            >
                {{ form.errors.deficiencia_outros_descricao }}
            </p>

        </div>


        <!-- NECESSIDADES DE ACESSIBILIDADE -->
        <div class="mt-6">

            <label class="block text-sm font-medium text-gray-700">
                Necessidades de acessibilidade
                <span class="font-normal text-gray-400">
                    (opcional)
                </span>
            </label>

            <textarea
                v-model="form.necessidades_acessibilidade"
                rows="3"
                maxlength="1000"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Informe alguma necessidade específica de acessibilidade."
            ></textarea>

            <p
                v-if="form.errors.necessidades_acessibilidade"
                class="mt-1 text-sm text-red-600"
            >
                {{ form.errors.necessidades_acessibilidade }}
            </p>

        </div>

    </div>
</template>
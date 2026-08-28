<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
})

function adicionarMedicamento(form) {
    form.medicamentos.push({
        nome: '',
        dosagem: '',
        frequencia: '',
        indicacao: '',
    })
}

function removerMedicamento(form, index) {
    form.medicamentos.splice(index, 1)
}
</script>

<template>
    <div>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                Medicamentos de Uso Continuo
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Caso utilize medicamentos, suplementos, vitaminas ou fitoterápicos regularmente, informe-os abaixo.
            </p>
        </div>

        <!-- Nenhum medicamento -->
        <div
            v-if="form.medicamentos.length === 0"
            class="rounded-xl border border-dashed p-8 text-center"
        >
            <p class="text-sm text-gray-500">
                Nenhum medicamento adicionado.
            </p>

            <p class="mt-1 text-xs text-gray-400">
                Caso não utilize medicamentos de uso contínuo, basta avançar.
            </p>
        </div>

        <!-- Medicamentos -->
        <div
            v-for="(medicamento, index) in form.medicamentos"
            :key="index"
            class="mb-4 rounded-xl border p-5"
        >

            <div class="mb-4 flex items-center justify-between">

                <h3 class="font-semibold text-gray-800">
                    Medicamento {{ index + 1 }}
                </h3>

                <button
                    type="button"
                    @click="removerMedicamento(form, index)"
                    class="text-sm font-medium text-red-600 hover:text-red-800"
                >
                    Remover
                </button>

            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Nome
                    </label>

                    <input
                        v-model="medicamento.nome"
                        type="text"
                        placeholder="Ex.: Losartana"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Dosagem
                    </label>

                    <input
                        v-model="medicamento.dosagem"
                        type="text"
                        placeholder="Ex.: 50 mg"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Frequência
                    </label>

                    <input
                        v-model="medicamento.frequencia"
                        type="text"
                        placeholder="Ex.: 1 vez ao dia"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Indicação
                    </label>

                    <input
                        v-model="medicamento.indicacao"
                        type="text"
                        placeholder="Ex.: Hipertensão"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                    >
                </div>

            </div>

        </div>

        <button
            type="button"
            @click="adicionarMedicamento(form)"
            class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700"
        >
            + Adicionar medicamento
        </button>

    </div>
</template>
<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
})

/*
|--------------------------------------------------------------------------
| ADICIONAR MEDICAMENTO
|--------------------------------------------------------------------------
*/

function adicionarMedicamento(form) {
    if (!Array.isArray(form.medicamentos)) {
        form.medicamentos = []
    }

    form.medicamentos.push({
        id: null,
        nome: '',
        dosagem: '',
        frequencia: '',
        indicacao: '',
    })
}


/*
|--------------------------------------------------------------------------
| REMOVER MEDICAMENTO
|--------------------------------------------------------------------------
*/

function removerMedicamento(form, index) {
    form.medicamentos.splice(index, 1)
}
</script>


<template>

    <div>

        <!-- ============================================================= -->
        <!-- CABEÇALHO -->
        <!-- ============================================================= -->

        <div class="mb-8">

            <h2 class="text-2xl font-bold text-gray-800">
                Medicamentos de uso contínuo
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Caso utilize medicamentos, suplementos, vitaminas ou
                fitoterápicos regularmente, informe-os abaixo.
            </p>

        </div>


        <!-- ============================================================= -->
        <!-- NENHUM MEDICAMENTO -->
        <!-- ============================================================= -->

        <div
            v-if="!form.medicamentos || form.medicamentos.length === 0"
            class="mb-6 rounded-xl border border-dashed border-gray-300 p-8 text-center"
        >

            <p class="text-sm text-gray-500">
                Nenhum medicamento adicionado.
            </p>

            <p class="mt-1 text-xs text-gray-400">
                Clique no botão abaixo para adicionar um medicamento.
            </p>

        </div>


        <!-- ============================================================= -->
        <!-- LISTA DE MEDICAMENTOS -->
        <!-- ============================================================= -->

        <div
            v-for="(medicamento, index) in form.medicamentos"
            :key="medicamento.id ?? `novo-${index}`"
            class="mb-5 rounded-xl border border-gray-200 p-5"
        >

            <!-- ========================================================= -->
            <!-- CABEÇALHO DO MEDICAMENTO -->
            <!-- ========================================================= -->

            <div class="mb-5 flex items-center justify-between">

                <h3 class="font-semibold text-gray-800">
                    Medicamento {{ index + 1 }}
                </h3>

                <button
                    type="button"
                    @click="removerMedicamento(form, index)"
                    style="color: #dc2626; background-color: transparent; border: none;"
                    class="text-sm font-medium transition hover:opacity-80"
                >
                    Remover
                </button>

            </div>


            <!-- ========================================================= -->
            <!-- CAMPOS -->
            <!-- ========================================================= -->

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <!-- NOME -->

                <div>

                    <label
                        class="block text-sm font-medium text-gray-700"
                    >
                        Nome
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        v-model="medicamento.nome"
                        type="text"
                        maxlength="255"
                        placeholder="Ex.: Losartana"
                        class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                </div>


                <!-- DOSAGEM -->

                <div>

                    <label
                        class="block text-sm font-medium text-gray-700"
                    >
                        Dosagem
                    </label>

                    <input
                        v-model="medicamento.dosagem"
                        type="text"
                        maxlength="100"
                        placeholder="Ex.: 50 mg"
                        class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                </div>


                <!-- FREQUÊNCIA -->

                <div>

                    <label
                        class="block text-sm font-medium text-gray-700"
                    >
                        Frequência
                    </label>

                    <input
                        v-model="medicamento.frequencia"
                        type="text"
                        maxlength="100"
                        placeholder="Ex.: 1 vez ao dia"
                        class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                </div>


                <!-- INDICAÇÃO -->

                <div>

                    <label
                        class="block text-sm font-medium text-gray-700"
                    >
                        Indicação
                    </label>

                    <input
                        v-model="medicamento.indicacao"
                        type="text"
                        maxlength="255"
                        placeholder="Ex.: Hipertensão"
                        class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                </div>

            </div>

        </div>


        <!-- ============================================================= -->
        <!-- ADICIONAR MEDICAMENTO -->
        <!-- ============================================================= -->

        <div class="mt-6">

            <button
                type="button"
                @click="adicionarMedicamento(form)"
                style="background-color: #4f46e5; color: white; border: 1px solid #4338ca;"
                class="rounded-lg px-5 py-3 text-sm font-semibold shadow-sm transition hover:opacity-90"
            >
                + Adicionar medicamento
            </button>

        </div>


        <!-- ============================================================= -->
        <!-- AVISO -->
        <!-- ============================================================= -->

        <div
            class="mt-8 rounded-xl border border-gray-200 bg-gray-50 p-5"
        >

            <p class="text-sm leading-relaxed text-gray-600">
                Informe também suplementos, vitaminas ou fitoterápicos
                utilizados regularmente, pois essas informações podem ser
                relevantes para a triagem.
            </p>

        </div>

    </div>

</template>
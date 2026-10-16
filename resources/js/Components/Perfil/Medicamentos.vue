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

            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">
                Medicamentos de uso contínuo
            </h2>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Caso utilize medicamentos, suplementos, vitaminas ou
                fitoterápicos regularmente, informe-os abaixo.
            </p>

        </div>


        <!-- ============================================================= -->
        <!-- NENHUM MEDICAMENTO -->
        <!-- ============================================================= -->

        <div
            v-if="!form.medicamentos || form.medicamentos.length === 0"
            class="mb-6 rounded-2xl border border-dashed border-slate-300/80 bg-slate-50/50 p-8 text-center transition dark:border-slate-800 dark:bg-slate-900/40"
        >

            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">
                Nenhum medicamento adicionado.
            </p>

            <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">
                Clique no botão abaixo para adicionar um medicamento.
            </p>

        </div>


        <!-- ============================================================= -->
        <!-- LISTA DE MEDICAMENTOS -->
        <!-- ============================================================= -->

        <div
            v-for="(medicamento, index) in form.medicamentos"
            :key="medicamento.id ?? `novo-${index}`"
            class="mb-5 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition dark:border-slate-800 dark:bg-slate-900"
        >

            <!-- ========================================================= -->
            <!-- CABEÇALHO DO MEDICAMENTO -->
            <!-- ========================================================= -->

            <div class="mb-5 flex items-center justify-between border-b border-slate-100 pb-3 dark:border-slate-800/80">

                <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                    Medicamento {{ index + 1 }}
                </h3>

                <button
                    type="button"
                    @click="removerMedicamento(form, index)"
                    class="inline-flex items-center gap-1 text-xs font-semibold text-rose-600 transition hover:text-rose-700 dark:text-rose-400 dark:hover:text-rose-300"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
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
                        class="block text-sm font-semibold text-slate-700 dark:text-slate-300"
                    >
                        Nome
                        <span class="text-rose-500">*</span>
                    </label>

                    <input
                        v-model="medicamento.nome"
                        type="text"
                        maxlength="255"
                        placeholder="Ex.: Losartana"
                        class="mt-1.5 block w-full rounded-xl border border-slate-200/80 bg-white p-3 text-sm text-slate-800 placeholder-slate-400 shadow-xs transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:placeholder-slate-500 dark:focus:border-emerald-500"
                    >

                </div>


                <!-- DOSAGEM -->

                <div>

                    <label
                        class="block text-sm font-semibold text-slate-700 dark:text-slate-300"
                    >
                        Dosagem
                    </label>

                    <input
                        v-model="medicamento.dosagem"
                        type="text"
                        maxlength="100"
                        placeholder="Ex.: 50 mg"
                        class="mt-1.5 block w-full rounded-xl border border-slate-200/80 bg-white p-3 text-sm text-slate-800 placeholder-slate-400 shadow-xs transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:placeholder-slate-500 dark:focus:border-emerald-500"
                    >

                </div>


                <!-- FREQUÊNCIA -->

                <div>

                    <label
                        class="block text-sm font-semibold text-slate-700 dark:text-slate-300"
                    >
                        Frequência
                    </label>

                    <input
                        v-model="medicamento.frequencia"
                        type="text"
                        maxlength="100"
                        placeholder="Ex.: 1 vez ao dia"
                        class="mt-1.5 block w-full rounded-xl border border-slate-200/80 bg-white p-3 text-sm text-slate-800 placeholder-slate-400 shadow-xs transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:placeholder-slate-500 dark:focus:border-emerald-500"
                    >

                </div>


                <!-- INDICAÇÃO -->

                <div>

                    <label
                        class="block text-sm font-semibold text-slate-700 dark:text-slate-300"
                    >
                        Indicação
                    </label>

                    <input
                        v-model="medicamento.indicacao"
                        type="text"
                        maxlength="255"
                        placeholder="Ex.: Hipertensão"
                        class="mt-1.5 block w-full rounded-xl border border-slate-200/80 bg-white p-3 text-sm text-slate-800 placeholder-slate-400 shadow-xs transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:placeholder-slate-500 dark:focus:border-emerald-500"
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
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-xs transition hover:bg-emerald-700 active:scale-95 dark:bg-emerald-600 dark:hover:bg-emerald-500"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Adicionar medicamento
            </button>

        </div>


        <!-- ============================================================= -->
        <!-- AVISO -->
        <!-- ============================================================= -->

        <div
            class="mt-8 rounded-xl border border-slate-200/80 bg-slate-50/60 p-5 dark:border-slate-800 dark:bg-slate-900/40"
        >

            <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                Informe também suplementos, vitaminas ou fitoterápicos
                utilizados regularmente, pois essas informações podem ser
                relevantes para a triagem.
            </p>

        </div>

    </div>

</template>
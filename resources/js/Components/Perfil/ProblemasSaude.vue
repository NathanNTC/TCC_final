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
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">
                Problemas de saúde prévios
            </h2>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Informe doenças, condições ou problemas de saúde já diagnosticados.
            </p>
        </div>

        <!-- CONDIÇÕES -->
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
                class="flex cursor-pointer items-center gap-3 rounded-xl border p-4 transition-all duration-200 dark:bg-slate-900"
                :class="
                    Array.isArray(form.problemas_saude_previos) &&
                    form.problemas_saude_previos.includes(item[0])
                        ? 'border-emerald-500 bg-emerald-50/50 ring-1 ring-emerald-500/20 dark:border-emerald-500/60 dark:bg-emerald-950/30'
                        : 'border-slate-200/80 bg-white hover:border-slate-300 dark:border-slate-800 dark:hover:border-slate-700'
                "
            >

                <input
                    v-model="form.problemas_saude_previos"
                    type="checkbox"
                    :value="item[0]"
                    class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-800 dark:checked:bg-emerald-500"
                >

                <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                    {{ item[1] }}
                </span>

            </label>

        </div>

        <!-- DETALHES -->
        <div class="mt-6">

            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                Detalhes adicionais
                <span class="font-normal text-slate-400 dark:text-slate-500">
                    (opcional)
                </span>
            </label>

            <textarea
                v-model="form.problemas_saude_detalhes"
                rows="5"
                class="mt-1.5 block w-full rounded-xl border border-slate-200/80 bg-white p-3 text-sm text-slate-800 placeholder-slate-400 shadow-xs transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:placeholder-slate-500 dark:focus:border-emerald-500"
                placeholder="Informe detalhes importantes sobre seus problemas de saúde."
            ></textarea>

            <p
                v-if="form.errors.problemas_saude_detalhes"
                class="mt-1.5 text-xs font-semibold text-rose-600 dark:text-rose-400"
            >
                {{ form.errors.problemas_saude_detalhes }}
            </p>

        </div>

    </div>
</template>
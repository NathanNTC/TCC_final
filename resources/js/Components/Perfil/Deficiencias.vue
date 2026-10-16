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
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">
                Deficiências
            </h2>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Selecione todas as opções que se aplicam.
            </p>
        </div>


        <!-- OPÇÕES -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            <label
                v-for="item in opcoesDeficiencias"
                :key="item.value"
                class="flex cursor-pointer items-center gap-3 rounded-xl border p-4 transition-all duration-200 dark:bg-slate-900"
                :class="
                    form.deficiencias?.includes(item.value)
                        ? 'border-emerald-500 bg-emerald-50/50 ring-1 ring-emerald-500/20 dark:border-emerald-500/60 dark:bg-emerald-950/30'
                        : 'border-slate-200/80 bg-white hover:border-slate-300 dark:border-slate-800 dark:hover:border-slate-700'
                "
            >

                <input
                    v-model="form.deficiencias"
                    type="checkbox"
                    :value="item.value"
                    class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-800 dark:checked:bg-emerald-500"
                >

                <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                    {{ item.label }}
                </span>

            </label>

        </div>


        <!-- ERRO -->
        <p
            v-if="form.errors.deficiencias"
            class="mt-2 text-xs font-semibold text-rose-600 dark:text-rose-400"
        >
            {{ form.errors.deficiencias }}
        </p>


        <!-- OUTROS -->
        <div
            v-if="form.deficiencias?.includes('outros')"
            class="mt-6"
        >

            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                Especifique
            </label>

            <textarea
                v-model="form.deficiencia_outros_descricao"
                rows="3"
                maxlength="1000"
                class="mt-1.5 block w-full rounded-xl border border-slate-200/80 bg-white p-3 text-sm text-slate-800 placeholder-slate-400 shadow-xs transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:placeholder-slate-500 dark:focus:border-emerald-500"
                placeholder="Descreva a deficiência."
            ></textarea>

            <p
                v-if="form.errors.deficiencia_outros_descricao"
                class="mt-1.5 text-xs font-semibold text-rose-600 dark:text-rose-400"
            >
                {{ form.errors.deficiencia_outros_descricao }}
            </p>

        </div>


        <!-- NECESSIDADES DE ACESSIBILIDADE -->
        <div class="mt-6">

            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                Necessidades de acessibilidade
                <span class="font-normal text-slate-400 dark:text-slate-500">
                    (opcional)
                </span>
            </label>

            <textarea
                v-model="form.necessidades_acessibilidade"
                rows="3"
                maxlength="1000"
                class="mt-1.5 block w-full rounded-xl border border-slate-200/80 bg-white p-3 text-sm text-slate-800 placeholder-slate-400 shadow-xs transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:placeholder-slate-500 dark:focus:border-emerald-500"
                placeholder="Informe alguma necessidade específica de acessibilidade."
            ></textarea>

            <p
                v-if="form.errors.necessidades_acessibilidade"
                class="mt-1.5 text-xs font-semibold text-rose-600 dark:text-rose-400"
            >
                {{ form.errors.necessidades_acessibilidade }}
            </p>

        </div>

    </div>
</template>
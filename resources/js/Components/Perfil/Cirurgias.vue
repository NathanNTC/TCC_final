<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
})

function adicionarCirurgia(form) {
    form.cirurgias.push({
        id: null,
        tipo: '',
        ano: '',
        anestesia: '',
        complicacoes: [],
        implantes: [],
        observacoes: '',
    })
}

function removerCirurgia(form, index) {
    form.cirurgias.splice(index, 1)
}

function selecionarNenhumaComplicacao(cirurgia, event) {
    if (event.target.checked) {
        cirurgia.complicacoes = ['nenhuma']
    } else {
        cirurgia.complicacoes = []
    }
}

function selecionarNenhumImplante(cirurgia, event) {
    if (event.target.checked) {
        cirurgia.implantes = ['nenhum']
    } else {
        cirurgia.implantes = []
    }
}
</script>

<template>
    <div>
        <!-- CABEÇALHO DA SEÇÃO -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">
                Cirurgias anteriores
            </h2>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Informe cirurgias que já realizou e qualquer complicação
                importante durante ou após o procedimento.
            </p>
        </div>

        <!-- NENHUMA CIRURGIA REGISTRADA -->
        <div
            v-if="form.cirurgias.length === 0"
            class="mb-6 rounded-2xl border border-dashed border-slate-200/80 bg-slate-50/50 p-8 text-center dark:border-slate-800 dark:bg-slate-900/40"
        >
            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                Nenhuma cirurgia informada.
            </p>

            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                Se já realizou alguma cirurgia, clique no botão "+ Adicionar cirurgia" abaixo.
            </p>
        </div>

        <!-- LISTA DE CIRURGIAS -->
        <div
            v-for="(cirurgia, index) in form.cirurgias"
            :key="cirurgia.id ?? index"
            class="mb-6 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs transition-all dark:border-slate-800 dark:bg-slate-900"
        >
            <!-- TÍTULO DO CARD -->
            <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">
                    Cirurgia {{ index + 1 }}
                </h3>

                <button
                    type="button"
                    @click="removerCirurgia(form, index)"
                    class="cursor-pointer text-xs font-semibold text-rose-600 transition hover:text-rose-700 active:scale-95 dark:text-rose-400 dark:hover:text-rose-300"
                >
                    Remover
                </button>
            </div>

            <!-- TIPO E ANO -->
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <!-- Tipo da Cirurgia -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Nome ou tipo da cirurgia
                        <span class="text-rose-500">*</span>
                    </label>

                    <input
                        v-model="cirurgia.tipo"
                        type="text"
                        placeholder="Ex.: Retirada da vesícula, cesárea, apendicectomia..."
                        class="mt-1.5 block w-full rounded-xl border border-slate-200/80 bg-white p-3 text-sm text-slate-800 placeholder-slate-400 shadow-xs transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:placeholder-slate-500 dark:focus:border-emerald-500"
                    >

                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Informe o nome ou tipo do procedimento realizado.
                    </p>
                </div>

                <!-- Ano da Cirurgia -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Ano da cirurgia
                        <span class="text-rose-500">*</span>
                    </label>

                    <input
                        v-model="cirurgia.ano"
                        type="number"
                        min="1900"
                        :max="new Date().getFullYear()"
                        placeholder="Ex.: 2022"
                        class="mt-1.5 block w-full rounded-xl border border-slate-200/80 bg-white p-3 font-mono text-sm text-slate-800 placeholder-slate-400 shadow-xs transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:placeholder-slate-500 dark:focus:border-emerald-500"
                    >

                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Informe o ano em que a cirurgia foi realizada.
                    </p>
                </div>
            </div>

            <!-- TIPO DE ANESTESIA -->
            <div class="mt-6">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Tipo de anestesia
                </label>

                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                    Se lembrar, informe o tipo utilizado.
                </p>

                <div class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-2">
                    <label
                        v-for="item in [
                            ['geral', 'Geral', 'Dormiu completamente / precisou de intubação'],
                            ['raqui_peridural', 'Nas costas', 'Raqui ou peridural'],
                            ['local_sedacao', 'Local ou sedação leve', ''],
                            ['nao_lembra', 'Não lembro', ''],
                        ]"
                        :key="item[0]"
                        class="flex cursor-pointer items-start gap-3 rounded-xl border p-4 transition-all duration-200 dark:bg-slate-900"
                        :class="
                            cirurgia.anestesia === item[0]
                                ? 'border-emerald-500 bg-emerald-50/50 ring-1 ring-emerald-500/20 dark:border-emerald-500/60 dark:bg-emerald-950/30'
                                : 'border-slate-200/80 bg-white hover:border-slate-300 dark:border-slate-800 dark:hover:border-slate-700'
                        "
                    >
                        <input
                            v-model="cirurgia.anestesia"
                            type="radio"
                            :value="item[0]"
                            class="mt-1 h-4 w-4 border-slate-300 text-emerald-600 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-800 dark:checked:bg-emerald-500"
                        >

                        <div>
                            <span class="block text-sm font-semibold text-slate-800 dark:text-slate-200">
                                {{ item[1] }}
                            </span>

                            <span
                                v-if="item[2]"
                                class="mt-0.5 block text-xs text-slate-500 dark:text-slate-400"
                            >
                                {{ item[2] }}
                            </span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- COMPLICAÇÕES -->
            <div class="mt-6">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Houve alguma complicação?
                </label>

                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                    Marque todas as opções que já aconteceram.
                </p>

                <div class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-2">
                    <label
                        v-for="item in [
                            ['uti', 'Precisou ir para a UTI'],
                            ['infeccao', 'Infecção no local da cirurgia'],
                            ['sangramento_transfusao', 'Sangramento forte ou transfusão de sangue'],
                            ['demora_acordar', 'Mal-estar grave ou demora para acordar da anestesia'],
                        ]"
                        :key="item[0]"
                        class="flex cursor-pointer items-center gap-3 rounded-xl border p-4 transition-all duration-200 dark:bg-slate-900"
                        :class="
                            cirurgia.complicacoes.includes(item[0])
                                ? 'border-emerald-500 bg-emerald-50/50 ring-1 ring-emerald-500/20 dark:border-emerald-500/60 dark:bg-emerald-950/30'
                                : 'border-slate-200/80 bg-white hover:border-slate-300 dark:border-slate-800 dark:hover:border-slate-700',
                            cirurgia.complicacoes.includes('nenhuma') ? 'opacity-50 cursor-not-allowed' : ''
                        "
                    >
                        <input
                            v-model="cirurgia.complicacoes"
                            type="checkbox"
                            :value="item[0]"
                            :disabled="cirurgia.complicacoes.includes('nenhuma')"
                            class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-800 dark:checked:bg-emerald-500"
                        >

                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                            {{ item[1] }}
                        </span>
                    </label>
                </div>

                <!-- NENHUMA COMPLICAÇÃO -->
                <label
                    class="mt-3 flex cursor-pointer items-center gap-3 rounded-xl border p-4 transition-all duration-200 dark:bg-slate-900"
                    :class="
                        cirurgia.complicacoes.includes('nenhuma')
                            ? 'border-emerald-500 bg-emerald-50/50 ring-1 ring-emerald-500/20 dark:border-emerald-500/60 dark:bg-emerald-950/30'
                            : 'border-slate-200/80 bg-white hover:border-slate-300 dark:border-slate-800 dark:hover:border-slate-700'
                    "
                >
                    <input
                        type="checkbox"
                        :checked="cirurgia.complicacoes.includes('nenhuma')"
                        class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-800 dark:checked:bg-emerald-500"
                        @change="selecionarNenhumaComplicacao(cirurgia, $event)"
                    >

                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                        Nenhuma complicação
                    </span>
                </label>
            </div>

            <!-- IMPLANTES -->
            <div class="mt-6">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Possui algum implante ou material no corpo?
                </label>

                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                    Marque todas as opções que se aplicam.
                </p>

                <div class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-2">
                    <label
                        v-for="item in [
                            ['placas_parafusos_pinos', 'Placas, parafusos ou pinos de metal'],
                            ['protese', 'Prótese (mamária, joelho, quadril etc.)'],
                            ['marcapasso', 'Marcapasso ou desfibrilador'],
                        ]"
                        :key="item[0]"
                        class="flex cursor-pointer items-center gap-3 rounded-xl border p-4 transition-all duration-200 dark:bg-slate-900"
                        :class="
                            cirurgia.implantes.includes(item[0])
                                ? 'border-emerald-500 bg-emerald-50/50 ring-1 ring-emerald-500/20 dark:border-emerald-500/60 dark:bg-emerald-950/30'
                                : 'border-slate-200/80 bg-white hover:border-slate-300 dark:border-slate-800 dark:hover:border-slate-700',
                            cirurgia.implantes.includes('nenhum') ? 'opacity-50 cursor-not-allowed' : ''
                        "
                    >
                        <input
                            v-model="cirurgia.implantes"
                            type="checkbox"
                            :value="item[0]"
                            :disabled="cirurgia.implantes.includes('nenhum')"
                            class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-800 dark:checked:bg-emerald-500"
                        >

                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                            {{ item[1] }}
                        </span>
                    </label>
                </div>

                <!-- NENHUM IMPLANTE -->
                <label
                    class="mt-3 flex cursor-pointer items-center gap-3 rounded-xl border p-4 transition-all duration-200 dark:bg-slate-900"
                    :class="
                        cirurgia.implantes.includes('nenhum')
                            ? 'border-emerald-500 bg-emerald-50/50 ring-1 ring-emerald-500/20 dark:border-emerald-500/60 dark:bg-emerald-950/30'
                            : 'border-slate-200/80 bg-white hover:border-slate-300 dark:border-slate-800 dark:hover:border-slate-700'
                    "
                >
                    <input
                        type="checkbox"
                        :checked="cirurgia.implantes.includes('nenhum')"
                        class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-800 dark:checked:bg-emerald-500"
                        @change="selecionarNenhumImplante(cirurgia, $event)"
                    >

                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                        Não tenho implantes ou materiais no corpo
                    </span>
                </label>
            </div>

            <!-- OBSERVAÇÕES -->
            <div class="mt-6">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Outras informações
                    <span class="font-normal text-slate-400 dark:text-slate-500">
                        (opcional)
                    </span>
                </label>

                <textarea
                    v-model="cirurgia.observacoes"
                    rows="3"
                    class="mt-1.5 block w-full rounded-xl border border-slate-200/80 bg-white p-3 text-sm text-slate-800 placeholder-slate-400 shadow-xs transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:placeholder-slate-500 dark:focus:border-emerald-500"
                    placeholder="Informe qualquer outra informação importante sobre a cirurgia."
                ></textarea>
            </div>
        </div>

        <!-- ADICIONAR CIRURGIA -->
        <button
            type="button"
            @click="adicionarCirurgia(form)"
            class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-xs transition-all duration-200 hover:border-slate-300 hover:bg-slate-50 hover:shadow-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 active:scale-95 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-slate-700 dark:hover:bg-slate-800"
        >
            + Adicionar cirurgia
        </button>
    </div>
</template>
<script setup>
defineProps({
    form: {
        type: Object,
        required: true,
    },
})

function adicionarCirurgia(form) {
    form.cirurgias.push({
        procedimento: '',
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
</script>


<template>

    <div>

        <!-- ============================================================= -->
        <!-- CABEÇALHO -->
        <!-- ============================================================= -->

        <div class="mb-8">

            <h2 class="text-2xl font-bold text-gray-800">
                Cirurgias anteriores
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Informe cirurgias que já realizou e qualquer complicação
                importante durante ou após o procedimento.
            </p>

        </div>


        <!-- ============================================================= -->
        <!-- NENHUMA CIRURGIA -->
        <!-- ============================================================= -->

        <div
            v-if="form.cirurgias.length === 0"
            class="mb-6 rounded-xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center"
        >

            <p class="text-sm text-gray-600">
                Nenhuma cirurgia informada.
            </p>

            <p class="mt-1 text-xs text-gray-500">
                Se já realizou alguma cirurgia, clique em
                "Adicionar cirurgia".
            </p>

        </div>


        <!-- ============================================================= -->
        <!-- CIRURGIAS -->
        <!-- ============================================================= -->

        <div
            v-for="(cirurgia, index) in form.cirurgias"
            :key="index"
            class="mb-6 rounded-xl border border-gray-200 p-5"
        >

            <!-- TÍTULO -->

            <div class="mb-6 flex items-center justify-between">

                <h3 class="text-base font-semibold text-gray-800">
                    Cirurgia {{ index + 1 }}
                </h3>

                <button
                    type="button"
                    @click="removerCirurgia(form, index)"
                    class="text-sm font-medium text-red-600 transition hover:text-red-800"
                >
                    Remover
                </button>

            </div>


            <!-- ========================================================= -->
            <!-- PROCEDIMENTO / ANO -->
            <!-- ========================================================= -->

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <!-- Procedimento -->

                <div>

                    <label class="block text-sm font-medium text-gray-700">
                        Nome ou tipo da cirurgia
                    </label>

                    <input
                        v-model="cirurgia.procedimento"
                        type="text"
                        placeholder="Ex.: Retirada da vesícula, cesárea, apêndice..."
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                </div>


                <!-- Ano -->

                <div>

                    <label class="block text-sm font-medium text-gray-700">
                        Ano aproximado
                    </label>

                    <input
                        v-model="cirurgia.ano"
                        type="text"
                        maxlength="4"
                        placeholder="Ex.: 2022"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                </div>

            </div>


            <!-- ========================================================= -->
            <!-- ANESTESIA -->
            <!-- ========================================================= -->

            <div class="mt-6">

                <label class="block text-sm font-medium text-gray-700">
                    Tipo de anestesia
                </label>

                <p class="mt-1 text-xs text-gray-500">
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
                        class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 p-4 transition hover:bg-gray-50"
                        :class="
                            cirurgia.anestesia === item[0]
                                ? 'border-indigo-500 bg-indigo-50'
                                : ''
                        "
                    >

                        <input
                            v-model="cirurgia.anestesia"
                            type="radio"
                            :value="item[0]"
                            class="mt-1 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                        >

                        <div>

                            <span class="block text-sm font-medium text-gray-800">
                                {{ item[1] }}
                            </span>

                            <span
                                v-if="item[2]"
                                class="mt-1 block text-xs text-gray-500"
                            >
                                {{ item[2] }}
                            </span>

                        </div>

                    </label>

                </div>

            </div>


            <!-- ========================================================= -->
            <!-- COMPLICAÇÕES -->
            <!-- ========================================================= -->

            <div class="mt-6">

                <label class="block text-sm font-medium text-gray-700">
                    Houve alguma complicação?
                </label>

                <p class="mt-1 text-xs text-gray-500">
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
                        class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 p-4 transition hover:bg-gray-50"
                        :class="
                            cirurgia.complicacoes.includes(item[0])
                                ? 'border-indigo-500 bg-indigo-50'
                                : ''
                        "
                    >

                        <input
                            v-model="cirurgia.complicacoes"
                            type="checkbox"
                            :value="item[0]"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                        >

                        <span class="text-sm text-gray-700">
                            {{ item[1] }}
                        </span>

                    </label>

                </div>


                <!-- NENHUMA COMPLICAÇÃO -->

                <label
                    class="mt-3 flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 p-4 transition hover:bg-gray-50"
                    :class="
                        cirurgia.complicacoes.includes('nenhuma')
                            ? 'border-green-500 bg-green-50'
                            : ''
                    "
                >

                    <input
                        type="checkbox"
                        :checked="cirurgia.complicacoes.includes('nenhuma')"
                        class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                        @change="
                            $event.target.checked
                                ? (cirurgia.complicacoes = ['nenhuma'])
                                : (cirurgia.complicacoes = [])
                        "
                    >

                    <span class="text-sm font-medium text-gray-700">
                        Nenhuma complicação
                    </span>

                </label>

            </div>


            <!-- ========================================================= -->
            <!-- IMPLANTES -->
            <!-- ========================================================= -->

            <div class="mt-6">

                <label class="block text-sm font-medium text-gray-700">
                    Possui algum implante ou material no corpo?
                </label>

                <p class="mt-1 text-xs text-gray-500">
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
                        class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 p-4 transition hover:bg-gray-50"
                        :class="
                            cirurgia.implantes.includes(item[0])
                                ? 'border-indigo-500 bg-indigo-50'
                                : ''
                        "
                    >

                        <input
                            v-model="cirurgia.implantes"
                            type="checkbox"
                            :value="item[0]"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                        >

                        <span class="text-sm text-gray-700">
                            {{ item[1] }}
                        </span>

                    </label>

                </div>


                <!-- NÃO POSSUI -->

                <label
                    class="mt-3 flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 p-4 transition hover:bg-gray-50"
                    :class="
                        cirurgia.implantes.includes('nenhum')
                            ? 'border-green-500 bg-green-50'
                            : ''
                    "
                >

                    <input
                        type="checkbox"
                        :checked="cirurgia.implantes.includes('nenhum')"
                        class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                        @change="
                            $event.target.checked
                                ? (cirurgia.implantes = ['nenhum'])
                                : (cirurgia.implantes = [])
                        "
                    >

                    <span class="text-sm font-medium text-gray-700">
                        Não tenho implantes ou materiais no corpo
                    </span>

                </label>

            </div>


            <!-- ========================================================= -->
            <!-- OBSERVAÇÕES -->
            <!-- ========================================================= -->

            <div class="mt-6">

                <label class="block text-sm font-medium text-gray-700">

                    Outras informações

                    <span class="font-normal text-gray-400">
                        (opcional)
                    </span>

                </label>

                <textarea
                    v-model="cirurgia.observacoes"
                    rows="3"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Informe qualquer outra informação importante sobre a cirurgia."
                ></textarea>

            </div>

        </div>


        <!-- ============================================================= -->
        <!-- ADICIONAR CIRURGIA -->
        <!-- ============================================================= -->

        <button
            type="button"
            @click="adicionarCirurgia(form)"
            class="rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50"
        >
            + Adicionar cirurgia
        </button>

    </div>

</template>
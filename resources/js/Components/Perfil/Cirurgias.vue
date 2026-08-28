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
        data: '',
        motivo: '',
        complicacoes: [],
        anestesia: '',
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

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                Cirurgias prévias
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Informe cirurgias importantes ou recentes.
            </p>
        </div>

        <div
            v-if="form.cirurgias.length === 0"
            class="mb-6 rounded-xl border border-dashed p-8 text-center"
        >
            <p class="text-sm text-gray-500">
                Nenhuma cirurgia adicionada.
            </p>
        </div>

        <div
            v-for="(cirurgia, index) in form.cirurgias"
            :key="index"
            class="mb-6 rounded-xl border p-5"
        >

            <div class="mb-5 flex items-center justify-between">

                <h3 class="font-semibold text-gray-800">
                    Cirurgia {{ index + 1 }}
                </h3>

                <button
                    type="button"
                    @click="removerCirurgia(form, index)"
                    class="text-sm text-red-600 hover:text-red-800"
                >
                    Remover
                </button>

            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <!-- Procedimento -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Nome do procedimento
                    </label>

                    <input
                        v-model="cirurgia.procedimento"
                        type="text"
                        placeholder="Ex.: Apendicectomia"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                    >
                </div>

                <!-- Data -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Data ou ano aproximado
                    </label>

                    <input
                        v-model="cirurgia.data"
                        type="text"
                        placeholder="Ex.: 2022"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                    >
                </div>

                <!-- Motivo -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Motivo da cirurgia
                    </label>

                    <input
                        v-model="cirurgia.motivo"
                        type="text"
                        placeholder="Ex.: Trauma, urgência, cirurgia eletiva..."
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                    >
                </div>

            </div>

            <!-- Anestesia -->
            <div class="mt-5">

                <label class="block text-sm font-medium text-gray-700">
                    Tipo de anestesia
                </label>

                <select
                    v-model="cirurgia.anestesia"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                >
                    <option value="">Não informado</option>
                    <option value="geral">Geral</option>
                    <option value="regional">Regional</option>
                    <option value="sedacao">Sedação</option>
                    <option value="local">Local</option>
                    <option value="nao_sabe">Não sabe / não lembra</option>
                </select>

            </div>

            <!-- Complicações -->
            <div class="mt-5">

                <label class="block text-sm font-medium text-gray-700">
                    Complicações
                </label>

                <div class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-2">

                    <label
                        v-for="item in [
                            ['uti', 'Necessidade de UTI'],
                            ['infeccao', 'Infecção da ferida'],
                            ['sangramento', 'Sangramento excessivo/transfusão'],
                            ['anestesia', 'Reação à anestesia'],
                            ['respiratorio', 'Problemas respiratórios'],
                            ['cardiaco', 'Problemas cardíacos'],
                            ['outros', 'Outros'],
                        ]"
                        :key="item[0]"
                        class="flex items-center gap-3"
                    >
                        <input
                            v-model="cirurgia.complicacoes"
                            type="checkbox"
                            :value="item[0]"
                            class="rounded border-gray-300"
                        >

                        <span class="text-sm text-gray-700">
                            {{ item[1] }}
                        </span>
                    </label>

                </div>

            </div>

            <!-- Implantes -->
            <div class="mt-5">

                <label class="block text-sm font-medium text-gray-700">
                    Possui algum implante ou material de síntese?
                </label>

                <div class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-2">

                    <label
                        v-for="item in [
                            ['placas_parafusos', 'Placas, parafusos ou hastes'],
                            ['protese', 'Próteses'],
                            ['marcapasso', 'Marcapasso/desfibrilador'],
                        ]"
                        :key="item[0]"
                        class="flex items-center gap-3"
                    >
                        <input
                            v-model="cirurgia.implantes"
                            type="checkbox"
                            :value="item[0]"
                            class="rounded border-gray-300"
                        >

                        <span class="text-sm text-gray-700">
                            {{ item[1] }}
                        </span>
                    </label>

                </div>

            </div>

            <!-- Observações -->
            <div class="mt-5">

                <label class="block text-sm font-medium text-gray-700">
                    Observações
                </label>

                <textarea
                    v-model="cirurgia.observacoes"
                    rows="3"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm"
                    placeholder="Outras informações importantes."
                ></textarea>

            </div>

        </div>

        <button
            type="button"
            @click="adicionarCirurgia(form)"
            class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700"
        >
            + Adicionar cirurgia
        </button>

    </div>
</template>
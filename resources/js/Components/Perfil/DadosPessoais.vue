<script setup>
import { watch, ref, onMounted } from 'vue'

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
})

const cidades = ref([])
const carregandoCidades = ref(false)

const estados = [
    { sigla: 'AC', nome: 'Acre' },
    { sigla: 'AL', nome: 'Alagoas' },
    { sigla: 'AP', nome: 'Amapá' },
    { sigla: 'AM', nome: 'Amazonas' },
    { sigla: 'BA', nome: 'Bahia' },
    { sigla: 'CE', nome: 'Ceará' },
    { sigla: 'DF', nome: 'Distrito Federal' },
    { sigla: 'ES', nome: 'Espírito Santo' },
    { sigla: 'GO', nome: 'Goiás' },
    { sigla: 'MA', nome: 'Maranhão' },
    { sigla: 'MT', nome: 'Mato Grosso' },
    { sigla: 'MS', nome: 'Mato Grosso do Sul' },
    { sigla: 'MG', nome: 'Minas Gerais' },
    { sigla: 'PA', nome: 'Pará' },
    { sigla: 'PB', nome: 'Paraíba' },
    { sigla: 'PR', nome: 'Paraná' },
    { sigla: 'PE', nome: 'Pernambuco' },
    { sigla: 'PI', nome: 'Piauí' },
    { sigla: 'RJ', nome: 'Rio de Janeiro' },
    { sigla: 'RN', nome: 'Rio Grande do Norte' },
    { sigla: 'RS', nome: 'Rio Grande do Sul' },
    { sigla: 'RO', nome: 'Rondônia' },
    { sigla: 'RR', nome: 'Roraima' },
    { sigla: 'SC', nome: 'Santa Catarina' },
    { sigla: 'SP', nome: 'São Paulo' },
    { sigla: 'SE', nome: 'Sergipe' },
    { sigla: 'TO', nome: 'Tocantins' },
]

/*
|--------------------------------------------------------------------------
| CPF
|--------------------------------------------------------------------------
*/

function formatarCpf(event) {
    let valor = event.target.value

    valor = valor.replace(/\D/g, '')
    valor = valor.substring(0, 11)

    if (valor.length > 9) {
        valor = valor.replace(
            /^(\d{3})(\d{3})(\d{3})(\d{2})$/,
            '$1.$2.$3-$4'
        )
    } else if (valor.length > 6) {
        valor = valor.replace(
            /^(\d{3})(\d{3})(\d{1,3})$/,
            '$1.$2.$3'
        )
    } else if (valor.length > 3) {
        valor = valor.replace(
            /^(\d{3})(\d{1,3})$/,
            '$1.$2'
        )
    }

    props.form.cpf = valor
}

/*
|--------------------------------------------------------------------------
| TELEFONE
|--------------------------------------------------------------------------
*/

function formatarTelefone(event) {
    let valor = event.target.value

    valor = valor.replace(/\D/g, '')
    valor = valor.substring(0, 11)

    if (valor.length > 10) {
        valor = valor.replace(
            /^(\d{2})(\d{5})(\d{4})$/,
            '($1) $2-$3'
        )
    } else if (valor.length > 6) {
        valor = valor.replace(
            /^(\d{2})(\d{4})(\d{0,4})$/,
            '($1) $2-$3'
        )
    } else if (valor.length > 2) {
        valor = valor.replace(
            /^(\d{2})(\d{0,5})$/,
            '($1) $2'
        )
    }

    props.form.telefone = valor
}

/*
|--------------------------------------------------------------------------
| BUSCAR CIDADES
|--------------------------------------------------------------------------
*/

async function buscarCidades(estado, limparCidade = true) {
    if (!estado) {
        cidades.value = []

        if (limparCidade) {
            props.form.cidade = ''
        }

        return
    }

    carregandoCidades.value = true
    cidades.value = []

    const cidadeAtual = props.form.cidade

    if (limparCidade) {
        props.form.cidade = ''
    }

    try {
        const resposta = await fetch(
            `https://servicodados.ibge.gov.br/api/v1/localidades/estados/${estado}/municipios`
        )

        if (!resposta.ok) {
            throw new Error('Erro ao buscar cidades')
        }

        const dados = await resposta.json()

        cidades.value = dados.sort((a, b) =>
            a.nome.localeCompare(b.nome)
        )

        /*
         * Na edição, mantém a cidade já cadastrada.
         */
        if (!limparCidade && cidadeAtual) {
            const cidadeExiste = cidades.value.some(
                cidade => cidade.nome === cidadeAtual
            )

            if (cidadeExiste) {
                props.form.cidade = cidadeAtual
            }
        }

    } catch (erro) {
        console.error('Erro ao buscar cidades:', erro)

    } finally {
        carregandoCidades.value = false
    }
}

/*
|--------------------------------------------------------------------------
| MUDANÇA DE ESTADO
|--------------------------------------------------------------------------
*/

watch(
    () => props.form.estado,
    (novoEstado, estadoAnterior) => {
        if (novoEstado === estadoAnterior) {
            return
        }

        buscarCidades(novoEstado, true)
    }
)

/*
|--------------------------------------------------------------------------
| CARREGAMENTO INICIAL
|--------------------------------------------------------------------------
|
| Importante para a tela de edição.
| Se o paciente já possui estado/cidade, carregamos as cidades
| sem apagar a cidade cadastrada.
|--------------------------------------------------------------------------
*/

onMounted(() => {
    if (props.form.estado) {
        buscarCidades(props.form.estado, false)
    }
})
</script>

<template>
    <div>
        <!-- CABEÇALHO -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">
                Dados pessoais
            </h2>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Informe seus dados básicos para completar seu perfil.
            </p>
        </div>

        <!-- CAMPOS -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <!-- CPF -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                    CPF
                    <span class="text-rose-500">*</span>
                </label>

                <input
                    :value="form.cpf"
                    @input="formatarCpf"
                    type="text"
                    inputmode="numeric"
                    maxlength="14"
                    placeholder="000.000.000-00"
                    class="mt-1.5 block w-full rounded-xl border border-slate-200/80 bg-white p-3 font-mono text-sm text-slate-800 placeholder-slate-400 shadow-xs transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:placeholder-slate-500 dark:focus:border-emerald-500"
                >

                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                    Digite apenas os números do CPF.
                </p>

                <p
                    v-if="form.errors.cpf"
                    class="mt-1.5 text-xs font-semibold text-rose-600 dark:text-rose-400"
                >
                    {{ form.errors.cpf }}
                </p>
            </div>

            <!-- SEXO -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Sexo
                    <span class="text-rose-500">*</span>
                </label>

                <select
                    v-model="form.sexo"
                    class="mt-1.5 block w-full rounded-xl border border-slate-200/80 bg-white p-3 text-sm text-slate-800 shadow-xs transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-emerald-500"
                >
                    <option value="">
                        Selecione
                    </option>

                    <option value="M">
                        Masculino
                    </option>

                    <option value="F">
                        Feminino
                    </option>
                </select>

                <p
                    v-if="form.errors.sexo"
                    class="mt-1.5 text-xs font-semibold text-rose-600 dark:text-rose-400"
                >
                    {{ form.errors.sexo }}
                </p>
            </div>

            <!-- DATA DE NASCIMENTO -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Data de nascimento
                    <span class="text-rose-500">*</span>
                </label>

                <input
                    v-model="form.data_nascimento"
                    type="date"
                    class="mt-1.5 block w-full rounded-xl border border-slate-200/80 bg-white p-3 text-sm text-slate-800 shadow-xs transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-emerald-500"
                >

                <p
                    v-if="form.errors.data_nascimento"
                    class="mt-1.5 text-xs font-semibold text-rose-600 dark:text-rose-400"
                >
                    {{ form.errors.data_nascimento }}
                </p>
            </div>

            <!-- TELEFONE -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Telefone
                    <span class="text-rose-500">*</span>
                </label>

                <input
                    :value="form.telefone"
                    @input="formatarTelefone"
                    type="text"
                    inputmode="numeric"
                    maxlength="15"
                    placeholder="(51) 99999-9999"
                    class="mt-1.5 block w-full rounded-xl border border-slate-200/80 bg-white p-3 font-mono text-sm text-slate-800 placeholder-slate-400 shadow-xs transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:placeholder-slate-500 dark:focus:border-emerald-500"
                >

                <p
                    v-if="form.errors.telefone"
                    class="mt-1.5 text-xs font-semibold text-rose-600 dark:text-rose-400"
                >
                    {{ form.errors.telefone }}
                </p>
            </div>

            <!-- ESTADO -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Estado
                    <span class="text-rose-500">*</span>
                </label>

                <select
                    v-model="form.estado"
                    class="mt-1.5 block w-full rounded-xl border border-slate-200/80 bg-white p-3 text-sm text-slate-800 shadow-xs transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-emerald-500"
                >
                    <option value="">
                        Selecione o estado
                    </option>

                    <option
                        v-for="estado in estados"
                        :key="estado.sigla"
                        :value="estado.sigla"
                    >
                        {{ estado.nome }}
                    </option>
                </select>

                <p
                    v-if="form.errors.estado"
                    class="mt-1.5 text-xs font-semibold text-rose-600 dark:text-rose-400"
                >
                    {{ form.errors.estado }}
                </p>
            </div>

            <!-- CIDADE -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Cidade
                    <span class="text-rose-500">*</span>
                </label>

                <select
                    v-model="form.cidade"
                    :disabled="!form.estado || carregandoCidades"
                    class="mt-1.5 block w-full rounded-xl border border-slate-200/80 bg-white p-3 text-sm text-slate-800 shadow-xs transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-emerald-500 dark:disabled:bg-slate-800/50 dark:disabled:text-slate-500"
                >
                    <option value="">
                        {{
                            !form.estado
                                ? 'Selecione primeiro o estado'
                                : carregandoCidades
                                    ? 'Carregando cidades...'
                                    : 'Selecione a cidade'
                        }}
                    </option>

                    <option
                        v-for="cidade in cidades"
                        :key="cidade.id"
                        :value="cidade.nome"
                    >
                        {{ cidade.nome }}
                    </option>
                </select>

                <p
                    v-if="form.errors.cidade"
                    class="mt-1.5 text-xs font-semibold text-rose-600 dark:text-rose-400"
                >
                    {{ form.errors.cidade }}
                </p>
            </div>

            <!-- PROFISSÃO -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Profissão
                </label>

                <input
                    v-model="form.profissao"
                    type="text"
                    maxlength="255"
                    placeholder="Ex.: Engenheiro de Software"
                    class="mt-1.5 block w-full rounded-xl border border-slate-200/80 bg-white p-3 text-sm text-slate-800 placeholder-slate-400 shadow-xs transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:placeholder-slate-500 dark:focus:border-emerald-500"
                >

                <p
                    v-if="form.errors.profissao"
                    class="mt-1.5 text-xs font-semibold text-rose-600 dark:text-rose-400"
                >
                    {{ form.errors.profissao }}
                </p>
            </div>
        </div>
    </div>
</template>
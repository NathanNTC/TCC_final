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
            <h2 class="text-2xl font-bold text-gray-800">
                Dados pessoais
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Informe seus dados básicos para completar seu perfil.
            </p>
        </div>


        <!-- CAMPOS -->

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            <!-- CPF -->

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    CPF
                    <span class="text-red-500">*</span>
                </label>

                <input
                    :value="form.cpf"
                    @input="formatarCpf"
                    type="text"
                    inputmode="numeric"
                    maxlength="14"
                    placeholder="000.000.000-00"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

                <p class="mt-1 text-xs text-gray-500">
                    Digite apenas os números do CPF.
                </p>

                <p
                    v-if="form.errors.cpf"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ form.errors.cpf }}
                </p>
            </div>


            <!-- SEXO -->

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Sexo
                    <span class="text-red-500">*</span>
                </label>

                <select
                    v-model="form.sexo"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                    class="mt-1 text-sm text-red-600"
                >
                    {{ form.errors.sexo }}
                </p>
            </div>


            <!-- DATA DE NASCIMENTO -->

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Data de nascimento
                    <span class="text-red-500">*</span>
                </label>

                <input
                    v-model="form.data_nascimento"
                    type="date"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

                <p
                    v-if="form.errors.data_nascimento"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ form.errors.data_nascimento }}
                </p>
            </div>


            <!-- TELEFONE -->

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Telefone
                    <span class="text-red-500">*</span>
                </label>

                <input
                    :value="form.telefone"
                    @input="formatarTelefone"
                    type="text"
                    inputmode="numeric"
                    maxlength="15"
                    placeholder="(51) 99999-9999"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

                <p
                    v-if="form.errors.telefone"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ form.errors.telefone }}
                </p>
            </div>


            <!-- ESTADO -->

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Estado
                    <span class="text-red-500">*</span>
                </label>

                <select
                    v-model="form.estado"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                    class="mt-1 text-sm text-red-600"
                >
                    {{ form.errors.estado }}
                </p>
            </div>


            <!-- CIDADE -->

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Cidade
                    <span class="text-red-500">*</span>
                </label>

                <select
                    v-model="form.cidade"
                    :disabled="!form.estado || carregandoCidades"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm disabled:cursor-not-allowed disabled:bg-gray-100"
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
                    class="mt-1 text-sm text-red-600"
                >
                    {{ form.errors.cidade }}
                </p>
            </div>


            <!-- PROFISSÃO -->

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">
                    Profissão
                </label>

                <input
                    v-model="form.profissao"
                    type="text"
                    maxlength="255"
                    placeholder="Ex.: Engenheiro de Software"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

                <p
                    v-if="form.errors.profissao"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ form.errors.profissao }}
                </p>
            </div>


            <!-- TIPO SANGUÍNEO -->

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Tipo sanguíneo
                </label>

                <select
                    v-model="form.tipo_sanguineo"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                    <option value="">
                        Não informado
                    </option>

                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                </select>

                <p
                    v-if="form.errors.tipo_sanguineo"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ form.errors.tipo_sanguineo }}
                </p>
            </div>

        </div>

    </div>
</template>
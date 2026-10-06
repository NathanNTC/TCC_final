<script setup>
import { router } from '@inertiajs/vue3'
import PerfilCard from './PerfilCard.vue'

defineProps({
    paciente: {
        type: Object,
        required: true,
    },

    perfilSaude: {
        type: Object,
        default: null,
    },
})

function editar() {
    router.visit('/perfil/editar/dados-pessoais')
}
</script>

<template>
    <PerfilCard
        titulo="Dados pessoais"
        descricao="Informações básicas do paciente"
    >
        <template #acao>
            <button
                type="button"
                @click="editar"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50"
            >
                Editar
            </button>
        </template>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            <!-- CPF -->
            <div>
                <p class="text-sm text-gray-500">
                    CPF
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ paciente.cpf || 'Não informado' }}
                </p>
            </div>

            <!-- SEXO -->
            <div>
                <p class="text-sm text-gray-500">
                    Sexo
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{
                        paciente.sexo === 'M'
                            ? 'Masculino'
                            : paciente.sexo === 'F'
                                ? 'Feminino'
                                : 'Não informado'
                    }}
                </p>
            </div>

            <!-- DATA DE NASCIMENTO -->
            <div>
                <p class="text-sm text-gray-500">
                    Data de nascimento
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ paciente.data_nascimento || 'Não informado' }}
                </p>
            </div>

            <!-- TELEFONE -->
            <div>
                <p class="text-sm text-gray-500">
                    Telefone
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ paciente.telefone || 'Não informado' }}
                </p>
            </div>

            <!-- CIDADE -->
            <div>
                <p class="text-sm text-gray-500">
                    Cidade
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ paciente.cidade || 'Não informado' }}
                </p>
            </div>

            <!-- ESTADO -->
            <div>
                <p class="text-sm text-gray-500">
                    Estado
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ paciente.estado || 'Não informado' }}
                </p>
            </div>

            <!-- PROFISSÃO -->
            <div>
                <p class="text-sm text-gray-500">
                    Profissão
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ paciente.profissao || 'Não informado' }}
                </p>
            </div>

            

        </div>
    </PerfilCard>
</template>
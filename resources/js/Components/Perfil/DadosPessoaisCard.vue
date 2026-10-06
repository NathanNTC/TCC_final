<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import PerfilCard from './PerfilCard.vue'

const props = defineProps({
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

const localizacao = computed(() => {
    if (props.paciente?.cidade && props.paciente?.estado) {
        return `${props.paciente.cidade} - ${props.paciente.estado}`
    }
    return props.paciente?.cidade || props.paciente?.estado || null
})
</script>

<template>
    <PerfilCard descricao="Informações básicas e de contato do paciente" titulo="Dados pessoais">
        <!-- BOTÃO EDITAR -->
        <template #acao>
            <button
                type="button"
                @click="editar"
                class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-700 shadow-xs transition hover:border-emerald-300 hover:bg-emerald-50/50 hover:text-emerald-700 active:scale-95"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5 text-slate-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                </svg>
                Editar
            </button>
        </template>

        <!-- GRID DE INFORMAÇÕES -->
        <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2 lg:grid-cols-3">

            <!-- CPF -->
            <div class="flex items-start gap-3 rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 transition hover:border-slate-200">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100/70 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h-.01M15 12h-.01M15 15h-.01M12 9h-.01M12 12h-.01M12 15h-.01M9 9h-.01M9 12h-.01M9 15h-.01M4 6h16a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">CPF</p>
                    <p :class="['mt-0.5 text-xs font-semibold break-words leading-relaxed', paciente.cpf ? 'text-slate-800' : 'text-slate-400 italic']">
                        {{ paciente.cpf || 'Não informado' }}
                    </p>
                </div>
            </div>

            <!-- SEXO -->
            <div class="flex items-start gap-3 rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 transition hover:border-slate-200">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100/70 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Sexo</p>
                    <p :class="['mt-0.5 text-xs font-semibold break-words leading-relaxed', paciente.sexo ? 'text-slate-800' : 'text-slate-400 italic']">
                        {{
                            paciente.sexo === 'M'
                                ? 'Masculino'
                                : paciente.sexo === 'F'
                                    ? 'Feminino'
                                    : 'Não informado'
                        }}
                    </p>
                </div>
            </div>

            <!-- DATA DE NASCIMENTO -->
            <div class="flex items-start gap-3 rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 transition hover:border-slate-200">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100/70 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Data de nascimento</p>
                    <p :class="['mt-0.5 text-xs font-semibold break-words leading-relaxed', paciente.data_nascimento ? 'text-slate-800' : 'text-slate-400 italic']">
                        {{ paciente.data_nascimento || 'Não informado' }}
                    </p>
                </div>
            </div>

            <!-- TELEFONE -->
            <div class="flex items-start gap-3 rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 transition hover:border-slate-200">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100/70 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.828-1.015-5.186-3.373-6.201-6.201l1.293-.97c.362-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Telefone</p>
                    <p :class="['mt-0.5 text-xs font-semibold break-words leading-relaxed', paciente.telefone ? 'text-slate-800' : 'text-slate-400 italic']">
                        {{ paciente.telefone || 'Não informado' }}
                    </p>
                </div>
            </div>

            <!-- CIDADE / ESTADO -->
            <div class="flex items-start gap-3 rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 transition hover:border-slate-200">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100/70 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Cidade / Estado</p>
                    <p :class="['mt-0.5 text-xs font-semibold break-words leading-relaxed', localizacao ? 'text-slate-800' : 'text-slate-400 italic']">
                        {{ localizacao || 'Não informado' }}
                    </p>
                </div>
            </div>

            <!-- PROFISSÃO -->
            <div class="flex items-start gap-3 rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 transition hover:border-slate-200">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100/70 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387M20.25 14.15a2.18 2.18 0 01-.75 1.661M3.75 14.15v4.25c0 1.094.787 2.036 1.872 2.18 2.087.277 4.216.42 6.378.42s4.291-.143 6.378-.42c1.085-.144 1.872-1.086 1.872-2.18v-4.25m-16.5 0a2.18 2.18 0 01-.75-1.661V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m0 0V5.25A2.25 2.25 0 018.25 3h7.5a2.25 2.25 0 012.25 2.25v1.281m-12 0a48.334 48.334 0 0112 0" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Profissão</p>
                    <p :class="['mt-0.5 text-xs font-semibold break-words leading-relaxed', paciente.profissao ? 'text-slate-800' : 'text-slate-400 italic']">
                        {{ paciente.profissao || 'Não informado' }}
                    </p>
                </div>
            </div>

        </div>
    </PerfilCard>
</template>
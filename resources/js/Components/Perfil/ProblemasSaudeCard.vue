<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import PerfilCard from './PerfilCard.vue'

const props = defineProps({
    perfilSaude: {
        type: Object,
        default: null,
    },
})

function editar() {
    router.visit('/perfil/editar/problemas-saude')
}

const nomes = {
    hipertensao: 'Pressão alta (Hipertensão)',
    diabetes: 'Diabetes',
    dislipidemia: 'Colesterol ou triglicerídeos altos',
    asma_dpoc: 'Asma / DPOC',
    infarto: 'Infarto prévio',
    arritmia: 'Arritmias',
    insuficiencia_cardiaca: 'Insuficiência cardíaca',
    epilepsia: 'Epilepsia',
    avc: 'AVC prévio',
    enxaqueca: 'Enxaqueca crônica',
    hipotireoidismo: 'Hipotireoidismo',
    cancer: 'Histórico de câncer',
    cirurgias: 'Cirurgias prévias',
    internacoes: 'Internações recentes',
    outros: 'Outra condição',
    nao_possui: 'Não possui',
}

// Filtra 'nao_possui' do loop de badges caso venha no array
const condicoesValidas = computed(() => {
    return (props.perfilSaude?.problemas_saude_previos || []).filter(item => item !== 'nao_possui')
})

const temNaoPossui = computed(() => {
    return props.perfilSaude?.problemas_saude_previos?.includes('nao_possui')
})
</script>

<template>
    <PerfilCard
        titulo="Condições de saúde"
        descricao="Diagnósticos prévios e histórico médico relevante"
    >
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

        <div class="space-y-4">
            <!-- BADGES DE CONDIÇÕES -->
            <div v-if="condicoesValidas.length > 0" class="flex flex-wrap gap-2">
                <span
                    v-for="item in condicoesValidas"
                    :key="item"
                    class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-800 ring-1 ring-inset ring-emerald-600/20"
                >
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    {{ nomes[item] || item }}
                </span>
            </div>

            <!-- ESTADO SEM CONDIÇÕES -->
            <div
                v-else
                class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50/60 p-3.5"
            >
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-700">
                        {{ temNaoPossui ? 'Sem condições prévias declaradas' : 'Nenhuma condição informada' }}
                    </p>
                    <p class="text-[11px] text-slate-400 italic">
                        Não há histórico de problemas de saúde registrados.
                    </p>
                </div>
            </div>

            <!-- DETALHES -->
            <div
                v-if="perfilSaude?.problemas_saude_detalhes"
                class="rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 transition hover:border-slate-200"
            >
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Observações e Detalhes Médicos</p>
                <p class="mt-1 text-xs font-medium leading-relaxed text-slate-800 whitespace-pre-line break-words">
                    {{ perfilSaude.problemas_saude_detalhes }}
                </p>
            </div>
        </div>
    </PerfilCard>
</template>
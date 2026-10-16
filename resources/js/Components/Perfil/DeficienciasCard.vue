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
    router.visit('/perfil/editar/deficiencias')
}

const nomes = {
    fisica: 'Física',
    visual: 'Visual',
    auditiva: 'Auditiva',
    intelectual: 'Intelectual/Cognitiva',
    psicossocial: 'Psicossocial',
    outros: 'Outros',
    nao_possui: 'Não possui',
}

// Filtra 'nao_possui' do loop de badges caso venha junto no array
const deficienciasValidas = computed(() => {
    return (props.perfilSaude?.deficiencias || []).filter(item => item !== 'nao_possui')
})

const temNaoPossui = computed(() => {
    return props.perfilSaude?.deficiencias?.includes('nao_possui')
})
</script>

<template>
    <PerfilCard
        titulo="Necessidades de acessibilidade"
        descricao="Informações importantes para o atendimento humanizado e acessível"
    >
        <!-- BOTÃO EDITAR -->
        <template #acao>
            <button
                type="button"
                @click="editar"
                class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-700 shadow-xs transition hover:border-emerald-300 hover:bg-emerald-50/50 hover:text-emerald-700 active:scale-95 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-slate-700 dark:hover:bg-slate-800 dark:hover:text-emerald-400"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5 text-slate-400 dark:text-slate-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                </svg>
                Editar
            </button>
        </template>

        <div class="space-y-4">

            <!-- LISTA DE DEFICIÊNCIAS (BADGES) -->
            <div v-if="deficienciasValidas.length > 0" class="flex flex-wrap gap-2">
                <span
                    v-for="item in deficienciasValidas"
                    :key="item"
                    class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-800 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-950/60 dark:text-emerald-300 dark:ring-emerald-500/30"
                >
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    {{ nomes[item] || item }}
                </span>
            </div>

            <!-- ESTADO SEM NECESSIDADES OU NÃO POSSUI -->
            <div
                v-else
                class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 dark:border-slate-800/80 dark:bg-slate-800/40"
            >
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                        {{ temNaoPossui ? 'Não possui necessidades declaradas' : 'Nenhuma necessidade informada' }}
                    </p>
                    <p class="text-[11px] text-slate-400 italic dark:text-slate-500">
                        Nenhuma deficiência ou limitação informada até o momento.
                    </p>
                </div>
            </div>

            <!-- OUTRAS DEFICIÊNCIAS (DESCRIÇÃO) -->
            <div
                v-if="perfilSaude?.deficiencia_outros_descricao"
                class="rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 transition hover:border-slate-200 dark:border-slate-800/80 dark:bg-slate-800/40 dark:hover:border-slate-700"
            >
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Outras deficiências ou limitações</p>
                <p class="mt-1 text-xs font-medium leading-relaxed text-slate-800 whitespace-pre-line break-words dark:text-slate-200">
                    {{ perfilSaude.deficiencia_outros_descricao }}
                </p>
            </div>

            <!-- NECESSIDADES ESPECÍFICAS DE ATENDIMENTO -->
            <div
                v-if="perfilSaude?.necessidades_acessibilidade"
                class="rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 transition hover:border-slate-200 dark:border-slate-800/80 dark:bg-slate-800/40 dark:hover:border-slate-700"
            >
                <div class="flex items-center gap-2 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4 text-emerald-600 dark:text-emerald-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Necessidades específicas para atendimento</p>
                </div>
                <p class="text-xs font-medium leading-relaxed text-slate-800 whitespace-pre-line break-words dark:text-slate-200">
                    {{ perfilSaude.necessidades_acessibilidade }}
                </p>
            </div>

        </div>
    </PerfilCard>
</template>
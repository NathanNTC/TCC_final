<script setup>
import { router } from '@inertiajs/vue3'
import PerfilCard from './PerfilCard.vue'

defineProps({
    perfilSaude: {
        type: Object,
        default: null,
    },
})

/*
|--------------------------------------------------------------------------
| Editar
|--------------------------------------------------------------------------
*/
function editar() {
    router.visit('/perfil/editar/alergias')
}

/*
|--------------------------------------------------------------------------
| Nomes dos tipos de alergia
|--------------------------------------------------------------------------
*/
const nomesAlergia = {
    medicamentosa: 'Medicamentos',
    alimentar: 'Alimentos',
    outros: 'Outros',
    nao_possui: 'Não possui',
}

/*
|--------------------------------------------------------------------------
| Nomes das reações
|--------------------------------------------------------------------------
*/
const nomesReacao = {
    urticaria: 'Manchas na pele / Coceira',
    inchaco: 'Inchaço nos lábios, olhos ou garganta',
    dificuldade_respirar: 'Dificuldade grave para respirar',
    tontura_desmaio: 'Tontura forte ou desmaio',
}
</script>

<template>
    <PerfilCard
        titulo="Alergias"
        descricao="Alergias conhecidas e reações registradas"
    >
        <!-- ============================================================= -->
        <!-- BOTÃO EDITAR -->
        <!-- ============================================================= -->
        <template #acao>
            <button
                type="button"
                @click="editar"
                class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-700 shadow-xs transition hover:border-emerald-300 hover:bg-emerald-50/50 hover:text-emerald-700 active:scale-95"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5 text-slate-400 group-hover:text-emerald-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                </svg>
                Editar
            </button>
        </template>

        <!-- ============================================================= -->
        <!-- SEM INFORMAÇÃO OU NÃO POSSUI ALERGIAS -->
        <!-- ============================================================= -->
        <div
            v-if="
                !perfilSaude?.alergias?.length ||
                perfilSaude.alergias.includes('nao_possui')
            "
        >
            <div
                v-if="perfilSaude?.alergias?.includes('nao_possui')"
                class="flex items-center gap-3 rounded-2xl border border-emerald-200/80 bg-emerald-50/50 p-4 text-emerald-900"
            >
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-emerald-900">
                        Nenhuma alergia relatada
                    </p>
                    <p class="text-xs text-emerald-700">
                        O paciente declarou não possuir alergias medicamentosas ou alimentares conhecidas.
                    </p>
                </div>
            </div>

            <div v-else class="rounded-2xl border border-dashed border-slate-200 p-6 text-center">
                <p class="text-sm font-medium text-slate-500">
                    Nenhuma informação de alergia cadastrada até o momento.
                </p>
            </div>
        </div>

        <!-- ============================================================= -->
        <!-- ALERGIAS REGISTRADAS (ALERTA) -->
        <!-- ============================================================= -->
        <div v-else class="space-y-5">
            <!-- TIPOS DE ALERGIA -->
            <div>
                <p class="mb-2.5 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Tipos de alergia
                </p>
                <div class="flex flex-wrap gap-2">
                    <span
                        v-for="item in perfilSaude.alergias"
                        :key="item"
                        class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-800 ring-1 ring-inset ring-rose-600/20"
                    >
                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                        {{ nomesAlergia[item] || item }}
                    </span>
                </div>
            </div>

            <!-- SUBSTÂNCIAS DETALHADAS -->
            <div v-if="perfilSaude.alergias_detalhes" class="rounded-xl border border-slate-100 bg-slate-50/70 p-3.5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Substâncias ou Agentes Causadores
                </p>
                <p class="mt-1 whitespace-pre-line text-sm font-semibold text-slate-800">
                    {{ perfilSaude.alergias_detalhes }}
                </p>
            </div>

            <!-- REAÇÕES CONHECIDAS -->
            <div v-if="perfilSaude.alergias_reacoes?.length">
                <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Sintomas e Reações Conhecidas
                </p>
                <div class="grid gap-2 sm:grid-cols-2">
                    <div
                        v-for="reacao in perfilSaude.alergias_reacoes"
                        :key="reacao"
                        class="flex items-center gap-2.5 rounded-xl border border-amber-200/60 bg-amber-50/40 px-3 py-2 text-xs font-medium text-amber-900"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4 shrink-0 text-amber-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                        <span>{{ nomesReacao[reacao] || reacao }}</span>
                    </div>
                </div>
            </div>

            <!-- OBSERVAÇÕES ADICIONAIS -->
            <div v-if="perfilSaude.alergias_observacoes">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Observações Adicionais
                </p>
                <p class="mt-1 text-xs text-slate-600 whitespace-pre-line leading-relaxed">
                    {{ perfilSaude.alergias_observacoes }}
                </p>
            </div>
        </div>
    </PerfilCard>
</template>
<script setup>
import { router } from '@inertiajs/vue3'
import PerfilCard from './PerfilCard.vue'

defineProps({
    medicamentos: {
        type: Array,
        default: () => [],
    },
})

function editar() {
    router.visit('/perfil/editar/medicamentos')
}
</script>

<template>
    <PerfilCard
        titulo="Medicamentos e suplementos"
        descricao="Medicamentos e suplementos utilizados regularmente"
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

        <!-- NENHUM MEDICAMENTO -->
        <div
            v-if="!medicamentos.length"
            class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 dark:border-slate-800/80 dark:bg-slate-800/40"
        >
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                    Nenhum medicamento informado
                </p>
                <p class="text-[11px] text-slate-400 italic dark:text-slate-500">
                    Não há registros de uso contínuo de medicamentos ou suplementos.
                </p>
            </div>
        </div>

        <!-- LISTA DE MEDICAMENTOS -->
        <div
            v-else
            class="space-y-3"
        >
            <div
                v-for="medicamento in medicamentos"
                :key="medicamento.id"
                class="rounded-xl border border-slate-100 bg-slate-50/60 p-4 transition hover:border-slate-200 dark:border-slate-800/80 dark:bg-slate-800/40 dark:hover:border-slate-700"
            >
                <div class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100/70 text-emerald-700 dark:bg-emerald-950/80 dark:text-emerald-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 18H7.5m9-6h2.25m-2.25 0a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 12h8.25" />
                        </svg>
                    </span>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200">
                        {{ medicamento.nome }}
                    </h3>
                </div>

                <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <!-- DOSAGEM -->
                    <div class="rounded-lg border border-slate-200/60 bg-white p-2.5 dark:border-slate-800 dark:bg-slate-900/60">
                        <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Dosagem</span>
                        <p class="mt-0.5 text-xs font-semibold text-slate-700 dark:text-slate-300">
                            {{ medicamento.dosagem || 'Não informado' }}
                        </p>
                    </div>

                    <!-- FREQUÊNCIA -->
                    <div class="rounded-lg border border-slate-200/60 bg-white p-2.5 dark:border-slate-800 dark:bg-slate-900/60">
                        <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Frequência</span>
                        <p class="mt-0.5 text-xs font-semibold text-slate-700 dark:text-slate-300">
                            {{ medicamento.frequencia || 'Não informado' }}
                        </p>
                    </div>

                    <!-- INDICAÇÃO -->
                    <div class="rounded-lg border border-slate-200/60 bg-white p-2.5 dark:border-slate-800 dark:bg-slate-900/60">
                        <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Indicação</span>
                        <p class="mt-0.5 text-xs font-semibold text-slate-700 dark:text-slate-300">
                            {{ medicamento.indicacao || 'Não informado' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </PerfilCard>
</template>
<script setup>
import { router } from '@inertiajs/vue3'
import PerfilCard from './PerfilCard.vue'

defineProps({
    perfilSaude: {
        type: Object,
        default: null,
    },
})

function editar() {
    router.visit('/perfil/editar/habitos')
}

function formatarBooleano(valor) {
    if (valor === true) return { texto: 'Sim', classe: 'text-emerald-700 bg-emerald-50 ring-emerald-600/20' }
    if (valor === false) return { texto: 'Não', classe: 'text-slate-600 bg-slate-100 ring-slate-500/10' }
    return { texto: 'Não informado', classe: 'text-slate-400 bg-slate-50 ring-slate-200' }
}
</script>

<template>
    <PerfilCard
        titulo="Hábitos e medidas"
        descricao="Hábitos de vida e medidas corporais do paciente"
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

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">

            <!-- TABAGISMO -->
            <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 transition hover:border-slate-200">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Tabagismo</span>
                <div class="mt-1.5">
                    <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset" :class="formatarBooleano(perfilSaude?.fumante).classe">
                        {{ formatarBooleano(perfilSaude?.fumante).texto }}
                    </span>
                </div>
            </div>

            <!-- ÁLCOOL -->
            <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 transition hover:border-slate-200">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Consumo de álcool</span>
                <div class="mt-1.5">
                    <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset" :class="formatarBooleano(perfilSaude?.alcoolismo).classe">
                        {{ formatarBooleano(perfilSaude?.alcoolismo).texto }}
                    </span>
                </div>
            </div>

            <!-- DROGAS -->
            <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 transition hover:border-slate-200">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Uso de substâncias</span>
                <div class="mt-1.5">
                    <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset" :class="formatarBooleano(perfilSaude?.uso_drogas).classe">
                        {{ formatarBooleano(perfilSaude?.uso_drogas).texto }}
                    </span>
                </div>
            </div>

            <!-- ATIVIDADE FÍSICA -->
            <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 transition hover:border-slate-200">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Exercício regular</span>
                <div class="mt-1.5">
                    <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset" :class="formatarBooleano(perfilSaude?.atividade_fisica_regular).classe">
                        {{ formatarBooleano(perfilSaude?.atividade_fisica_regular).texto }}
                    </span>
                </div>
            </div>

            <!-- ALTURA -->
            <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 transition hover:border-slate-200">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Altura</span>
                <p class="mt-1 text-sm font-semibold text-slate-800">
                    {{ perfilSaude?.altura ? `${perfilSaude.altura} m` : 'Não informado' }}
                </p>
            </div>

            <!-- PESO -->
            <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 transition hover:border-slate-200">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Peso</span>
                <p class="mt-1 text-sm font-semibold text-slate-800">
                    {{ perfilSaude?.peso ? `${perfilSaude.peso} kg` : 'Não informado' }}
                </p>
            </div>

        </div>
    </PerfilCard>
</template>
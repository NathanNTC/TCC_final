<script setup>
import { router } from '@inertiajs/vue3'
import PerfilCard from './PerfilCard.vue'

defineProps({
    cirurgias: {
        type: Array,
        default: () => [],
    },
})

function editar() {
    router.visit('/perfil/editar/cirurgias')
}

const anestesias = {
    geral: 'Geral',
    raqui_peridural: 'Nas costas (raqui ou peridural)',
    local_sedacao: 'Local ou sedação leve',
    nao_lembra: 'Não lembra',
}

const complicacoes = {
    uti: 'Precisou ir para a UTI',
    infeccao: 'Infecção no local da cirurgia',
    sangramento_transfusao: 'Sangramento forte ou transfusão de sangue',
    demora_acordar: 'Mal-estar grave ou demora para acordar da anestesia',
    nenhuma: 'Nenhuma complicação',
}

const implantes = {
    placas_parafusos_pinos: 'Placas, parafusos ou pinos de metal',
    protese: 'Prótese',
    marcapasso: 'Marcapasso ou desfibrilador',
    nenhum: 'Não possui implantes',
}
</script>

<template>
    <PerfilCard
        titulo="Cirurgias anteriores"
        descricao="Histórico cirúrgico, anestésico e implantes registrados"
    >
        <!-- ============================================================= -->
        <!-- BOTÃO EDITAR -->
        <!-- ============================================================= -->
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

        <!-- ============================================================= -->
        <!-- SEM HISTÓRICO CIRÚRGICO -->
        <!-- ============================================================= -->
        <div v-if="!cirurgias.length" class="rounded-2xl border border-dashed border-slate-200 p-6 text-center dark:border-slate-800">
            <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25h3c1.022 0 1.879.683 2.15 1.623m-5.8 0a48.455 48.455 0 0 1 5.8 0" />
                </svg>
            </div>
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">
                Nenhuma cirurgia anterior informada.
            </p>
        </div>

        <!-- ============================================================= -->
        <!-- LISTA DE CIRURGIAS -->
        <!-- ============================================================= -->
        <div v-else class="space-y-4">
            <div
                v-for="(cirurgia, index) in cirurgias"
                :key="cirurgia.id ?? index"
                class="rounded-2xl border border-slate-200/80 bg-slate-50/50 p-5 transition hover:border-slate-300 dark:border-slate-800 dark:bg-slate-800/40 dark:hover:border-slate-700"
            >
                <!-- CABEÇALHO DO ITEM -->
                <div class="mb-3 flex items-center justify-between border-b border-slate-200/60 pb-3 dark:border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 text-xs font-bold text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            #{{ index + 1 }}
                        </span>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">
                            {{ cirurgia.tipo || `Cirurgia ${index + 1}` }}
                        </h3>
                    </div>

                    <span
                        v-if="cirurgia.ano"
                        class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-500/10 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700"
                    >
                        Ano: {{ cirurgia.ano }}
                    </span>
                </div>

                <!-- DETALHES EM GRID -->
                <div class="grid gap-4 sm:grid-cols-2">
                    <!-- TIPO DE ANESTESIA -->
                    <div v-if="cirurgia.anestesia">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                            Tipo de Anestesia
                        </p>
                        <p class="mt-0.5 text-xs font-semibold text-slate-700 dark:text-slate-300">
                            {{ anestesias[cirurgia.anestesia] || cirurgia.anestesia }}
                        </p>
                    </div>

                    <!-- IMPLANTES OU MATERIAIS -->
                    <div v-if="cirurgia.implantes?.length">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                            Implantes / Próteses
                        </p>
                        <div class="mt-1 flex flex-wrap gap-1.5">
                            <span
                                v-for="item in cirurgia.implantes"
                                :key="item"
                                class="inline-flex items-center gap-1 rounded-md bg-indigo-50 px-2 py-0.5 text-[11px] font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-700/10 dark:bg-indigo-950/60 dark:text-indigo-300 dark:ring-indigo-500/30"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3 w-3 text-indigo-500 dark:text-indigo-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                {{ implantes[item] || item }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- COMPLICAÇÕES (SE HOUVER) -->
                <div v-if="cirurgia.complicacoes?.length" class="mt-4 border-t border-slate-200/60 pt-3 dark:border-slate-800">
                    <p class="mb-1.5 text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        Complicações Relatadas
                    </p>
                    <div class="space-y-1.5">
                        <div
                            v-for="item in cirurgia.complicacoes"
                            :key="item"
                            :class="[
                                'flex items-center gap-2 rounded-xl px-3 py-1.5 text-xs font-medium',
                                item === 'nenhuma'
                                    ? 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400'
                                    : 'bg-amber-50 text-amber-900 ring-1 ring-amber-600/20 dark:bg-amber-950/60 dark:text-amber-300 dark:ring-amber-500/30'
                            ]"
                        >
                            <svg v-if="item !== 'nenhuma'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4 shrink-0 text-amber-600 dark:text-amber-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                            <span>{{ complicacoes[item] || item }}</span>
                        </div>
                    </div>
                </div>

                <!-- OBSERVAÇÕES ADICIONAIS -->
                <div v-if="cirurgia.observacoes" class="mt-3 border-t border-slate-200/60 pt-3 dark:border-slate-800">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        Observações
                    </p>
                    <p class="mt-0.5 text-xs text-slate-600 whitespace-pre-line leading-relaxed dark:text-slate-400">
                        {{ cirurgia.observacoes }}
                    </p>
                </div>
            </div>
        </div>
    </PerfilCard>
</template>
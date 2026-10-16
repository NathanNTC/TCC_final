<script setup>
import PerfilSecaoCard from './PerfilSecaoCard.vue'

defineProps({
    perfilSecoes: {
        type: Object,
        default: () => ({}),
    },
})
</script>

<template>
    <section class="mt-8">

        <!-- TÍTULO E LEGENDA -->
        <div class="mb-5 flex items-end justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-slate-100">
                    Informações do perfil
                </h2>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Situação das informações cadastradas.
                </p>
            </div>

            <!-- LEGENDA -->
            <div class="hidden items-center gap-2 sm:flex">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">
                    Completo
                </span>

                <span class="ml-2 h-2 w-2 rounded-full bg-amber-500"></span>
                <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">
                    Pendente
                </span>
            </div>
        </div>

        <!-- CARDS -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <PerfilSecaoCard
                titulo="Dados pessoais"
                descricao="Informações básicas e identificação."
                icone="usuario"
                :completo="Boolean(perfilSecoes.dados_pessoais)"
            />

            <PerfilSecaoCard
                titulo="Acessibilidade"
                descricao="Necessidades e condições de acessibilidade."
                icone="acessibilidade"
                :completo="Boolean(perfilSecoes.acessibilidade)"
            />

            <PerfilSecaoCard
                titulo="Condições de saúde"
                descricao="Condições e problemas de saúde anteriores."
                icone="saude"
                :completo="Boolean(perfilSecoes.condicoes_saude)"
            />

            <PerfilSecaoCard
                titulo="Histórico familiar"
                descricao="Histórico de saúde da família."
                icone="familia"
                :completo="Boolean(perfilSecoes.historico_familiar)"
            />

            <PerfilSecaoCard
                titulo="Alergias"
                descricao="Alergias, reações e sensibilidades conhecidas."
                icone="alergia"
                :completo="Boolean(perfilSecoes.alergias)"
            />

            <PerfilSecaoCard
                titulo="Medicamentos"
                descricao="Medicamentos e suplementos utilizados."
                icone="medicamentos"
                :completo="Boolean(perfilSecoes.medicamentos)"
            />

            <PerfilSecaoCard
                titulo="Cirurgias anteriores"
                descricao="Procedimentos e cirurgias realizadas."
                icone="cirurgia"
                :completo="Boolean(perfilSecoes.cirurgias)"
            />

            <PerfilSecaoCard
                titulo="Hábitos e medidas"
                descricao="Hábitos de vida e medidas corporais."
                icone="habitos"
                :completo="Boolean(perfilSecoes.habitos_medidas)"
            />
        </div>

    </section>
</template>
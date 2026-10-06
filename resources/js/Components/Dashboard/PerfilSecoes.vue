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

        <!-- TÍTULO -->

        <div class="mb-5 flex items-end justify-between">

            <div>
                <h2 class="text-lg font-semibold text-slate-900">
                    Informações do perfil
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Situação das informações cadastradas.
                </p>
            </div>

            <!-- LEGENDA -->

            <div class="hidden items-center gap-2 sm:flex">

                <span class="h-2 w-2 rounded-full bg-green-500"></span>

                <span class="text-xs font-medium text-slate-500">
                    
                </span>

                <span class="ml-2 h-2 w-2 rounded-full bg-amber-400"></span>

                <span class="text-xs font-medium text-slate-500">
                   
                </span>

            </div>

        </div>

        <!-- CARDS -->

        <div
            class="grid grid-cols-1 gap-4
                   sm:grid-cols-2 lg:grid-cols-4"
        >

            <PerfilSecaoCard
                titulo="Dados pessoais"
                descricao="Informações básicas e identificação."
                icone="usuario"
                :completo="perfilSecoes.dados_pessoais"
            />

            <PerfilSecaoCard
                titulo="Acessibilidade"
                descricao="Necessidades e condições de acessibilidade."
                icone="acessibilidade"
                :completo="perfilSecoes.acessibilidade"
            />

            <PerfilSecaoCard
                titulo="Condições de saúde"
                descricao="Condições e problemas de saúde anteriores."
                icone="saude"
                :completo="perfilSecoes.condicoes_saude"
            />

            <PerfilSecaoCard
                titulo="Histórico familiar"
                descricao="Histórico de saúde da família."
                icone="familia"
                :completo="perfilSecoes.historico_familiar"
            />

            <PerfilSecaoCard
                titulo="Alergias"
                descricao="Alergias, reações e sensibilidades conhecidas."
                icone="alergia"
                :completo="perfilSecoes.alergias"
            />

            <PerfilSecaoCard
                titulo="Medicamentos"
                descricao="Medicamentos e suplementos utilizados."
                icone="medicamentos"
                :completo="perfilSecoes.medicamentos"
            />

            <PerfilSecaoCard
                titulo="Cirurgias anteriores"
                descricao="Procedimentos e cirurgias realizadas."
                icone="cirurgia"
                :completo="perfilSecoes.cirurgias"
            />

            <PerfilSecaoCard
                titulo="Hábitos e medidas"
                descricao="Hábitos de vida e medidas corporais."
                icone="habitos"
                :completo="perfilSecoes.habitos_medidas"
            />

        </div>

    </section>
</template>
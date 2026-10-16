<script setup>
import ResumoConsumo from './ResumoConsumo.vue'
import GraficoCustos from './GraficoCustos.vue'
import DetalhamentoTokens from './DetalhamentoTokens.vue'
import ResumoPeriodo from './ResumoPeriodo.vue'
import ConsumoPorModelo from './ConsumoPorModelo.vue'
import HistoricoChamadas from './HistoricoChamadas.vue'
import AvisoCustos from './AvisoCustos.vue'

defineProps({
    consumoGemini: {
        type: Object,
        default: () => ({}),
    },
    custosGrafico: {
        type: Array,
        default: () => [],
    },
    periodoCustosGrafico: {
        type: Object,
        default: () => ({}),
    },
    consumoPorModelo: {
        type: Array,
        default: () => [],
    },
    registrosGemini: {
        type: Array,
        default: () => [],
    },
})
</script>

<template>
    <div class="space-y-6">
        <!-- AVISO INFORMATIVO DE CUSTOS -->
        <AvisoCustos />

        <!-- RESUMO PRINCIPAL DE CONSUMO -->
        <ResumoConsumo
            :consumo-gemini="consumoGemini"
        />

        <!-- GRÁFICO HISTÓRICO DE CUSTOS -->
        <GraficoCustos
            :custos-grafico="custosGrafico"
            :periodo-custos-grafico="periodoCustosGrafico"
        />

        <!-- DETALHAMENTO EM GRID (TOKENS + PERÍODO) -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <DetalhamentoTokens
                :consumo-gemini="consumoGemini"
            />

            <ResumoPeriodo
                :consumo-gemini="consumoGemini"
            />
        </div>

        <!-- CONSUMO POR MODELO -->
        <ConsumoPorModelo
            :consumo-por-modelo="consumoPorModelo"
        />

        <!-- HISTÓRICO DE CHAMADAS DE API -->
        <HistoricoChamadas
            :registros-gemini="registrosGemini"
        />
    </div>
</template>
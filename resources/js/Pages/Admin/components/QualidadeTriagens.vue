<script setup>
import { computed } from 'vue'

const props = defineProps({
    qualidade: {
        type: Object,
        default: () => ({
            media: 0,
            total_avaliacoes: 0,
            distribuicao: [],
        }),
    },
})

const distribuicao = computed(() => {
    const dados = props.qualidade?.distribuicao ?? []

    if (Array.isArray(dados)) {
        return [5, 4, 3, 2, 1].map((nota) => {
            const item = dados.find(
                (registro) => Number(registro.nota) === nota
                    || Number(registro.qualidade) === nota,
            )

            return {
                nota,
                total: Number(item?.total ?? item?.quantidade ?? 0),
            }
        })
    }

    return [5, 4, 3, 2, 1].map((nota) => ({
        nota,
        total: Number(dados[nota] ?? 0),
    }))
})

const totalAvaliacoes = computed(() => {
    if (props.qualidade?.total_avaliacoes) {
        return Number(props.qualidade.total_avaliacoes)
    }
    return distribuicao.value.reduce((acc, curr) => acc + curr.total, 0)
})

const maiorTotal = computed(() =>
    Math.max(1, ...distribuicao.value.map((item) => item.total)),
)

function calcularPorcentagem(total) {
    if (!totalAvaliacoes.value) return '0%'
    return `${Math.round((total / totalAvaliacoes.value) * 100)}%`
}
</script>

<template>
    <section class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs sm:p-8 dark:border-slate-800 dark:bg-slate-900">
        
        <!-- CABEÇALHO DA SEÇÃO -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 ring-1 ring-emerald-600/10 dark:bg-emerald-950/50 dark:text-emerald-400 dark:ring-emerald-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                        </svg>
                    </div>

                    <h2 class="text-base font-bold text-slate-900 dark:text-slate-100">
                        Qualidade das avaliações
                    </h2>
                </div>

                <p class="mt-1 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                    Distribuição das notas de qualidade atribuídas pelos avaliadores médicos.
                </p>
            </div>

            <!-- CARD DA MÉDIA GERAL -->
            <div class="flex shrink-0 items-center gap-4 rounded-xl border border-emerald-100 bg-emerald-50/60 p-4 dark:border-emerald-900/40 dark:bg-emerald-950/30">
                <div class="text-center">
                    <p class="text-xs font-semibold uppercase tracking-wider text-emerald-800 dark:text-emerald-300">
                        Média Geral
                    </p>

                    <div class="mt-1 flex items-baseline justify-center gap-1 font-mono">
                        <span class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-100">
                            {{ Number(qualidade?.media ?? 0).toFixed(1) }}
                        </span>
                        <span class="text-xs font-medium text-emerald-700 dark:text-emerald-400">/ 5.0</span>
                    </div>
                </div>

                <!-- ESTRELAS VISUAIS -->
                <div class="flex gap-1 text-amber-400">
                    <svg
                        v-for="i in 5"
                        :key="i"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                        class="h-4 w-4"
                        :class="i <= Math.round(Number(qualidade?.media ?? 0)) ? 'text-amber-400' : 'text-slate-200 dark:text-slate-700'"
                    >
                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- BARRAS DE DISTRIBUIÇÃO DE NOTAS -->
        <div class="mt-8 space-y-3.5">
            <div
                v-for="item in distribuicao"
                :key="item.nota"
                class="flex items-center gap-3"
            >
                <!-- RÓTULO DA NOTA -->
                <div class="flex w-16 shrink-0 items-center justify-end gap-1 text-xs font-semibold text-slate-700 dark:text-slate-300">
                    <span class="font-mono">{{ item.nota }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3.5 w-3.5 text-amber-400">
                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                    </svg>
                </div>

                <!-- TRILHO DA BARRA -->
                <div class="h-3.5 flex-1 overflow-hidden rounded-full bg-slate-100 p-0.5 dark:bg-slate-800">
                    <div
                        class="h-full rounded-full transition-all duration-500"
                        :class="{
                            'bg-emerald-600 dark:bg-emerald-500': item.nota >= 4,
                            'bg-amber-500 dark:bg-amber-400': item.nota === 3,
                            'bg-rose-500 dark:bg-rose-400': item.nota <= 2
                        }"
                        :style="{
                            width: `${maiorTotal > 0 ? (item.total / maiorTotal) * 100 : 0}%`,
                        }"
                    />
                </div>

                <!-- VALORES NUMÉRICOS E PORCENTAGEM -->
                <div class="flex w-20 shrink-0 items-center justify-between font-mono text-xs">
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ item.total }}</span>
                    <span class="text-slate-400 dark:text-slate-500">{{ calcularPorcentagem(item.total) }}</span>
                </div>
            </div>
        </div>

        <!-- RODAPÉ DA SEÇÃO -->
        <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4 text-xs text-slate-500 dark:border-slate-800 dark:text-slate-400">
            <span>Total de avaliações computadas:</span>
            <strong class="font-mono text-sm font-bold text-slate-900 dark:text-slate-100">
                {{ totalAvaliacoes }} {{ totalAvaliacoes === 1 ? 'avaliação' : 'avaliações' }}
            </strong>
        </div>

    </section>
</template>
<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    indicadores: {
        type: Object,
        default: () => ({}),
    },
    qualidade: {
        type: Object,
        default: () => ({}),
    },
    filtroPerfil: {
        type: String,
        default: 'todos',
    },
    opcoesPerfil: {
        type: Array,
        default: () => [],
    },
    avaliadoresPorPerfil: {
        type: Array,
        default: () => [],
    },
})

function valorOpcao(opcao) {
    return typeof opcao === 'string' ? opcao : opcao.value
}

function labelOpcao(opcao) {
    if (typeof opcao === 'string') {
        const perfis = {
            todos: 'Todas as avaliações',
            Estudante: 'Estudantes',
            Medico: 'Médicos',
            Residente: 'Residentes',
        }

        return perfis[opcao] ?? opcao
    }

    return opcao.label
}

function filtrarAvaliacoes(perfil) {
    if (!perfil) return

    router.get(
        '/admin',
        { perfil },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        },
    )
}

const cards = computed(() => [
    {
        titulo: 'Triagens concluídas',
        valor: props.indicadores.triagens_concluidas ?? 0,
        descricao: 'Total de triagens finalizadas no sistema',
        badge: 'Total',
        corBadge: 'bg-slate-100 text-slate-700 ring-slate-500/10',
        icone: 'check-circle',
    },
    {
        titulo: 'Triagens avaliadas',
        valor: props.indicadores.triagens_avaliadas ?? 0,
        descricao: 'Triagens avaliadas pelo perfil selecionado',
        badge: 'Perfil',
        corBadge: 'bg-emerald-50 text-emerald-800 ring-emerald-600/20',
        icone: 'clipboard-document-check',
    },
    {
        titulo: 'Total de avaliações',
        valor: props.indicadores.total_avaliacoes ?? 0,
        descricao: 'Avaliações registradas pelo perfil selecionado',
        badge: 'Registros',
        corBadge: 'bg-slate-100 text-slate-700 ring-slate-500/10',
        icone: 'document-text',
    },
    {
        titulo: 'Concordância IA / Humano',
        valor: `${Number(
            props.indicadores.concordancia_percentual ?? 0,
        ).toFixed(1)}%`,
        descricao: 'Prioridade da IA coincide com a do avaliador',
        badge: 'Taxa',
        corBadge: 'bg-emerald-50 text-emerald-800 ring-emerald-600/20',
        icone: 'check',
    },
    {
        titulo: 'Discordâncias',
        valor: props.indicadores.discordancias ?? 0,
        descricao: 'Casos com prioridades divergentes',
        badge: 'Atenção',
        corBadge: 'bg-amber-50 text-amber-800 ring-amber-600/20',
        icone: 'exclamation-triangle',
    },
    {
        titulo: 'Média de qualidade',
        valor: Number(
            props.qualidade?.media
            ?? props.indicadores.media_qualidade
            ?? 0,
        ).toFixed(1),
        descricao: 'Nota média dada pelo perfil selecionado',
        badge: 'Score',
        corBadge: 'bg-emerald-50 text-emerald-800 ring-emerald-600/20',
        icone: 'star',
    },
])

const totalAvaliadores = computed(() =>
    props.avaliadoresPorPerfil.reduce(
        (total, item) => total + Number(item.total || 0),
        0,
    ),
)

const fatiasPizza = computed(() => {
    const coresPerfis = {
        Médicos: '#059669', // Emerald 600
        Estudantes: '#0284c7', // Sky 600
        Residentes: '#d97706', // Amber 600
    }

    let anguloAtual = 0
    const total = totalAvaliadores.value

    return props.avaliadoresPorPerfil.map((item) => {
        const quantidade = Number(item.total || 0)
        const percentual = total > 0 ? quantidade / total : 0

        const anguloInicial = anguloAtual
        anguloAtual += percentual * 360

        let caminho = ''

        if (percentual >= 1) {
            caminho = [
                'M 100 10',
                'A 90 90 0 1 1 100 190',
                'A 90 90 0 1 1 100 10',
                'Z',
            ].join(' ')
        } else if (percentual > 0) {
            const inicio = (anguloInicial - 90) * Math.PI / 180
            const fim = (anguloAtual - 90) * Math.PI / 180

            const x1 = 100 + 90 * Math.cos(inicio)
            const y1 = 100 + 90 * Math.sin(inicio)
            const x2 = 100 + 90 * Math.cos(fim)
            const y2 = 100 + 90 * Math.sin(fim)

            const arcoGrande = percentual > 0.5 ? 1 : 0

            caminho = [
                'M 100 100',
                `L ${x1} ${y1}`,
                `A 90 90 0 ${arcoGrande} 1 ${x2} ${y2}`,
                'Z',
            ].join(' ')
        }

        return {
            perfil: item.perfil,
            total: quantidade,
            percentual: percentual * 100,
            cor: coresPerfis[item.perfil] ?? '#64748b',
            caminho,
        }
    })
})
</script>

<template>
    <section class="space-y-6">
        
        <!-- BARRA DE FILTROS DOS INDICADORES -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs sm:p-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 ring-1 ring-emerald-600/10">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>
                        </div>
                        <h2 class="text-base font-bold text-slate-900">
                            Indicadores do sistema
                        </h2>
                    </div>

                    <p class="mt-1 text-sm leading-relaxed text-slate-500">
                        Filtre os dados métricos gerais por perfil do avaliador.
                    </p>
                </div>

                <!-- BOTOES DE FILTRO -->
                <div class="flex flex-wrap gap-2">
                    <button
                        v-for="opcao in opcoesPerfil"
                        :key="valorOpcao(opcao)"
                        type="button"
                        class="rounded-xl border px-3.5 py-1.5 text-xs font-semibold transition-all cursor-pointer"
                        :class="valorOpcao(opcao) === filtroPerfil
                            ? 'border-emerald-600 bg-emerald-600 text-white shadow-xs'
                            : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:bg-slate-50'"
                        @click="filtrarAvaliacoes(valorOpcao(opcao))"
                    >
                        {{ labelOpcao(opcao) }}
                    </button>
                </div>
            </div>
        </div>

        <!-- CARDS DOS INDICADORES -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <article
                v-for="card in cards"
                :key="card.titulo"
                class="flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition-all hover:border-slate-300 hover:shadow-sm"
            >
                <div>
                    <div class="flex items-start justify-between gap-3">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                            {{ card.titulo }}
                        </p>

                        <span
                            :class="[
                                'inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold ring-1 ring-inset',
                                card.corBadge
                            ]"
                        >
                            {{ card.badge }}
                        </span>
                    </div>

                    <p class="mt-3 text-3xl font-black tracking-tight text-slate-900">
                        {{ card.valor }}
                    </p>
                </div>

                <p class="mt-4 border-t border-slate-100 pt-3 text-xs leading-relaxed text-slate-500">
                    {{ card.descricao }}
                </p>
            </article>
        </div>

        <!-- GRÁFICO DE DISTRIBUIÇÃO DE AVALIADORES (DONUT) -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs sm:p-8">
            <div class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 ring-1 ring-emerald-600/10">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                    </svg>
                </div>
                <h2 class="text-base font-bold text-slate-900">
                    Avaliadores por perfil
                </h2>
            </div>

            <p class="mt-1 text-sm leading-relaxed text-slate-500">
                Distribuição das pessoas cadastradas que realizaram avaliações no sistema.
            </p>

            <div class="mt-8 flex flex-col items-center gap-8 sm:flex-row sm:justify-center">
                
                <!-- GRÁFICO SVG DONUT -->
                <div class="relative h-52 w-52 shrink-0">
                    <svg
                        viewBox="0 0 200 200"
                        class="h-full w-full"
                        role="img"
                        aria-label="Gráfico de pizza da distribuição de avaliadores por perfil"
                    >
                        <!-- Círculo de fundo se estiver vazio -->
                        <circle
                            cx="100"
                            cy="100"
                            r="90"
                            fill="#f1f5f9"
                        />

                        <!-- Fatias -->
                        <path
                            v-for="fatia in fatiasPizza"
                            :key="fatia.perfil"
                            :d="fatia.caminho"
                            :fill="fatia.cor"
                            stroke="#ffffff"
                            stroke-width="2"
                        />

                        <!-- Recorte central do Donut -->
                        <circle
                            cx="100"
                            cy="100"
                            r="55"
                            fill="#ffffff"
                        />
                    </svg>

                    <!-- TEXTO NO CENTRO DO DONUT -->
                    <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-3xl font-black tracking-tight text-slate-900">
                            {{ totalAvaliadores }}
                        </span>
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                            Avaliadores
                        </span>
                    </div>
                </div>

                <!-- LEGENDA DO GRÁFICO -->
                <div class="w-full max-w-xs space-y-3.5">
                    <div
                        v-for="fatia in fatiasPizza"
                        :key="fatia.perfil"
                        class="flex items-center justify-between gap-4 rounded-xl border border-slate-100 bg-slate-50/50 p-3"
                    >
                        <div class="flex items-center gap-2.5">
                            <span
                                class="h-3.5 w-3.5 rounded-md shadow-xs shrink-0"
                                :style="{ backgroundColor: fatia.cor }"
                            />

                            <span class="text-xs font-bold text-slate-800">
                                {{ fatia.perfil }}
                            </span>
                        </div>

                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-900">
                                {{ fatia.total }}
                            </span>

                            <span class="ml-1.5 text-[11px] font-semibold text-slate-400">
                                ({{ fatia.percentual.toFixed(1) }}%)
                            </span>
                        </div>
                    </div>

                    <p
                        v-if="totalAvaliadores === 0"
                        class="text-center text-xs text-slate-400"
                    >
                        Nenhum avaliador registrado até o momento.
                    </p>
                </div>

            </div>
        </div>
    </section>
</template>
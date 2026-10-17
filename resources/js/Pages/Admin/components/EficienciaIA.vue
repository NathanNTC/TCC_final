
<script setup>
import { computed } from 'vue'

const props = defineProps({
    eficienciaIA: {
        type: Object,
        default: () => ({
            total_avaliacoes: 0,
            media_geral: null,
            medias: [],
        }),
    },

    eficienciaPorPerfil: {
        type: Array,
        default: () => [],
    },

    comentariosEficiencia: {
        type: Array,
        default: () => [],
    },

    indicadoresPaciente: {
        type: Object,
        default: () => ({
            total_pacientes: 0,
            total_triagens: 0,
            triagens_concluidas: 0,
            percentual_concluidas: 0,
            total_feedbacks: 0,
            media_nota_pacientes: null,
            facilidade: [],
            clareza: [],
            triagens_por_status: [],
            triagens_por_prioridade: [],
        }),
    },
})

const prioridadesIA = [
    { valor: 'emergencial', rotulo: 'Emergencial' },
    { valor: 'muito_urgente', rotulo: 'Muito urgente' },
    { valor: 'urgente', rotulo: 'Urgente' },
    { valor: 'pouco_urgente', rotulo: 'Pouco urgente' },
    { valor: 'autocuidado', rotulo: 'Autocuidado' },
]

const avaliacaoMedia = computed(() => {
    const media = props.eficienciaIA?.media_geral

    return media !== null && media !== undefined
        ? Number(media).toFixed(2)
        : '—'
})

const prioridadesFormatadas = computed(() => {
    const dados = props.indicadoresPaciente?.triagens_por_prioridade ?? []

    const normalizar = (valor) => String(valor ?? '')
        .trim()
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[\s-]+/g, '_')

    return [
        ...prioridadesIA.map((prioridade) => {
            const registro = dados.find((item) => {
                return normalizar(item.categoria) === prioridade.valor
            })

            return {
                categoria: prioridade.rotulo,
                total: Number(registro?.total ?? 0),
                valor: prioridade.valor,
            }
        }),

        {
            categoria: 'Não informada',
            valor: 'nao_informada',
            total: dados
                .filter((item) => {
                    const categoria = normalizar(item.categoria)

                    return [
                        '',
                        'null',
                        'nao_informada',
                        'sem_prioridade',
                        'sem_prioridade_ia',
                    ].includes(categoria)
                })
                .reduce((total, item) => total + Number(item.total ?? 0), 0),
        },
    ]
})

const totalPrioridadesRegistradas = computed(() =>
    prioridadesFormatadas.value.reduce(
        (total, item) => total + item.total,
        0
    )
)

function percentualMedia(media) {
    if (media === null || media === undefined || media === '') {
        return 0
    }

    return Math.max(0, Math.min(100, (Number(media) / 5) * 100))
}

function percentualPrioridade(total) {
    if (totalPrioridadesRegistradas.value === 0) {
        return 0
    }

    return Math.min(
        100,
        (Number(total) / totalPrioridadesRegistradas.value) * 100
    )
}

function formatarMedia(media) {
    return media === null || media === undefined || media === ''
        ? 'Sem avaliações'
        : `${Number(media).toFixed(2)} / 5`
}

function formatarData(data) {
    return data || 'Data não informada'
}

function formatarCategoria(valor) {
    if (!valor) {
        return 'Não informado'
    }

    return String(valor)
        .replaceAll('_', ' ')
        .replace(/\b\w/g, (letra) => letra.toUpperCase())
}
</script>

<template>
    <div class="space-y-6">
        <!-- TÍTULO -->
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">
                
            </h2>

            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Avaliações gerais dos profissionais e indicadores operacionais das triagens.
            </p>
        </div>

        <!-- CARDS PRINCIPAIS -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                    Avaliações de eficiência
                </p>

                <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-slate-100">
                    {{ eficienciaIA.total_avaliacoes }}
                </p>

                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                    Respostas registradas
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                    Média geral da IA
                </p>

                <p class="mt-3 text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                    {{ eficienciaIA.media_geral !== null && eficienciaIA.media_geral !== undefined ? `${avaliacaoMedia} / 5` : '—' }}
                </p>

                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                    Avaliação dos profissionais
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                    Pacientes cadastrados
                </p>

                <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-slate-100">
                    {{ indicadoresPaciente.total_pacientes }}
                </p>

                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                    Contas com perfil de paciente
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                    Triagens concluídas
                </p>

                <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-slate-100">
                    {{ indicadoresPaciente.triagens_concluidas }}
                </p>

                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                    {{ indicadoresPaciente.percentual_concluidas }}% das triagens
                </p>
            </div>
        </div>

        <!-- FEEDBACK DOS PACIENTES -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                    Feedbacks de pacientes
                </p>

                <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-slate-100">
                    {{ indicadoresPaciente.total_feedbacks ?? 0 }}
                </p>

                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                    Respostas sobre a experiência de uso
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                    Nota média dos pacientes
                </p>

                <p class="mt-3 text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                    {{ formatarMedia(indicadoresPaciente.media_nota_pacientes) }}
                </p>

                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                    Avaliação da experiência de utilização
                </p>
            </div>
        </div>

        <!-- MÉDIAS POR CRITÉRIO -->
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
            <div class="mb-5">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                    Avaliação dos critérios da IA
                </h3>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Médias das notas atribuídas pelos avaliadores, em uma escala de 1 a 5.
                </p>
            </div>

            <div v-if="eficienciaIA.medias?.length" class="space-y-5">
                <div
                    v-for="item in eficienciaIA.medias"
                    :key="item.campo"
                >
                    <div class="mb-2 flex flex-wrap items-center justify-between gap-2">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ item.rotulo }}
                        </span>

                        <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                            {{ formatarMedia(item.media) }}
                        </span>
                    </div>

                    <div class="h-2.5 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                        <div
                            class="h-full rounded-full bg-emerald-500 transition-all duration-500"
                            :style="{ width: `${percentualMedia(item.media)}%` }"
                        ></div>
                    </div>
                </div>
            </div>

            <p v-else class="text-sm text-slate-500 dark:text-slate-400">
                Ainda não existem critérios de avaliação disponíveis.
            </p>
        </section>

        <!-- COMPARAÇÃO POR PERFIL -->
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
            <div class="mb-5">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                    Eficiência por perfil profissional
                </h3>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Comparação das avaliações de médicos, residentes e estudantes.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                Perfil
                            </th>

                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                Avaliações
                            </th>

                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                Média geral
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <tr
                            v-for="perfil in eficienciaPorPerfil"
                            :key="perfil.perfil"
                        >
                            <td class="whitespace-nowrap px-4 py-4 text-sm font-medium text-slate-800 dark:text-slate-200">
                                {{ perfil.perfil }}
                            </td>

                            <td class="px-4 py-4 text-center text-sm text-slate-600 dark:text-slate-300">
                                {{ perfil.total }}
                            </td>

                            <td class="px-4 py-4 text-center text-sm font-semibold text-slate-800 dark:text-slate-200">
                                {{ formatarMedia(perfil.media_geral) }}
                            </td>
                        </tr>

                        <tr v-if="!eficienciaPorPerfil.length">
                            <td colspan="3" class="px-4 py-6 text-center text-sm text-slate-500 dark:text-slate-400">
                                Nenhum perfil disponível.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- DISTRIBUIÇÃO DAS TRIAGENS -->
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <!-- STATUS -->
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                    Triagens por status
                </h3>

                <div
                    v-if="indicadoresPaciente.triagens_por_status?.length"
                    class="mt-5 space-y-4"
                >
                    <div
                        v-for="item in indicadoresPaciente.triagens_por_status"
                        :key="item.categoria"
                        class="flex items-center justify-between gap-4"
                    >
                        <span class="text-sm text-slate-600 dark:text-slate-300">
                            {{ formatarCategoria(item.categoria) }}
                        </span>

                        <span class="rounded-lg bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                            {{ item.total }}
                        </span>
                    </div>
                </div>

                <p v-else class="mt-4 text-sm text-slate-500 dark:text-slate-400">
                    Nenhuma triagem registrada.
                </p>
            </section>

            <!-- PRIORIDADES DA IA -->
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                    Triagens por prioridade indicada pela IA
                </h3>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Distribuição das prioridades registradas, sem representar diagnóstico.
                </p>

                <div class="mt-5 space-y-5">
                    <div
                        v-for="item in prioridadesFormatadas"
                        :key="item.valor"
                    >
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ item.categoria }}
                            </span>

                            <span class="rounded-lg bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                {{ item.total }}
                            </span>
                        </div>

                        <div class="h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                            <div
                                class="h-full rounded-full bg-emerald-500 transition-all duration-500"
                                :style="{ width: `${percentualPrioridade(item.total)}%` }"
                            ></div>
                        </div>
                    </div>
                </div>

                <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">
                    Total de triagens contabilizadas nesta distribuição:
                    {{ totalPrioridadesRegistradas }}.
                </p>
            </section>
        </div>

        <!-- COMENTÁRIOS E MELHORIAS -->
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
            <div class="mb-5">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                    Comentários e sugestões de melhoria
                </h3>

                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Observações registradas nas avaliações gerais da IA e nos feedbacks dos pacientes.
                </p>
            </div>

            <div v-if="comentariosEficiencia.length" class="space-y-4">
                <article
                    v-for="comentario in comentariosEficiencia"
                    :key="comentario.id"
                    class="rounded-xl border border-slate-100 p-4 dark:border-slate-800"
                >
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                            {{ comentario.perfil }}
                        </span>

                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            {{ formatarData(comentario.created_at) }}
                        </span>
                    </div>

                    <div v-if="comentario.comentarios" class="mt-3">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            Comentário
                        </p>

                        <p class="mt-1 whitespace-pre-line text-sm leading-relaxed text-slate-700 dark:text-slate-300">
                            {{ comentario.comentarios }}
                        </p>
                    </div>

                    <div v-if="comentario.pontos_melhoria" class="mt-3">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            Pontos de melhoria
                        </p>

                        <p class="mt-1 whitespace-pre-line text-sm leading-relaxed text-slate-700 dark:text-slate-300">
                            {{ comentario.pontos_melhoria }}
                        </p>
                    </div>
                </article>
            </div>

            <p v-else class="text-sm text-slate-500 dark:text-slate-400">
                Ainda não há comentários ou sugestões de melhoria.
            </p>
        </section>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    avaliacoes: {
        type: Array,
        default: () => [],
    },
    filtroPerfil: {
        type: String,
        default: 'todos',
    },
    opcoesPerfil: {
        type: Array,
        default: () => [],
    },
})

const recolhido = ref(false)
const paginaAtual = ref(1)
const porPagina = 10

const totalPaginas = computed(() =>
    Math.max(1, Math.ceil(props.avaliacoes.length / porPagina)),
)

const avaliacoesPaginadas = computed(() => {
    const inicio = (paginaAtual.value - 1) * porPagina
    return props.avaliacoes.slice(inicio, inicio + porPagina)
})

const primeiraAvaliacao = computed(() =>
    props.avaliacoes.length === 0
        ? 0
        : (paginaAtual.value - 1) * porPagina + 1,
)

const ultimaAvaliacao = computed(() =>
    Math.min(
        paginaAtual.value * porPagina,
        props.avaliacoes.length,
    ),
)

watch(
    () => props.avaliacoes,
    () => {
        paginaAtual.value = 1
    },
)

watch(
    () => props.filtroPerfil,
    () => {
        paginaAtual.value = 1
    },
)

function irParaPagina(pagina) {
    if (pagina < 1 || pagina > totalPaginas.value) return
    paginaAtual.value = pagina
}

function valorOpcao(opcao) {
    return typeof opcao === 'string' ? opcao : opcao.value
}

function labelOpcao(opcao) {
    if (typeof opcao === 'string') {
        return formatarPerfil(opcao)
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

function formatarData(data) {
    if (!data) return '—'

    const dataConvertida = new Date(data)

    if (Number.isNaN(dataConvertida.getTime())) {
        return data
    }

    return dataConvertida.toLocaleString('pt-BR', {
        dateStyle: 'short',
        timeStyle: 'short',
    })
}

function formatarTexto(valor) {
    if (valor === null || valor === undefined || valor === '') {
        return 'Não informado'
    }
    return valor
}

function formatarPerfil(perfil) {
    const perfis = {
        todos: 'Todos os perfis',
        Estudante: 'Estudante',
        Medico: 'Médico',
        Residente: 'Residente',
    }

    return perfis[perfil] ?? perfil ?? 'Não informado'
}

function badgePrioridadeClass(prioridade) {
    const estilos = {
        emergencial: 'bg-rose-100 text-rose-800 ring-rose-600/20',
        muito_urgente: 'bg-rose-50 text-rose-700 ring-rose-500/20',
        urgente: 'bg-amber-100 text-amber-800 ring-amber-600/20',
        pouco_urgente: 'bg-amber-50 text-amber-700 ring-amber-500/20',
        nao_emergencial: 'bg-emerald-50 text-emerald-800 ring-emerald-600/20',
        autocuidado: 'bg-emerald-100 text-emerald-800 ring-emerald-600/20',
    }

    return estilos[prioridade] ?? 'bg-slate-100 text-slate-700 ring-slate-500/10'
}

function formatarPrioridade(prioridade) {
    if (!prioridade) return 'Não informada'

    const prioridades = {
        emergencial: 'Emergencial',
        muito_urgente: 'Muito urgente',
        urgente: 'Urgente',
        pouco_urgente: 'Pouco urgente',
        nao_emergencial: 'Não emergencial',
        autocuidado: 'Autocuidado',
    }

    return prioridades[prioridade] ?? prioridade
}

function badgeNotaClass(nota) {
    const valor = Number(nota)
    if (valor >= 4) return 'bg-emerald-50 text-emerald-700 ring-emerald-600/20'
    if (valor === 3) return 'bg-amber-50 text-amber-700 ring-amber-600/20'
    return 'bg-rose-50 text-rose-700 ring-rose-600/20'
}
</script>

<template>
    <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
        
        <!-- CABEÇALHO DO COMPONENTE -->
        <div class="flex items-start justify-between gap-4 border-b border-slate-100 p-6 sm:p-8">
            <div>
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 ring-1 ring-emerald-600/10">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.25-2.142V8.25" />
                        </svg>
                    </div>

                    <h2 class="text-base font-bold text-slate-900">
                        Avaliações registradas
                    </h2>
                </div>

                <p class="mt-1 text-sm leading-relaxed text-slate-500">
                    Consulte os registros detalhados enviados por estudantes, residentes e médicos.
                </p>

                <p class="mt-2 text-xs font-semibold text-slate-400">
                    Total: {{ avaliacoes.length }} {{ avaliacoes.length === 1 ? 'avaliação registrada' : 'avaliações registradas' }}
                </p>
            </div>

            <button
                type="button"
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-slate-300 hover:bg-slate-50 active:scale-95 cursor-pointer"
                :aria-expanded="!recolhido"
                :aria-label="recolhido ? 'Expandir avaliações' : 'Recolher avaliações'"
                :title="recolhido ? 'Expandir avaliações' : 'Recolher avaliações'"
                @click="recolhido = !recolhido"
            >
                <svg
                    class="h-4 w-4 transition-transform duration-200"
                    :class="{ 'rotate-180': recolhido }"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>
        </div>

        <!-- CONTEÚDO EXPANDÍVEL -->
        <div v-if="!recolhido">
            
            <!-- BARRA DE FILTROS -->
            <div class="border-b border-slate-100 bg-slate-50/50 p-5 sm:px-8">
                <div class="flex flex-wrap items-center gap-2">
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

                <p class="mt-3 text-xs text-slate-400">
                    Exibindo perfil: <span class="font-semibold text-slate-700">{{ formatarPerfil(filtroPerfil) }}</span>
                </p>
            </div>

            <!-- GRID DE AVALIAÇÕES -->
            <div
                v-if="avaliacoes.length"
                class="grid grid-cols-1 gap-4 p-6 sm:p-8 lg:grid-cols-2"
            >
                <article
                    v-for="(avaliacao, indice) in avaliacoesPaginadas"
                    :key="avaliacao.id ?? indice"
                    class="flex flex-col justify-between rounded-xl border border-slate-200/80 bg-white p-5 transition-all hover:border-slate-300 hover:shadow-xs"
                >
                    <div>
                        <!-- CABEÇALHO DO CARD -->
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">
                                    Triagem #{{ avaliacao.triagem_id ?? '—' }}
                                </h3>

                                <p class="mt-0.5 text-xs font-medium text-slate-700">
                                    {{ avaliacao.avaliador?.name ?? avaliacao.avaliador_nome ?? 'Avaliador não informado' }}
                                </p>

                                <span class="mt-1 inline-block rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-600">
                                    {{ formatarPerfil(avaliacao.perfil_avaliador ?? avaliacao.perfil) }}
                                </span>
                            </div>

                            <div
                                :class="[
                                    'inline-flex items-center gap-1 rounded-lg px-2.5 py-1 text-xs font-extrabold ring-1 ring-inset',
                                    badgeNotaClass(avaliacao.nota_qualidade ?? avaliacao.qualidade)
                                ]"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3.5 w-3.5 text-amber-400">
                                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                                </svg>
                                <span>Nota {{ avaliacao.nota_qualidade ?? avaliacao.qualidade ?? '—' }}</span>
                            </div>
                        </div>

                        <!-- COMPARAÇÃO DE PRIORIDADES -->
                        <div class="mt-4 grid grid-cols-1 gap-2.5 sm:grid-cols-2">
                            <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                    Prioridade IA
                                </p>
                                <span
                                    :class="[
                                        'mt-1.5 inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold ring-1 ring-inset',
                                        badgePrioridadeClass(avaliacao.prioridade_ia ?? avaliacao.triagem?.prioridade_ia)
                                    ]"
                                >
                                    {{ formatarPrioridade(avaliacao.prioridade_ia ?? avaliacao.triagem?.prioridade_ia) }}
                                </span>
                            </div>

                            <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                    Prioridade Humana
                                </p>
                                <span
                                    :class="[
                                        'mt-1.5 inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold ring-1 ring-inset',
                                        badgePrioridadeClass(avaliacao.prioridade_avaliador ?? avaliacao.prioridade_humana)
                                    ]"
                                >
                                    {{ formatarPrioridade(avaliacao.prioridade_avaliador ?? avaliacao.prioridade_humana) }}
                                </span>
                            </div>
                        </div>

                        <!-- DATA -->
                        <p class="mt-3 text-[11px] text-slate-400">
                            Registrada em: {{ formatarData(avaliacao.created_at) }}
                        </p>
                    </div>

                    <!-- DETALHES EXPANDÍVEIS -->
                    <details class="group mt-4 border-t border-slate-100 pt-3">
                        <summary class="flex cursor-pointer items-center justify-between text-xs font-semibold text-emerald-600 transition hover:text-emerald-700">
                            <span>Ver detalhes da avaliação</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5 transition-transform duration-200 group-open:rotate-180">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </summary>

                        <div class="mt-3 space-y-3 rounded-xl bg-slate-50 p-3.5 text-xs">
                            <div>
                                <p class="font-bold text-slate-800">Justificativa</p>
                                <p class="mt-1 whitespace-pre-line leading-relaxed text-slate-600">
                                    {{ formatarTexto(avaliacao.justificativa) }}
                                </p>
                            </div>

                            <div class="border-t border-slate-200/60 pt-2.5">
                                <p class="font-bold text-slate-800">Observações</p>
                                <p class="mt-1 whitespace-pre-line leading-relaxed text-slate-600">
                                    {{ formatarTexto(avaliacao.observacoes) }}
                                </p>
                            </div>

                            <div class="border-t border-slate-200/60 pt-2.5">
                                <p class="font-bold text-slate-800">Comentário sobre a Qualidade</p>
                                <p class="mt-1 whitespace-pre-line leading-relaxed text-slate-600">
                                    {{ formatarTexto(avaliacao.comentario_qualidade ?? avaliacao.comentario) }}
                                </p>
                            </div>
                        </div>
                    </details>
                </article>
            </div>

            <!-- ESTADO VAZIO -->
            <div v-else class="p-12 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </div>
                <p class="mt-3 text-sm font-bold text-slate-900">Nenhuma avaliação encontrada</p>
                <p class="mt-1 text-xs text-slate-500">Não há registros de avaliações para o perfil selecionado.</p>
            </div>

            <!-- PAGINAÇÃO -->
            <div
                v-if="avaliacoes.length > 0"
                class="flex flex-col gap-3 border-t border-slate-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <p class="text-xs text-slate-500">
                    Exibindo <span class="font-bold text-slate-800">{{ primeiraAvaliacao }}–{{ ultimaAvaliacao }}</span> de <span class="font-bold text-slate-800">{{ avaliacoes.length }}</span> avaliações
                </p>

                <div class="flex items-center gap-2 justify-between sm:justify-end">
                    <button
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-xs transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 cursor-pointer"
                        :disabled="paginaAtual === 1"
                        @click="irParaPagina(paginaAtual - 1)"
                    >
                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m15 18-6-6 6-6" />
                        </svg>
                        Anterior
                    </button>

                    <span class="text-xs font-medium text-slate-600">
                        {{ paginaAtual }} / {{ totalPaginas }}
                    </span>

                    <button
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-xs transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40 cursor-pointer"
                        :disabled="paginaAtual === totalPaginas"
                        @click="irParaPagina(paginaAtual + 1)"
                    >
                        Próxima
                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </button>
                </div>
            </div>

        </div>
    </section>
</template>
<script setup>
defineProps({
    titulo: {
        type: String,
        required: true,
    },

    descricao: {
        type: String,
        default: '',
    },
})
</script>

<template>
    <section class="rounded-2xl border border-slate-200/80 bg-white shadow-xs transition hover:border-slate-300/80">
        <!-- CABEÇALHO -->
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-5 py-4 sm:px-6">
            <div>
                <h2 class="text-base font-semibold text-slate-800">
                    {{ titulo }}
                </h2>

                <p
                    v-if="descricao"
                    class="mt-0.5 text-xs text-slate-500"
                >
                    {{ descricao }}
                </p>
            </div>

            <div v-if="$slots.acao">
                <slot name="acao" />
            </div>
        </div>

        <!-- CONTEÚDO -->
        <div class="p-5 sm:p-6">
            <slot />
        </div>
    </section>
</template>
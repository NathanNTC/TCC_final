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
    <section
        class="rounded-2xl border border-gray-200 bg-white shadow-sm"
    >
        <!-- CABEÇALHO -->
        <div
            class="flex items-center justify-between border-b border-gray-100 px-6 py-5"
        >
            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ titulo }}
                </h2>

                <p
                    v-if="descricao"
                    class="mt-1 text-sm text-gray-500"
                >
                    {{ descricao }}
                </p>
            </div>

            <div>
                <slot name="acao" />
            </div>
        </div>

        <!-- CONTEÚDO -->
        <div class="px-6 py-6">
            <slot />
        </div>
    </section>
</template>
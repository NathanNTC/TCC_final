<script setup>
defineProps({
    titulo: {
        type: String,
        required: true,
    },

    descricao: {
        type: String,
        required: true,
    },

    completo: {
        type: Boolean,
        default: false,
    },

    icone: {
        type: String,
        required: true,
    },
})
</script>

<template>
    <div
        class="group relative overflow-hidden rounded-2xl
               border bg-white p-5 shadow-sm
               transition-all duration-200
               hover:-translate-y-0.5 hover:shadow-md
               dark:bg-slate-900"
        :class="completo 
            ? 'border-emerald-200/80 dark:border-emerald-900/50' 
            : 'border-slate-200/80 dark:border-slate-800'"
    >

        <div class="flex items-start justify-between">

            <!-- ÍCONE -->

            <div
                class="flex h-11 w-11 items-center
                       justify-center rounded-xl"
                :class="
                    completo
                        ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400'
                        : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'
                "
            >
                <svg
                    v-if="icone === 'usuario'"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="h-5 w-5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a8.25 8.25 0 0 1 15 0"
                    />
                </svg>

                <svg
                    v-else-if="icone === 'acessibilidade'"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="h-5 w-5"
                >
                    <circle cx="12" cy="5" r="1.5" />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 9h14M12 9v10M8 21l4-7 4 7"
                    />
                </svg>

                <svg
                    v-else-if="icone === 'saude'"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="h-5 w-5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 21s8-4.5 8-10.5V5.5L12 3 4 5.5v5C4 16.5 12 21 12 21Z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 12h6M12 9v6"
                    />
                </svg>

                <svg
                    v-else-if="icone === 'familia'"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="h-5 w-5"
                >
                    <circle cx="9" cy="7" r="3" />
                    <circle cx="17" cy="9" r="2" />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 20a6 6 0 0 1 12 0M15 16a5 5 0 0 1 6 4"
                    />
                </svg>

                <svg
                    v-else-if="icone === 'alergia'"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="h-5 w-5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 3 4.5 6v5.5c0 4.5 3.2 7.5 7.5 9.5 4.3-2 7.5-5 7.5-9.5V6L12 3Z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 12h6M12 9v6"
                    />
                </svg>

                <svg
                    v-else-if="icone === 'medicamentos'"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="h-5 w-5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m9.5 4.5 10 10a3.536 3.536 0 0 1-5 5l-10-10a3.536 3.536 0 0 1 5-5Z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m7 7 5 5"
                    />
                </svg>

                <svg
                    v-else-if="icone === 'cirurgia'"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="h-5 w-5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m14.5 4.5 5 5M16 3l5 5-3 3-5-5 3-3ZM13 7 4 16l-1 5 5-1 9-9"
                    />
                </svg>

                <svg
                    v-else-if="icone === 'habitos'"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="h-5 w-5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 12h4l2-6 4 12 2-6h6"
                    />
                </svg>
            </div>

            <!-- STATUS -->

            <span
                class="rounded-full px-2.5 py-1
                       text-xs font-semibold ring-1 ring-inset"
                :class="
                    completo
                        ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-950/60 dark:text-emerald-300 dark:ring-emerald-500/30'
                        : 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-950/60 dark:text-amber-300 dark:ring-amber-500/30'
                "
            >
                {{ completo ? 'Completo' : 'Pendente' }}
            </span>

        </div>

        <div class="mt-4">

            <h3 class="font-semibold text-slate-900 dark:text-slate-100">
                {{ titulo }}
            </h3>

            <p class="mt-1 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                {{ descricao }}
            </p>

        </div>

    </div>
</template>
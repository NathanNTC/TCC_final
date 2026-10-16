<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <section class="relative mt-8 overflow-hidden rounded-2xl border border-emerald-100/80 bg-emerald-50/60 shadow-xs dark:border-emerald-900/40 dark:bg-emerald-950/20">
        <!-- CÍRCULO DECORATIVO DE FUNDO -->
        <div class="absolute -right-16 -top-16 h-40 w-40 rounded-full bg-emerald-100/60 dark:bg-emerald-900/20" />

        <div class="relative flex flex-col gap-6 p-6 sm:p-7 md:flex-row md:items-center md:justify-between">
            <div class="max-w-2xl">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-xs dark:bg-emerald-500">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="h-5 w-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 6v12M6 12h12"
                            />
                        </svg>
                    </div>

                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                        Iniciar triagem
                    </h2>
                </div>

                <p class="mt-4 text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                    Utilize suas informações de saúde cadastradas para iniciar uma nova coleta de dados clínicos com auxílio da inteligência artificial.
                </p>
            </div>

            <div class="flex shrink-0 flex-col gap-3 sm:flex-row">
                <!-- NOVA TRIAGEM -->
                <Link
                    href="/triagens/nova"
                    class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-xs transition-all duration-200 hover:bg-emerald-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 active:scale-95 dark:bg-emerald-600 dark:hover:bg-emerald-500"
                >
                    Iniciar triagem

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        class="h-4 w-4"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"
                        />
                    </svg>
                </Link>

                <!-- MINHAS TRIAGENS -->
                <Link
                    href="/triagens"
                    class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-emerald-200/80 bg-white px-6 py-3 text-sm font-semibold text-emerald-800 shadow-xs transition-all duration-200 hover:border-emerald-300 hover:bg-emerald-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 active:scale-95 dark:border-emerald-800/80 dark:bg-slate-900 dark:text-emerald-300 dark:hover:border-emerald-700 dark:hover:bg-slate-800"
                >
                    Minhas triagens

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                        class="h-4 w-4"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z"
                        />
                    </svg>
                </Link>
            </div>
        </div>
    </section>
</template>
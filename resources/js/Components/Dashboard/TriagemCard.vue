<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <section
        class="relative mt-8 overflow-hidden rounded-2xl
               border border-blue-100 bg-blue-50"
    >

        <div
            class="absolute -right-16 -top-16 h-40 w-40
                   rounded-full bg-blue-100/70"
        />

        <div
            class="relative flex flex-col gap-6 p-6
                   sm:p-7 md:flex-row md:items-center
                   md:justify-between"
        >

            <div class="max-w-2xl">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-11 w-11 items-center
                               justify-center rounded-xl
                               bg-blue-600 text-white shadow-sm"
                    >
                        <svg
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
                                d="M12 6v12M6 12h12"
                            />
                        </svg>
                    </div>

                    <h2 class="text-lg font-semibold text-slate-900">
                        Iniciar triagem
                    </h2>

                </div>

                <p
                    class="mt-4 text-sm leading-relaxed
                           text-slate-600"
                >
                    Utilize suas informações de saúde cadastradas para
                    iniciar uma nova coleta de dados clínicos com auxílio
                    da inteligência artificial.
                </p>

            </div>

            <Link
                href="/triagem"
                class="inline-flex shrink-0 items-center
                       justify-center gap-2 rounded-xl
                       bg-blue-600 px-6 py-3 text-sm
                       font-semibold text-white shadow-sm
                       transition-all duration-200
                       hover:bg-blue-700 hover:shadow-md
                       focus:outline-none focus:ring-2
                       focus:ring-blue-500 focus:ring-offset-2"
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

        </div>

    </section>
</template>
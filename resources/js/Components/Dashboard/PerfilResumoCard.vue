<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    perfilCompletude: {
        type: Number,
        default: 0,
    },
})
</script>

<template>
    <section
        class="overflow-hidden rounded-2xl border
               border-slate-200 bg-white shadow-sm"
    >
        <div class="p-6 sm:p-7">

            <!-- =========================================================
                 CABEÇALHO DO CARD
            ========================================================== -->

            <div
                class="flex flex-col gap-6
                       md:flex-row md:items-center
                       md:justify-between"
            >

                <!-- INFORMAÇÕES -->

                <div class="max-w-2xl">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-11 w-11 items-center
                                   justify-center rounded-xl
                                   bg-blue-50 text-blue-600"
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
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a8.25 8.25 0 0 1 15 0"
                                />
                            </svg>
                        </div>

                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 class="text-lg font-semibold text-slate-900">
                                    Perfil de saúde
                                </h2>
                                <span class="text-xs font-semibold text-slate-950 bg-amber-400 border border-black rounded px-2 py-0.5 shadow-sm">
                                    (utilizaremos esses dados apenas para optimização da coleta e triagem conversacional)
                                </span>
                            </div>

                            <p class="text-sm text-slate-500">
                                Suas informações pessoais e de saúde.
                            </p>
                        </div>

                    </div>

                    <p
                        class="mt-5 text-sm leading-relaxed text-slate-500"
                    >
                        Mantenha suas informações atualizadas para melhorar a
                        coleta de dados durante a triagem.
                    </p>

                </div>

                <!-- =====================================================
                     PERCENTUAL
                ====================================================== -->

                <div class="shrink-0 md:text-right">

                    <div
                        class="flex items-baseline gap-1
                               md:justify-end"
                    >
                        <span
                            class="text-4xl font-bold tracking-tight
                                   text-slate-900"
                        >
                            
                        </span>

                        <span
                            class="text-xl font-semibold text-slate-400"
                        >
                            
                        </span>
                    </div>

                    <p class="mt-1 text-sm text-slate-500">
                        
                    </p>

                </div>

            </div>

            <!-- =========================================================
                 BARRA DE PROGRESSO
            ========================================================== -->

            <div class="mt-7">

                <div
                    class="mb-2 flex items-center justify-between"
                >
                    <span
                        class="text-xs font-medium text-slate-500"
                    >
                        Progresso do perfil
                    </span>

                    <span
                        class="text-xs font-semibold text-slate-700"
                    >
                        {{ perfilCompletude }}%
                    </span>
                </div>

                <div
                    class="h-2.5 w-full overflow-hidden
                           rounded-full bg-slate-100"
                >
                    <div
                        class="h-full rounded-full bg-blue-600
                               transition-all duration-700"
                        :style="{
                            width: `${Math.min(
                                Math.max(perfilCompletude, 0),
                                100
                            )}%`
                        }"
                    />
                </div>

            </div>

            <!-- =========================================================
                 AÇÕES
            ========================================================== -->

            <div
                class="mt-7 flex flex-wrap gap-3
                       border-t border-slate-100 pt-6"
            >

                <!-- =====================================================
                     VISUALIZAR PERFIL
                     Mesmo layout do botão Completar Perfil
                ====================================================== -->

                <Link
                    href="/perfil"
                    class="inline-flex items-center justify-center
                           gap-2 rounded-xl border border-slate-300
                           bg-white px-5 py-2.5 text-sm font-semibold
                           text-slate-700 shadow-sm transition-all
                           duration-200 hover:border-slate-400
                           hover:bg-slate-50 hover:shadow-md
                           focus:outline-none focus:ring-2
                           focus:ring-slate-400 focus:ring-offset-2"
                >
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
                            d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"
                        />

                        <circle
                            cx="12"
                            cy="12"
                            r="2.5"
                        />
                    </svg>

                    Visualizar perfil
                </Link>

              

            </div>

        </div>
    </section>
</template>

<template>
    <aside class="flex items-start gap-3 rounded-2xl border border-amber-200/80 bg-amber-50/60 p-4 text-xs text-amber-900 dark:border-amber-900/40 dark:bg-amber-950/20 dark:text-amber-200">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.8"
            stroke="currentColor"
            class="mt-0.5 h-5 w-5 shrink-0 text-amber-600 dark:text-amber-400"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z"
            />
        </svg>

        <div>
            <h3 class="font-bold">
                Sobre os custos apresentados
            </h3>

            <p class="mt-0.5 leading-relaxed text-amber-800/90 dark:text-amber-300/80">
                Os valores exibidos são estimativas calculadas com base nos registros
                de consumo e nos preços configurados para os modelos utilizados.
                Eles podem apresentar diferenças em relação à cobrança real do provedor.
                O painel serve para acompanhar o consumo da API e auxiliar no controle
                dos custos do sistema.
            </p>
        </div>
    </aside>
</template>
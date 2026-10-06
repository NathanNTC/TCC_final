<script setup>
import { computed, ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import DadosPessoaisCard from '@/Components/Perfil/DadosPessoaisCard.vue'
import DeficienciasCard from '@/Components/Perfil/DeficienciasCard.vue'
import ProblemasSaudeCard from '@/Components/Perfil/ProblemasSaudeCard.vue'
import HistoricoFamiliarCard from '@/Components/Perfil/HistoricoFamiliarCard.vue'
import AlergiasCard from '@/Components/Perfil/AlergiasCard.vue'
import MedicamentosCard from '@/Components/Perfil/MedicamentosCard.vue'
import CirurgiasCard from '@/Components/Perfil/CirurgiasCard.vue'
import HabitosCard from '@/Components/Perfil/HabitosCard.vue'

const props = defineProps({
    paciente: {
        type: Object,
        required: true,
    },

    perfilSaude: {
        type: Object,
        default: () => ({}),
    },

    medicamentos: {
        type: Array,
        default: () => [],
    },

    cirurgias: {
        type: Array,
        default: () => [],
    },
})

/*
|--------------------------------------------------------------------------
| PERFIL
|--------------------------------------------------------------------------
*/

const perfilSaudeSeguro = computed(() => props.perfilSaude ?? {})

/*
|--------------------------------------------------------------------------
| USUÁRIO
|--------------------------------------------------------------------------
*/

const primeiroNome = computed(() => {
    return props.paciente?.name?.split(' ')[0] || 'Paciente'
})

const inicialNome = computed(() => {
    return props.paciente?.name?.charAt(0)?.toUpperCase() || 'P'
})

/*
|--------------------------------------------------------------------------
| TIPO SANGUÍNEO
|--------------------------------------------------------------------------
*/

const editandoTipoSanguineo = ref(false)

const tipoSanguineo = ref(
    props.perfilSaude?.tipo_sanguineo || ''
)

const tiposSanguineos = [
    'A+',
    'A-',
    'B+',
    'B-',
    'AB+',
    'AB-',
    'O+',
    'O-',
]

const salvarTipoSanguineo = () => {
    router.patch(
        '/perfil/tipo-sanguineo',
        {
            tipo_sanguineo: tipoSanguineo.value || null,
        },
        {
            preserveScroll: true,

            onSuccess: () => {
                editandoTipoSanguineo.value = false
            },
        }
    )
}

const cancelarEdicaoTipoSanguineo = () => {
    tipoSanguineo.value =
        props.perfilSaude?.tipo_sanguineo || ''

    editandoTipoSanguineo.value = false
}

/*
|--------------------------------------------------------------------------
| CARDS
|--------------------------------------------------------------------------
*/

const cardClasses =
    'overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md'
</script>

<template>
    <Head title="Perfil de Saúde" />

    <AuthenticatedLayout>

        <div class="min-h-screen bg-slate-50/70 pb-16">

            <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">

                <!-- =====================================================
                     BANNER HEADER
                ====================================================== -->

                <div
                    class="relative mb-8 overflow-hidden rounded-3xl
                           bg-gradient-to-r from-emerald-600
                           via-emerald-700 to-teal-800
                           p-6 text-white shadow-lg
                           shadow-emerald-900/10 sm:p-8"
                >

                    <!-- TEXTURA -->

                    <div
                        class="pointer-events-none absolute -right-10 -top-10
                               h-64 w-64 rounded-full bg-white/5 blur-2xl"
                    ></div>

                    <div
                        class="pointer-events-none absolute -bottom-12
                               right-1/3 h-48 w-48 rounded-full
                               bg-emerald-400/10 blur-xl"
                    ></div>


                    <!-- CONTEÚDO -->

                    <div
                        class="relative z-10 flex flex-col gap-6
                               sm:flex-row sm:items-center
                               sm:justify-between"
                    >

                        <!-- =================================================
                             IDENTIDADE
                        ================================================== -->

                        <div class="flex items-center gap-4">

                            <!-- AVATAR -->

                            <div
                                class="flex h-14 w-14 shrink-0
                                       items-center justify-center
                                       rounded-2xl bg-white/10
                                       text-xl font-bold text-white
                                       shadow-inner backdrop-blur-md
                                       ring-1 ring-white/20"
                            >
                                {{ inicialNome }}
                            </div>


                            <!-- INFORMAÇÕES -->

                            <div>

                                <div
                                    class="flex items-center gap-2"
                                >

                                    <h1
                                        class="text-2xl font-bold
                                               tracking-tight text-white
                                               sm:text-3xl"
                                    >
                                        Olá, {{ primeiroNome }}
                                    </h1>

                                    <span
                                        class="inline-flex items-center
                                               rounded-full
                                               bg-emerald-400/20
                                               px-2.5 py-0.5
                                               text-xs font-semibold
                                               text-emerald-100
                                               ring-1 ring-inset
                                               ring-emerald-300/30"
                                    >
                                        Prontuário Ativo
                                    </span>

                                </div>

                                <p
                                    class="mt-1 text-sm
                                           text-emerald-100/80"
                                >
                                    Acompanhe seu histórico clínico,
                                    medicamentos e hábitos de saúde
                                    em um só lugar.
                                </p>

                            </div>

                        </div>


                        <!-- =================================================
                             INDICADORES
                        ================================================== -->

                        <div
                            class="flex flex-wrap items-center gap-3
                                   sm:flex-nowrap"
                        >

                            <!-- =============================================
                                 TIPO SANGUÍNEO
                            ============================================== -->

                            <div
                                class="flex items-center gap-3
                                       rounded-2xl bg-white/10
                                       px-4 py-2.5
                                       backdrop-blur-md
                                       ring-1 ring-white/15"
                            >

                                <span
                                    class="text-xs font-medium
                                           uppercase tracking-wider
                                           text-emerald-200"
                                >
                                    Tipo sanguíneo
                                </span>


                                <!-- VISUALIZAÇÃO -->

                                <button
                                    v-if="!editandoTipoSanguineo"
                                    type="button"
                                    @click="editandoTipoSanguineo = true"
                                    class="group flex items-center gap-2
                                           text-base font-bold text-white
                                           transition-colors
                                           hover:text-emerald-100"
                                >

                                    <span>
                                        {{ tipoSanguineo || 'N/I' }}
                                    </span>

                                    <!-- ÍCONE EDITAR -->

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                        stroke="currentColor"
                                        class="h-3.5 w-3.5
                                               opacity-60
                                               transition-opacity
                                               group-hover:opacity-100"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652l-8.25 8.25-3.22.537.537-3.22 6.594-6.531Z"
                                        />
                                    </svg>

                                </button>


                                <!-- EDIÇÃO -->

                                <div
                                    v-else
                                    class="flex items-center gap-2"
                                >

                                    <select
                                        v-model="tipoSanguineo"
                                        class="rounded-lg border-0
                                               bg-white px-2 py-1
                                               text-sm font-bold
                                               text-slate-800 shadow-sm
                                               focus:ring-2
                                               focus:ring-emerald-300"
                                    >

                                        <option value="">
                                            Não informado
                                        </option>

                                        <option
                                            v-for="tipo in tiposSanguineos"
                                            :key="tipo"
                                            :value="tipo"
                                        >
                                            {{ tipo }}
                                        </option>

                                    </select>


                                    <!-- SALVAR -->

                                    <button
                                        type="button"
                                        @click="salvarTipoSanguineo"
                                        class="rounded-lg bg-white/15
                                               px-2.5 py-1 text-xs
                                               font-semibold text-white
                                               transition-colors
                                               hover:bg-white/25"
                                    >
                                        Salvar
                                    </button>


                                    <!-- CANCELAR -->

                                    <button
                                        type="button"
                                        @click="cancelarEdicaoTipoSanguineo"
                                        class="text-xs font-medium
                                               text-white/70
                                               transition-colors
                                               hover:text-white"
                                    >
                                        Cancelar
                                    </button>

                                </div>

                            </div>


                            <!-- =============================================
                                 MEDICAMENTOS
                            ============================================== -->

                            <div
                                class="flex items-center gap-3
                                       rounded-2xl bg-white/10
                                       px-4 py-2.5
                                       backdrop-blur-md
                                       ring-1 ring-white/15"
                            >

                                <span
                                    class="text-xs font-medium
                                           uppercase tracking-wider
                                           text-emerald-200"
                                >
                                    Medicamentos
                                </span>

                                <span
                                    class="text-base font-bold text-white"
                                >
                                    {{ medicamentos.length }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- =====================================================
                     LISTA DE CARDS
                ====================================================== -->

                <div class="flex flex-col gap-6">

                    <!-- DADOS PESSOAIS -->

                    <DadosPessoaisCard
                        :paciente="paciente"
                        :perfil-saude="perfilSaudeSeguro"
                        :class="cardClasses"
                    />


                    <!-- HÁBITOS -->

                    <HabitosCard
                        :perfil-saude="perfilSaudeSeguro"
                        :class="cardClasses"
                    />


                    <!-- ALERGIAS -->

                    <AlergiasCard
                        :perfil-saude="perfilSaudeSeguro"
                        :class="cardClasses"
                    />


                    <!-- CONDIÇÕES / PROBLEMAS DE SAÚDE -->

                    <ProblemasSaudeCard
                        :perfil-saude="perfilSaudeSeguro"
                        :class="cardClasses"
                    />


                    <!-- MEDICAMENTOS -->

                    <MedicamentosCard
                        :medicamentos="medicamentos"
                        :class="cardClasses"
                    />


                    <!-- CIRURGIAS -->

                    <CirurgiasCard
                        :cirurgias="cirurgias"
                        :class="cardClasses"
                    />


                    <!-- DEFICIÊNCIAS / ACESSIBILIDADE -->

                    <DeficienciasCard
                        :perfil-saude="perfilSaudeSeguro"
                        :class="cardClasses"
                    />


                    <!-- HISTÓRICO FAMILIAR -->

                    <HistoricoFamiliarCard
                        :perfil-saude="perfilSaudeSeguro"
                        :class="cardClasses"
                    />

                </div>

            </div>

        </div>

    </AuthenticatedLayout>
</template>
```

### Agora falta apenas o backend

Esse Vue chama:

```text
PATCH /perfil/tipo-sanguineo
```

Então você precisa ter a rota correspondente. Se **essa rota ainda não existe**, o campo vai aparecer e permitir selecionar, mas ao clicar em **Salvar** o Laravel vai retornar `404`.

No seu `routes/web.php`:

```php
use App\Http\Controllers\PerfilController;

Route::patch(
    '/perfil/tipo-sanguineo',
    [PerfilController::class, 'atualizarTipoSanguineo']
)->name('perfil.tipo-sanguineo');
```

E no `PerfilController`:

```php
public function atualizarTipoSanguineo(Request $request)
{
    $validated = $request->validate([
        'tipo_sanguineo' => [
            'nullable',
            'in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        ],
    ]);

    $paciente = auth()->user()->paciente;

    $paciente->perfilSaude()->updateOrCreate(
        [],
        [
            'tipo_sanguineo' => $validated['tipo_sanguineo'] ?? null,
        ]
    );

    return back();
}
```

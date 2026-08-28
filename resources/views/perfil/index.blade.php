<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Perfil do Paciente
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong>Corrija os seguintes erros:</strong>

                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('perfil.store') }}">
                @csrf

                {{-- Dados pessoais --}}
                @include('perfil.components.dados-pessoais')

                {{-- Localização --}}
                @include('perfil.components.localizacao')

                {{-- Informações de saúde --}}
                @include('perfil.components.inf_saude.deficiencias')

                @include('perfil.components.inf_saude.problemas-de-saude-previos')

                @include('perfil.components.inf_saude.historico-familiar')

                @include('perfil.components.inf_saude.medicamentos')

                @include('perfil.components.inf_saude.alergias')

                {{-- Hábitos --}}
                @include('perfil.components.habitos')

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="px-6 py-3 bg-gray-800 text-white rounded-md hover:bg-gray-700"
                    >
                        Salvar perfil
                    </button>
                </div>

            </form>

        </div>
    </div>

</x-app-layout>
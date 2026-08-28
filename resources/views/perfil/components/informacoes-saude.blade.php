<div class="bg-white shadow-sm rounded-lg p-6 mb-6">

    <h3 class="text-lg font-semibold text-gray-800 mb-6">
        Informações de saúde
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Tipo sanguíneo --}}
        <div>
            <label
                for="tipo_sanguineo"
                class="block text-sm font-medium text-gray-700"
            >
                Tipo sanguíneo
            </label>

            <select
                name="tipo_sanguineo"
                id="tipo_sanguineo"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            >
                <option value="">Não informado</option>

                @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $tipo)

                    <option
                        value="{{ $tipo }}"
                        {{ old('tipo_sanguineo', $perfilSaude?->tipo_sanguineo) === $tipo ? 'selected' : '' }}
                    >
                        {{ $tipo }}
                    </option>

                @endforeach

            </select>
        </div>

        {{-- Altura --}}
        <div>

            <label
                for="altura"
                class="block text-sm font-medium text-gray-700"
            >
                Altura (metros)
            </label>

            <input
                type="number"
                name="altura"
                id="altura"
                step="0.01"
                min="0.5"
                max="3"
                value="{{ old('altura', $perfilSaude?->altura) }}"
                placeholder="Ex.: 1.75"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            >

        </div>

        {{-- Peso --}}
        <div>

            <label
                for="peso"
                class="block text-sm font-medium text-gray-700"
            >
                Peso (kg)
            </label>

            <input
                type="number"
                name="peso"
                id="peso"
                step="0.01"
                min="1"
                max="500"
                value="{{ old('peso', $perfilSaude?->peso) }}"
                placeholder="Ex.: 75.5"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            >

        </div>

    </div>

    {{-- Deficiências --}}
    <div class="mt-6">

        <label
            for="deficiencias"
            class="block text-sm font-medium text-gray-700"
        >
            Deficiências
        </label>

        <textarea
            name="deficiencias"
            id="deficiencias"
            rows="3"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
        >{{ old('deficiencias', $perfilSaude?->deficiencias) }}</textarea>

    </div>

    {{-- Problemas de saúde --}}
    <div class="mt-6">

        <label
            for="problemas_saude_previos"
            class="block text-sm font-medium text-gray-700"
        >
            Problemas de saúde prévios
        </label>

        <textarea
            name="problemas_saude_previos"
            id="problemas_saude_previos"
            rows="3"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
        >{{ old('problemas_saude_previos', $perfilSaude?->problemas_saude_previos) }}</textarea>

    </div>

    {{-- Histórico familiar --}}
    <div class="mt-6">

        <label
            for="historico_familiar"
            class="block text-sm font-medium text-gray-700"
        >
            Histórico familiar
        </label>

        <textarea
            name="historico_familiar"
            id="historico_familiar"
            rows="3"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
        >{{ old('historico_familiar', $perfilSaude?->historico_familiar) }}</textarea>

    </div>

    {{-- Medicamentos --}}
    <div class="mt-6">

        <label
            for="medicacoes_uso_continuo"
            class="block text-sm font-medium text-gray-700"
        >
            Medicamentos de uso contínuo
        </label>

        <textarea
            name="medicacoes_uso_continuo"
            id="medicacoes_uso_continuo"
            rows="3"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
        >{{ old('medicacoes_uso_continuo', $perfilSaude?->medicacoes_uso_continuo) }}</textarea>

    </div>

    {{-- Alergias --}}
    <div class="mt-6">

        <label
            for="alergias"
            class="block text-sm font-medium text-gray-700"
        >
            Alergias
        </label>

        <textarea
            name="alergias"
            id="alergias"
            rows="3"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
        >{{ old('alergias', $perfilSaude?->alergias) }}</textarea>

    </div>

    {{-- Cirurgias --}}
    <div class="mt-6">

        <label
            for="cirurgias_previas"
            class="block text-sm font-medium text-gray-700"
        >
            Cirurgias prévias
        </label>

        <textarea
            name="cirurgias_previas"
            id="cirurgias_previas"
            rows="3"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
        >{{ old('cirurgias_previas', $perfilSaude?->cirurgias_previas) }}</textarea>

    </div>

</div>
<div class="bg-white shadow-sm rounded-lg p-6 mb-6">

    <h3 class="text-lg font-semibold text-gray-800 mb-6">
        Hábitos e estilo de vida
    </h3>

    <div class="space-y-4">

        <label class="flex items-center">

            <input
                type="hidden"
                name="fumante"
                value="0"
            >

            <input
                type="checkbox"
                name="fumante"
                value="1"
                {{ old('fumante', $perfilSaude?->fumante) ? 'checked' : '' }}
                class="rounded border-gray-300"
            >

            <span class="ml-2">
                Fumante
            </span>

        </label>


        <label class="flex items-center">

            <input
                type="hidden"
                name="alcoolismo"
                value="0"
            >

            <input
                type="checkbox"
                name="alcoolismo"
                value="1"
                {{ old('alcoolismo', $perfilSaude?->alcoolismo) ? 'checked' : '' }}
                class="rounded border-gray-300"
            >

            <span class="ml-2">
                Consome bebidas alcoólicas
            </span>

        </label>


        <label class="flex items-center">

            <input
                type="hidden"
                name="uso_drogas"
                value="0"
            >

            <input
                type="checkbox"
                name="uso_drogas"
                value="1"
                {{ old('uso_drogas', $perfilSaude?->uso_drogas) ? 'checked' : '' }}
                class="rounded border-gray-300"
            >

            <span class="ml-2">
                Faz uso de drogas
            </span>

        </label>


        <label class="flex items-center">

            <input
                type="hidden"
                name="atividade_fisica_regular"
                value="0"
            >

            <input
                type="checkbox"
                name="atividade_fisica_regular"
                value="1"
                {{ old('atividade_fisica_regular', $perfilSaude?->atividade_fisica_regular) ? 'checked' : '' }}
                class="rounded border-gray-300"
            >

            <span class="ml-2">
                Pratica atividade física regularmente
            </span>

        </label>

    </div>

</div>
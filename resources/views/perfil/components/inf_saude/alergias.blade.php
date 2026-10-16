<div class="mt-8">

    <h3 class="text-lg font-semibold text-gray-800">
        Alergias
    </h3>

    <p class="mt-1 mb-4 text-sm text-gray-600">
        Informe alergias conhecidas.
    </p>

    @php
        $alergiasSelecionadas = old(
            'alergias',
            $perfilSaude?->alergias ?? []
        );
    @endphp

    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">

        <label class="flex items-center gap-2">
            <input
                type="checkbox"
                name="alergias[]"
                value="medicamentosa"
                class="alergia-checkbox rounded border-gray-300"
                @checked(in_array('medicamentosa', $alergiasSelecionadas))
            >
            <span>Medicamentos</span>
        </label>

        <label class="flex items-center gap-2">
            <input
                type="checkbox"
                name="alergias[]"
                value="alimentar"
                class="alergia-checkbox rounded border-gray-300"
                @checked(in_array('alimentar', $alergiasSelecionadas))
            >
            <span>Alimentar</span>
        </label>

        <label class="flex items-center gap-2">
            <input
                type="checkbox"
                name="alergias[]"
                value="ambiental"
                class="alergia-checkbox rounded border-gray-300"
                @checked(in_array('ambiental', $alergiasSelecionadas))
            >
            <span>Ambiental/Outras</span>
        </label>

        <label class="flex items-center gap-2">
            <input
                type="checkbox"
                name="alergias[]"
                value="nao_possui"
                id="alergia-nao-possui"
                class="alergia-checkbox rounded border-gray-300"
                @checked(in_array('nao_possui', $alergiasSelecionadas))
            >
            <span>Não possui alergias conhecidas</span>
        </label>

    </div>

    <div class="mt-4">

        <label
            for="alergias_detalhes"
            class="block text-sm font-medium text-gray-700"
        >
            Detalhes das alergias
        </label>

        <textarea
            name="alergias_detalhes"
            id="alergias_detalhes"
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            placeholder="Informe o agente causador e qual reação ocorre. Ex.: Dipirona — urticária..."
        >{{ old('alergias_detalhes', $perfilSaude?->alergias_detalhes) }}</textarea>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const naoPossui = document.getElementById('alergia-nao-possui');

    const checkboxes = document.querySelectorAll('.alergia-checkbox');

    naoPossui.addEventListener('change', function () {

        if (this.checked) {

            checkboxes.forEach(function (checkbox) {

                if (checkbox !== naoPossui) {
                    checkbox.checked = false;
                }

            });

        }

    });

    checkboxes.forEach(function (checkbox) {

        checkbox.addEventListener('change', function () {

            if (this.checked && this !== naoPossui) {
                naoPossui.checked = false;
            }

        });

    });

});
</script>
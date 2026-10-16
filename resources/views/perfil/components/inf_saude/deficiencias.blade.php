<div class="mt-8">

    <h3 class="text-lg font-semibold text-gray-800">
        Deficiências
    </h3>

    <p class="mt-1 mb-4 text-sm text-gray-600">
        Selecione as opções que se aplicam.
    </p>

    @php
        $deficienciasSelecionadas = old(
            'deficiencias',
            $perfilSaude?->deficiencias ?? []
        );
    @endphp

    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">

        <label class="flex items-center gap-2">
            <input
                type="checkbox"
                name="deficiencias[]"
                value="fisica"
                class="deficiencia-checkbox rounded border-gray-300"
                @checked(in_array('fisica', $deficienciasSelecionadas))
            >
            <span>Física</span>
        </label>

        <label class="flex items-center gap-2">
            <input
                type="checkbox"
                name="deficiencias[]"
                value="visual"
                class="deficiencia-checkbox rounded border-gray-300"
                @checked(in_array('visual', $deficienciasSelecionadas))
            >
            <span>Visual</span>
        </label>

        <label class="flex items-center gap-2">
            <input
                type="checkbox"
                name="deficiencias[]"
                value="auditiva"
                class="deficiencia-checkbox rounded border-gray-300"
                @checked(in_array('auditiva', $deficienciasSelecionadas))
            >
            <span>Auditiva</span>
        </label>

        <label class="flex items-center gap-2">
            <input
                type="checkbox"
                name="deficiencias[]"
                value="intelectual_cognitiva"
                class="deficiencia-checkbox rounded border-gray-300"
                @checked(in_array('intelectual_cognitiva', $deficienciasSelecionadas))
            >
            <span>Intelectual/Cognitiva</span>
        </label>

        <label class="flex items-center gap-2">
            <input
                type="checkbox"
                name="deficiencias[]"
                value="psicossocial"
                class="deficiencia-checkbox rounded border-gray-300"
                @checked(in_array('psicossocial', $deficienciasSelecionadas))
            >
            <span>Psicossocial</span>
        </label>

        <label class="flex items-center gap-2">
            <input
                type="checkbox"
                name="deficiencias[]"
                value="nao_possui"
                id="deficiencia-nao-possui"
                class="deficiencia-checkbox rounded border-gray-300"
                @checked(in_array('nao_possui', $deficienciasSelecionadas))
            >
            <span>Não possui</span>
        </label>

        <label class="flex items-center gap-2">
            <input
                type="checkbox"
                name="deficiencias[]"
                value="outros"
                id="deficiencia-outros"
                class="deficiencia-checkbox rounded border-gray-300"
                @checked(in_array('outros', $deficienciasSelecionadas))
            >
            <span>Outros</span>
        </label>

    </div>

    <div
        id="deficiencia-outros-container"
        class="mt-4 hidden"
    >
        <label
            for="deficiencia_outros_descricao"
            class="block text-sm font-medium text-gray-700"
        >
            Especifique
        </label>

        <input
            type="text"
            name="deficiencia_outros_descricao"
            id="deficiencia_outros_descricao"
            value="{{ old('deficiencia_outros_descricao', $perfilSaude?->deficiencia_outros_descricao) }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            placeholder="Descreva a deficiência"
        >
    </div>

    <div class="mt-4">
        <label
            for="necessidades_acessibilidade"
            class="block text-sm font-medium text-gray-700"
        >
            Necessidades de acessibilidade
        </label>

        <textarea
            name="necessidades_acessibilidade"
            id="necessidades_acessibilidade"
            rows="3"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            placeholder="Informe alguma necessidade específica de acessibilidade..."
        >{{ old('necessidades_acessibilidade', $perfilSaude?->necessidades_acessibilidade) }}</textarea>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const outros = document.getElementById('deficiencia-outros');
    const outrosContainer = document.getElementById('deficiencia-outros-container');
    const naoPossui = document.getElementById('deficiencia-nao-possui');

    const checkboxes = document.querySelectorAll('.deficiencia-checkbox');

    function atualizarOutros() {
        if (outros.checked) {
            outrosContainer.classList.remove('hidden');
        } else {
            outrosContainer.classList.add('hidden');
        }
    }

    outros.addEventListener('change', atualizarOutros);

    naoPossui.addEventListener('change', function () {

        if (this.checked) {

            checkboxes.forEach(function (checkbox) {
                if (checkbox !== naoPossui) {
                    checkbox.checked = false;
                }
            });

            atualizarOutros();
        }

    });

    checkboxes.forEach(function (checkbox) {

        checkbox.addEventListener('change', function () {

            if (this.checked && this !== naoPossui) {
                naoPossui.checked = false;
            }

        });

    });

    atualizarOutros();

});
</script>
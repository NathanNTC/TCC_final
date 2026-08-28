<div class="mt-8">

    <h3 class="text-lg font-semibold text-gray-800">
        Medicamentos de uso contínuo
    </h3>

    <p class="mt-1 mb-4 text-sm text-gray-600">
        Informe medicamentos, suplementos, vitaminas ou fitoterápicos utilizados regularmente.
    </p>

    <div id="medicamentos-container">

        <div class="medicamento-item rounded-lg border border-gray-200 p-4 mb-4">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Nome do medicamento
                    </label>

                    <input
                        type="text"
                        name="medicamentos[0][nome]"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        placeholder="Ex.: Losartana"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Dosagem
                    </label>

                    <input
                        type="text"
                        name="medicamentos[0][dosagem]"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        placeholder="Ex.: 50 mg"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Frequência
                    </label>

                    <input
                        type="text"
                        name="medicamentos[0][frequencia]"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        placeholder="Ex.: 1 vez ao dia"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Indicação
                    </label>

                    <input
                        type="text"
                        name="medicamentos[0][indicacao]"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        placeholder="Ex.: Hipertensão"
                    >
                </div>

            </div>

        </div>

    </div>

    <button
        type="button"
        id="adicionar-medicamento"
        class="rounded-md bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300"
    >
        + Adicionar medicamento
    </button>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('medicamentos-container');

    const botao = document.getElementById('adicionar-medicamento');

    let contador = 1;

    botao.addEventListener('click', function () {

        const medicamento = document.createElement('div');

        medicamento.className =
            'medicamento-item rounded-lg border border-gray-200 p-4 mb-4';

        medicamento.innerHTML = `
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Nome do medicamento
                    </label>

                    <input
                        type="text"
                        name="medicamentos[${contador}][nome]"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        placeholder="Ex.: Losartana"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Dosagem
                    </label>

                    <input
                        type="text"
                        name="medicamentos[${contador}][dosagem]"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        placeholder="Ex.: 50 mg"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Frequência
                    </label>

                    <input
                        type="text"
                        name="medicamentos[${contador}][frequencia]"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        placeholder="Ex.: 1 vez ao dia"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Indicação
                    </label>

                    <input
                        type="text"
                        name="medicamentos[${contador}][indicacao]"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        placeholder="Ex.: Hipertensão"
                    >
                </div>

            </div>

            <button
                type="button"
                class="remover-medicamento mt-3 text-sm text-red-600 hover:text-red-800"
            >
                Remover
            </button>
        `;

        container.appendChild(medicamento);

        contador++;

    });

    container.addEventListener('click', function (event) {

        if (event.target.classList.contains('remover-medicamento')) {

            event.target
                .closest('.medicamento-item')
                .remove();

        }

    });

});
</script>
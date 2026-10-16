<div class="bg-white shadow-sm rounded-lg p-6 mb-6">

    <h3 class="text-lg font-semibold text-gray-800 mb-6">
        Localização
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Estado --}}
        <div>

            <label
                for="estado"
                class="block text-sm font-medium text-gray-700"
            >
                Estado
            </label>

            <select
                name="estado"
                id="estado"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                required
            >
                <option value="">
                    Selecione o estado
                </option>

                <option value="AC">Acre</option>
                <option value="AL">Alagoas</option>
                <option value="AP">Amapá</option>
                <option value="AM">Amazonas</option>
                <option value="BA">Bahia</option>
                <option value="CE">Ceará</option>
                <option value="DF">Distrito Federal</option>
                <option value="ES">Espírito Santo</option>
                <option value="GO">Goiás</option>
                <option value="MA">Maranhão</option>
                <option value="MT">Mato Grosso</option>
                <option value="MS">Mato Grosso do Sul</option>
                <option value="MG">Minas Gerais</option>
                <option value="PA">Pará</option>
                <option value="PB">Paraíba</option>
                <option value="PR">Paraná</option>
                <option value="PE">Pernambuco</option>
                <option value="PI">Piauí</option>
                <option value="RJ">Rio de Janeiro</option>
                <option value="RN">Rio Grande do Norte</option>
                <option value="RS">Rio Grande do Sul</option>
                <option value="RO">Rondônia</option>
                <option value="RR">Roraima</option>
                <option value="SC">Santa Catarina</option>
                <option value="SP">São Paulo</option>
                <option value="SE">Sergipe</option>
                <option value="TO">Tocantins</option>
            </select>

        </div>

        {{-- Cidade --}}
        <div>

            <label
                for="cidade"
                class="block text-sm font-medium text-gray-700"
            >
                Cidade
            </label>

            <select
                name="cidade"
                id="cidade"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                required
                disabled
            >
                <option value="">
                    Selecione primeiro o estado
                </option>
            </select>

            <p
                id="cidade-loading"
                class="mt-1 text-sm text-gray-500 hidden"
            >
                Carregando cidades...
            </p>

        </div>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const estadoInput = document.getElementById('estado');
        const cidadeInput = document.getElementById('cidade');
        const cidadeLoading = document.getElementById('cidade-loading');

        if (!estadoInput || !cidadeInput) {
            return;
        }

        estadoInput.addEventListener('change', async function () {

            const uf = this.value;

            cidadeInput.innerHTML = '';
            cidadeInput.disabled = true;

            if (!uf) {

                cidadeInput.innerHTML = `
                    <option value="">
                        Selecione primeiro o estado
                    </option>
                `;

                return;
            }

            cidadeLoading.classList.remove('hidden');

            cidadeInput.innerHTML = `
                <option value="">
                    Carregando cidades...
                </option>
            `;

            try {

                const response = await fetch(
                    `https://servicodados.ibge.gov.br/api/v1/localidades/estados/${uf}/municipios`
                );

                if (!response.ok) {
                    throw new Error('Erro ao consultar o IBGE.');
                }

                const cidades = await response.json();

                cidadeInput.innerHTML = `
                    <option value="">
                        Selecione a cidade
                    </option>
                `;

                cidades.forEach(function (cidade) {

                    const option = document.createElement('option');

                    option.value = cidade.nome;
                    option.textContent = cidade.nome;

                    cidadeInput.appendChild(option);

                });

                cidadeInput.disabled = false;

            } catch (error) {

                console.error(error);

                cidadeInput.innerHTML = `
                    <option value="">
                        Não foi possível carregar as cidades
                    </option>
                `;

            } finally {

                cidadeLoading.classList.add('hidden');

            }

        });

    });
</script>
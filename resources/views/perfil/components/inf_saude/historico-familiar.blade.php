<div class="mt-8">

    <h3 class="text-lg font-semibold text-gray-800">
        Histórico familiar
    </h3>

    <p class="mt-1 mb-4 text-sm text-gray-600">
        Selecione doenças ou condições relevantes presentes na sua família.
    </p>

    @php
        $historicoSelecionado = old(
            'historico_familiar',
            $perfilSaude?->historico_familiar ?? []
        );
    @endphp

    @php
        $historicos = [
            'doenca_cardiovascular' => 'Doença cardiovascular',
            'infarto' => 'Infarto',
            'morte_subita' => 'Morte súbita',
            'diabetes' => 'Diabetes',
            'hipertensao' => 'Hipertensão',
            'cancer_mama' => 'Câncer de mama',
            'cancer_colon' => 'Câncer de cólon/intestino',
            'cancer_prostata' => 'Câncer de próstata',
            'doenca_genetica' => 'Doença genética ou rara',
            'outros' => 'Outros',
            'nao_possui' => 'Não possui',
        ];
    @endphp

    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">

        @foreach ($historicos as $valor => $descricao)

            <label class="flex items-center gap-2">

                <input
                    type="checkbox"
                    name="historico_familiar[]"
                    value="{{ $valor }}"
                    class="historico-familiar-checkbox rounded border-gray-300"
                    @checked(in_array($valor, $historicoSelecionado))
                >

                <span>{{ $descricao }}</span>

            </label>

        @endforeach

    </div>

    <div class="mt-4">

        <label
            for="historico_familiar_detalhes"
            class="block text-sm font-medium text-gray-700"
        >
            Detalhes do histórico familiar
        </label>

        <textarea
            name="historico_familiar_detalhes"
            id="historico_familiar_detalhes"
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            placeholder="Informe quem possui a condição e outros detalhes relevantes..."
        >{{ old('historico_familiar_detalhes', $perfilSaude?->historico_familiar_detalhes) }}</textarea>

    </div>

</div>
<div class="mt-8">

    <h3 class="text-lg font-semibold text-gray-800">
        Problemas de saúde prévios
    </h3>

    <p class="mt-1 mb-4 text-sm text-gray-600">
        Selecione doenças ou condições de saúde que você possui ou já possuiu.
    </p>

    @php
        $problemasSelecionados = old(
            'problemas_saude_previos',
            $perfilSaude?->problemas_saude_previos ?? []
        );
    @endphp

    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">

        @php
            $problemas = [
                'hipertensao' => 'Hipertensão (pressão alta)',
                'diabetes' => 'Diabetes',
                'dislipidemia' => 'Dislipidemia (colesterol/triglicerídeos)',
                'asma_dpoc' => 'Asma/DPOC',
                'infarto' => 'Infarto prévio',
                'arritmia' => 'Arritmia cardíaca',
                'insuficiencia_cardiaca' => 'Insuficiência cardíaca',
                'epilepsia' => 'Epilepsia',
                'avc' => 'AVC prévio',
                'enxaqueca_cronica' => 'Enxaqueca crônica',
                'hipotireoidismo' => 'Hipotireoidismo',
                'doenca_adrenal' => 'Doença da adrenal',
                'cancer' => 'Histórico de câncer',
                'outros' => 'Outros',
                'nao_possui' => 'Não possui',
            ];
        @endphp

        @foreach ($problemas as $valor => $descricao)

            <label class="flex items-center gap-2">

                <input
                    type="checkbox"
                    name="problemas_saude_previos[]"
                    value="{{ $valor }}"
                    class="problema-saude-checkbox rounded border-gray-300"
                    @checked(in_array($valor, $problemasSelecionados))
                >

                <span>{{ $descricao }}</span>

            </label>

        @endforeach

    </div>

    <div class="mt-4">

        <label
            for="problemas_saude_detalhes"
            class="block text-sm font-medium text-gray-700"
        >
            Detalhes adicionais
        </label>

        <textarea
            name="problemas_saude_detalhes"
            id="problemas_saude_detalhes"
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            placeholder="Informe detalhes importantes, como diagnóstico, ano, tratamento ou situação atual..."
        >{{ old('problemas_saude_detalhes', $perfilSaude?->problemas_saude_detalhes) }}</textarea>

    </div>

</div>
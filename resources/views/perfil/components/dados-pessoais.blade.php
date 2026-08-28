<div class="bg-white shadow-sm rounded-lg p-6 mb-6">

    <h3 class="text-lg font-semibold text-gray-800 mb-6">
        Dados pessoais
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- CPF --}}
        <div>
            <label for="cpf" class="block text-sm font-medium text-gray-700">
                CPF
            </label>

            <input
                type="text"
                name="cpf"
                id="cpf"
                value="{{ old('cpf', $paciente->cpf) }}"
                maxlength="14"
                inputmode="numeric"
                placeholder="000.000.000-00"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                required
            >
        </div>

        {{-- Sexo --}}
        <div>
            <label for="sexo" class="block text-sm font-medium text-gray-700">
                Sexo
            </label>

            <select
                name="sexo"
                id="sexo"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                required
            >
                <option value="">Selecione</option>

                <option value="M"
                    {{ old('sexo', $paciente->sexo) === 'M' ? 'selected' : '' }}>
                    Masculino
                </option>

                <option value="F"
                    {{ old('sexo', $paciente->sexo) === 'F' ? 'selected' : '' }}>
                    Feminino
                </option>
            </select>
        </div>

        {{-- Data de nascimento --}}
        <div>
            <label for="data_nascimento" class="block text-sm font-medium text-gray-700">
                Data de nascimento
            </label>

            <input
                type="date"
                name="data_nascimento"
                id="data_nascimento"
                value="{{ old('data_nascimento', $paciente->data_nascimento) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                required
            >
        </div>

        {{-- Telefone --}}
        <div>
            <label for="telefone" class="block text-sm font-medium text-gray-700">
                Telefone
            </label>

            <input
                type="text"
                name="telefone"
                id="telefone"
                value="{{ old('telefone', $paciente->telefone) }}"
                maxlength="15"
                inputmode="numeric"
                placeholder="(00) 00000-0000"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                required
            >
        </div>

        {{-- Profissão --}}
        <div>
            <label for="profissao" class="block text-sm font-medium text-gray-700">
                Profissão
            </label>

            <input
                type="text"
                name="profissao"
                id="profissao"
                value="{{ old('profissao', $paciente->profissao) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            >
        </div>

    </div>

</div>
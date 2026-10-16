<?php

namespace App\Services\Paciente;

use App\Models\Paciente;
use App\Models\PerfilSaude;
use Illuminate\Support\Facades\DB;

class PerfilSaudeService
{
    /**
     * Salva o perfil completo.
     *
     * Usado no cadastro inicial.
     */
    public function salvar(Paciente $paciente, array $dados): void
    {
        DB::transaction(function () use ($paciente, $dados) {

            $this->atualizarDadosPessoais(
                $paciente,
                $dados
            );

            $this->atualizarPerfilSaude(
                $paciente,
                $dados
            );

            $this->atualizarMedicamentos(
                $paciente,
                $dados
            );

            $this->atualizarCirurgias(
                $paciente,
                $dados
            );
        });
    }

    /**
     * Atualiza apenas os dados pessoais.
     */
    public function atualizarDadosPessoais(
        Paciente $paciente,
        array $dados
    ): void {
        $paciente->update([
            'cpf' => $dados['cpf'] ?? null,
            'sexo' => $dados['sexo'] ?? null,
            'data_nascimento' => $dados['data_nascimento'] ?? null,
            'telefone' => $dados['telefone'] ?? null,
            'cidade' => $dados['cidade'] ?? null,
            'estado' => $dados['estado'] ?? null,
            'profissao' => $dados['profissao'] ?? null,
        ]);
    }

    /**
     * Atualiza deficiências e necessidades de acessibilidade.
     */
    public function atualizarDeficiencias(
        Paciente $paciente,
        array $dados
    ): void {
        $this->perfil($paciente)->update([
            'deficiencias' =>
                $dados['deficiencias'] ?? [],

            'deficiencia_outros_descricao' =>
                $dados['deficiencia_outros_descricao'] ?? null,

            'necessidades_acessibilidade' =>
                $dados['necessidades_acessibilidade'] ?? null,
        ]);
    }

    /**
     * Atualiza problemas de saúde.
     */
    public function atualizarProblemasSaude(
        Paciente $paciente,
        array $dados
    ): void {
        $this->perfil($paciente)->update([
            'problemas_saude_previos' =>
                $dados['problemas_saude_previos'] ?? [],

            'problemas_saude_detalhes' =>
                $dados['problemas_saude_detalhes'] ?? null,
        ]);
    }

    /**
     * Atualiza histórico familiar.
     */
    public function atualizarHistoricoFamiliar(
        Paciente $paciente,
        array $dados
    ): void {
        $this->perfil($paciente)->update([
            'historico_familiar' =>
                $dados['historico_familiar'] ?? [],

            'historico_familiar_detalhes' =>
                $dados['historico_familiar_detalhes'] ?? null,
        ]);
    }

    /**
     * Atualiza alergias.
     */
    public function atualizarAlergias(
        Paciente $paciente,
        array $dados
    ): void {
        $this->perfil($paciente)->update([
            'alergias' =>
                $dados['alergias'] ?? [],

            'alergias_detalhes' =>
                $dados['alergias_detalhes'] ?? null,
        ]);
    }

    /**
     * Atualiza medicamentos.
     */
    public function atualizarMedicamentos(
        Paciente $paciente,
        array $dados
    ): void {
        $paciente->medicamentos()->delete();

        $medicamentos = $dados['medicamentos'] ?? [];

        foreach ($medicamentos as $medicamento) {

            if (
                !isset($medicamento['nome']) ||
                trim($medicamento['nome']) === ''
            ) {
                continue;
            }

            $paciente->medicamentos()->create([
                'nome' =>
                    trim($medicamento['nome']),

                'dosagem' =>
                    $medicamento['dosagem'] ?? null,

                'frequencia' =>
                    $medicamento['frequencia'] ?? null,

                'indicacao' =>
                    $medicamento['indicacao'] ?? null,
            ]);
        }
    }

    /**
     * Atualiza cirurgias do paciente.
     *
     * As cirurgias ficam armazenadas na tabela
     * de cirurgias relacionada ao paciente.
     */
    public function atualizarCirurgias(
        Paciente $paciente,
        array $dados
    ): void {
        $cirurgias = $dados['cirurgias'] ?? [];

        /*
         * IDs das cirurgias que continuam existindo.
         *
         * Somente IDs pertencentes ao paciente
         * serão considerados posteriormente.
         */
        $idsMantidos = collect($cirurgias)
            ->pluck('id')
            ->filter(fn ($id) => !empty($id))
            ->map(fn ($id) => (int) $id)
            ->values()
            ->toArray();

        /*
         * Remove cirurgias antigas que não estão
         * mais presentes no formulário.
         */
        $query = $paciente->cirurgias();

        if (!empty($idsMantidos)) {
            $query->whereNotIn('id', $idsMantidos);
        }

        $query->delete();

        /*
         * Cria novas cirurgias ou atualiza
         * as existentes.
         */
        foreach ($cirurgias as $cirurgia) {

            /*
             * Ignora registros vazios.
             */
            if (
                !isset($cirurgia['tipo']) ||
                trim((string) $cirurgia['tipo']) === ''
            ) {
                continue;
            }

            /*
             * Dados que serão salvos.
             */
            $dadosCirurgia = [
                'tipo' =>
                    trim((string) $cirurgia['tipo']),

                'ano' =>
                    !empty($cirurgia['ano'])
                        ? (int) $cirurgia['ano']
                        : null,

                'anestesia' =>
                    $cirurgia['anestesia'] ?? null,

                'complicacoes' =>
                    $cirurgia['complicacoes'] ?? [],

                'implantes' =>
                    $cirurgia['implantes'] ?? [],

                'observacoes' =>
                    $cirurgia['observacoes'] ?? null,
            ];

            /*
             * Se possui ID, atualiza.
             *
             * Através da relação do paciente, garantimos
             * que o registro pertence a ele.
             */
            if (!empty($cirurgia['id'])) {

                $paciente->cirurgias()
                    ->where('id', (int) $cirurgia['id'])
                    ->update($dadosCirurgia);

                continue;
            }

            /*
             * Se não possui ID, cria uma nova cirurgia.
             */
            $paciente->cirurgias()->create(
                $dadosCirurgia
            );
        }
    }

    /**
     * Atualiza hábitos, atividade física e medidas.
     */
    public function atualizarHabitos(
        Paciente $paciente,
        array $dados
    ): void {
        $this->perfil($paciente)->update([
            'fumante' =>
                $dados['fumante'] ?? false,

            'alcoolismo' =>
                $dados['alcoolismo'] ?? false,

            'uso_drogas' =>
                $dados['uso_drogas'] ?? false,

            'atividade_fisica_regular' =>
                $dados['atividade_fisica_regular'] ?? false,

            'altura' =>
                $dados['altura'] ?? null,

            'peso' =>
                $dados['peso'] ?? null,
        ]);
    }

    /**
     * Atualiza apenas o tipo sanguíneo.
     *
     * Usado quando o paciente altera o tipo sanguíneo
     * diretamente no perfil.
     */
    public function atualizarTipoSanguineo(
        Paciente $paciente,
        ?string $tipoSanguineo
    ): void {
        $this->perfil($paciente)->update([
            'tipo_sanguineo' => $tipoSanguineo,
        ]);
    }

    /**
     * Atualiza os dados gerais do perfil de saúde.
     *
     * Usado durante o cadastro inicial.
     */
    private function atualizarPerfilSaude(
        Paciente $paciente,
        array $dados
    ): void {
        PerfilSaude::updateOrCreate(
            [
                'paciente_id' => $paciente->id,
            ],
            [
                'tipo_sanguineo' =>
                    $dados['tipo_sanguineo'] ?? null,

                'deficiencias' =>
                    $dados['deficiencias'] ?? [],

                'deficiencia_outros_descricao' =>
                    $dados['deficiencia_outros_descricao'] ?? null,

                'necessidades_acessibilidade' =>
                    $dados['necessidades_acessibilidade'] ?? null,

                'problemas_saude_previos' =>
                    $dados['problemas_saude_previos'] ?? [],

                'problemas_saude_detalhes' =>
                    $dados['problemas_saude_detalhes'] ?? null,

                'historico_familiar' =>
                    $dados['historico_familiar'] ?? [],

                'historico_familiar_detalhes' =>
                    $dados['historico_familiar_detalhes'] ?? null,

                'alergias' =>
                    $dados['alergias'] ?? [],

                'alergias_detalhes' =>
                    $dados['alergias_detalhes'] ?? null,

                'fumante' =>
                    $dados['fumante'] ?? false,

                'alcoolismo' =>
                    $dados['alcoolismo'] ?? false,

                'uso_drogas' =>
                    $dados['uso_drogas'] ?? false,

                'atividade_fisica_regular' =>
                    $dados['atividade_fisica_regular'] ?? false,

                'altura' =>
                    $dados['altura'] ?? null,

                'peso' =>
                    $dados['peso'] ?? null,
            ]
        );
    }

    /**
     * Retorna o perfil de saúde do paciente.
     *
     * Se ainda não existir, cria um perfil vazio.
     */
    private function perfil(Paciente $paciente): PerfilSaude
    {
        return PerfilSaude::firstOrCreate([
            'paciente_id' => $paciente->id,
        ]);
    }
}
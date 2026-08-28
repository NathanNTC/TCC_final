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
            'cpf' => $dados['cpf'],
            'sexo' => $dados['sexo'],
            'data_nascimento' => $dados['data_nascimento'],
            'telefone' => $dados['telefone'],
            'cidade' => $dados['cidade'],
            'estado' => $dados['estado'],
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
        DB::transaction(function () use ($paciente, $dados) {

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
        });
    }

    /**
     * Atualiza cirurgias.
     */
    public function atualizarCirurgias(
        Paciente $paciente,
        array $dados
    ): void {
        $this->perfil($paciente)->update([
            'cirurgias_previas' =>
                $dados['cirurgias_previas'] ?? null,
        ]);
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

                'cirurgias_previas' =>
                    $dados['cirurgias_previas'] ?? null,

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
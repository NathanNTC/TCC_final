<?php

namespace App\Services\Paciente;

use App\Models\Medicamento;
use App\Models\Paciente;
use App\Models\PerfilSaude;
use Illuminate\Support\Facades\DB;

class PerfilSaudeService
{
    public function salvar(Paciente $paciente, array $dados): void
    {
        DB::transaction(function () use ($paciente, $dados) {

            /*
             * =====================================================
             * DADOS PESSOAIS DO PACIENTE
             * =====================================================
             */

            $paciente->update([
                'cpf' => $dados['cpf'],
                'sexo' => $dados['sexo'],
                'data_nascimento' => $dados['data_nascimento'],
                'telefone' => $dados['telefone'],
                'cidade' => $dados['cidade'],
                'estado' => $dados['estado'],
                'profissao' => $dados['profissao'] ?? null,
            ]);

            /*
             * =====================================================
             * PERFIL DE SAÚDE
             * =====================================================
             */

            PerfilSaude::updateOrCreate(
                [
                    'paciente_id' => $paciente->id,
                ],
                [
                    'tipo_sanguineo' => $dados['tipo_sanguineo'] ?? null,

                    'deficiencias' => $dados['deficiencias'] ?? [],
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

            /*
             * =====================================================
             * MEDICAMENTOS
             * =====================================================
             *
             * Os medicamentos agora ficam em uma tabela própria.
             *
             * Como o formulário envia todos os medicamentos atuais,
             * removemos os antigos e cadastramos novamente os atuais.
             *
             */

            $paciente->medicamentos()->delete();

            $medicamentos = $dados['medicamentos'] ?? [];

            foreach ($medicamentos as $medicamento) {

                if (empty($medicamento['nome'])) {
                    continue;
                }

                Medicamento::create([
                    'paciente_id' => $paciente->id,
                    'nome' => $medicamento['nome'],
                    'dosagem' => $medicamento['dosagem'] ?? null,
                    'frequencia' => $medicamento['frequencia'] ?? null,
                    'indicacao' => $medicamento['indicacao'] ?? null,
                ]);
            }
        });
    }
}
<?php

namespace App\Services\Paciente;

use App\Models\Paciente;
use App\Models\PerfilSaude;
use Illuminate\Support\Facades\DB;

class PerfilSaudeService
{
    public function salvar(Paciente $paciente, array $dados): void
    {
        DB::transaction(function () use ($paciente, $dados) {

            /*
            |--------------------------------------------------------------------------
            | DADOS PESSOAIS
            |--------------------------------------------------------------------------
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
            |--------------------------------------------------------------------------
            | PERFIL DE SAÚDE
            |--------------------------------------------------------------------------
            */

            PerfilSaude::updateOrCreate(
                [
                    'paciente_id' => $paciente->id,
                ],
                [
                    'tipo_sanguineo' => $dados['tipo_sanguineo'] ?? null,

                    // Deficiências
                    'deficiencias' => $dados['deficiencias'] ?? [],
                    'deficiencia_outros_descricao' =>
                        $dados['deficiencia_outros_descricao'] ?? null,
                    'necessidades_acessibilidade' =>
                        $dados['necessidades_acessibilidade'] ?? null,

                    // Problemas de saúde prévios
                    'problemas_saude_previos' =>
                        $dados['problemas_saude_previos'] ?? [],
                    'problemas_saude_detalhes' =>
                        $dados['problemas_saude_detalhes'] ?? null,

                    // Histórico familiar
                    'historico_familiar' =>
                        $dados['historico_familiar'] ?? [],
                    'historico_familiar_detalhes' =>
                        $dados['historico_familiar_detalhes'] ?? null,

                    // Alergias
                    'alergias' =>
                        $dados['alergias'] ?? [],
                    'alergias_detalhes' =>
                        $dados['alergias_detalhes'] ?? null,

                    // Hábitos
                    'fumante' =>
                        $dados['fumante'] ?? false,
                    'alcoolismo' =>
                        $dados['alcoolismo'] ?? false,
                    'uso_drogas' =>
                        $dados['uso_drogas'] ?? false,

                    // Cirurgias
                    'cirurgias_previas' =>
                        $dados['cirurgias_previas'] ?? null,

                    // Atividade física
                    'atividade_fisica_regular' =>
                        $dados['atividade_fisica_regular'] ?? false,

                    // Medidas
                    'altura' =>
                        $dados['altura'] ?? null,
                    'peso' =>
                        $dados['peso'] ?? null,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | MEDICAMENTOS
            |--------------------------------------------------------------------------
            |
            | Medicamentos ficam em uma tabela própria.
            |
            | O formulário envia a lista completa atual.
            | Portanto:
            |
            | 1. Apagamos os medicamentos antigos;
            | 2. Cadastramos os medicamentos enviados.
            |
            | Se o paciente não usar medicamentos, a lista fica vazia
            | e nenhum registro será criado.
            |
            */

            $paciente->medicamentos()->delete();

            $medicamentos = $dados['medicamentos'] ?? [];

            foreach ($medicamentos as $medicamento) {

                // Ignora campos vazios
                if (
                    !isset($medicamento['nome']) ||
                    trim($medicamento['nome']) === ''
                ) {
                    continue;
                }

                $paciente->medicamentos()->create([
                    'nome' => trim($medicamento['nome']),
                    'dosagem' => $medicamento['dosagem'] ?? null,
                    'frequencia' => $medicamento['frequencia'] ?? null,
                    'indicacao' => $medicamento['indicacao'] ?? null,
                ]);
            }
        });
    }
}
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

            $paciente->update([
                'cpf' => $dados['cpf'],
                'sexo' => $dados['sexo'],
                'data_nascimento' => $dados['data_nascimento'],
                'telefone' => $dados['telefone'],
                'cidade' => $dados['cidade'],
                'estado' => $dados['estado'],
                'profissao' => $dados['profissao'] ?? null,
            ]);

            PerfilSaude::updateOrCreate(
                [
                    'paciente_id' => $paciente->id,
                ],
                [
                    'tipo_sanguineo' => $dados['tipo_sanguineo'] ?? null,
                    'deficiencias' => $dados['deficiencias'] ?? null,
                    'problemas_saude_previos' => $dados['problemas_saude_previos'] ?? null,
                    'historico_familiar' => $dados['historico_familiar'] ?? null,
                    'medicacoes_uso_continuo' => $dados['medicacoes_uso_continuo'] ?? null,
                    'alergias' => $dados['alergias'] ?? null,
                    'fumante' => $dados['fumante'] ?? false,
                    'alcoolismo' => $dados['alcoolismo'] ?? false,
                    'uso_drogas' => $dados['uso_drogas'] ?? false,
                    'cirurgias_previas' => $dados['cirurgias_previas'] ?? null,
                    'atividade_fisica_regular' => $dados['atividade_fisica_regular'] ?? false,
                    'altura' => $dados['altura'] ?? null,
                    'peso' => $dados['peso'] ?? null,
                ]
            );
        });
    }
}
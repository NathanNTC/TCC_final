<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePerfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | DADOS PESSOAIS
            |--------------------------------------------------------------------------
            */

            'cpf' => [
                'required',
                'string',
                'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/',
            ],

            'sexo' => [
                'required',
                'in:M,F',
            ],

            'data_nascimento' => [
                'required',
                'date',
            ],

            'telefone' => [
                'required',
                'string',
                'regex:/^\(\d{2}\) \d{5}-\d{4}$/',
            ],

            'estado' => [
                'required',
                'string',
                'size:2',
            ],

            'cidade' => [
                'required',
                'string',
                'max:255',
            ],

            'profissao' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | INFORMAÇÕES DE SAÚDE
            |--------------------------------------------------------------------------
            */

            'tipo_sanguineo' => [
                'nullable',
                'string',
                'max:10',
            ],

            /*
            |--------------------------------------------------------------------------
            | DEFICIÊNCIAS
            |--------------------------------------------------------------------------
            */

            'deficiencias' => [
                'nullable',
                'array',
            ],

            'deficiencias.*' => [
                'string',
                'in:fisica,visual,auditiva,intelectual_cognitiva,psicossocial,outros,nao_possui',
            ],

            'deficiencia_outros_descricao' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'necessidades_acessibilidade' => [
                'nullable',
                'string',
                'max:2000',
            ],

            /*
            |--------------------------------------------------------------------------
            | PROBLEMAS DE SAÚDE PRÉVIOS
            |--------------------------------------------------------------------------
            */

            'problemas_saude_previos' => [
                'nullable',
                'array',
            ],

            'problemas_saude_previos.*' => [
                'string',
                'in:hipertensao,diabetes,dislipidemia,asma_dpoc,infarto,arritmia,insuficiencia_cardiaca,epilepsia,avc,enxaqueca_cronica,hipotireoidismo,doenca_adrenal,cancer,outros,nao_possui',
            ],

            'problemas_saude_detalhes' => [
                'nullable',
                'string',
                'max:2000',
            ],

            /*
            |--------------------------------------------------------------------------
            | HISTÓRICO FAMILIAR
            |--------------------------------------------------------------------------
            */

            'historico_familiar' => [
                'nullable',
                'array',
            ],

            'historico_familiar.*' => [
                'string',
                'in:doenca_cardiovascular,infarto,morte_subita,diabetes,hipertensao,cancer_mama,cancer_colon,cancer_prostata,doenca_genetica,outros,nao_possui',
            ],

            'historico_familiar_detalhes' => [
                'nullable',
                'string',
                'max:2000',
            ],

            /*
            |--------------------------------------------------------------------------
            | ALERGIAS
            |--------------------------------------------------------------------------
            */

            'alergias' => [
                'nullable',
                'array',
            ],

            'alergias.*' => [
                'string',
                'in:medicamentosa,alimentar,ambiental,nao_possui',
            ],

            'alergias_detalhes' => [
                'nullable',
                'string',
                'max:2000',
            ],

            /*
            |--------------------------------------------------------------------------
            | HÁBITOS
            |--------------------------------------------------------------------------
            */

            'fumante' => [
                'boolean',
            ],

            'alcoolismo' => [
                'boolean',
            ],

            'uso_drogas' => [
                'boolean',
            ],

            /*
            |--------------------------------------------------------------------------
            | CIRURGIAS
            |--------------------------------------------------------------------------
            */

            'cirurgias_previas' => [
                'nullable',
                'string',
                'max:5000',
            ],

            /*
            |--------------------------------------------------------------------------
            | ATIVIDADE FÍSICA
            |--------------------------------------------------------------------------
            */

            'atividade_fisica_regular' => [
                'boolean',
            ],

            /*
            |--------------------------------------------------------------------------
            | MEDIDAS
            |--------------------------------------------------------------------------
            */

            'altura' => [
                'nullable',
                'numeric',
                'min:0.5',
                'max:3.0',
            ],

            'peso' => [
                'nullable',
                'numeric',
                'min:1',
                'max:500',
            ],

            /*
            |--------------------------------------------------------------------------
            | MEDICAMENTOS
            |--------------------------------------------------------------------------
            |
            | O paciente pode não utilizar nenhum medicamento.
            |
            */

            'medicamentos' => [
                'nullable',
                'array',
            ],

            'medicamentos.*' => [
                'array',
            ],

            'medicamentos.*.nome' => [
                'required',
                'string',
                'max:255',
            ],

            'medicamentos.*.dosagem' => [
                'nullable',
                'string',
                'max:100',
            ],

            'medicamentos.*.frequencia' => [
                'nullable',
                'string',
                'max:100',
            ],

            'medicamentos.*.indicacao' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }
}
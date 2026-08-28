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

            'tipo_sanguineo' => [
                'nullable',
                'string',
                'max:10',
            ],

            'deficiencias' => [
                'nullable',
                'string',
            ],

            'problemas_saude_previos' => [
                'nullable',
                'string',
            ],

            'historico_familiar' => [
                'nullable',
                'string',
            ],

            'medicacoes_uso_continuo' => [
                'nullable',
                'string',
            ],

            'alergias' => [
                'nullable',
                'string',
            ],

            'fumante' => [
                'boolean',
            ],

            'alcoolismo' => [
                'boolean',
            ],

            'uso_drogas' => [
                'boolean',
            ],

            'cirurgias_previas' => [
                'nullable',
                'string',
            ],

            'atividade_fisica_regular' => [
                'boolean',
            ],

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
        ];
    }
}
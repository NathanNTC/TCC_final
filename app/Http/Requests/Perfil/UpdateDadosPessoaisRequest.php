<?php

namespace App\Http\Requests\Perfil;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDadosPessoaisRequest extends FormRequest
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
                'in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            ],
        ];
    }
}
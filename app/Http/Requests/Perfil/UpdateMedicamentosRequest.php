<?php

namespace App\Http\Requests\Perfil;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicamentosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'medicamentos' => [
                'nullable',
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
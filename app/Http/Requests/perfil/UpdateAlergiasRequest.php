<?php

namespace App\Http\Requests\Perfil;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAlergiasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'alergias' => [
                'nullable',
                'array',
            ],

            'alergias.*' => [
                'string',
                'in:medicamentosa,alimentar,ambiental,latex,contraste,insetos,outros,nao_possui',
            ],

            'alergias_detalhes' => [
                'nullable',
                'string',
                'max:3000',
            ],
        ];
    }
}
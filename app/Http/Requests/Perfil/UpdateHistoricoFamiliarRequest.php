<?php

namespace App\Http\Requests\Perfil;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHistoricoFamiliarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
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
        ];
    }
}
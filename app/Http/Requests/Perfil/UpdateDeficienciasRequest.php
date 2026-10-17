<?php

namespace App\Http\Requests\Perfil;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeficienciasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
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
                'max:1000',
            ],

            'necessidades_acessibilidade' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }
}
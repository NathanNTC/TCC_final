<?php

namespace App\Http\Requests\Perfil;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProblemasSaudeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
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
        ];
    }
}
<?php

namespace App\Http\Requests\Perfil;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCirurgiasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'cirurgias' => [
                'nullable',
                'array',
            ],

            'cirurgias.*.id' => [
                'nullable',
                'integer',
            ],

            'cirurgias.*.tipo' => [
                'required',
                'string',
                'max:255',
            ],

            'cirurgias.*.ano' => [
                'nullable',
                'integer',
                'min:1900',
                'max:' . date('Y'),
            ],

            'cirurgias.*.anestesia' => [
                'nullable',
                'string',
                'in:geral,raqui_peridural,local_sedacao,nao_lembra',
            ],

            'cirurgias.*.complicacoes' => [
                'nullable',
                'array',
            ],

            'cirurgias.*.complicacoes.*' => [
                'string',
                'in:uti,infeccao,sangramento_transfusao,demora_acordar,nenhuma',
            ],

            'cirurgias.*.implantes' => [
                'nullable',
                'array',
            ],

            'cirurgias.*.implantes.*' => [
                'string',
                'in:placas_parafusos_pinos,protese,marcapasso,nenhum',
            ],

            'cirurgias.*.observacoes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }
}
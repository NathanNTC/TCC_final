<?php

namespace App\Http\Requests\Perfil;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHabitosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'fumante' => [
                'required',
                'boolean',
            ],

            'alcoolismo' => [
                'required',
                'boolean',
            ],

            'uso_drogas' => [
                'required',
                'boolean',
            ],

            'atividade_fisica_regular' => [
                'required',
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
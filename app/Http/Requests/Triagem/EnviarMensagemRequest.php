<?php

namespace App\Http\Requests\Triagem;

use Illuminate\Foundation\Http\FormRequest;

class EnviarMensagemRequest extends FormRequest
{
    /**
     * Autoriza a requisição.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Regras de validação.
     */
    public function rules(): array
    {
        return [
            'mensagem' => [
                'required',
                'string',
                'min:1',
                'max:5000',
            ],
        ];
    }

    /**
     * Mensagens de validação.
     */
    public function messages(): array
    {
        return [
            'mensagem.required' => 'Digite uma mensagem.',
            'mensagem.string' => 'A mensagem deve ser um texto.',
            'mensagem.min' => 'A mensagem não pode estar vazia.',
            'mensagem.max' => 'A mensagem não pode ultrapassar 5000 caracteres.',
        ];
    }
}
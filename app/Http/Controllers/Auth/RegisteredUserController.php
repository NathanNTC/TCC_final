<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Paciente\PacienteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Exibe a tela de cadastro usando Vue + Inertia.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Realiza o cadastro de um novo paciente.
     *
     * @throws ValidationException
     */
    public function store(
        Request $request,
        PacienteService $pacienteService
    ): RedirectResponse {

        $dadosValidados = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);

        /*
         * O PacienteService continua responsável por:
         *
         * - criar o usuário;
         * - criar o paciente;
         * - definir os dados iniciais;
         * - manter a regra de cadastro centralizada.
         */
        $user = $pacienteService->cadastrar($dadosValidados);

        /*
         * Faz login automaticamente após o cadastro.
         */
        Auth::login($user);

        /*
         * Regenera a sessão por segurança.
         */
        $request->session()->regenerate();

        /*
         * Após criar a conta, o paciente é direcionado
         * para o perfil de saúde.
         */
        return redirect()->route('perfil.index');
    }
}


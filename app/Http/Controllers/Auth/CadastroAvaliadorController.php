<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Convite;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class CadastroAvaliadorController extends Controller
{
    /**
     * Exibe o formulário de cadastro por convite.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/CadastroAvaliador');
    }

    /**
     * Cadastra um avaliador utilizando um convite válido.
     */
    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'codigo' => ['required', 'string', 'max:64'],
            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],
        ]);

        $usuario = DB::transaction(function () use ($dados) {
            $convite = Convite::query()
                ->where('codigo', Str::upper(trim($dados['codigo'])))
                ->lockForUpdate()
                ->first();

            if (!$convite || !$convite->estaValido()) {
                throw ValidationException::withMessages([
                    'codigo' => 'Convite inválido, expirado ou já utilizado.',
                ]);
            }

            if (!in_array($convite->perfil, [
                'Estudante',
                'Residente',
                'Medico',
            ], true)) {
                throw ValidationException::withMessages([
                    'codigo' => 'Este convite não autoriza o cadastro de avaliadores.',
                ]);
            }

            $usuario = User::create([
                'name' => $dados['name'],
                'email' => $dados['email'],
                'password' => $dados['password'],
            ]);

            $usuario->assignRole($convite->perfil);

            $convite->update([
                'utilizado_por' => $usuario->id,
                'utilizado_em' => now(),
                'ativo' => false,
            ]);

            return $usuario;
        });

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()
            ->route('avaliacao.index')
            ->with('success', 'Cadastro realizado com sucesso.');
    }
}


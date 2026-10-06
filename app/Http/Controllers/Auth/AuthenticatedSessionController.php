<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Exibe a tela de login usando Vue + Inertia.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => true,
            'status' => session('status'),
        ]);
    }

    /**
     * Processa uma tentativa de autenticação.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Valida e autentica o usuário.
        $request->authenticate();

        // Regenera a sessão por segurança.
        $request->session()->regenerate();

        // Redireciona para a página originalmente solicitada
        // ou para o Dashboard.
        return redirect()->intended(
            route('dashboard', absolute: false)
        );
    }

    /**
     * Encerra a sessão do usuário.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        // Invalida a sessão atual.
        $request->session()->invalidate();

        // Gera um novo token CSRF.
        $request->session()->regenerateToken();

        // Volta para o login.
        return redirect()->route('login');
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePerfilRequest;
use App\Services\Paciente\PerfilSaudeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PerfilController extends Controller
{
    public function index(): View
    {
        $paciente = Auth::user()->paciente;

        $perfilSaude = $paciente->perfilSaude;

        return view('perfil.index', compact(
            'paciente',
            'perfilSaude'
        ));
    }

    public function store(
        StorePerfilRequest $request,
        PerfilSaudeService $perfilSaudeService
    ): RedirectResponse {
        $paciente = Auth::user()->paciente;

        $perfilSaudeService->salvar(
            $paciente,
            $request->validated()
        );

        return redirect()
            ->route('dashboard')
            ->with('success', 'Perfil atualizado com sucesso.');
    }
}
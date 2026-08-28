<?php

namespace App\Http\Controllers\Perfil;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePerfilRequest;
use App\Services\Paciente\PerfilSaudeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PerfilController extends Controller
{
    /**
     * Exibe o perfil completo.
     */
    public function index(): Response
    {
        $paciente = Auth::user()->paciente;

        return Inertia::render('Perfil/Index', [
            'paciente' => $paciente,
            'perfilSaude' => $paciente->perfilSaude,
            'medicamentos' => $paciente->medicamentos,
        ]);
    }

    /**
     * Salva o perfil completo.
     */
    public function store(
        StorePerfilRequest $request,
        PerfilSaudeService $service
    ): RedirectResponse {
        $paciente = Auth::user()->paciente;

        $service->salvar(
            $paciente,
            $request->validated()
        );

        return redirect()
            ->route('perfil.index')
            ->with(
                'success',
                'Perfil salvo com sucesso.'
            );
    }
}
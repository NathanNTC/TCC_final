<?php

namespace App\Http\Controllers\Perfil;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAlergiasRequest;
use App\Services\Paciente\PerfilSaudeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AlergiasController extends Controller
{
    public function edit(): Response
    {
        $paciente = Auth::user()->paciente;

        return Inertia::render('Perfil/Editar', [
            'secao' => 'alergias',
            'paciente' => $paciente,
            'perfilSaude' => $paciente->perfilSaude,
        ]);
    }

    public function update(
        UpdateAlergiasRequest $request,
        PerfilSaudeService $service
    ): RedirectResponse {
        $paciente = Auth::user()->paciente;

        $service->atualizarAlergias(
            $paciente,
            $request->validated()
        );

        return redirect()
            ->route('perfil.index')
            ->with(
                'success',
                'Alergias atualizadas com sucesso.'
            );
    }
}
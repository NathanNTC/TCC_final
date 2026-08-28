<?php

namespace App\Http\Controllers\Perfil;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCirurgiasRequest;
use App\Services\Paciente\PerfilSaudeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CirurgiasController extends Controller
{
    public function edit(): Response
    {
        $paciente = Auth::user()->paciente;

        return Inertia::render('Perfil/Editar', [
            'secao' => 'cirurgias',
            'paciente' => $paciente,
            'perfilSaude' => $paciente->perfilSaude,
        ]);
    }

    public function update(
        UpdateCirurgiasRequest $request,
        PerfilSaudeService $service
    ): RedirectResponse {
        $paciente = Auth::user()->paciente;

        $service->atualizarCirurgias(
            $paciente,
            $request->validated()
        );

        return redirect()
            ->route('perfil.index')
            ->with(
                'success',
                'Informações sobre cirurgias atualizadas com sucesso.'
            );
    }
}
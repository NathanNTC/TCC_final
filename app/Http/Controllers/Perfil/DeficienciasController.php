<?php

namespace App\Http\Controllers\Perfil;

use App\Http\Controllers\Controller;
use App\Http\Requests\Perfil\UpdateDeficienciasRequest;
use App\Services\Paciente\PerfilSaudeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DeficienciasController extends Controller
{
    public function edit(): Response
    {
        $paciente = Auth::user()->paciente;

        return Inertia::render('Perfil/Editar', [
            'secao' => 'deficiencias',
            'paciente' => $paciente,
            'perfilSaude' => $paciente->perfilSaude,
        ]);
    }

    public function update(
        UpdateDeficienciasRequest $request,
        PerfilSaudeService $service
    ): RedirectResponse {
        $paciente = Auth::user()->paciente;

        $service->atualizarDeficiencias(
            $paciente,
            $request->validated()
        );

        return redirect()
            ->route('perfil.index')
            ->with(
                'success',
                'Informações de acessibilidade atualizadas com sucesso.'
            );
    }
}
<?php

namespace App\Http\Controllers\Perfil;

use App\Http\Controllers\Controller;
use App\Http\Requests\Perfil\UpdateProblemasSaudeRequest;
use App\Services\Paciente\PerfilSaudeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ProblemasSaudeController extends Controller
{
    public function edit(): Response
    {
        $paciente = Auth::user()->paciente;

        return Inertia::render('Perfil/Editar', [
            'secao' => 'problemas-saude',
            'paciente' => $paciente,
            'perfilSaude' => $paciente->perfilSaude,
        ]);
    }

    public function update(
        UpdateProblemasSaudeRequest $request,
        PerfilSaudeService $service
    ): RedirectResponse {
        $paciente = Auth::user()->paciente;

        $service->atualizarProblemasSaude(
            $paciente,
            $request->validated()
        );

        return redirect()
            ->route('perfil.index')
            ->with(
                'success',
                'Problemas de saúde atualizados com sucesso.'
            );
    }
}
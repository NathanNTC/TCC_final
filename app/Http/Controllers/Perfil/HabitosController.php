<?php

namespace App\Http\Controllers\Perfil;

use App\Http\Controllers\Controller;
use App\Http\Requests\Perfil\UpdateHabitosRequest;
use App\Services\Paciente\PerfilSaudeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class HabitosController extends Controller
{
    public function edit(): Response
    {
        $paciente = Auth::user()->paciente;

        return Inertia::render('Perfil/Editar', [
            'secao' => 'habitos',
            'paciente' => $paciente,
            'perfilSaude' => $paciente->perfilSaude,
        ]);
    }

    public function update(
        UpdateHabitosRequest $request,
        PerfilSaudeService $service
    ): RedirectResponse {
        $paciente = Auth::user()->paciente;

        $service->atualizarHabitos(
            $paciente,
            $request->validated()
        );

        return redirect()
            ->route('perfil.index')
            ->with(
                'success',
                'Hábitos e medidas atualizados com sucesso.'
            );
    }
}
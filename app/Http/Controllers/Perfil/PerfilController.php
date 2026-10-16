<?php

namespace App\Http\Controllers\Perfil;

use App\Http\Controllers\Controller;
use App\Http\Requests\Perfil\StorePerfilRequest;
use App\Services\Paciente\PerfilSaudeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'cirurgias' => $paciente->cirurgias,
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

    /**
     * Atualiza apenas o tipo sanguíneo.
     */
    public function atualizarTipoSanguineo(
        Request $request,
        PerfilSaudeService $service
    ): RedirectResponse {
        $validated = $request->validate([
            'tipo_sanguineo' => [
                'nullable',
                'in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            ],
        ]);

        $paciente = Auth::user()->paciente;

        $service->atualizarTipoSanguineo(
            $paciente,
            $validated['tipo_sanguineo'] ?? null
        );

        return back()->with(
            'success',
            'Tipo sanguíneo atualizado com sucesso.'
        );
    }
}
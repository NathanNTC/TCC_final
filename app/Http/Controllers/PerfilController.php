<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePerfilRequest;
use App\Services\Paciente\PerfilSaudeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PerfilController extends Controller
{
    /**
     * Exibe o perfil completo do paciente.
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
        PerfilSaudeService $perfilSaudeService
    ): RedirectResponse {
        $paciente = Auth::user()->paciente;

        $perfilSaudeService->salvar(
            $paciente,
            $request->validated()
        );

        return redirect()
            ->route('perfil.index')
            ->with('success', 'Perfil salvo com sucesso.');
    }

    /**
     * Abre a tela de edição de uma seção específica.
     */
    public function editar(string $secao): Response
    {
        $this->validarSecao($secao);

        $paciente = Auth::user()->paciente;

        return Inertia::render('Perfil/Editar', [
            'secao' => $secao,
            'paciente' => $paciente,
            'perfilSaude' => $paciente->perfilSaude,
            'medicamentos' => $paciente->medicamentos,
        ]);
    }

    /**
     * Atualiza uma seção específica.
     */
    public function atualizarSecao(
        StorePerfilRequest $request,
        PerfilSaudeService $perfilSaudeService,
        string $secao
    ): RedirectResponse {
        $this->validarSecao($secao);

        $paciente = Auth::user()->paciente;

        $perfilSaudeService->salvar(
            $paciente,
            $request->validated()
        );

        return redirect()
            ->route('perfil.index')
            ->with('success', 'Informações atualizadas com sucesso.');
    }

    /**
     * Valida se a seção existe.
     */
    private function validarSecao(string $secao): void
    {
        $secoesPermitidas = [
            'dados-pessoais',
            'deficiencias',
            'problemas-saude',
            'historico-familiar',
            'alergias',
            'medicamentos',
            'cirurgias',
            'habitos',
        ];

        abort_unless(
            in_array($secao, $secoesPermitidas, true),
            404
        );
    }
}
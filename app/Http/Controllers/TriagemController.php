<?php

namespace App\Http\Controllers;

use App\Models\Triagem;
use App\Services\Triagem\TriagemService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Feedback;

class TriagemController extends Controller
{
    public function __construct(
        private readonly TriagemService $triagemService
    ) {
    }

    /**
     * Lista as triagens do paciente autenticado.
     */
    public function index(Request $request): Response
    {
        $paciente = $request->user()->paciente;

        abort_unless($paciente, 403);

        $triagens = $this->triagemService
            ->listarPorPaciente($paciente);

        return Inertia::render('Triagem/Index', [
            'triagens' => $triagens,
        ]);
    }

    /**
     * Exibe a tela para iniciar uma nova triagem.
     */
    public function create(Request $request): Response
    {
        $paciente = $request->user()->paciente;

        abort_unless($paciente, 403);

        return Inertia::render('Triagem/Create');
    }

    /**
     * Inicia uma nova triagem.
     */
    public function store(Request $request): RedirectResponse
    {
        $paciente = $request->user()->paciente;

        abort_unless($paciente, 403);

        $triagem = $this->triagemService
            ->iniciar($paciente);

        return redirect()
            ->route('triagens.show', $triagem)
            ->with('success', 'Triagem iniciada com sucesso.');
    }

    /**
     * Exibe uma triagem específica.
     */
    /**
     * Exibe uma triagem específica.
     */
    public function show(
        Request $request,
        Triagem $triagem
    ): Response {
        $paciente = $request->user()->paciente;

        abort_unless($paciente, 403);

        $triagem = $this->triagemService
            ->buscarDoPaciente($paciente, $triagem);

        $feedbackEnviado = Feedback::query()
            ->where('user_id', $request->user()->id)
            ->where('triagem_id', $triagem->id)
            ->exists();

        return Inertia::render('Triagem/Show', [
            'triagem' => $triagem,
            'feedbackEnviado' => $feedbackEnviado,
        ]);
    }

    /**
     * Recebe uma mensagem enviada pelo paciente.
     */
    public function enviarMensagem(
        Request $request,
        Triagem $triagem
    ): RedirectResponse {
        $paciente = $request->user()->paciente;

        abort_unless($paciente, 403);

        abort_unless(
            $triagem->paciente_id === $paciente->id,
            403
        );

        abort_unless(
            $triagem->status === 'em_andamento',
            422
        );

        $dados = $request->validate([
            'mensagem' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        $this->triagemService->adicionarMensagemPaciente(
            $triagem,
            $dados['mensagem']
        );

        // Resposta temporária.
        // Depois vamos substituir pelo GeminiService.
        $this->triagemService->adicionarMensagemIa(
            $triagem,
            'Obrigado pelas informações. Vou analisar o que você relatou e fazer algumas perguntas para compreender melhor a situação.'
        );

        return redirect()
            ->route('triagens.show', $triagem);
    }
}
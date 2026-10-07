<?php

namespace App\Http\Controllers;

use App\Http\Requests\Triagem\EnviarMensagemRequest;
use App\Models\MensagemTriagem;
use App\Models\Triagem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TriagemMensagemController extends Controller
{
    /**
     * Envia uma mensagem do paciente para uma triagem.
     */
    public function store(
        EnviarMensagemRequest $request,
        Triagem $triagem
    ): JsonResponse {
        $paciente = $request->user()->paciente;

        abort_unless($paciente, 403);

        // Garante que a triagem pertence ao paciente autenticado.
        abort_unless(
            $triagem->paciente_id === $paciente->id,
            403
        );

        // Não permite enviar mensagens em triagens finalizadas.
        abort_if(
            $triagem->status !== 'em_andamento',
            422,
            'Esta triagem não está mais em andamento.'
        );

        // Descobre a próxima ordem da conversa.
        $ultimaOrdem = $triagem->mensagens()
            ->max('ordem');

        $ordem = ($ultimaOrdem ?? 0) + 1;

        $mensagem = $triagem->mensagens()->create([
            'remetente' => 'paciente',
            'mensagem' => $request->validated('mensagem'),
            'ordem' => $ordem,
            'enviada_em' => now(),
        ]);

        return response()->json([
            'success' => true,
            'mensagem' => $mensagem,
        ], 201);
    }
}
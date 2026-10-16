<?php

namespace App\Services\Triagem;

use App\Models\MensagemTriagem;
use App\Models\Paciente;
use App\Models\Triagem;
use Illuminate\Support\Facades\DB;

class TriagemService
{
    /**
     * Lista as triagens do paciente.
     */
    public function listarPorPaciente(Paciente $paciente)
    {
        return $paciente->triagens()
            ->latest()
            ->get();
    }

    /**
     * Inicia uma nova triagem.
     */
    public function iniciar(Paciente $paciente): Triagem
    {
        return DB::transaction(function () use ($paciente) {
            return $paciente->triagens()->create([
                'status' => 'em_andamento',
                'iniciada_em' => now(),
            ]);
        });
    }

    /**
     * Busca uma triagem garantindo que ela pertence ao paciente.
     */
    public function buscarDoPaciente(
        Paciente $paciente,
        Triagem $triagem
    ): Triagem {
        abort_unless(
            $triagem->paciente_id === $paciente->id,
            403
        );

        return $triagem->load('mensagens');
    }

    /**
     * Adiciona uma mensagem do paciente à triagem.
     */
    public function adicionarMensagemPaciente(
        Triagem $triagem,
        string $mensagem
    ): MensagemTriagem {
        $ordem = ($triagem->mensagens()->max('ordem') ?? 0) + 1;

        return $triagem->mensagens()->create([
            'remetente' => 'paciente',
            'mensagem' => $mensagem,
            'ordem' => $ordem,
            'enviada_em' => now(),
        ]);
    }

    /**
     * Adiciona uma mensagem da IA à triagem.
     */
    public function adicionarMensagemIa(
        Triagem $triagem,
        string $mensagem
    ): MensagemTriagem {
        $ordem = ($triagem->mensagens()->max('ordem') ?? 0) + 1;

        return $triagem->mensagens()->create([
            'remetente' => 'ia',
            'mensagem' => $mensagem,
            'ordem' => $ordem,
            'enviada_em' => now(),
        ]);
    }
}
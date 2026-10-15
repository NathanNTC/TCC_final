<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvaliacaoMedica extends Model
{
    use HasFactory;

    protected $fillable = [
        'triagem_id',
        'avaliador_id',
        'prioridade_avaliador',
        'nota_qualidade',
        'comentario_qualidade',
        'justificativa',
        'observacoes',
    ];

    public function triagem(): BelongsTo
    {
        return $this->belongsTo(Triagem::class);
    }

    public function avaliador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'avaliador_id');
    }

    public function concordaComIa(): bool
    {
        $prioridadeIa = $this->triagem?->prioridade_ia;

        return $prioridadeIa !== null
            && $prioridadeIa === $this->prioridade_avaliador;
    }
}
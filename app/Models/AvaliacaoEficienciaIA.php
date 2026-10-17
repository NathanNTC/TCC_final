<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvaliacaoEficienciaIA extends Model
{
    protected $table = 'avaliacoes_eficiencia_ia';

    protected $fillable = [
        'avaliador_id',
        'clareza_perguntas',
        'relevancia_perguntas',
        'coleta_dados',
        'qualidade_respostas',
        'utilidade_resumo',
        'eficiencia_geral',
        'pontos_melhoria',
        'comentarios',
    ];

    protected function casts(): array
    {
        return [
            'clareza_perguntas' => 'integer',
            'relevancia_perguntas' => 'integer',
            'coleta_dados' => 'integer',
            'qualidade_respostas' => 'integer',
            'utilidade_resumo' => 'integer',
            'eficiencia_geral' => 'integer',
        ];
    }

    public function avaliador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'avaliador_id');
    }
}

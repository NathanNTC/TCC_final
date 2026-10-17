<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Triagem extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'status',
        'prioridade_ia',
        'resumo_ia',
        'orientacao_ia',
        'possiveis_causas_ia',
        'resultado_completo_ia',
        'modelo_ia',
        'tempo_processamento_ms',
        'iniciada_em',
        'finalizada_em',
    ];

        /**
     * Feedback de experiência do paciente.
     */
    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    protected function casts(): array
    {
        return [
            'possiveis_causas_ia' => 'array',
            'resultado_completo_ia' => 'array',
            'iniciada_em' => 'datetime',
            'finalizada_em' => 'datetime',
        ];
    }

    /**
     * Paciente que realizou a triagem.
     */
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    /**
     * Mensagens da triagem.
     */
    public function mensagens(): HasMany
    {
        return $this->hasMany(MensagemTriagem::class)
            ->orderBy('ordem');
    }

    /**
     * Avaliações médicas da triagem.
     */
    public function avaliacoesMedicas(): HasMany
    {
        return $this->hasMany(AvaliacaoMedica::class);
    }
}
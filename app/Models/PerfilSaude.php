<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerfilSaude extends Model
{
    use HasFactory;

    protected $table = 'perfil_saude';

    protected $fillable = [
        'paciente_id',

        // Dados básicos de saúde
        'tipo_sanguineo',

        // Deficiências
        'deficiencias',
        'deficiencia_outros_descricao',
        'necessidades_acessibilidade',

        // Problemas de saúde prévios
        'problemas_saude_previos',
        'problemas_saude_detalhes',

        // Histórico familiar
        'historico_familiar',
        'historico_familiar_detalhes',

        // Alergias
        'alergias',
        'alergias_detalhes',

        // Hábitos
        'fumante',
        'alcoolismo',
        'uso_drogas',

        // Cirurgias
        'cirurgias_previas',

        // Atividade física
        'atividade_fisica_regular',

        // Medidas
        'altura',
        'peso',
    ];

    protected $casts = [
        // Campos armazenados como JSON
        'deficiencias' => 'array',
        'problemas_saude_previos' => 'array',
        'historico_familiar' => 'array',
        'alergias' => 'array',

        // Campos booleanos
        'fumante' => 'boolean',
        'alcoolismo' => 'boolean',
        'uso_drogas' => 'boolean',
        'atividade_fisica_regular' => 'boolean',

        // Campos numéricos
        'altura' => 'decimal:2',
        'peso' => 'decimal:2',
    ];

    /**
     * Perfil de saúde pertence a um paciente.
     */
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }
}
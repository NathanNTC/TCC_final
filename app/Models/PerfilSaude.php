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

        'tipo_sanguineo',

        // Deficiências
        'deficiencias',
        'deficiencia_outros_descricao',
        'necessidades_acessibilidade',

        // Problemas de saúde
        'problemas_saude_previos',
        'problemas_saude_detalhes',

        // Histórico familiar
        'historico_familiar',
        'historico_familiar_detalhes',

        // Medicamentos
        'medicacoes_uso_continuo',

        // Alergias
        'alergias',
        'alergias_detalhes',

        // Hábitos
        'fumante',
        'alcoolismo',
        'uso_drogas',

        // Outros
        'cirurgias_previas',
        'atividade_fisica_regular',
        'altura',
        'peso',
    ];

    protected $casts = [
        // Campos JSON
        'deficiencias' => 'array',
        'problemas_saude_previos' => 'array',
        'historico_familiar' => 'array',
        'alergias' => 'array',

        // Booleanos
        'fumante' => 'boolean',
        'alcoolismo' => 'boolean',
        'uso_drogas' => 'boolean',
        'atividade_fisica_regular' => 'boolean',

        // Números
        'altura' => 'decimal:2',
        'peso' => 'decimal:2',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }
}
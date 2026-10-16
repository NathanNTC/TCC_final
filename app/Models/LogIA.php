<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $triagem_id
 * @property string $modelo
 * @property int $tokens_entrada
 * @property int $tokens_saida
 * @property int $tokens_raciocinio
 * @property int $tokens_cache
 * @property int $tokens_total
 * @property float $custo_usd
 * @property float $custo_brl
 * @property int $tempo_processamento_ms
 * @property string $status
 * @property string|null $erro
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Triagem|null $triagem
 */
class LogIA extends Model
{
    use HasFactory;

    /**
     * Tabela associada ao model.
     *
     * @var string
     */
    protected $table = 'log_ias';

    /**
     * Os atributos que podem ser preenchidos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'triagem_id',
        'modelo',
        'tokens_entrada',
        'tokens_saida',
        'tokens_raciocinio',
        'tokens_cache',
        'tokens_total',
        'custo_usd',
        'custo_brl',
        'tempo_processamento_ms',
        'status',
        'erro',
    ];

    /**
     * Mapeamento dos tipos de dados dos atributos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'triagem_id' => 'integer',
            'tokens_entrada' => 'integer',
            'tokens_saida' => 'integer',
            'tokens_raciocinio' => 'integer',
            'tokens_cache' => 'integer',
            'tokens_total' => 'integer',
            'custo_usd' => 'decimal:8',
            'custo_brl' => 'decimal:6',
            'tempo_processamento_ms' => 'integer',
        ];
    }

    /**
     * Relacionamento com a triagem associada a este log de IA.
     */
    public function triagem(): BelongsTo
    {
        return $this->belongsTo(Triagem::class);
    }
}
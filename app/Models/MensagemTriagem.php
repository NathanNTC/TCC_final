<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MensagemTriagem extends Model
{
    use HasFactory;

    protected $fillable = [
        'triagem_id',
        'remetente',
        'mensagem',
        'ordem',
        'enviada_em',
    ];

    protected function casts(): array
    {
        return [
            'enviada_em' => 'datetime',
        ];
    }

    /**
     * Triagem à qual a mensagem pertence.
     */
    public function triagem(): BelongsTo
    {
        return $this->belongsTo(Triagem::class);
    }
}
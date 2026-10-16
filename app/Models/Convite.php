<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Convite extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'perfil',
        'criado_por',
        'utilizado_por',
        'expira_em',
        'utilizado_em',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'expira_em' => 'datetime',
            'utilizado_em' => 'datetime',
            'ativo' => 'boolean',
        ];
    }

    public function criador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'criado_por');
    }

    public function usuarioUtilizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilizado_por');
    }

    public function estaValido(): bool
    {
        return $this->ativo
            && $this->utilizado_em === null
            && (
                $this->expira_em === null
                || $this->expira_em->isFuture()
            );
    }
}
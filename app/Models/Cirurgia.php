<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cirurgia extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'tipo',
        'ano',
        'anestesia',
        'complicacoes',
        'implantes',
        'observacoes',
    ];

    protected $casts = [
        'complicacoes' => 'array',
        'implantes' => 'array',
        'ano' => 'integer',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }
}
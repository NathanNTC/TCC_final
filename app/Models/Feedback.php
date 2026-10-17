<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    protected $table = 'feedback';

    protected $fillable = [
        'user_id',
        'triagem_id',
        'nota',
        'facilidade',
        'clareza',
        'comentario',
    ];

    protected function casts(): array
    {
        return [
            'nota' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function triagem(): BelongsTo
    {
        return $this->belongsTo(Triagem::class);
    }
}

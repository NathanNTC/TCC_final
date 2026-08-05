<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'crm',
        'instituicao'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

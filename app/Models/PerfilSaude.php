<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilSaude extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}
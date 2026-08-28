<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cpf',
        'sexo',
        'data_nascimento',
        'telefone',
        'cidade',
        'estado',
        'profissao',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function perfilSaude()
    {
        return $this->hasOne(PerfilSaude::class);
    }

    public function medicamentos(): HasMany
    {
        return $this->hasMany(Medicamento::class);
    }

    public function triagens()
    {
        return $this->hasMany(Triagem::class);
    }
}
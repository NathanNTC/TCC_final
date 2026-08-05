<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'profissao'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function perfilSaude()
    {
        return $this->hasOne(PerfilSaude::class);
    }

    public function triagens()
    {
        return $this->hasMany(Triagem::class);
    }
}
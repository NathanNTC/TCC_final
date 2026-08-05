<?php

namespace App\Services\Paciente;

use App\Models\Paciente;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PacienteService
{
    public function cadastrar(array $dados): User
    {
        return DB::transaction(function () use ($dados) {

            $user = User::create([
                'name' => $dados['name'],
                'email' => $dados['email'],
                'password' => Hash::make($dados['password']),
            ]);

            $user->assignRole('Paciente');

            Paciente::create([
                'user_id' => $user->id,
            ]);

            event(new Registered($user));

            return $user;
        });
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'Administrador']);
        Role::firstOrCreate(['name' => 'Paciente']);
        Role::firstOrCreate(['name' => 'Medico']);
        Role::firstOrCreate(['name' => 'Estudante']);
        Role::firstOrCreate(['name' => 'Residente']);
    }
}
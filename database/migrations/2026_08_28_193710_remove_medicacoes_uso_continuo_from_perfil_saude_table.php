<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perfil_saude', function (Blueprint $table) {
            $table->dropColumn('medicacoes_uso_continuo');
        });
    }

    public function down(): void
    {
        Schema::table('perfil_saude', function (Blueprint $table) {
            $table->text('medicacoes_uso_continuo')->nullable();
        });
    }
};
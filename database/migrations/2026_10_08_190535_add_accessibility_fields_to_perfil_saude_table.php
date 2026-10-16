<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perfil_saude', function (Blueprint $table) {
            $table->text('deficiencia_outros_descricao')->nullable();
            $table->text('necessidades_acessibilidade')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('perfil_saude', function (Blueprint $table) {
            $table->dropColumn([
                'deficiencia_outros_descricao',
                'necessidades_acessibilidade',
            ]);
        });
    }
};


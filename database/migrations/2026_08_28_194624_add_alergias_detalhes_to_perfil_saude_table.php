<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perfil_saude', function (Blueprint $table) {
            $table->text('alergias_detalhes')
                ->nullable()
                ->after('alergias');
        });
    }

    public function down(): void
    {
        Schema::table('perfil_saude', function (Blueprint $table) {
            $table->dropColumn('alergias_detalhes');
        });
    }
};
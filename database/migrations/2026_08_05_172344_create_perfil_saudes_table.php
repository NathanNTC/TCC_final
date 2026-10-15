<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('perfil_saude', function (Blueprint $table) {

            $table->id();

            $table->foreignId('paciente_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('tipo_sanguineo')->nullable();

            $table->text('deficiencias')->nullable();

            $table->text('problemas_saude_previos')->nullable();

            $table->text('historico_familiar')->nullable();

            $table->text('medicacoes_uso_continuo')->nullable();

            $table->text('alergias')->nullable();

            $table->boolean('fumante')->default(false);

            $table->boolean('alcoolismo')->default(false);

            $table->boolean('uso_drogas')->default(false);

            $table->text('cirurgias_previas')->nullable();

            $table->boolean('atividade_fisica_regular')->default(false);

            $table->decimal('altura',4,2)->nullable();

            $table->decimal('peso',5,2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfil_saude');
    }
};

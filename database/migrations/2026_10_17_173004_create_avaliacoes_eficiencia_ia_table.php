
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avaliacoes_eficiencia_ia', function (Blueprint $table) {
            $table->id();

            // Avaliador responsável pela resposta
            $table->foreignId('avaliador_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Notas de 1 a 5
            $table->unsignedTinyInteger('clareza_perguntas');
            $table->unsignedTinyInteger('relevancia_perguntas');
            $table->unsignedTinyInteger('coleta_dados');
            $table->unsignedTinyInteger('qualidade_respostas');
            $table->unsignedTinyInteger('utilidade_resumo');
            $table->unsignedTinyInteger('eficiencia_geral');

            // Comentários opcionais
            $table->text('pontos_melhoria')->nullable();
            $table->text('comentarios')->nullable();

            $table->timestamps();

            // Cada avaliador terá uma avaliação geral,
            // que poderá ser atualizada posteriormente.
            $table->unique('avaliador_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacoes_eficiencia_ia');
    }
};

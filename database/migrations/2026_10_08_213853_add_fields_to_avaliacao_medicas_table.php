
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('avaliacao_medicas', function (Blueprint $table) {
            $table->foreignId('triagem_id')
                ->constrained('triagems')
                ->cascadeOnDelete();

            $table->foreignId('avaliador_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('prioridade_avaliador', 30);
            $table->text('justificativa')->nullable();
            $table->text('observacoes')->nullable();

            $table->unique(
                ['triagem_id', 'avaliador_id'],
                'avaliacao_triagem_avaliador_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('avaliacao_medicas', function (Blueprint $table) {
            $table->dropForeign(['triagem_id']);
            $table->dropForeign(['avaliador_id']);

            $table->dropUnique(
                'avaliacao_triagem_avaliador_unique'
            );

            $table->dropColumn([
                'triagem_id',
                'avaliador_id',
                'prioridade_avaliador',
                'justificativa',
                'observacoes',
            ]);
        });
    }
};
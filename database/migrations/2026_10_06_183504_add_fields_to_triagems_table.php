
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('triagems', function (Blueprint $table) {
            $table->foreignId('paciente_id')
                ->constrained('pacientes')
                ->cascadeOnDelete();

            $table->string('status', 30)
                ->default('em_andamento');

            $table->string('prioridade_ia', 30)->nullable();
            $table->text('resumo_ia')->nullable();
            $table->text('orientacao_ia')->nullable();

            $table->json('possiveis_causas_ia')->nullable();
            $table->json('resultado_completo_ia')->nullable();

            $table->string('modelo_ia')->nullable();

            $table->integer('tempo_processamento_ms')->nullable();

            $table->timestamp('iniciada_em')->nullable();
            $table->timestamp('finalizada_em')->nullable();

            $table->index(['paciente_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('triagems', function (Blueprint $table) {
            $table->dropForeign(['paciente_id']);
            $table->dropIndex(['paciente_id', 'status']);

            $table->dropColumn([
                'paciente_id',
                'status',
                'prioridade_ia',
                'resumo_ia',
                'orientacao_ia',
                'possiveis_causas_ia',
                'resultado_completo_ia',
                'modelo_ia',
                'tempo_processamento_ms',
                'iniciada_em',
                'finalizada_em',
            ]);
        });
    }
};
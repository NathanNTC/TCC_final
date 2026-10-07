<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa a migration.
     */
    public function up(): void
    {
        Schema::table('triagems', function (Blueprint $table) {
            $table->foreignId('paciente_id')
                ->after('id')
                ->constrained('pacientes')
                ->cascadeOnDelete();

            $table->string('status', 30)
                ->default('em_andamento')
                ->after('paciente_id');

            $table->string('prioridade_ia', 30)
                ->nullable()
                ->after('status');

            $table->text('resumo_ia')
                ->nullable()
                ->after('prioridade_ia');

            $table->text('orientacao_ia')
                ->nullable()
                ->after('resumo_ia');

            $table->json('possiveis_causas_ia')
                ->nullable()
                ->after('orientacao_ia');

            $table->json('resultado_completo_ia')
                ->nullable()
                ->after('possiveis_causas_ia');

            $table->string('modelo_ia')
                ->nullable()
                ->after('resultado_completo_ia');

            $table->unsignedInteger('tempo_processamento_ms')
                ->nullable()
                ->after('modelo_ia');

            $table->timestamp('iniciada_em')
                ->nullable()
                ->after('tempo_processamento_ms');

            $table->timestamp('finalizada_em')
                ->nullable()
                ->after('iniciada_em');

            $table->index(['paciente_id', 'status']);
        });
    }

    /**
     * Reverte a migration.
     */
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
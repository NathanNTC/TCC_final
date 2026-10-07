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
        Schema::table('mensagem_triagems', function (Blueprint $table) {
            $table->foreignId('triagem_id')
                ->after('id')
                ->constrained('triagems')
                ->cascadeOnDelete();

            $table->string('remetente', 20)
                ->after('triagem_id');

            $table->longText('mensagem')
                ->after('remetente');

            $table->unsignedInteger('ordem')
                ->default(0)
                ->after('mensagem');

            $table->timestamp('enviada_em')
                ->nullable()
                ->after('ordem');

            $table->index(['triagem_id', 'ordem']);
        });
    }

    /**
     * Reverte a migration.
     */
    public function down(): void
    {
        Schema::table('mensagem_triagems', function (Blueprint $table) {
            $table->dropForeign(['triagem_id']);

            $table->dropIndex(['triagem_id', 'ordem']);

            $table->dropColumn([
                'triagem_id',
                'remetente',
                'mensagem',
                'ordem',
                'enviada_em',
            ]);
        });
    }
};
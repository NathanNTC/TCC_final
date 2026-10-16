
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mensagem_triagems', function (Blueprint $table) {
            $table->foreignId('triagem_id')
                ->constrained('triagems')
                ->cascadeOnDelete();

            $table->string('remetente', 20);
            $table->text('mensagem');

            $table->integer('ordem')->default(0);

            $table->timestamp('enviada_em')->nullable();

            $table->index(['triagem_id', 'ordem']);
        });
    }

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
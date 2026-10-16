
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_ias', function (Blueprint $table) {
            $table->id();

            $table->foreignId('triagem_id')
                ->nullable()
                ->constrained('triagems')
                ->nullOnDelete();

            $table->string('modelo', 100);

            $table->unsignedInteger('tokens_entrada')->default(0);
            $table->unsignedInteger('tokens_saida')->default(0);
            $table->unsignedInteger('tokens_raciocinio')->default(0);
            $table->unsignedInteger('tokens_cache')->default(0);
            $table->unsignedInteger('tokens_total')->default(0);

            // Valores estimados, não valores confirmados da fatura.
            $table->decimal('custo_usd', 16, 8)->nullable();
            $table->decimal('custo_brl', 16, 6)->nullable();

            $table->unsignedInteger('tempo_processamento_ms')->nullable();

            $table->string('status', 30)->default('sucesso');
            $table->text('erro')->nullable();

            $table->timestamps();

            $table->index('created_at');
            $table->index(['triagem_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_ias');
    }
};

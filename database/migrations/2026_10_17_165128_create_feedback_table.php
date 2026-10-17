
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('triagem_id')
                ->constrained('triagems')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('nota');

            $table->string('facilidade', 30);

            $table->string('clareza', 30);

            $table->text('comentario')->nullable();

            $table->timestamps();

            // Um feedback por usuário para cada triagem.
            $table->unique(
                ['user_id', 'triagem_id'],
                'feedback_user_triagem_unique'
            );

            $table->index('nota');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('convites', function (Blueprint $table) {
            $table->string('codigo', 64)->unique();
            $table->string('perfil', 30);

            $table->foreignId('criado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('utilizado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('expira_em')->nullable();
            $table->timestamp('utilizado_em')->nullable();
            $table->boolean('ativo')->default(true);

            $table->index(['ativo', 'perfil']);
        });
    }

    public function down(): void
    {
        Schema::table('convites', function (Blueprint $table) {
            $table->dropForeign(['criado_por']);
            $table->dropForeign(['utilizado_por']);
            $table->dropIndex(['ativo', 'perfil']);

            $table->dropColumn([
                'codigo',
                'perfil',
                'criado_por',
                'utilizado_por',
                'expira_em',
                'utilizado_em',
                'ativo',
            ]);
        });
    }
};
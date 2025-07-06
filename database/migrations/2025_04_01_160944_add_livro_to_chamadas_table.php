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
        Schema::table('chamadas', function (Blueprint $table) {
            $table->boolean('livro')->default(false)->after('aluno_id'); // Adiciona a coluna 'livro'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chamadas', function (Blueprint $table) {
            $table->dropColumn('livro'); // Remove a coluna 'livro' se a migração for revertida
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->foreignId('turma_id')->after('igreja_id')->constrained('turmas');
            $table->integer('ano')->after('turma_id');
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropForeign(['turma_id']);
            $table->dropColumn(['turma_id', 'ano']);
        });
    }
};

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
        Schema::create('delegado_disciplinas', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('disciplina_id');
            $table->foreign('disciplina_id')->references('id')->on('disciplinas')->cascadeOnDelete();
            $table->primary(['user_id', 'disciplina_id']);
        });

        Schema::create('delegado_partidos', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('partido_id');
            $table->foreign('partido_id')->references('id')->on('partidos')->cascadeOnDelete();
            $table->primary(['user_id', 'partido_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delegado_partidos');
        Schema::dropIfExists('delegado_disciplinas');
    }
};

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
        Schema::create('nominas', function (Blueprint $table) {
            $table->id();
            $table->string('delegacion_id');
            $table->foreign('delegacion_id')->references('id')->on('delegaciones')->cascadeOnDelete();
            $table->string('disciplina_id');
            $table->foreign('disciplina_id')->references('id')->on('disciplinas')->cascadeOnDelete();
            $table->string('dni', 20);
            $table->string('nombre_completo');
            $table->string('numero_camiseta', 10)->nullable();
            $table->string('rol_equipo', 50)->default('Titular');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nominas');
    }
};

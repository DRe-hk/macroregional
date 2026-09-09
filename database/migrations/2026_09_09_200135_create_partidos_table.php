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
        Schema::create('partidos', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('serie_id')->constrained('series')->cascadeOnDelete();
            $table->integer('ronda_numero')->default(1);
            $table->string('ronda_nombre')->default('Ronda 1');
            $table->string('local_id')->nullable();
            $table->foreign('local_id')->references('id')->on('delegaciones')->nullOnDelete();
            $table->string('visitante_id')->nullable();
            $table->foreign('visitante_id')->references('id')->on('delegaciones')->nullOnDelete();
            $table->integer('local_goles')->nullable();
            $table->integer('visitante_goles')->nullable();
            $table->string('ganador_id')->nullable();
            $table->foreign('ganador_id')->references('id')->on('delegaciones')->nullOnDelete();
            $table->string('estado')->default('PROGRAMADO');
            $table->string('horario')->nullable();
            $table->string('cancha')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partidos');
    }
};

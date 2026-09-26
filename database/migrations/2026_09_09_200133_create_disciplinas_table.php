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
        Schema::create('disciplinas', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('disciplinas')->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('nombre');
            $table->string('categoria')->nullable();
            $table->string('genero')->nullable(); // Varones, Damas, Mixto
            $table->string('tipo')->default('COLECTIVO'); // COLECTIVO, INDIVIDUAL
            $table->string('sistema_puntuacion')->default('FUTBOL'); // FUTBOL, BASQUET, HANDBALL, VOLEIBOL, INDIVIDUAL
            $table->string('color_acento')->default('#2563eb');
            $table->text('foto_url')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('sede_principal')->nullable();
            $table->text('sede_maps_url')->nullable();
            $table->string('fechas_cronograma')->nullable();
            $table->string('horario_cronograma')->nullable();
            $table->string('campeon_actual')->nullable();
            $table->json('podio')->nullable(); // Para deportes individuales: { 'oro': ..., 'plata': ..., 'bronce': ... }
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disciplinas');
    }
};

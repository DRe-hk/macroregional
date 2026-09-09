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
            $table->string('slug')->unique();
            $table->string('nombre');
            $table->string('categoria');
            $table->string('color_acento')->default('#2563eb');
            $table->text('foto_url')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('sede_principal')->nullable();
            $table->text('sede_maps_url')->nullable();
            $table->string('campeon_actual')->nullable();
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

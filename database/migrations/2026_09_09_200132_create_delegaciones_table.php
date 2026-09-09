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
        Schema::create('delegaciones', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('nombre');
            $table->string('siglas');
            $table->string('provincia');
            $table->integer('puntos')->default(0);
            $table->integer('pj')->default(0);
            $table->integer('pg')->default(0);
            $table->integer('pe')->default(0);
            $table->integer('pp')->default(0);
            $table->integer('gf')->default(0);
            $table->integer('gc')->default(0);
            $table->integer('dg')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delegaciones');
    }
};

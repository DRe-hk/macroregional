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
        Schema::table('torneos', function (Blueprint $table) {
            $table->text('logo_url')->nullable()->after('sede_principal');
            $table->text('portada_url')->nullable()->after('logo_url');
        });

        Schema::table('delegaciones', function (Blueprint $table) {
            $table->text('logo_url')->nullable()->after('provincia');
        });

        Schema::table('disciplinas', function (Blueprint $table) {
            $table->text('foto_referencia_url')->nullable()->after('foto_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disciplinas', function (Blueprint $table) {
            $table->dropColumn('foto_referencia_url');
        });

        Schema::table('delegaciones', function (Blueprint $table) {
            $table->dropColumn('logo_url');
        });

        Schema::table('torneos', function (Blueprint $table) {
            $table->dropColumn(['logo_url', 'portada_url']);
        });
    }
};

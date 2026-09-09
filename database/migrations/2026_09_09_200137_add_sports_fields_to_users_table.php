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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('id');
            $table->string('role')->default('DELEGADO')->after('password');
            $table->string('delegacion_id')->nullable()->after('role');
            $table->foreign('delegacion_id')->references('id')->on('delegaciones')->nullOnDelete();
            $table->boolean('activo')->default(true)->after('delegacion_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['delegacion_id']);
            $table->dropColumn(['username', 'role', 'delegacion_id', 'activo']);
        });
    }
};

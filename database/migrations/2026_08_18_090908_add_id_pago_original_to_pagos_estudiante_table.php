<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pagos_estudiante', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pago_original')->nullable()->after('id_curso_estudiante');

            $table->foreign('id_pago_original')
                ->references('id_pagos_estudiante')
                ->on('pagos_estudiante')
                ->nullOnDelete();

            $table->index('id_pago_original');
        });
    }

    public function down(): void
    {
        Schema::table('pagos_estudiante', function (Blueprint $table) {
            $table->dropForeign(['id_pago_original']);
            $table->dropIndex(['id_pago_original']);
            $table->dropColumn('id_pago_original');
        });
    }
};
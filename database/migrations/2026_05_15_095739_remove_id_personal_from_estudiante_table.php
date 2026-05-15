<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('estudiante', function (Blueprint $table) {
            // Eliminamos la columna que está causando el error 500
            if (Schema::hasColumn('estudiante', 'id_personal')) {
                $table->dropColumn('id_personal');
            }
        });
    }

    public function down(): void
    {
        Schema::table('estudiante', function (Blueprint $table) {
            $table->unsignedBigInteger('id_personal')->nullable();
        });
    }
};
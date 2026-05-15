<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('estudiante', function (Blueprint $table) {
            // Verificamos si la columna existe antes de intentar borrarla
            if (Schema::hasColumn('estudiante', 'id_personal')) {
                // Si hay una llave foránea asociada, primero la quitamos
                try {
                    $table->dropForeign(['id_personal']);
                } catch (\Exception $e) {
                    // Si no tiene llave foránea, solo ignoramos el error
                }
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
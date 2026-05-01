<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pagos_estudiante', function (Blueprint $table) {
            // Añadimos la columna. La ponemos como decimal y que empiece en 0.
            $table->decimal('monto_pagado', 12, 2)->default(0)->after('monto_pagar');
        });
    }

    public function down(): void
    {
        Schema::table('pagos_estudiante', function (Blueprint $table) {
            $table->dropColumn('monto_pagado');
        });
    }
};

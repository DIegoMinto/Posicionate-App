<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('modulo', function (Blueprint $table) {
            $table->dropColumn([
                'fecha_inicio_tentativa',
                'fecha_fin_tentativa',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('modulo', function (Blueprint $table) {
            $table->date('fecha_inicio_tentativa')->nullable();
            $table->date('fecha_fin_tentativa')->nullable();
        });
    }
};

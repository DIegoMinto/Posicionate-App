<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('modulo', function (Blueprint $table) {
            $table->date('fecha_inicio_descanso')->nullable();
            $table->date('fecha_fin_descanso')->nullable();

            $table->dropColumn('dias_descanso');
        });
    }

    public function down(): void
    {
        Schema::table('modulo', function (Blueprint $table) {
            $table->unsignedSmallInteger('dias_descanso')->default(0);

            $table->dropColumn([
                'fecha_inicio_descanso',
                'fecha_fin_descanso',
            ]);
        });
    }
};

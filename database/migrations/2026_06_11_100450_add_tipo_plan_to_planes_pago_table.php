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
        Schema::table('planes_pago', function (Blueprint $table) {
            $table->enum('tipo_plan', [
                'CONTADO',
                'CUOTAS'
            ])->default('CONTADO')
                ->after('precio_base');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planes_pago', function (Blueprint $table) {
            $table->dropColumn('tipo_plan');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('planes_pago', function (Blueprint $table) {

            $table->boolean('tiene_titulacion')
                ->default(false);

            $table->decimal('monto_titulacion', 10, 2)
                ->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('planes_pago', function (Blueprint $table) {

            $table->dropColumn([
                'tiene_titulacion',
                'monto_titulacion'
            ]);

        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pagos_estudiante', function (Blueprint $table) {
            $table->string('numero_recibo', 6)->nullable()->unique()->after('estado');
        });

        $pagos = DB::table('pagos_estudiante')
            ->where('monto_pagado', '>', 0)
            ->orderBy('fecha_pagada')
            ->orderBy('id_pagos_estudiante')
            ->get();

        $contador = 1000;
        foreach ($pagos as $pago) {
            DB::table('pagos_estudiante')
                ->where('id_pagos_estudiante', $pago->id_pagos_estudiante)
                ->update(['numero_recibo' => str_pad($contador, 6, '0', STR_PAD_LEFT)]);
            $contador++;
        }
    }

    public function down(): void
    {
        Schema::table('pagos_estudiante', function (Blueprint $table) {
            $table->dropColumn('numero_recibo');
        });
    }
};
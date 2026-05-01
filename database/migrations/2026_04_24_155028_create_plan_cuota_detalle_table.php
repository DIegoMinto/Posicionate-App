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
        Schema::create('plan_cuota_detalle', function (Blueprint $table) {
            $table->id('id_plan_cuota_detalle');

            // Relación con la cabecera
            $table->unsignedBigInteger('id_planes_pago');

            $table->integer('nro_cuota');
            $table->decimal('monto_cuota', 10, 2);
            $table->date('fecha_vencimiento')->nullable(); // Nullable para la cuota 1
            $table->string('detalle')->nullable(); // Ej: "Pago inicial", "Cuota 2"

            $table->timestamps();

            // Relación con borrado en cascada (si borras el plan, se borran las cuotas)
            $table->foreign('id_planes_pago')
                ->references('id_planes_pago')
                ->on('planes_pago')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_cuota_detalle');
    }
};

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
        Schema::table('curso_estudiante', function (Blueprint $table) {
            // Añadimos las columnas como nulables por si ya tienes registros antiguos
            $table->unsignedBigInteger('id_planes_pago')->nullable()->after('id_curso');
            $table->unsignedBigInteger('id_descuento')->nullable()->after('id_planes_pago');

            // Creamos las llaves foráneas
            $table->foreign('id_planes_pago')
                ->references('id_planes_pago')
                ->on('planes_pago')
                ->onDelete('set null'); // Si se borra el plan, la inscripción no se borra

            $table->foreign('id_descuento')
                ->references('id_descuentos') // Ojo: tu tabla de descuentos usa 'id_descuentos'
                ->on('descuentos')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('curso_estudiante', function (Blueprint $table) {
            $table->dropForeign(['id_planes_pago']);
            $table->dropForeign(['id_descuento']);
            $table->dropColumn(['id_planes_pago', 'id_descuento']);
        });
    }
};

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
        Schema::create('pagos_estudiante', function (Blueprint $table) {
            $table->id('id_pagos_estudiante');

            // Aquí pusiste 'id', pero tú querías 'id_curso_estudiante'
            $table->unsignedBigInteger('id_curso_estudiante');

            $table->string('detalle');
            $table->decimal('monto_pagar', 10, 2);
            $table->date('fecha_programada');
            $table->dateTime('fecha_pagada')->nullable();
            $table->string('estado')->default('pendiente');
            $table->timestamps();

            // Ahora sí coinciden: 'id_curso_estudiante' apunta al 'id' de la otra tabla
            $table->foreign('id_curso_estudiante')
                ->references('id')
                ->on('curso_estudiante')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos_estudiante');
    }
};

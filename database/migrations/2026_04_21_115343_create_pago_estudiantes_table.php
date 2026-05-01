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
        Schema::create('pago_estudiantes', function (Blueprint $table) {
            $table->id('id_pagos_estudiante');
            $table->foreignId('id_curso_estudiante')
                ->constrained('curso_estudiante')
                ->onDelete('cascade');

            $table->string('detalle');
            $table->decimal('monto_pagar', 10, 2);
            $table->date('fecha_programada');
            $table->date('fecha_pagada')->nullable();

            $table->string('estado')->default('pendiente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago_estudiantes');
    }
};

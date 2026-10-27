<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('registros_retiro_abandono', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_curso_estudiante')
                ->constrained('curso_estudiante', 'id')
                ->restrictOnDelete();

            $table->string('tipo', 20);
            $table->date('fecha_registro');
            $table->text('modulos_cursados')->nullable();
            $table->text('detalle');

            $table->string('nombre_estudiante');
            $table->string('ci_estudiante');
            $table->string('telefono_estudiante')->nullable();
            $table->string('nombre_curso');

            $table->decimal('monto_total', 12, 2)->default(0);
            $table->decimal('monto_pagado', 12, 2)->default(0);
            $table->decimal('saldo_pendiente', 12, 2)->default(0);

            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registros_retiro_abandono');
    }
};
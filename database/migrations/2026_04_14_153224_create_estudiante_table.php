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
        Schema::create('estudiante', function (Blueprint $table) {
            $table->id('id_estudiante');

            // Datos de Identidad
            $table->string('ci', 20)->unique();
            $table->string('extension_ci', 10); // Ejemplo: CH, LP, SC
            $table->string('nombre', 100);
            $table->string('apellido_p', 100);
            $table->string('apellido_m', 100)->nullable();
            $table->date('fecha_nacimiento');
            $table->text('domicilio')->nullable();
            $table->string('telefono_movil', 20)->nullable();
            $table->string('correo_electronico', 150)->unique();
            $table->char('genero', 1); // M, F, O
            $table->boolean('estado')->default(true); // Activo/Inactivo

            // Llaves Foráneas (Relaciones)
            $table->unsignedBigInteger('id_ciudad');
            $table->unsignedBigInteger('id_personal'); // Quién lo registró
            $table->unsignedBigInteger('id_institucion_egreso');
            $table->unsignedBigInteger('id_grado_academico');
            $table->unsignedBigInteger('id_profesion');

            // Definición de constraints (Asegúrate que las tablas existan)
            $table->foreign('id_ciudad')->references('id_ciudad')->on('ciudad');
            $table->foreign('id_personal')->references('id_personal')->on('personal');
            $table->foreign('id_institucion_egreso')->references('id_institucion_egreso')->on('institucion_egreso');
            $table->foreign('id_grado_academico')->references('id_grado_academico')->on('grado_academico');
            $table->foreign('id_profesion')->references('id_profesion')->on('profesion');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiante');
    }
};

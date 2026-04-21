<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('docente', function (Blueprint $table) {
            $table->id('id_docente');

            // Datos personales
            $table->string('ci', 20)->unique();
            $table->string('extension_ci', 5);
            $table->string('nombre', 100);
            $table->string('apellido_p', 100);
            $table->string('apellido_m', 100)->nullable();
            $table->date('fecha_nacimiento');
            $table->string('domicilio', 255);
            $table->string('telefono_movil', 20);
            $table->string('correo_electronico', 150)->unique();
            $table->enum('genero', ['M', 'F']);

            // Archivos
            $table->string('curriculum'); // PDF
            $table->string('fotocarnet'); // PDF
            $table->string('fotografia'); // JPG

            // Financiero y Proyectos
            $table->string('numero_cuenta_bancaria', 50)->nullable();
            $table->enum('emite_factura', ['SI', 'NO'])->default('NO');
            $table->text('programas_dar')->nullable();

            // --- LLAVES FORÁNEAS (Ajustadas a tus tablas en singular) ---
            $table->unsignedBigInteger('id_ciudad');
            $table->unsignedBigInteger('id_institucion_egreso');
            $table->unsignedBigInteger('id_grado_academico');
            $table->unsignedBigInteger('id_profesion');
            $table->unsignedBigInteger('id_institucion_bancaria');

            // Constraints exactos según tu imagen
            $table->foreign('id_ciudad')->references('id_ciudad')->on('ciudad');
            $table->foreign('id_institucion_egreso')->references('id_institucion_egreso')->on('institucion_egreso');
            $table->foreign('id_grado_academico')->references('id_grado_academico')->on('grado_academico');
            $table->foreign('id_profesion')->references('id_profesion')->on('profesion');
            $table->foreign('id_institucion_bancaria')->references('id_institucion_bancaria')->on('institucion_bancaria');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docente');
    }
};
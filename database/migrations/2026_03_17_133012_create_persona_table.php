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
        Schema::create('persona', function (Blueprint $table) {
            $table->id('id_persona');
            $table->string('ci', 20)->unique();
            $table->string('nombre', 100);
            $table->string('apellido_p', 100);
            $table->string('apellido_m', 100)->nullable();
            $table->date('fecha_nacimiento');
            $table->string('domicilio', 255)->nullable();
            $table->text('enlace_ubicacion_maps')->nullable();
            $table->string('telefono_movil', 20);
            $table->string('correo_electronico', 150)->unique();
            $table->enum('genero', ['M', 'F', 'Otro']);
            $table->string('curriculum')->nullable();
            $table->string('foto_carnet')->nullable();
            $table->string('fotografia')->nullable();
            $table->string('numero_cuenta_bancaria')->nullable();
            $table->string('referencia_familiar_1')->nullable();
            $table->string('celular_familiar_1')->nullable();
            $table->string('referencia_familiar_2')->nullable();
            $table->string('celular_familiar_2')->nullable();
            $table->text('habilidades_tecnicas')->nullable();
            $table->text('habilidades_blandas')->nullable();


            $table->unsignedBigInteger('id_ciudad');
            $table->unsignedBigInteger('id_institucion_egreso');
            $table->unsignedBigInteger('id_grado_academico');
            $table->unsignedBigInteger('id_profesion');
            $table->unsignedBigInteger('id_institucion_bancaria')->nullable();

            $table->foreign('id_ciudad')->references('id_ciudad')->on('ciudad');
            $table->foreign('id_institucion_egreso')->references('id_institucion_egreso')->on('institucion_egreso');
            $table->foreign('id_grado_academico')->references('id_grado_academico')->on('grado_academico');
            $table->foreign('id_profesion')->references('id_profesion')->on('profesion');
            $table->foreign('id_institucion_bancaria')->references('id_institucion_bancaria')->on('institucion_bancaria');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persona');
    }
};

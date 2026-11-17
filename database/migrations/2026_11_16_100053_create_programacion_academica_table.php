<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('programacion_academica', function (Blueprint $table) {
            $table->id('id_programacion');

            // Un diplomado tendrá una sola programación
            $table->unsignedBigInteger('id_curso')->unique();

            // Datos generales
            $table->unsignedSmallInteger('duracion_meses');
            $table->unsignedSmallInteger('cantidad_modulos');

            // Días de clases: por ejemplo ["martes", "jueves"]
            $table->json('dias_clase')->nullable();

            // Horario general
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();

            // Carga horaria total
            $table->decimal('carga_horaria', 8, 2)->nullable();

            // Coordinador opcional
            $table->unsignedBigInteger('id_docente_coordinador')->nullable();

            $table->timestamps();

            $table->foreign('id_curso')
                ->references('id_curso')
                ->on('curso')
                ->onDelete('cascade');

            $table->foreign('id_docente_coordinador')
                ->references('id_docente')
                ->on('docente')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programacion_academica');
    }
};

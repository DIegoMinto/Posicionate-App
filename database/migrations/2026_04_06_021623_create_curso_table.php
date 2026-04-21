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
        Schema::create('curso', function (Blueprint $table) {
            $table->id('id_curso');
            $table->string('nombre', 200);
            $table->string('codigo_qr', 255)->nullable(); // Ruta de la imagen del QR
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();

            // Estado: puede ser 'Activo', 'Finalizado', 'Pendiente'
            $table->string('estado', 50)->default('Activo');

            // Contadores
            $table->integer('inscritos')->default(0);
            $table->integer('pre_inscritos')->default(0);

            // Relaciones (Llaves Foráneas)
            $table->unsignedBigInteger('id_docente');
            $table->unsignedBigInteger('id_institucion');
            $table->unsignedBigInteger('id_sede');

            // Definición de las restricciones
            $table->foreign('id_docente')->references('id_docente')->on('docente')->onDelete('cascade');
            $table->foreign('id_institucion')->references('id_institucion')->on('institucion')->onDelete('cascade');
            $table->foreign('id_sede')->references('id_sede')->on('sede')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curso');
    }
};

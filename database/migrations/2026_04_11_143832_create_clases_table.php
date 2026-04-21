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
        Schema::create('clase', function (Blueprint $table) {
            $table->id('id_clase'); // Primary Key
            $table->string('nombre', 150);
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->string('tipo', 50); // Ej: Presencial, Virtual, Examen

            // Llave foránea relacionada con la tabla curso
            // Asumiendo que el PK de curso es 'id_curso'
            $table->foreignId('id_curso')
                ->constrained('curso', 'id_curso')
                ->onDelete('cascade') // Si se borra el curso, se borran sus clases
                ->onUpdate('cascade');

            $table->timestamps();
        });
    }
};

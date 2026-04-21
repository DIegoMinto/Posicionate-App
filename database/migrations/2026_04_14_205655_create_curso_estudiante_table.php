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
        Schema::create('curso_estudiante', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_curso');
            $table->unsignedBigInteger('id_estudiante');

            $table->string('estado')->default('pre_inscrito');

            $table->timestamps();

            // Relaciones
            $table->foreign('id_curso')
                ->references('id_curso')
                ->on('curso')
                ->onDelete('cascade');

            $table->foreign('id_estudiante')
                ->references('id_estudiante')
                ->on('estudiante')
                ->onDelete('cascade');

            // Evita duplicados (MUY IMPORTANTE)
            $table->unique(['id_curso', 'id_estudiante']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curso_estudiante');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('docentes_adicionales', function (Blueprint $table) {
            $table->id('id_docentes_adicionales');
            $table->unsignedBigInteger('id_docente');
            $table->unsignedBigInteger('id_curso');
            $table->timestamps();

            // Relaciones (Constreñimientos)
            $table->foreign('id_docente')->references('id_docente')->on('docente')->onDelete('cascade');
            $table->foreign('id_curso')->references('id_curso')->on('curso')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docentes_adicionales');
    }
};

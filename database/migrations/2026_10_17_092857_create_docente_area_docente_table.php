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
        Schema::create('docente_area_docente', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_docente');
            $table->unsignedBigInteger('id_area_docente');

            $table->timestamps();

            $table->foreign('id_docente')
                ->references('id_docente')
                ->on('docente')
                ->cascadeOnDelete();

            $table->foreign('id_area_docente')
                ->references('id_area_docente')
                ->on('area_docente')
                ->cascadeOnDelete();

            $table->unique(['id_docente', 'id_area_docente']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docente_area_docente');
    }
};

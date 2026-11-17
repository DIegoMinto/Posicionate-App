<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('masterclass', function (Blueprint $table) {
            $table->id('id_masterclass');

            $table->unsignedBigInteger('id_curso');
            $table->unsignedBigInteger('id_docente')->nullable();

            $table->string('titulo', 255);
            $table->date('fecha_inicio');

            $table->timestamps();

            $table->foreign('id_curso')
                ->references('id_curso')
                ->on('curso')
                ->cascadeOnDelete();

            $table->foreign('id_docente')
                ->references('id_docente')
                ->on('docente')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('masterclass');
    }
};
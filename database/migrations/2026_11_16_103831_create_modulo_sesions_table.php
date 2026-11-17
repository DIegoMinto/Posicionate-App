<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('modulo_sesion', function (Blueprint $table) {

            $table->id('id_sesion');

            $table->unsignedBigInteger('id_modulo');

            $table->unsignedSmallInteger('numero_sesion');

            $table->string('titulo')->nullable();

            $table->date('fecha');

            $table->time('hora_inicio');

            $table->time('hora_fin');

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->foreign('id_modulo')
                ->references('id_modulo')
                ->on('modulo')
                ->cascadeOnDelete();

            $table->unique(
                ['id_modulo', 'numero_sesion'],
                'modulo_sesion_numero_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modulo_sesion');
    }
};

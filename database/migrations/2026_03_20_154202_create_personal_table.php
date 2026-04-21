<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('personal', function (Blueprint $table) {
            $table->id('id_personal');
            $table->unsignedBigInteger('id_persona')->unique();
            $table->string('codigo_personal')->unique();
            $table->string('user')->unique();
            $table->string('password');
            $table->string('rol')->default('admin');
            // Quitamos el ->after() porque en un Create no funciona
            $table->string('cargo')->nullable();
            $table->timestamps();

            $table->foreign('id_persona')->references('id_persona')->on('persona')->onDelete('cascade');
            $table->boolean('es_vigente')->default(true);
        });
    }

    public function down(): void
    {
        // El down debe borrar la tabla completa
        Schema::dropIfExists('personal');
    }
};
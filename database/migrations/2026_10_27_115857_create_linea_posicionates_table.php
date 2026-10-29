<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lineas_posicionate', function (Blueprint $table) {
            $table->id();
            $table->string('celular', 20)->unique();
            $table->string('tipo', 50)->nullable();
            $table->string('pertenencia_tipo', 20)->default('area');
            $table->unsignedBigInteger('id_persona')->nullable();
            $table->string('pertenencia_texto')->nullable();
            $table->string('correo')->nullable();
            $table->text('contrasena')->nullable();
            $table->date('fecha_ultima_recarga')->nullable();
            $table->date('fecha_siguiente_recarga')->nullable();
            $table->timestamps();

            $table->foreign('id_persona')
                ->references('id_persona')
                ->on('persona')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lineas_posicionate');
    }
};
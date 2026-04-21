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
        Schema::create('banco_pais', function (Blueprint $table) {
            $table->id('id_banco_pais');
            $table->foreignId('id_pais')->constrained('pais', 'id_pais');
            $table->foreignId('id_institucion_bancaria')->constrained('institucion_bancaria', 'id_institucion_bancaria');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banco_pais');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('personal_cargo', function (Blueprint $table) {
            $table->foreignId('id_personal')->constrained('personal', 'id_personal')->cascadeOnDelete();
            $table->foreignId('id_cargo')->constrained('cargos', 'id_cargo')->cascadeOnDelete();
            $table->primary(['id_personal', 'id_cargo']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_cargo');
    }
};

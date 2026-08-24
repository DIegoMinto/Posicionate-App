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
        Schema::create('personal_rol', function (Blueprint $table) {
            $table->foreignId('id_personal')->constrained('personal', 'id_personal')->cascadeOnDelete();
            $table->foreignId('id_rol')->constrained('roles', 'id_rol')->cascadeOnDelete();
            $table->primary(['id_personal', 'id_rol']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_rol');
    }
};

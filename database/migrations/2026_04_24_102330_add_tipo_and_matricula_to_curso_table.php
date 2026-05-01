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
        Schema::table('curso', function (Blueprint $table) {
            $table->enum('tipo', ['CURSO', 'PROGRAMA', 'DIPLOMADO'])->default('CURSO');
            $table->decimal('costo_matricula', 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('curso', function (Blueprint $table) {
            //
        });
    }
};

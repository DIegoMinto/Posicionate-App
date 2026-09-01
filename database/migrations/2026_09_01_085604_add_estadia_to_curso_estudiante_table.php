<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('curso_estudiante', function (Blueprint $table) {
            $table->enum('estadia', ['activo', 'abandono', 'retirado'])->default('activo')->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('curso_estudiante', function (Blueprint $table) {
            $table->dropColumn('estadia');
        });
    }
};

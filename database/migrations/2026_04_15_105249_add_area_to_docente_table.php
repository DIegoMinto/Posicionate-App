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
        Schema::table('docente', function (Blueprint $table) {
            $table->string('area')->nullable(); // 👈 clave
        });
    }

    public function down(): void
    {
        Schema::table('docente', function (Blueprint $table) {
            $table->dropColumn('area');
        });
    }

};

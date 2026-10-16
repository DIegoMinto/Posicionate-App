<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('docente', function (Blueprint $table) {
            $table->bigInteger('id_ciudad')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('docente', function (Blueprint $table) {
            $table->bigInteger('id_ciudad')->nullable(false)->change();
        });
    }
};
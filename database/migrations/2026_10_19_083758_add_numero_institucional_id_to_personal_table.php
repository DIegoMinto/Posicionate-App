<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('personal', function (Blueprint $table) {
            $table->unsignedBigInteger('id_numero_institucional')
                ->nullable();

            $table->foreign('id_numero_institucional')
                ->references('id_numero_institucional')
                ->on('numero_institucional')
                ->nullOnDelete();

            $table->unique('id_numero_institucional');
        });
    }

    public function down(): void
    {
        Schema::table('personal', function (Blueprint $table) {
            $table->dropForeign(['id_numero_institucional']);
            $table->dropUnique(['id_numero_institucional']);
            $table->dropColumn('id_numero_institucional');
        });
    }
};

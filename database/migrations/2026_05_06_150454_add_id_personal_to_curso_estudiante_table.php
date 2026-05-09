<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('curso_estudiante', function (Blueprint $table) {

            $table->unsignedBigInteger('id_personal')->nullable()->after('id_estudiante');

            $table->foreign('id_personal')
                ->references('id_personal')
                ->on('personal')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('curso_estudiante', function (Blueprint $table) {

            $table->dropForeign(['id_personal']);
            $table->dropColumn('id_personal');
        });
    }
};

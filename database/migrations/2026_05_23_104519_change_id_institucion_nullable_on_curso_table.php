<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('curso', function (Blueprint $table) {

            $table->dropForeign(['id_institucion']);

            $table->unsignedBigInteger('id_institucion')
                ->nullable()
                ->change();

            $table->foreign('id_institucion')
                ->references('id_institucion')
                ->on('institucion')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('curso', function (Blueprint $table) {

            $table->dropForeign(['id_institucion']);

            $table->unsignedBigInteger('id_institucion')
                ->nullable(false)
                ->change();

            $table->foreign('id_institucion')
                ->references('id_institucion')
                ->on('institucion')
                ->cascadeOnDelete();
        });
    }
};
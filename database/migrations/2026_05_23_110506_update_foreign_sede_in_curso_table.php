<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('curso', function (Blueprint $table) {

            $table->dropForeign(['id_sede']);
            $table->unsignedBigInteger('id_sede')->nullable()->change();
            $table->foreign('id_sede')
                ->references('id_sede')
                ->on('sede')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('curso', function (Blueprint $table) {

            $table->dropForeign(['id_sede']);

            $table->unsignedBigInteger('id_sede')->nullable(false)->change();

            $table->foreign('id_sede')
                ->references('id_sede')
                ->on('sede')
                ->onDelete('cascade');
        });
    }
};
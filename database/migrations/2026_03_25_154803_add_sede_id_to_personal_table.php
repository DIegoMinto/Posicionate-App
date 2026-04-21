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
        Schema::table('personal', function (Blueprint $table) {
            $table->unsignedBigInteger('id_sede')->nullable()->after('id_personal');

            $table->foreign('id_sede')->references('id_sede')->on('sede')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal', function (Blueprint $table) {
            //
        });
    }
};

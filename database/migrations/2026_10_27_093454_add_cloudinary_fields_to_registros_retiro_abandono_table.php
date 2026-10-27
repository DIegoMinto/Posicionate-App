<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('registros_retiro_abandono', function (Blueprint $table) {
            $table->text('pdf_url')->nullable();
            $table->string('pdf_public_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('registros_retiro_abandono', function (Blueprint $table) {
            $table->dropColumn(['pdf_url', 'pdf_public_id']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('registros_retiro_abandono', function (Blueprint $table) {
            $table->string('nombre_emisor')->nullable();
            $table->text('adjunto_url')->nullable();
            $table->string('adjunto_public_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('registros_retiro_abandono', function (Blueprint $table) {
            $table->dropColumn([
                'nombre_emisor',
                'adjunto_url',
                'adjunto_public_id',
            ]);
        });
    }
};
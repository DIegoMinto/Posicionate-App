<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('estudiante', function (Blueprint $table) {
            $table->string('moodle_usuario')->nullable()->unique()->after('correo_electronico');
            $table->string('moodle_password')->nullable()->after('moodle_usuario');
            $table->enum('moodle_habilitado', ['pendiente', 'habilitado'])
                ->default('pendiente')
                ->after('moodle_password');
        });
    }

    public function down(): void
    {
        Schema::table('estudiante', function (Blueprint $table) {
            $table->dropColumn(['moodle_usuario', 'moodle_password', 'moodle_habilitado']);
        });
    }
};
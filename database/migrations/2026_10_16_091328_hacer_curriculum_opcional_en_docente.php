<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('docente', function (Blueprint $table) {
            $table->string('curriculum')->nullable()->change();
        });
    }

    public function down(): void
    {
        // No revertir a NOT NULL automáticamente:
        // podrían existir docentes sin currículum.
    }
};
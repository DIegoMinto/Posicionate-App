<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('ALTER TABLE curso_estudiante ALTER COLUMN id_personal SET NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE curso_estudiante ALTER COLUMN id_personal DROP NOT NULL');
    }
};

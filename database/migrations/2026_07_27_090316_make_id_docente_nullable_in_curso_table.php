<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {

        DB::statement('ALTER TABLE curso ALTER COLUMN id_docente DROP NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE curso ALTER COLUMN id_docente SET NOT NULL');
    }
};
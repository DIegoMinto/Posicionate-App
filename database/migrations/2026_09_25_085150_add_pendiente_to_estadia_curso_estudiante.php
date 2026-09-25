<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE curso_estudiante DROP CONSTRAINT IF EXISTS curso_estudiante_estadia_check");

        DB::statement("ALTER TABLE curso_estudiante ADD CONSTRAINT curso_estudiante_estadia_check CHECK (estadia::text = ANY (ARRAY['pendiente','activo','abandono','retirado']::text[]))");

        DB::statement("ALTER TABLE curso_estudiante ALTER COLUMN estadia SET DEFAULT 'pendiente'");

        DB::table('curso_estudiante')
            ->where('estado', 'pre_inscrito')
            ->update(['estadia' => 'pendiente']);
    }

    public function down(): void
    {
        DB::table('curso_estudiante')
            ->where('estadia', 'pendiente')
            ->update(['estadia' => 'activo']);

        DB::statement("ALTER TABLE curso_estudiante ALTER COLUMN estadia SET DEFAULT 'activo'");

        DB::statement("ALTER TABLE curso_estudiante DROP CONSTRAINT IF EXISTS curso_estudiante_estadia_check");

        DB::statement("ALTER TABLE curso_estudiante ADD CONSTRAINT curso_estudiante_estadia_check CHECK (estadia::text = ANY (ARRAY['activo','abandono','retirado']::text[]))");
    }
};
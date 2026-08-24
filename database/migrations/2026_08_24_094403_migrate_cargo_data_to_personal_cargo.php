<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $cargos = [
            ['nombre' => 'gerente_marketing', 'nombre_visible' => 'Gerente de Marketing'],
            ['nombre' => 'supervisor_marketing', 'nombre_visible' => 'Supervisor de Marketing'],
            ['nombre' => 'coordinador_marketing', 'nombre_visible' => 'Coordinador de Marketing'],
            ['nombre' => 'asesor_marketing', 'nombre_visible' => 'Asesor de Marketing'],
            ['nombre' => 'supervisor_academico', 'nombre_visible' => 'Supervisor Académico'],
            ['nombre' => 'coordinador_academico', 'nombre_visible' => 'Coordinador Académico'],
            ['nombre' => 'asistente_academico', 'nombre_visible' => 'Asistente Académico'],
            ['nombre' => 'contador', 'nombre_visible' => 'Contador'],
            ['nombre' => 'asistente_contable', 'nombre_visible' => 'Asistente Contable'],
            ['nombre' => 'recursos_humanos', 'nombre_visible' => 'Recursos Humanos'],
        ];

        foreach ($cargos as $cargo) {
            DB::table('cargos')->updateOrInsert(
                ['nombre' => $cargo['nombre']],
                [
                    'nombre_visible' => $cargo['nombre_visible'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        DB::table('personal')->whereNotNull('cargo')->get()->each(function ($p) {
            $idCargo = DB::table('cargos')->where('nombre', $p->cargo)->value('id_cargo');

            if ($idCargo) {
                DB::table('personal_cargo')->insert([
                    'id_personal' => $p->id_personal,
                    'id_cargo' => $idCargo,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    public function down(): void
    {
        DB::table('personal_cargo')->truncate();
    }
};
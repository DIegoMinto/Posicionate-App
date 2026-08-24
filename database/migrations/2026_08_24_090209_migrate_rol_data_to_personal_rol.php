<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $roles = [
            ['nombre' => 'super_admin', 'nombre_visible' => 'Super Administrador'],
            ['nombre' => 'admin', 'nombre_visible' => 'Administrador'],
            ['nombre' => 'user', 'nombre_visible' => 'Usuario'],
            ['nombre' => 'viewer', 'nombre_visible' => 'Solo Lectura'],
        ];

        foreach ($roles as $rol) {
            DB::table('roles')->updateOrInsert(
                ['nombre' => $rol['nombre']],
                [
                    'nombre_visible' => $rol['nombre_visible'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        DB::table('personal')->whereNotNull('rol')->get()->each(function ($p) {
            $idRol = DB::table('roles')->where('nombre', $p->rol)->value('id_rol');

            if ($idRol) {
                DB::table('personal_rol')->insert([
                    'id_personal' => $p->id_personal,
                    'id_rol' => $idRol,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    public function down(): void
    {
        DB::table('personal_rol')->truncate();
    }
};
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persona;
use App\Models\Personal;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class MainAdminSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Personal::truncate();
        Persona::truncate();
        Schema::enableForeignKeyConstraints();

        // 1. Crear la Persona con CADA campo del fillable
        $persona = Persona::create([
            'nombre' => 'Javier Diego',
            'apellido_p' => 'Minto',
            'apellido_m' => 'Arze',
            'ci' => '10384292',
            'extension_ci' => 'CH',
            'fecha_nacimiento' => '2004-03-29',
            'domicilio' => 'Calle San Martín de Porres #96, Sucre',
            'enlace_ubicacion_maps' => 'https://maps.app.goo.gl/xxxxx',
            'telefono_movil' => '+591 75780041',
            'correo_electronico' => 'diegominto2@gmail.com',
            'genero' => 'M',
            'curriculum' => 'cv_diego.pdf',
            'foto_carnet' => 'fc_diego.jpg',
            'fotografia' => 'foto_diego.png',
            'numero_cuenta_bancaria' => '123456789',
            'referencia_familiar_1' => 'Referencia 1',
            'celular_familiar_1' => '70000001',
            'referencia_familiar_2' => 'Referencia 2',
            'celular_familiar_2' => '70000002',
            'habilidades_tecnicas' => 'PHP, Laravel, Docker, Postgres',
            'habilidades_blandas' => 'Liderazgo, Resolución de problemas',
            'id_ciudad' => 1,
            'id_institucion_egreso' => 1,
            'id_grado_academico' => 1,
            'id_profesion' => 1,
            'id_institucion_bancaria' => 1,
        ]);

        // 2. Crear el Usuario Personal
        Personal::create([
            'id_persona' => $persona->id_persona,
            'codigo_personal' => 'ADM-001',
            'user' => 'DM29Diego',
            'password' => Hash::make('10384292'),
            'rol' => 'admin',
            'cargo' => 'Ingeniero de TI',
        ]);

        $this->command->info('¡Usuario creado con todos los campos obligatorios!');
    }
}
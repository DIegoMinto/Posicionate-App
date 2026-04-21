<?php

namespace Database\Seeders;

use App\Models\Pais;
use App\Models\Departamento;
use App\Models\Ciudad;
use App\Models\Profesion;
use App\Models\GradoAcademico;
use App\Models\InstitucionEgreso;
use App\Models\InstitucionBancaria;
use App\Models\Persona;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UbicacionBoliviaSeeder extends Seeder
{
    public function run(): void
    {
        $pais = Pais::create(['nombre' => 'Bolivia', 'codigo_numero' => '+591']);
        $banco = InstitucionBancaria::create(['nombre' => 'Banco Nacional de Bolivia (BNB)']);
        DB::table('banco_pais')->insert([
            'id_pais' => 1,
            'id_institucion_bancaria' => $banco->id_institucion_bancaria
        ]);

        $deps = [
            ['nombre' => 'Chuquisaca', 'extension' => 'CH'],
            ['nombre' => 'La Paz', 'extension' => 'LP'],
            ['nombre' => 'Santa Cruz', 'extension' => 'SC'],
            ['nombre' => 'Cochabamba', 'extension' => 'CB'],
            ['nombre' => 'Oruro', 'extension' => 'OR'],
            ['nombre' => 'Potosí', 'extension' => 'PT'],
            ['nombre' => 'Tarija', 'extension' => 'TJ'],
            ['nombre' => 'Beni', 'extension' => 'BE'],
            ['nombre' => 'Pando', 'extension' => 'PA'],
        ];


        foreach ($deps as $d) {
            $depto = Departamento::create([
                'nombre' => $d['nombre'],
                'extension_ci' => $d['extension'],
                'id_pais' => $pais->id_pais
            ]);

            Ciudad::create([
                'nombre' => ($d['nombre'] == 'Chuquisaca') ? 'Sucre' : $d['nombre'],
                'id_departamento' => $depto->id_departamento
            ]);
        }

        Profesion::create(['nombre' => 'Ingeniero de Sistemas']);
        Profesion::create(['nombre' => 'Licenciado en Comercio Exterior']);
        Profesion::create(['nombre' => 'Licenciado en Administracion de Empresas']);
        Profesion::create(['nombre' => 'Ingeniero Comercial']);

        GradoAcademico::create(['nombre' => 'Licenciatura']);
        GradoAcademico::create(['nombre' => 'Técnico Superior']);
        GradoAcademico::create(['nombre' => 'Técnico Medio']);

        InstitucionEgreso::create(['nombre' => 'Universidad de San Francisco Xavier (USFX)']);
        InstitucionEgreso::create(['nombre' => 'Universidad Privada Domingo Savio (UPDS)']);

        InstitucionBancaria::create(['nombre' => 'Banco Nacional de Bolivia']);
        InstitucionBancaria::create(['nombre' => 'Banco Unión']);
        InstitucionBancaria::create(['nombre' => 'Banco Visa']);
        InstitucionBancaria::create(['nombre' => 'Banco Sol']);
        InstitucionBancaria::create(['nombre' => 'Banco Mercantil de Santa Cruz']);
        InstitucionBancaria::create(['nombre' => 'Banco Económico']);
        InstitucionBancaria::create(['nombre' => 'Banco Prodem']);
    }
}
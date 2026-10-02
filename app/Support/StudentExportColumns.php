<?php

namespace App\Support;

use Carbon\Carbon;

class StudentExportColumns
{
    public static function labels(): array
    {
        return [
            'ci' => 'CI',
            'extension_ci' => 'Ext',
            'nombre' => 'Nombre',
            'apellido_p' => 'Apellido Paterno',
            'apellido_m' => 'Apellido Materno',
            'estado_civil' => 'Estado Civil',
            'telefono' => 'Número de Celular',
            'correo' => 'Correo Electrónico',
            'fecha_nacimiento' => 'Fecha de Nacimiento',
            'lugar_nacimiento' => 'Lugar de Nacimiento',
            'domicilio' => 'Domicilio / Zona',
            'asesor' => 'Asesor',
            'fecha' => 'Fecha de Registro',
            'cuota_inicial' => 'Cuota Inicial',
            'estado' => 'Estado',
            'estadia' => 'Estadía',
        ];
    }

    public static function value($e, string $key, int $index = 0)
    {
        return match ($key) {
            'nro' => $index + 1,
            'ci' => $e->ci,
            'extension_ci' => $e->extension_ci,
            'nombre' => $e->nombre,
            'apellido_p' => $e->apellido_p,
            'apellido_m' => $e->apellido_m,
            'estado_civil' => $e->estado_civil
            ? ucfirst(str_replace('_', ' ', $e->estado_civil)) : '-',
            'telefono' => $e->telefono_movil ?: '-',
            'correo' => $e->correo_electronico ?: '-',
            'fecha_nacimiento' => $e->fecha_nacimiento
            ? Carbon::parse($e->fecha_nacimiento)->format('d/m/Y') : '-',
            'lugar_nacimiento' => $e->ciudad_residencia ? mb_convert_case($e->ciudad_residencia, MB_CASE_TITLE, 'UTF-8') : '-',
            'domicilio' => $e->domicilio ?: '-',
            'asesor' => trim($e->asesor_nombre . ' ' . $e->asesor_apellido),
            'fecha' => Carbon::parse($e->fecha_inscripcion)->format('d/m/Y H:i'),
            'cuota_inicial' => $e->cuota_inicial !== null
            ? number_format((float) $e->cuota_inicial, 2) : '-',
            'estado' => $e->estado,
            'estadia' => $e->estadia,
            default => '',
        };
    }
}
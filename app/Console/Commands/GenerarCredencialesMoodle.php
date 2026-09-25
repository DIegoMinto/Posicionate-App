<?php

namespace App\Console\Commands;

use App\Models\Estudiante;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerarCredencialesMoodle extends Command
{
    protected $signature = 'moodle:generar-credenciales';
    protected $description = 'Genera usuario y contraseña de Moodle para estudiantes inscritos que aún no las tienen';

    public function handle()
    {
        // Solo estudiantes que ya están inscritos en al menos un curso y no tienen usuario aún
        $estudiantes = Estudiante::whereNull('moodle_usuario')
            ->whereHas('cursosEstudiante', function ($q) {
                $q->where('estado', 'inscrito');
            })
            ->get();

        if ($estudiantes->isEmpty()) {
            $this->info('No hay estudiantes pendientes de generar credenciales.');
            return;
        }

        $this->info("Generando credenciales para {$estudiantes->count()} estudiante(s)...");

        foreach ($estudiantes as $estudiante) {
            $estudiante->moodle_usuario = $this->generarUsuario(
                $estudiante->nombre,
                $estudiante->apellido_p,
                $estudiante->apellido_m
            );
            $estudiante->moodle_password = $this->generarPassword(
                $estudiante->ci,
                $estudiante->extension_ci
            );
            $estudiante->moodle_habilitado = 'pendiente';
            $estudiante->save();

            $this->line("- {$estudiante->nombre} {$estudiante->apellido_p} -> {$estudiante->moodle_usuario} / {$estudiante->moodle_password}");
        }

        $this->info('Listo.');
    }

    private function limpiarTexto(string $texto): string
    {
        $texto = Str::ascii($texto);
        $texto = preg_replace('/[^A-Za-z]/', '', $texto);
        return mb_strtolower($texto);
    }

    private function generarUsuario(string $nombre, ?string $apellidoP, ?string $apellidoM): string
    {
        $primerNombre = trim(explode(' ', trim($nombre))[0] ?? '');

        $inicial = mb_substr($this->limpiarTexto($primerNombre), 0, 1);
        $ap1 = $this->limpiarTexto($apellidoP ?? '');
        $ap2 = $this->limpiarTexto($apellidoM ?? '');

        $base = $inicial . $ap1 . $ap2;

        $usuario = $base;
        $i = 1;
        while (Estudiante::where('moodle_usuario', $usuario)->exists()) {
            $usuario = $base . $i;
            $i++;
        }

        return $usuario;
    }

    private function generarPassword(string $ci, string $extensionCi): string
    {
        $ciLimpio = preg_replace('/[^0-9]/', '', $ci);
        $ext = trim($extensionCi);

        $extFormateada = mb_strtoupper(mb_substr($ext, 0, 1)) . mb_strtolower(mb_substr($ext, 1));

        return "{$ciLimpio}.{$extFormateada}";
    }
}
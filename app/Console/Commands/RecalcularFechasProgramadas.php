<?php

namespace App\Console\Commands;

use App\Models\CursoEstudiante;
use App\Models\PagoEstudiante;
use App\Models\PlanesPago;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecalcularFechasProgramadas extends Command
{
    protected $signature = 'pagos:recalcular-fechas {--dry-run : Solo muestra los cambios sin guardarlos}';
    protected $description = 'Recalcula fecha_programada de todos los pagos según las nuevas reglas de negocio';

    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $this->info($dryRun
            ? '=== MODO DRY-RUN: no se guardará nada ==='
            : '=== MODO REAL: se van a guardar los cambios ===');

        $inscripciones = CursoEstudiante::with(['curso'])->get();

        $totalCambiados = 0;

        foreach ($inscripciones as $inscripcion) {

            $plan = PlanesPago::find($inscripcion->id_planes_pago);
            $curso = $inscripcion->curso;

            if (!$plan || !$curso) {
                $this->warn("Inscripción {$inscripcion->id}: sin plan o curso asociado, se omite.");
                continue;
            }

            $pagos = PagoEstudiante::where('id_curso_estudiante', $inscripcion->id)
                ->orderBy('id_pagos_estudiante')
                ->get();

            if ($pagos->isEmpty()) {
                continue;
            }

            $fechaInscripcion = $curso->fecha_inicio
                ? Carbon::parse($curso->fecha_inicio)
                : Carbon::parse($inscripcion->created_at);

            // Para CONTADO: la base es la fecha REAL de pago del INICIAL/MATRÍCULA, si existe
            $fechaBaseContado = $fechaInscripcion;
            foreach ($pagos as $p) {
                if (in_array($p->detalle, ['PAGO INICIAL', 'PAGO DE MATRÍCULA']) && $p->fecha_pagada) {
                    $fechaBaseContado = Carbon::parse($p->fecha_pagada);
                }
            }

            $ultimaFechaProgramada = null;
            $posicionCuotaRegular = 0;

            foreach ($pagos as $pago) {

                // No tocamos pagos ya completados/pagados: su fecha programada ya se cumplió
                if ($pago->estado === 'pagado') {
                    if (in_array($pago->detalle, ['PAGO INICIAL', 'PAGO DE MATRÍCULA'])) {
                        continue; // nunca tuvieron fecha_programada, no aporta a la cadena tampoco
                    }
                    $ultimaFechaProgramada = $pago->fecha_programada
                        ? Carbon::parse($pago->fecha_programada)
                        : $ultimaFechaProgramada;
                    if (!in_array($pago->detalle, ['TITULACION'])) {
                        $posicionCuotaRegular++;
                    }
                    continue;
                }

                if (in_array($pago->detalle, ['PAGO INICIAL', 'PAGO DE MATRÍCULA'])) {
                    $nuevaFecha = null;

                } elseif ($pago->detalle === 'TITULACION') {
                    $nuevaFecha = $ultimaFechaProgramada
                        ? $ultimaFechaProgramada->copy()->addDays(15)
                        : $fechaInscripcion->copy()->addDays(15);
                    $ultimaFechaProgramada = $nuevaFecha->copy();

                } else {
                    $posicionCuotaRegular++;

                    if ($plan->tipo_plan === 'CONTADO') {
                        $nuevaFecha = $fechaBaseContado->copy()->addDays($posicionCuotaRegular * 30);
                    } else {
                        $nuevaFecha = $fechaInscripcion->copy()
                            ->startOfMonth()
                            ->addMonths($posicionCuotaRegular)
                            ->day(15);
                    }
                    $ultimaFechaProgramada = $nuevaFecha->copy();
                }

                $nuevaFechaStr = $nuevaFecha ? $nuevaFecha->format('Y-m-d') : null;

                if ($pago->fecha_programada != $nuevaFechaStr) {
                    $this->line(sprintf(
                        "Inscripción %d | %s (id_pago %d): %s -> %s",
                        $inscripcion->id,
                        $pago->detalle,
                        $pago->id_pagos_estudiante,
                        $pago->fecha_programada ?? 'null',
                        $nuevaFechaStr ?? 'null'
                    ));

                    $totalCambiados++;

                    if (!$dryRun) {
                        $pago->fecha_programada = $nuevaFechaStr;
                        $pago->save();
                    }
                }
            }
        }

        $this->info("Total de pagos con fecha_programada distinta: {$totalCambiados}");

        if ($dryRun) {
            $this->info('Corré el comando sin --dry-run para aplicar los cambios.');
        } else {
            $this->info('¡Listo! Cambios guardados.');
        }

        return 0;
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\CursoEstudiante;
use App\Models\Personal;
use Illuminate\Support\Facades\DB;

class StatiticController extends Controller
{
    public function index(Request $request)
    {
        $usuario = auth()->user()->load('persona');

        $mes = $request->mes;
        $anio = $request->anio;
        $id_personal = $request->id_personal;
        $orden = $request->orden ?? 'desc';
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;

        $usarRango = $fecha_inicio || $fecha_fin;

        $cursos = Curso::orderBy('nombre')->get();

        $personales = Personal::with('persona')->get();

        $primerPagoSub = DB::table('pagos_estudiante')
            ->select('id_curso_estudiante', DB::raw('MIN(fecha_pagada) as fecha_primer_pago'))
            ->whereNotNull('fecha_pagada')
            ->groupBy('id_curso_estudiante');

        $inscripciones = CursoEstudiante::query()
            ->leftJoinSub($primerPagoSub, 'primer_pago', function ($join) {
                $join->on('primer_pago.id_curso_estudiante', '=', 'curso_estudiante.id_curso_estudiante');
            })
            ->select(
                'curso_estudiante.id_personal',
                'curso_estudiante.id_curso',
                DB::raw('COUNT(*) as total')
            )
            ->where('curso_estudiante.estado', 'inscrito')
            ->when($usarRango, function ($q) use ($fecha_inicio, $fecha_fin) {
                if ($fecha_inicio && $fecha_fin) {
                    $q->whereRaw(
                        'COALESCE(primer_pago.fecha_primer_pago, curso_estudiante.created_at) BETWEEN ? AND ?',
                        [$fecha_inicio, $fecha_fin]
                    );
                } elseif ($fecha_inicio) {
                    $q->whereRaw(
                        'COALESCE(primer_pago.fecha_primer_pago, curso_estudiante.created_at) >= ?',
                        [$fecha_inicio]
                    );
                } elseif ($fecha_fin) {
                    $q->whereRaw(
                        'COALESCE(primer_pago.fecha_primer_pago, curso_estudiante.created_at) <= ?',
                        [$fecha_fin]
                    );
                }
            })
            ->when(!$usarRango && $mes, function ($q) use ($mes) {
                $q->whereRaw(
                    'MONTH(COALESCE(primer_pago.fecha_primer_pago, curso_estudiante.created_at)) = ?',
                    [$mes]
                );
            })
            ->when(!$usarRango && $anio, function ($q) use ($anio) {
                $q->whereRaw(
                    'YEAR(COALESCE(primer_pago.fecha_primer_pago, curso_estudiante.created_at)) = ?',
                    [$anio]
                );
            })
            ->groupBy('curso_estudiante.id_personal', 'curso_estudiante.id_curso')
            ->get();

        $data = [];
        $totalesCursos = [];

        foreach ($cursos as $c) {
            $totalesCursos[$c->id_curso] = 0;
        }

        foreach ($personales as $p) {

            if ($id_personal && $p->id_personal != $id_personal)
                continue;

            $fila = [
                'personal' => $p,
                'total_inscritos' => 0,
                'puntaje_diplomados' => 0,
                'puntaje_cursos' => 0,
                'puntaje' => 0,
                'cursos' => []
            ];

            $inscritosDiplomados = 0;
            $inscritosCursosRegulares = 0;

            foreach ($cursos as $curso) {

                $match = $inscripciones->first(
                    fn($i) =>
                    $i->id_personal == $p->id_personal &&
                    $i->id_curso == $curso->id_curso
                );

                $count = $match ? $match->total : 0;

                $fila['cursos'][$curso->id_curso] = $count;
                $fila['total_inscritos'] += $count;
                $totalesCursos[$curso->id_curso] += $count;

                if (strtolower($curso->tipo) === 'diplomado') {
                    $inscritosDiplomados += $count;
                } elseif (strtolower($curso->tipo) === 'curso') {
                    $inscritosCursosRegulares += $count;
                }
            }

            $fila['puntaje_diplomados'] = $inscritosDiplomados;
            $fila['puntaje_cursos'] = intdiv($inscritosCursosRegulares, 3);
            $fila['puntaje'] = $fila['puntaje_diplomados'] + $fila['puntaje_cursos'];

            $data[] = $fila;
        }

        usort($data, function ($a, $b) use ($orden) {
            return $orden === 'asc'
                ? $a['puntaje'] <=> $b['puntaje']
                : $b['puntaje'] <=> $a['puntaje'];
        });

        $totalInscritosGeneral = array_sum(array_column($data, 'total_inscritos'));
        $totalPuntajeDiplomadosGeneral = array_sum(array_column($data, 'puntaje_diplomados'));
        $totalPuntajeCursosGeneral = array_sum(array_column($data, 'puntaje_cursos'));
        $totalPuntajeGeneral = array_sum(array_column($data, 'puntaje'));

        return view('statitics.index', compact(
            'usuario',
            'data',
            'cursos',
            'personales',
            'totalesCursos',
            'totalInscritosGeneral',
            'totalPuntajeDiplomadosGeneral',
            'totalPuntajeCursosGeneral',
            'totalPuntajeGeneral',
            'orden'
        ));
    }
}
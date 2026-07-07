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

        $cursos = Curso::when($usarRango, function ($q) use ($fecha_inicio, $fecha_fin) {
            if ($fecha_inicio && $fecha_fin) {
                $q->whereBetween('fecha_inicio', [$fecha_inicio, $fecha_fin]);
            } elseif ($fecha_inicio) {
                $q->where('fecha_inicio', '>=', $fecha_inicio);
            } elseif ($fecha_fin) {
                $q->where('fecha_inicio', '<=', $fecha_fin);
            }
        })
            ->when(!$usarRango && $mes, fn($q) => $q->whereMonth('fecha_inicio', $mes))
            ->when(!$usarRango && $anio, fn($q) => $q->whereYear('fecha_inicio', $anio))
            ->orderBy('nombre')
            ->get();

        $personales = Personal::with('persona')->get();

        $inscripciones = CursoEstudiante::select(
            'id_personal',
            'id_curso',
            DB::raw('COUNT(*) as total')
        )
            ->where('estado', 'inscrito')
            ->when($usarRango, function ($q) use ($fecha_inicio, $fecha_fin) {
                if ($fecha_inicio && $fecha_fin) {
                    $q->whereBetween('created_at', [$fecha_inicio, $fecha_fin]);
                } elseif ($fecha_inicio) {
                    $q->where('created_at', '>=', $fecha_inicio);
                } elseif ($fecha_fin) {
                    $q->where('created_at', '<=', $fecha_fin);
                }
            })
            ->when(!$usarRango && $mes, fn($q) => $q->whereMonth('created_at', $mes))
            ->when(!$usarRango && $anio, fn($q) => $q->whereYear('created_at', $anio))
            ->groupBy('id_personal', 'id_curso')
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
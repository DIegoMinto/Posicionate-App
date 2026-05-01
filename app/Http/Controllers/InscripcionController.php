<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Personal;
use App\Models\Ciudad;
use App\Models\Profesion;
use App\Models\GradoAcademico;
use App\Models\InstitucionEgreso;
use App\Models\Estudiante;
use App\Models\PlanesPago;
use App\Models\Descuento;
use App\Models\PagoEstudiante;
use Illuminate\Http\Request;
use App\Models\Pais;
use Illuminate\Support\Facades\DB;

class InscripcionController extends Controller
{
    public function showForm($id_curso, $id_personal)
    {
        // 1. Verificamos que el curso y el asesor existan
        $curso = Curso::findOrFail($id_curso);
        $asesor = Personal::with('persona')->findOrFail($id_personal);

        // 2. Cargamos las tablas maestras (AQUÍ AGREGAMOS PAÍSES)
        $paises = Pais::all(); // <--- FALTA ESTO
        $ciudades = Ciudad::all();
        $profesiones = Profesion::all();
        $grados = GradoAcademico::all();
        $instituciones = InstitucionEgreso::all();

        // 3. Retornamos la vista con la variable 'paises' incluida
        return view('public.inscripcion', compact(
            'curso',
            'asesor',
            'paises', // <--- AGREGAR AQUÍ
            'ciudades',
            'profesiones',
            'grados',
            'instituciones'
        ));
    }
    /**
     * Guarda el registro del estudiante vinculado al asesor
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ci' => 'required|unique:estudiante,ci',
            'nombre' => 'required|string|max:100',
            'apellido_p' => 'required|string|max:100',
            'correo_electronico' => 'required|email|unique:estudiante,correo_electronico',
            'id_curso' => 'required',
            'id_personal' => 'required',
        ]);

        // 1. Crear estudiante
        $estudiante = Estudiante::create($request->all());

        // 2. Insertar en tabla pivote curso_estudiante
        \DB::table('curso_estudiante')->insert([
            'id_curso' => $validated['id_curso'],
            'id_estudiante' => $estudiante->id_estudiante,
            'estado' => 'pre_inscrito',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Redirigir
        return redirect()->route('inscripcion.public', [
            'id_curso' => $validated['id_curso'],
            'id_personal' => $validated['id_personal']
        ])->with('success', 'Registro exitoso');
    }

    public function list(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);
        $usuario = auth()->user();

        $query = DB::table('curso_estudiante')
            ->join('estudiante', 'curso_estudiante.id_estudiante', '=', 'estudiante.id_estudiante')
            ->join('personal', 'estudiante.id_personal', '=', 'personal.id_personal')
            ->join('persona', 'personal.id_persona', '=', 'persona.id_persona')
            ->where('curso_estudiante.id_curso', $id)
            ->select(
                'estudiante.*',
                'curso_estudiante.estado',
                'curso_estudiante.created_at as fecha_inscripcion',
                'persona.nombre as asesor_nombre',
                'persona.apellido_p as asesor_apellido'
            );

        if ($usuario->rol === 'user') {
            $query->where('estudiante.id_personal', $usuario->id_personal);
        }

        if ($request->filled('id_personal')) {
            $query->where('estudiante.id_personal', $request->id_personal);
        }

        if ($request->filled('estado')) {
            $query->where('curso_estudiante.estado', $request->estado);
        }

        $estudiantes = $query->get();

        $personales = Personal::with('persona')->get();

        return view('cursos.estudiantes', compact(
            'curso',
            'estudiantes',
            'usuario',
            'personales'
        ));
    }

    public function change(Request $request, $id)
    {
        $usuario = auth()->user();
        $estudiante = Estudiante::findOrFail($id);

        // Capturamos el id_curso que viene por la URL (?id_curso=X)
        $id_curso = $request->query('id_curso');

        if (!$id_curso) {
            return redirect()->back()->with('error', 'No se especificó un curso válido.');
        }

        $curso = Curso::findOrFail($id_curso); // Ahora sí buscamos el curso real

        // FILTRADO REAL: Solo planes de este curso
        $planes = PlanesPago::where('id_curso', $id_curso)->get();
        $descuentos = Descuento::all();

        return view('students.change', compact('estudiante', 'usuario', 'curso', 'planes', 'descuentos'));
    }

    public function store_change(Request $request, $id_estudiante)
    {
        try {
            return \DB::transaction(function () use ($request, $id_estudiante) {

                $plan = PlanesPago::with([
                    'detalles' => function ($q) {
                        $q->orderBy('nro_cuota');
                    }
                ])->findOrFail($request->id_plan);
                $id_descuento = ($request->id_descuento == 0) ? null : $request->id_descuento;

                $inscripcion = \App\Models\CursoEstudiante::where('id_curso', $request->id_curso)
                    ->where('id_estudiante', $id_estudiante)
                    ->firstOrFail();

                $inscripcion->update([
                    'id_planes_pago' => $request->id_plan,
                    'id_descuento' => $id_descuento,
                    'estado' => 'inscrito',
                ]);

                foreach ($plan->detalles as $index => $detallePlan) {

                    $montoPlan = (float) $detallePlan->monto_cuota;

                    // aplicar descuento si existe
                    if ($id_descuento) {
                        $descuento = Descuento::find($id_descuento);
                        $montoPlan -= ($montoPlan * ($descuento->porcentaje / 100));
                    }

                    // 🔥 LO IMPORTANTE (ahora sí coincide con el frontend)
                    $montoPagado = isset($request->cuotas[$index]['monto_pagado'])
                        ? (float) $request->cuotas[$index]['monto_pagado']
                        : 0;

                    $fechaPagada = isset($request->cuotas[$index]['fecha_pagada'])
                        ? $request->cuotas[$index]['fecha_pagada']
                        : null;

                    // estado automático
                    if ($montoPagado == 0) {
                        $estado = 'pendiente';
                    } elseif ($montoPagado < $montoPlan) {
                        $estado = 'parcial';
                    } else {
                        $estado = 'pagado';
                    }

                    \App\Models\PagoEstudiante::create([
                        'id_curso_estudiante' => $inscripcion->id,
                        'detalle' => $detallePlan->detalle,
                        'monto_pagar' => $montoPlan,
                        'monto_pagado' => $montoPagado,
                        'fecha_programada' => $detallePlan->fecha_vencimiento ?? now()->format('Y-m-d'),

                        'fecha_pagada' => $fechaPagada,
                        'estado' => $estado,
                    ]);
                }

                return redirect()->route('curso.estudiantes', $request->id_curso)
                    ->with('success', '¡Inscripción y plan de pagos generados con éxito!');
            });

        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function facturacion(Request $request, $id_estudiante)
    {
        $usuario = auth()->user();

        $id_curso = $request->query('id_curso');

        $estudiante = \App\Models\Estudiante::findOrFail($id_estudiante);
        $curso = \App\Models\Curso::findOrFail($id_curso);

        // inscripción
        $inscripcion = \App\Models\CursoEstudiante::where('id_estudiante', $id_estudiante)
            ->where('id_curso', $id_curso)
            ->firstOrFail();

        // pagos
        $pagos = PagoEstudiante::where('id_curso_estudiante', $inscripcion->id)
            ->orderBy('fecha_programada')
            ->get();



        return view('students.facturacion', compact(
            'usuario',
            'estudiante',
            'curso',
            'pagos',
            'inscripcion'
        ));
    }

    public function destroy(Request $request, $id_estudiante)
    {
        if (!\Hash::check($request->password_confirm, auth()->user()->password)) {
            return back()->with('error', 'Contraseña incorrecta');
        }

        DB::transaction(function () use ($id_estudiante, $request) {
            $inscripcion = \App\Models\CursoEstudiante::where('id_estudiante', $id_estudiante)
                ->where('id_curso', $request->id_curso)
                ->first();

            if ($inscripcion) {
                \App\Models\PagoEstudiante::where('id_curso_estudiante', $inscripcion->id)->delete();
                $inscripcion->delete();
            }
        });

        return redirect()->back()->with('success', 'Estudiante eliminado del curso correctamente');
    }


    public function editPago($id)
    {
        $usuario = auth()->user();

        $pago = \App\Models\PagoEstudiante::findOrFail($id);

        $inscripcion = \App\Models\CursoEstudiante::findOrFail($pago->id_curso_estudiante);

        $estudiante = \App\Models\Estudiante::findOrFail($inscripcion->id_estudiante);

        $curso = \App\Models\Curso::findOrFail($inscripcion->id_curso);

        return view('students.edit_pago', compact(
            'usuario',
            'pago',
            'estudiante',
            'curso'
        ));
    }

    public function updatePago(Request $request, $id)
    {
        $request->validate([
            'monto_pagado' => 'required|numeric|min:0',
            'fecha_pagada' => 'required|date',
        ]);

        $pago = \App\Models\PagoEstudiante::findOrFail($id);

        $nuevoMonto = $pago->monto_pagado + $request->monto_pagado;

        if ($nuevoMonto > $pago->monto_pagar) {
            return back()->withErrors([
                'monto_pagado' => 'No puedes pagar más del saldo pendiente'
            ]);
        }

        $pago->monto_pagado = $nuevoMonto;
        $pago->fecha_pagada = $request->fecha_pagada;
        $pago->estado = ($nuevoMonto >= $pago->monto_pagar) ? 'pagado' : 'pendiente';

        $pago->save();

        // 🔥 ESTO FALTABA
        $inscripcion = \App\Models\CursoEstudiante::findOrFail($pago->id_curso_estudiante);

        return redirect()->route('students.facturacion', [
            'id' => $inscripcion->id_estudiante,
            'id_curso' => $inscripcion->id_curso
        ])->with('success', '¡Pago actualizado con éxito!');
    }

}
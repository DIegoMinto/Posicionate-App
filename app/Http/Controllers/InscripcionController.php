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
use Carbon\Carbon;

class InscripcionController extends Controller
{
    public function showForm($id_curso, $id_personal)
    {
        $curso = Curso::findOrFail($id_curso);
        $asesor = Personal::with('persona')->findOrFail($id_personal);

        $paises = Pais::all();
        $ciudades = Ciudad::all();
        $profesiones = Profesion::all();
        $grados = GradoAcademico::all();
        $instituciones = InstitucionEgreso::all();

        return view('public.inscripcion', compact(
            'curso',
            'asesor',
            'paises',
            'ciudades',
            'profesiones',
            'grados',
            'instituciones'
        ));
    }

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

        $estudiante = Estudiante::create($request->all());

        \DB::table('curso_estudiante')->insert([
            'id_curso' => $validated['id_curso'],
            'id_estudiante' => $estudiante->id_estudiante,
            'id_personal' => $validated['id_personal'],
            'estado' => 'pre_inscrito',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('inscripcion.public', [
            'id_curso' => $validated['id_curso'],
            'id_personal' => $validated['id_personal']
        ])->with('success', '¡Registro exitoso!');
    }

    public function list(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);
        $usuario = auth()->user();

        $query = DB::table('curso_estudiante')
            ->join('estudiante', 'curso_estudiante.id_estudiante', '=', 'estudiante.id_estudiante')
            ->join('personal', 'curso_estudiante.id_personal', '=', 'personal.id_personal')
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
            $query->where('curso_estudiante.id_personal', $usuario->id_personal);
        }

        if ($request->filled('id_personal')) {
            $query->where('curso_estudiante.id_personal', $request->id_personal);
        }

        if ($request->filled('estado')) {
            $query->where('curso_estudiante.estado', $request->estado);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('estudiante.nombre', 'ILIKE', "%$search%")
                    ->orWhere('estudiante.apellido_p', 'ILIKE', "%$search%")
                    ->orWhere('estudiante.apellido_m', 'ILIKE', "%$search%")
                    ->orWhere('estudiante.ci', 'ILIKE', "%$search%");
            });
        }

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('curso_estudiante.created_at', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('curso_estudiante.created_at', '<=', $request->fecha_fin);
        }

        $estudiantes = $query
            ->orderBy('curso_estudiante.created_at', 'desc')
            ->get();

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

        $id_curso = $request->query('id_curso');

        if (!$id_curso) {
            return redirect()->back()->with('error', 'No se especificó un curso válido.');
        }

        $curso = Curso::findOrFail($id_curso);

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

                $curso = Curso::findOrFail($request->id_curso);
                $fechaInscripcion = $curso->fecha_inicio
                    ? Carbon::parse($curso->fecha_inicio)
                    : Carbon::parse($inscripcion->created_at);

                $ultimaFechaProgramada = null;
                $posicion = 0;
                foreach ($plan->detalles as $detallePlan) {

                    $nro = $detallePlan->nro_cuota;

                    $montoPlan = (float) $detallePlan->monto_cuota;

                    if ($id_descuento) {
                        $descuento = Descuento::find($id_descuento);
                        $montoPlan -= ($montoPlan * ($descuento->porcentaje / 100));
                    }

                    $montoPagado = isset($request->cuotas[$nro]['monto_pagado'])
                        ? (float) $request->cuotas[$nro]['monto_pagado']
                        : 0;

                    $fechaPagada = isset($request->cuotas[$nro]['fecha_pagada']) && $request->cuotas[$nro]['fecha_pagada'] !== ''
                        ? $request->cuotas[$nro]['fecha_pagada']
                        : null;

                    $estado = $montoPagado > 0 ? 'revision' : 'pendiente';

                    if ($detallePlan->detalle === 'TITULACION') {
                        $fechaProgramada = $ultimaFechaProgramada
                            ? $ultimaFechaProgramada->copy()->addDays(15)
                            : $fechaInscripcion->copy()->addDays(15);
                    } else {
                        if ($plan->tipo_plan === 'CONTADO') {
                            $fechaProgramada = $fechaInscripcion->copy()->addDays($posicion * 30);
                        } else {
                            $fechaProgramada = $posicion == 0
                                ? $fechaInscripcion->copy()
                                : $fechaInscripcion->copy()->startOfMonth()->addMonths($posicion)->day(15);
                        }
                        $ultimaFechaProgramada = $fechaProgramada->copy();
                    }

                    \App\Models\PagoEstudiante::create([
                        'id_curso_estudiante' => $inscripcion->id,
                        'detalle' => $detallePlan->detalle,
                        'monto_pagar' => $montoPlan,
                        'monto_pagado' => $montoPagado,
                        'fecha_programada' => $fechaProgramada->format('Y-m-d'),
                        'fecha_pagada' => $fechaPagada,
                        'estado' => $estado,
                    ]);

                    $posicion++;
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
            'fecha_pagada' => 'nullable|date',
        ]);

        $pago = \App\Models\PagoEstudiante::findOrFail($id);

        if ($request->monto_pagado > $pago->monto_pagar) {
            return back()->withErrors([
                'monto_pagado' => 'El monto pagado no puede superar el monto total (' . $pago->monto_pagar . ' Bs)'
            ]);
        }

        $pago->monto_pagado = $request->monto_pagado;

        if ($request->monto_pagado == 0) {
            // reinicio: sin fecha, vuelve a pendiente
            $pago->fecha_pagada = null;
            $pago->estado = 'pendiente';
        } else {
            $pago->fecha_pagada = $request->fecha_pagada;
            $pago->estado = 'revision';
        }

        $pago->save();

        $inscripcion = \App\Models\CursoEstudiante::findOrFail($pago->id_curso_estudiante);

        return redirect()->route('students.facturacion', [
            'id' => $inscripcion->id_estudiante,
            'id_curso' => $inscripcion->id_curso
        ])->with('success', '¡Pago actualizado con éxito!');
    }

    public function agregarEstudiante(Request $request)
    {
        $request->validate([
            'id_estudiante' => 'required|exists:estudiante,id_estudiante',
            'id_curso' => 'required|exists:curso,id_curso',
            'id_personal' => 'required'
        ]);

        $exists = \App\Models\CursoEstudiante::where('id_estudiante', $request->id_estudiante)
            ->where('id_curso', $request->id_curso)
            ->exists();

        if ($exists) {
            return back()->with('error', 'El estudiante ya está en este curso');
        }

        \App\Models\CursoEstudiante::create([
            'id_estudiante' => $request->id_estudiante,
            'id_curso' => $request->id_curso,
            'id_personal' => $request->id_personal,
            'estado' => 'pre_inscrito'
        ]);

        return back()->with('success', 'Estudiante añadido correctamente');
    }

    public function validarPago(Request $request, $id)
    {
        $request->validate([
            'password_contabilidad' => 'required|string',
        ]);

        $configuracionArea = \App\Models\ContrasenaArea::whereHas('area', function ($query) {
            $query->where('nombre', 'ILIKE', '%contabilidad%');
        })
            ->latest()
            ->first();

        if (!$configuracionArea) {
            return redirect()->back()->with('error', 'No se encontró la configuración de seguridad para el área de Contabilidad.');
        }
        try {
            $passwordDecodificada = \Illuminate\Support\Facades\Crypt::decryptString($configuracionArea->contrasena_encriptada);

            if ($request->password_contabilidad !== $passwordDecodificada) {
                return redirect()->back()->with('error', 'La contraseña de validación para el área de Contabilidad es incorrecta.');
            }
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            if (!\Hash::check($request->password_contabilidad, $configuracionArea->contrasena_encriptada)) {
                return redirect()->back()->with('error', 'La contraseña de validación para el área de Contabilidad es incorrecta.');
            }
        }

        $pago = PagoEstudiante::findOrFail($id);

        $pago->update([
            'estado' => 'pagado',
            'monto_pagado' => $pago->monto_pagar,
            'fecha_pagada' => Carbon::now()->toDateString()
        ]);

        return redirect()->back()->with('success', 'El pago ha sido validado y completado con éxito por el área de Contabilidad.');
    }

}
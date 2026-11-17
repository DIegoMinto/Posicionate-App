<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Modulo;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\ProgramacionAcademica;
use Carbon\Carbon;
use App\Models\Masterclass;
use Spatie\Browsershot\Browsershot;
use Barryvdh\DomPDF\Facade\Pdf;

class ModuloController extends Controller
{
    public function create(Request $request)
    {
        $id_curso = $request->query('id_curso');

        if (!$id_curso) {
            return redirect()->route('programs.index')
                ->with('error', 'Debe seleccionar un curso.');
        }

        $usuario = auth()->user();

        return view('modulos.create', compact('id_curso', 'usuario'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'id_curso' => 'required|exists:curso,id_curso',
        ]);

        Modulo::create([
            'nombre' => $request->nombre,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'id_curso' => $request->id_curso,
        ]);

        return redirect()->route('programs.index')
            ->with('success', '¡Módulo registrado exitosamente!');
    }

    public function programacionPdf($id_curso)
    {
        $curso = Curso::with([
            'institucion',
            'programacionAcademica.coordinador',
            'modulos.docente',
            'modulos.sesiones'
        ])->findOrFail($id_curso);

        $masterclasses = Masterclass::with('docente')
            ->where('id_curso', $id_curso)
            ->orderBy('fecha_inicio')
            ->orderBy('id_masterclass')
            ->get();

        $programacion = $curso->programacionAcademica;

        return Pdf::loadView('modulos.programacion-pdf', compact(
            'curso',
            'programacion',
            'masterclasses'
        ))
            ->setPaper('a4', 'portrait')
            ->setOption(['isRemoteEnabled' => true])
            ->download('programacion-' . $id_curso . '.pdf');
    }

    public function destroy(Request $request, $id)
    {
        if (auth()->user()->rol !== 'super_admin') {
            abort(403, 'No autorizado');
        }

        $request->validate([
            'password_confirm' => 'required'
        ]);

        $usuario = auth()->user();

        if (!Hash::check($request->password_confirm, $usuario->password)) {
            return back()->with('error', 'Contraseña incorrecta.');
        }

        $modulo = Modulo::findOrFail($id);
        $modulo->delete();

        return back()->with('success', 'Módulo eliminado correctamente.');
    }

    public function programacionShow($id_curso)
    {
        $usuario = auth()->user()->load('persona');

        $curso = Curso::with([
            'institucion',
            'programacionAcademica.coordinador',
            'modulos.docente',
            'modulos.sesiones'
        ])->findOrFail($id_curso);
        $masterclasses = Masterclass::with('docente')
            ->where('id_curso', $id_curso)
            ->orderBy('fecha_inicio')
            ->orderBy('id_masterclass')
            ->get();

        $programacion = $curso->programacionAcademica;

        return view('modulos.programacion-show', compact(
            'usuario',
            'curso',
            'programacion',
            'masterclasses'
        ));
    }

    public function programacionEdit($id_curso)
    {
        $usuario = auth()->user()->load('persona');

        $curso = Curso::with([
            'programacionAcademica',
            'modulos'
        ])->findOrFail($id_curso);

        $docentes = Docente::orderBy('apellido_p')
            ->orderBy('nombre')
            ->get();

        $programacion = $curso->programacionAcademica;

        return view('modulos.programacion-edit', compact(
            'usuario',
            'curso',
            'docentes',
            'programacion'
        ));
    }


    // Guardar configuración general y módulos

    public function programacionStore(Request $request, $id_curso)
    {
        $curso = Curso::findOrFail($id_curso);

        $datos = $request->validate([

            // Configuración general
            'duracion_meses' => 'required|integer|min:1|max:60',
            'cantidad_modulos' => 'required|integer|min:1|max:100',

            'dias_clase' => 'nullable|array',
            'dias_clase.*' => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',

            'hora_inicio' => 'nullable|date_format:H:i',
            'hora_fin' => 'nullable|date_format:H:i|after:hora_inicio',

            'carga_horaria' => 'nullable|numeric|min:0|max:999999.99',

            'id_docente_coordinador' => 'nullable|exists:docente,id_docente',

            // Módulos
            'modulos' => [
                'required',
                'array',
                'size:' . $request->input('cantidad_modulos')
            ],

            'modulos.*.nombre' => 'required|string|max:255',
            'modulos.*.orden' => 'required|integer|min:1|distinct',
            'modulos.*.contenido_academico' => 'nullable|string',
            'modulos.*.fecha_inicio' => 'required|date',
            'modulos.*.fecha_fin' => 'required|date',
            'modulos.*.id_docente' => 'nullable|exists:docente,id_docente',

            // Periodo de descanso de cada módulo
            'modulos.*.fecha_inicio_descanso' => 'nullable|date',
            'modulos.*.fecha_fin_descanso' => 'nullable|date',
        ]);

        // Validar fechas de módulos y periodos de descanso
        foreach ($datos['modulos'] as $indice => $modulo) {

            // Fecha final del módulo
            if (
                Carbon::parse($modulo['fecha_fin'])
                    ->lt(Carbon::parse($modulo['fecha_inicio']))
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        "modulos.$indice.fecha_fin" =>
                            'La fecha de finalización no puede ser anterior a la fecha de inicio del módulo ' . ($indice + 1) . '.'
                    ]);
            }

            $inicioDescanso = $modulo['fecha_inicio_descanso'] ?? null;
            $finDescanso = $modulo['fecha_fin_descanso'] ?? null;

            // Ambas fechas deben estar completas
            if ($inicioDescanso && !$finDescanso) {
                return back()
                    ->withInput()
                    ->withErrors([
                        "modulos.$indice.fecha_fin_descanso" =>
                            'Debes indicar la fecha de finalización del descanso del módulo ' . ($indice + 1) . '.'
                    ]);
            }

            if (!$inicioDescanso && $finDescanso) {
                return back()
                    ->withInput()
                    ->withErrors([
                        "modulos.$indice.fecha_inicio_descanso" =>
                            'Debes indicar la fecha de inicio del descanso del módulo ' . ($indice + 1) . '.'
                    ]);
            }

            // La fecha final del descanso no puede ser anterior a la inicial
            if (
                $inicioDescanso &&
                $finDescanso &&
                Carbon::parse($finDescanso)->lt(Carbon::parse($inicioDescanso))
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        "modulos.$indice.fecha_fin_descanso" =>
                            'La fecha de finalización del descanso no puede ser anterior a su fecha de inicio en el módulo ' . ($indice + 1) . '.'
                    ]);
            }
        }

        // Separar módulos de la configuración general
        $modulos = $datos['modulos'];
        unset($datos['modulos']);

        $datos['id_curso'] = $curso->id_curso;

        $datos['dias_clase'] = array_values(
            array_intersect(
                $datos['dias_clase'] ?? [],
                ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo']
            )
        );

        DB::transaction(function () use ($datos, $modulos, $curso) {

            // Guardar configuración general
            ProgramacionAcademica::updateOrCreate(
                ['id_curso' => $curso->id_curso],
                $datos
            );

            foreach ($modulos as $moduloDatos) {

                Modulo::updateOrCreate(
                    [
                        'id_curso' => $curso->id_curso,
                        'orden' => $moduloDatos['orden'],
                    ],
                    [
                        'nombre' => $moduloDatos['nombre'],
                        'contenido_academico' => $moduloDatos['contenido_academico'] ?? null,

                        'fecha_inicio' => $moduloDatos['fecha_inicio'],
                        'fecha_fin' => $moduloDatos['fecha_fin'],
                        'id_docente' => $moduloDatos['id_docente'] ?? null,
                        'fecha_inicio_descanso' => $moduloDatos['fecha_inicio_descanso'] ?? null,
                        'fecha_fin_descanso' => $moduloDatos['fecha_fin_descanso'] ?? null,
                    ]
                );
            }
        });

        return redirect()
            ->route('programacion.show', $curso->id_curso)
            ->with('success', 'Configuración académica y módulos guardados correctamente.');
    }

    public function modulosIndex($id_curso)
    {
        $usuario = auth()->user()->load('persona');

        $curso = Curso::with('programacionAcademica')
            ->findOrFail($id_curso);

        $modulos = Modulo::with(['docente', 'sesiones'])
            ->where('id_curso', $id_curso)
            ->orderBy('orden')
            ->orderBy('id_modulo')
            ->get();

        return view('modulos.index', compact(
            'usuario',
            'curso',
            'modulos'
        ));
    }


    // Formulario individual de creación
    public function modulosCreate($id_curso)
    {
        $usuario = auth()->user()->load('persona');

        $curso = Curso::with('programacionAcademica')
            ->findOrFail($id_curso);

        $docentes = Docente::orderBy('apellido_p')
            ->orderBy('nombre')
            ->get();

        return view('modulos.form', compact(
            'usuario',
            'curso',
            'docentes'
        ));
    }


    public function modulosStore(Request $request, $id_curso)
    {
        $curso = Curso::with('programacionAcademica')
            ->findOrFail($id_curso);

        $datos = $request->validate([
            'orden' => 'required|integer|min:1|max:100',
            'nombre' => 'required|string|max:255',
            'contenido_academico' => 'nullable|string',
            'id_docente' => 'nullable|exists:docente,id_docente',
            'fecha_inicio' => 'required|date',
            'cantidad_sesiones' => 'required|integer|min:1|max:100',
            'dias_clase' => 'required|array|min:1',
            'dias_clase.*' => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'fecha_inicio_descanso' => 'nullable|date',
            'fecha_fin_descanso' => 'nullable|date|after_or_equal:fecha_inicio_descanso',
        ]);

        $ordenExiste = Modulo::where('id_curso', $id_curso)
            ->where('orden', $datos['orden'])
            ->exists();

        if ($ordenExiste) {
            return back()->withInput()->withErrors([
                'orden' => 'Ya existe un módulo con ese número de orden.'
            ]);
        }

        $cantidadActual = Modulo::where('id_curso', $id_curso)->count();
        $cantidadPermitida = $curso->programacionAcademica?->cantidad_modulos;

        if ($cantidadPermitida && $cantidadActual >= $cantidadPermitida) {
            return back()->withInput()->withErrors([
                'cantidad_modulos' => 'Ya alcanzaste la cantidad de módulos configurada para este diplomado.'
            ]);
        }

        DB::transaction(function () use ($datos, $id_curso) {

            $modulo = Modulo::create([
                'id_curso' => $id_curso,
                'id_docente' => $datos['id_docente'] ?? null,
                'orden' => $datos['orden'],
                'nombre' => $datos['nombre'],
                'contenido_academico' => $datos['contenido_academico'] ?? null,
                'fecha_inicio' => $datos['fecha_inicio'],
                'fecha_fin' => null,
                'cantidad_sesiones' => $datos['cantidad_sesiones'],
                'dias_clase' => $datos['dias_clase'],
                'hora_inicio' => $datos['hora_inicio'],
                'hora_fin' => $datos['hora_fin'],
                'fecha_inicio_descanso' => $datos['fecha_inicio_descanso'] ?? null,
                'fecha_fin_descanso' => $datos['fecha_fin_descanso'] ?? null,
            ]);

            $fecha = Carbon::parse($datos['fecha_inicio']);

            $dias = [
                'lunes' => 1,
                'martes' => 2,
                'miercoles' => 3,
                'jueves' => 4,
                'viernes' => 5,
                'sabado' => 6,
                'domingo' => 7,
            ];

            $diasNumericos = collect($datos['dias_clase'])
                ->map(fn($dia) => $dias[$dia])
                ->sort()
                ->values()
                ->all();

            $fechaInicioDescanso = !empty($datos['fecha_inicio_descanso'])
                ? Carbon::parse($datos['fecha_inicio_descanso'])
                : null;

            $fechaFinDescanso = !empty($datos['fecha_fin_descanso'])
                ? Carbon::parse($datos['fecha_fin_descanso'])
                : null;

            $numeroSesion = 1;

            while ($numeroSesion <= $datos['cantidad_sesiones']) {

                $esDiaDeClase = in_array($fecha->dayOfWeekIso, $diasNumericos);

                $estaEnDescanso = $fechaInicioDescanso && $fechaFinDescanso
                    && $fecha->betweenIncluded($fechaInicioDescanso, $fechaFinDescanso);

                if ($esDiaDeClase && !$estaEnDescanso) {

                    $modulo->sesiones()->create([
                        'numero_sesion' => $numeroSesion,
                        'fecha' => $fecha->toDateString(),
                        'hora_inicio' => $datos['hora_inicio'],
                        'hora_fin' => $datos['hora_fin'],
                    ]);

                    $numeroSesion++;
                }

                $fecha->addDay();
            }

            $ultimaSesion = $modulo->sesiones()
                ->orderByDesc('fecha')
                ->first();

            $modulo->update([
                'fecha_fin' => $ultimaSesion->fecha,
            ]);
        });

        return redirect()
            ->route('programacion.modulos.index', $id_curso)
            ->with('success', 'Módulo y sesiones registrados correctamente.');
    }

    // Formulario para editar módulo
    public function modulosEdit($id_curso, $id_modulo)
    {
        $usuario = auth()->user()->load('persona');

        $curso = Curso::with('programacionAcademica')
            ->findOrFail($id_curso);

        $modulo = Modulo::where('id_curso', $id_curso)
            ->with('sesiones')
            ->findOrFail($id_modulo);

        $docentes = Docente::orderBy('apellido_p')
            ->orderBy('nombre')
            ->get();

        return view('modulos.form', compact(
            'usuario',
            'curso',
            'modulo',
            'docentes'
        ));
    }


    public function modulosUpdate(Request $request, $id_curso, $id_modulo)
    {
        $modulo = Modulo::where('id_curso', $id_curso)
            ->findOrFail($id_modulo);

        $datos = $request->validate([
            'orden' => 'required|integer|min:1|max:100',
            'nombre' => 'required|string|max:255',
            'contenido_academico' => 'nullable|string',
            'id_docente' => 'nullable|exists:docente,id_docente',
            'fecha_inicio_descanso' => 'nullable|date',
            'fecha_fin_descanso' => 'nullable|date|after_or_equal:fecha_inicio_descanso',
        ]);

        if (
            !empty($datos['fecha_inicio_descanso']) &&
            empty($datos['fecha_fin_descanso'])
        ) {
            return back()->withInput()->withErrors([
                'fecha_fin_descanso' => 'Debes indicar la fecha de finalización del descanso.'
            ]);
        }

        if (
            empty($datos['fecha_inicio_descanso']) &&
            !empty($datos['fecha_fin_descanso'])
        ) {
            return back()->withInput()->withErrors([
                'fecha_inicio_descanso' => 'Debes indicar la fecha de inicio del descanso.'
            ]);
        }

        $ordenExiste = Modulo::where('id_curso', $id_curso)
            ->where('orden', $datos['orden'])
            ->where('id_modulo', '!=', $id_modulo)
            ->exists();

        if ($ordenExiste) {
            return back()->withInput()->withErrors([
                'orden' => 'Ya existe otro módulo con ese número de orden.'
            ]);
        }

        $modulo->update($datos);

        return redirect()
            ->route('programacion.modulos.index', $id_curso)
            ->with('success', 'Módulo actualizado correctamente.');
    }

    public function modulosDestroy(Request $request, $id_curso, $id_modulo)
    {
        if (auth()->user()->rol !== 'super_admin') {
            abort(403, 'No autorizado para eliminar módulos.');
        }

        $request->validate([
            'password_confirm' => 'required|string'
        ]);

        $usuario = auth()->user();

        if (!Hash::check($request->password_confirm, $usuario->password)) {
            return back()->with('error', 'Contraseña incorrecta.');
        }

        $modulo = Modulo::where('id_curso', $id_curso)
            ->findOrFail($id_modulo);

        $modulo->delete();

        return redirect()
            ->route('programacion.modulos.index', $id_curso)
            ->with('success', 'Módulo eliminado correctamente.');
    }
}
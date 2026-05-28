<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Personal;
use App\Models\Sede;
use App\Models\Curso;
use App\Models\Institucion;
use App\Models\Docente;
use Cloudinary\Cloudinary;
use App\Models\Clase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Modulo;

class DashboardController extends Controller
{

    public function index()
    {
        $usuario = auth()->user()->load('persona');

        $rankingGeneral = Personal::with('persona')
            ->withCount('cursoEstudiantes')
            ->orderByDesc('curso_estudiantes_count')
            ->take(3)
            ->get();
        $rankingMensual = Personal::with('persona')
            ->withCount([
                'cursoEstudiantes as inscritos_mes_count' => function ($q) {
                    $q->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                }
            ])
            ->orderByDesc('inscritos_mes_count')
            ->take(3)
            ->get();

        return view(
            'dashboard.index',
            compact(
                'usuario',
                'rankingGeneral',
                'rankingMensual'
            )
        );
    }

    public function students(Request $request)
    {
        $usuario = auth()->user();

        $query = DB::table('curso_estudiante')
            ->join('estudiante', 'curso_estudiante.id_estudiante', '=', 'estudiante.id_estudiante')
            ->join('curso', 'curso_estudiante.id_curso', '=', 'curso.id_curso')
            ->join('personal', 'curso_estudiante.id_personal', '=', 'personal.id_personal')
            ->join('persona', 'personal.id_persona', '=', 'persona.id_persona')
            ->select(
                'estudiante.*',
                'curso.nombre as curso_nombre',
                'curso_estudiante.estado',
                'curso_estudiante.id_curso',
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

        return view('dashboard.people', compact(
            'usuario',
            'estudiantes',
            'personales'
        ));
    }

    public function creations()
    {
        $usuario = auth()->user()->load('persona');
        return view('dashboard.creations', compact('usuario'));
    }

    public function staff(Request $request)
    {
        $usuario = auth()->user()->load('persona');


        $sedes = Sede::all();


        $query = Personal::with('persona', 'sede');

        if ($request->has('id_sede') && $request->id_sede != '') {
            $query->where('id_sede', $request->id_sede);
        }

        if ($request->has('cargo') && $request->cargo != '') {
            $query->where('cargo', $request->cargo);
        }

        if ($request->has('estado') && $request->estado != '') {
            $query->where('es_vigente', $request->estado);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('persona', function ($q) use ($search) {
                $q->where('nombre', 'ilike', "%$search%")
                    ->orWhere('apellido_p', 'ilike', "%$search%")
                    ->orWhere('apellido_m', 'ilike', "%$search%");
            });
        }


        $personales = $query->paginate(25);


        $areas = Personal::select('cargo')
            ->distinct()
            ->orderBy('cargo')
            ->pluck('cargo');

        return view('dashboard.staff', compact('usuario', 'personales', 'sedes', 'areas'));
    }
    public function programs(Request $request)
    {
        $usuario = auth()->user()->load('persona');

        $query = \App\Models\Curso::with(['institucion', 'sede'])

            ->withCount([

                'estudiantes as inscritos' => function ($q) {
                    $q->where('curso_estudiante.estado', 'inscrito');
                },

                'estudiantes as pre_inscritos' => function ($q) {
                    $q->where('curso_estudiante.estado', 'pre_inscrito');
                }

            ]);

        if ($request->search) {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('nombre', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('institucion', function ($q2) use ($searchTerm) {
                        $q2->where('nombre', 'LIKE', "%{$searchTerm}%");
                    });
            });
        }

        if ($request->id_institucion) {
            $query->where('id_institucion', $request->id_institucion);
        }

        if ($request->id_sede) {
            $query->where('id_sede', $request->id_sede);
        }

        if ($request->estado) {
            $query->where('estado', $request->estado);
        }

        if ($request->fecha_inicio && $request->fecha_fin) {
            $query->whereBetween('fecha_inicio', [
                $request->fecha_inicio,
                $request->fecha_fin
            ]);
        }

        $cursos = $query->orderBy('created_at', 'desc')->get();

        $sedes = \App\Models\Sede::all();
        $instituciones = \App\Models\Institucion::all();

        $allEstudiantes = \App\Models\Estudiante::orderBy('nombre')->get();

        return view('dashboard.programs', compact(
            'usuario',
            'cursos',
            'sedes',
            'instituciones',
            'allEstudiantes'
        ));
    }

    public function programsEdit($id)
    {
        if (auth()->user()->rol !== 'super_admin') {
            abort(403, 'No autorizado');
        }

        $curso = Curso::with(['institucion', 'modulos', 'docente', 'sede'])
            ->findOrFail($id);

        $usuario = auth()->user();

        $docentes = Docente::all();
        $sedes = Sede::all();
        $instituciones = Institucion::all();

        return view('programs.edit', compact(
            'curso',
            'usuario',
            'docentes',
            'sedes',
            'instituciones'
        ));
    }

    public function programsCreate()
    {
        if (auth()->user()->rol !== 'super_admin') {
            abort(403, 'No autorizado');
        }
        $usuario = auth()->user()->load('persona');
        $instituciones = \App\Models\Institucion::all();
        $docentes = \App\Models\Docente::all();
        $personas = \App\Models\Persona::all();
        $sedes = \App\Models\Sede::all();

        return view('dashboard.programs_create', compact('usuario', 'instituciones', 'docentes', 'personas', 'sedes'));
    }

    public function programsStore(Request $request)
    {
        if (auth()->user()->rol !== 'super_admin') {
            abort(403, 'No autorizado');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_institucion' => 'required|exists:institucion,id_institucion',
            'id_sede' => 'required|exists:sede,id_sede',
            'id_docente' => 'nullable|exists:docente,id_docente',
            'codigo_curso' => 'required|string|unique:curso,codigo_curso',

            'imagen_formulario' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'tipo' => 'required|in:CURSO,PROGRAMA,DIPLOMADO',
            'costo_matricula' => 'required_if:tipo,DIPLOMADO,PROGRAMA|numeric|min:0',
            'docentes_adicionales' => 'nullable|array',
            'docentes_adicionales.*' => 'exists:docente,id_docente'
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen_formulario')) {
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => [
                    'secure' => true
                ]
            ]);

            $upload = $cloudinary->uploadApi()->upload(
                $request->file('imagen_formulario')->getRealPath(),
                [
                    'folder' => 'cursos_formularios'
                ]
            );

            $data['imagen_formulario'] = $upload['secure_url'];
        }

        $data['estado'] = $data['estado'] ?? 'Activo';
        $data['inscritos'] = 0;
        $data['pre_inscritos'] = 0;

        $curso = \App\Models\Curso::create($data);

        if ($request->has('docentes_adicionales')) {
            foreach ($request->docentes_adicionales as $doc_id) {
                if ($doc_id) {
                    $curso->docentesAdicionales()->create([
                        'id_docente' => $doc_id
                    ]);
                }
            }
        }

        return redirect()->route('programs.payments.setup', $curso->id_curso)
            ->with('success', 'Programa creado. Ahora define los planes de pago.');
    }

    public function programsPaymentsSetup($id)
    {
        $usuario = auth()->user()->load('persona');

        $curso = \App\Models\Curso::findOrFail($id);

        $planes = \App\Models\PlanesPago::where('id_curso', $id)->with('detalles')->get();

        return view('dashboard.programs_payments_setup', compact('usuario', 'curso', 'planes'));
    }

    public function setup($id)
    {

        return view('plans.setup', compact('id'));
    }

    public function programsUpdate(Request $request, $id)
    {
        if (auth()->user()->rol !== 'super_admin') {
            abort(403, 'No autorizado');
        }

        $curso = Curso::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_institucion' => 'nullable|exists:institucion,id_institucion',
            'id_sede' => 'nullable|exists:sede,id_sede',
            'id_docente' => 'nullable|exists:docente,id_docente',
        ]);

        $dataCurso = $request->only([
            'nombre',
            'codigo_curso',
            'fecha_inicio',
            'fecha_fin',
            'estado',
            'id_docente',
            'id_institucion',
            'id_sede',
        ]);

        if ($request->hasFile('imagen_formulario')) {
            try {
                $cloudinary = new Cloudinary([
                    'cloud' => [
                        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                        'api_key' => env('CLOUDINARY_API_KEY'),
                        'api_secret' => env('CLOUDINARY_API_SECRET'),
                    ],
                    'url' => ['secure' => true]
                ]);

                $upload = $cloudinary->uploadApi()->upload(
                    $request->file('imagen_formulario')->getRealPath(),
                    ['folder' => 'cursos_formularios']
                );

                $dataCurso['imagen_formulario'] = $upload['secure_url'];

            } catch (\Exception $e) {
                return back()->withErrors(['imagen_formulario' => 'Error Cloudinary: ' . $e->getMessage()]);
            }
        }

        $curso->update($dataCurso);

        if ($request->has('modulos')) {
            foreach ($request->modulos as $id_modulo => $data) {
                $modulo = Modulo::find($id_modulo);
                if ($modulo) {
                    $modulo->update([
                        'nombre' => $data['nombre'],
                        'fecha_inicio' => !empty($data['fecha_inicio']) ? $data['fecha_inicio'] : null,
                        'fecha_fin' => !empty($data['fecha_fin']) ? $data['fecha_fin'] : null,
                    ]);
                }
            }
        }

        return redirect()->route('programs.index')
            ->with('success', 'Programa actualizado correctamente');
    }

    public function wpsender()
    {
        $usuario = auth()->user()->load('persona');
        return view('dashboard.wpsender', compact('usuario'));
    }

    public function show_student($id)
    {
        $estudiante = \App\Models\Estudiante::with([
            'ciudad.departamento',
            'institucionEgreso',
            'gradoAcademico',
            'profesion'
        ])->findOrFail($id);
        $usuario = auth()->user();
        return view('students.show', compact('estudiante', 'usuario'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Personal;
use App\Models\Sede;
use App\Models\Curso;
use App\Models\Institucion;
use App\Models\Docente;
use App\Models\Clase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{

    public function index()
    {
        $usuario = auth()->user()->load('persona');
        return view('dashboard.index', compact('usuario'));
    }

    public function students()
    {
        $usuario = auth()->user()->load('persona');
        return view('dashboard.people', compact('usuario'));
    }
    public function creations()
    {
        $usuario = auth()->user()->load('persona');
        return view('dashboard.creations', compact('usuario'));
    }

    public function staff(Request $request)
    {
        $usuario = auth()->user()->load('persona');

        // Traer sedes para el select
        $sedes = Sede::all();

        // Traer personal con relaciones necesarias y filtros
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

        // Paginación
        $personales = $query->paginate(25);

        // Traer áreas únicas del campo cargo
        $areas = Personal::select('cargo')
            ->distinct()
            ->orderBy('cargo')
            ->pluck('cargo');

        return view('dashboard.staff', compact('usuario', 'personales', 'sedes', 'areas'));
    }
    public function programs(Request $request)
    {
        $usuario = auth()->user()->load('persona');

        $query = \App\Models\Curso::with(['institucion', 'sede']);

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

        return view('dashboard.programs', compact(
            'usuario',
            'cursos',
            'sedes',
            'instituciones'
        ));
    }


    public function programsCreate()
    {
        $usuario = auth()->user()->load('persona');
        $instituciones = \App\Models\Institucion::all();
        $docentes = \App\Models\Docente::all();
        $personas = \App\Models\Persona::all();
        $sedes = \App\Models\Sede::all();

        return view('dashboard.programs_create', compact('usuario', 'instituciones', 'docentes', 'personas', 'sedes'));
    }

    public function programsStore(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_institucion' => 'required|exists:institucion,id_institucion',
            'id_sede' => 'required|exists:sede,id_sede', // Agregamos validación de sede
            'id_docente' => 'nullable|exists:docente,id_docente',
            'codigo_curso' => 'required|string|unique:curso,codigo_curso', // Importante validar el código único
            'codigo_qr' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $data = $request->all();

        // Lógica para guardar la imagen del QR
        if ($request->hasFile('codigo_qr')) {
            $data['codigo_qr'] = $request->file('codigo_qr')->store('qrs', 'public');
        }

        // Valores por defecto para evitar errores de NOT NULL
        $data['estado'] = $data['estado'] ?? 'Activo';
        $data['inscritos'] = 0;
        $data['pre_inscritos'] = 0;

        \App\Models\Curso::create($data);

        return redirect()->route('programs.index')->with('success', 'Programa creado con éxito');
    }

    public function programsShow($id)
    {
        // Traemos el curso con todas sus relaciones cargadas
        $curso = Curso::with([
            'institucion',
            'docente',
            'sede',
            'clases' => function ($query) {
                $query->orderBy('fecha', 'asc')->orderBy('hora_inicio', 'asc');
            }
        ])->findOrFail($id);

        $usuario = auth()->user();

        return view('programs.show', compact('curso', 'usuario'));
    }

    public function programsDestroy(Request $request, $id)
    {
        $request->validate([
            // Cambiado de confirmation a confirm
            'password_confirm' => 'required',
        ]);

        // Verificar si la contraseña coincide con la del usuario autenticado
        if (!Hash::check($request->password_confirm, auth()->user()->password)) {
            // Regresamos con un error específico si falla
            return back()->withErrors(['password_confirm' => 'La contraseña es incorrecta.']);
        }

        $curso = Curso::findOrFail($id);
        $curso->delete();

        return redirect()->route('programs.index')->with('success', 'Programa eliminado correctamente.');
    }

    public function updateStatus(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);
        $curso->update(['estado' => $request->estado]);
        return back()->with('success', 'Estado actualizado');
    }

    public function programsEdit($id)
    {
        $curso = Curso::with(['institucion', 'clases', 'docente', 'sede'])->findOrFail($id);
        $usuario = auth()->user();

        // Necesitamos estos para los selects
        $docentes = Docente::all();
        $sedes = Sede::all();
        $instituciones = Institucion::all();

        return view('programs.edit', compact('curso', 'usuario', 'docentes', 'sedes', 'instituciones'));
    }

    // 2. Procesar Actualización
    public function programsUpdate(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);

        // Actualizar Curso
        $dataCurso = $request->only([
            'nombre',
            'codigo_curso',
            'fecha_inicio',
            'fecha_fin',
            'estado',
            'inscritos',
            'pre_inscritos',
            'id_docente',
            'id_institucion',
            'id_sede'
        ]);

        if ($request->hasFile('codigo_qr')) {
            // Borrar anterior si existe
            if ($curso->codigo_qr) {
                Storage::disk('public')->delete($curso->codigo_qr);
            }
            $dataCurso['codigo_qr'] = $request->file('codigo_qr')->store('qrs', 'public');
        }

        $curso->update($dataCurso);

        // Actualizar Institución (Si se permite editar info básica desde aquí)
        if ($curso->id_institucion) {
            $institucion = Institucion::find($curso->id_institucion);
            $institucion->update($request->only(['direccion', 'telefono']));

            if ($request->hasFile('logo_institucion')) {
                if ($institucion->imagen) {
                    Storage::disk('public')->delete($institucion->imagen);
                }
                $institucion->imagen = $request->file('logo_institucion')->store('instituciones', 'public');
                $institucion->save();
            }
        }

        // Actualizar Clases (Manejo de múltiples registros)
        if ($request->has('clases')) {
            foreach ($request->clases as $id_clase => $claseData) {
                $clase = Clase::find($id_clase);
                if ($clase) {
                    $clase->update($claseData);
                }
            }
        }

        return redirect()->route('programs.show', $id)->with('success', 'Programa actualizado correctamente');
    }

    public function wpsender()
    {
        $usuario = auth()->user()->load('persona');
        return view('dashboard.wpsender', compact('usuario'));
    }

    //Funciones para personal

}
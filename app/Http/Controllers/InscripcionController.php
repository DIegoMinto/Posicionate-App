<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Personal;
use App\Models\Ciudad;
use App\Models\Profesion;
use App\Models\GradoAcademico;
use App\Models\InstitucionEgreso;
use App\Models\Estudiante;
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

    public function list($id)
    {
        $curso = Curso::findOrFail($id);

        $estudiantes = DB::table('curso_estudiante')
            ->join('estudiante', 'curso_estudiante.id_estudiante', '=', 'estudiante.id_estudiante')
            ->join('personal', 'estudiante.id_personal', '=', 'personal.id_personal') // 👈 AQUÍ EL FIX
            ->join('persona', 'personal.id_persona', '=', 'persona.id_persona')
            ->where('curso_estudiante.id_curso', $id)
            ->select(
                'estudiante.*',
                'curso_estudiante.estado',
                'curso_estudiante.created_at as fecha_inscripcion',
                'persona.nombre as asesor_nombre',
                'persona.apellido_p as asesor_apellido'
            )
            ->get();


        $usuario = auth()->user();

        return view('cursos.estudiantes', compact('curso', 'estudiantes', 'usuario'));
    }



}
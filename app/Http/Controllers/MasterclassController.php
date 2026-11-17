<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Docente;
use App\Models\Masterclass;
use Illuminate\Http\Request;

class MasterclassController extends Controller
{
    public function index($id_curso)
    {
        $usuario = auth()->user()->load('persona');

        $curso = Curso::findOrFail($id_curso);

        $masterclasses = Masterclass::with('docente')
            ->where('id_curso', $id_curso)
            ->orderBy('fecha_inicio')
            ->orderBy('id_masterclass')
            ->get();

        return view('modulos.masterclass.index', compact(
            'usuario',
            'curso',
            'masterclasses'
        ));
    }

    public function create($id_curso)
    {
        $usuario = auth()->user()->load('persona');

        $curso = Curso::findOrFail($id_curso);

        $docentes = Docente::orderBy('apellido_p')
            ->orderBy('nombre')
            ->get();

        return view('modulos.masterclass.form', compact(
            'usuario',
            'curso',
            'docentes'
        ));
    }

    public function store(Request $request, $id_curso)
    {
        $curso = Curso::findOrFail($id_curso);

        $datos = $request->validate([
            'titulo' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'id_docente' => 'nullable|exists:docente,id_docente',
        ]);

        Masterclass::create([
            'id_curso' => $curso->id_curso,
            'titulo' => $datos['titulo'],
            'fecha_inicio' => $datos['fecha_inicio'],
            'id_docente' => $datos['id_docente'] ?? null,
        ]);

        return redirect()
            ->route('programacion.masterclass.index', $id_curso)
            ->with('success', 'Masterclass registrada correctamente.');
    }

    public function edit($id_curso, $id_masterclass)
    {
        $usuario = auth()->user()->load('persona');

        $curso = Curso::findOrFail($id_curso);

        $masterclass = Masterclass::where('id_curso', $id_curso)
            ->findOrFail($id_masterclass);

        $docentes = Docente::orderBy('apellido_p')
            ->orderBy('nombre')
            ->get();

        return view('modulos.masterclass.form', compact(
            'usuario',
            'curso',
            'masterclass',
            'docentes'
        ));
    }

    public function update(Request $request, $id_curso, $id_masterclass)
    {
        $masterclass = Masterclass::where('id_curso', $id_curso)
            ->findOrFail($id_masterclass);

        $datos = $request->validate([
            'titulo' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'id_docente' => 'nullable|exists:docente,id_docente',
        ]);

        $masterclass->update($datos);

        return redirect()
            ->route('programacion.masterclass.index', $id_curso)
            ->with('success', 'Masterclass actualizada correctamente.');
    }

    public function destroy($id_curso, $id_masterclass)
    {
        $masterclass = Masterclass::where('id_curso', $id_curso)
            ->findOrFail($id_masterclass);

        $masterclass->delete();

        return redirect()
            ->route('programacion.masterclass.index', $id_curso)
            ->with('success', 'Masterclass eliminada correctamente.');
    }
}
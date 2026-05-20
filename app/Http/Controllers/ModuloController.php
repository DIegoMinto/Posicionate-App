<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Modulo;

class ModuloController extends Controller
{
    public function create(Request $request)
    {
        $id_curso = $request->query('id_curso');

        if (!$id_curso) {
            return redirect()->route('programs.index')->with('error', 'Debe seleccionar un curso.');
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

            return back()->with(
                'error',
                'Contraseña incorrecta.'
            );
        }

        $modulo = Modulo::findOrFail($id);

        $modulo->delete();

        return back()->with(
            'success',
            'Módulo eliminado correctamente.'
        );
    }

}
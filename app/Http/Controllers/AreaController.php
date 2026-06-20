<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::withCount('contrasenas')->orderBy('nombre', 'asc')->get();
        $usuario = auth()->user();

        return view('areas.index', compact('areas', 'usuario'));
    }

    public function create()
    {
        $usuario = auth()->user();

        $areas = \App\Models\Area::orderBy('nombre', 'asc')->get();

        return view('contrasenas.create', compact('usuario', 'areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:areas,nombre',
        ], [
            'nombre.unique' => 'Esta área ya se encuentra registrada en el sistema.',
        ]);

        Area::create([
            'nombre' => strtolower($request->input('nombre'))
        ]);

        return redirect()->route('areas.index')->with('success', 'Nueva área registrada correctamente.');
    }

    public function destroy($id)
    {
        $area = Area::withCount('contrasenas')->findOrFail($id);

        if ($area->contrasenas_count > 0) {
            return redirect()->route('areas.index')->with('error', 'No puedes eliminar un área que contiene contraseñas activas.');
        }

        $area->delete();
        return redirect()->route('areas.index')->with('success', 'Área eliminada correctamente.');
    }
}
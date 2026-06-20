<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\ContrasenaArea;
use Illuminate\Support\Facades\Crypt;

class ContrasenaController extends Controller
{
    public function index(Request $request)
    {
        $query = ContrasenaArea::with('area');

        if ($request->filled('area_id')) {
            $query->where('area_id', $request->input('area_id'));
        }

        $contrasenas = $query->latest()->get();

        $areas = Area::orderBy('nombre', 'asc')->get();
        $usuario = auth()->user();

        return view('contrasenas.index', compact('contrasenas', 'areas', 'usuario'));
    }

    public function create()
    {
        $usuario = auth()->user();

        $areas = Area::orderBy('nombre', 'asc')->get();

        return view('contrasenas.create', compact('usuario', 'areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'area_id' => 'required|exists:areas,id',
            'contrasena' => 'required|string|min:6',
        ]);

        ContrasenaArea::create([
            'area_id' => $request->input('area_id'),
            'contrasena_encriptada' => Crypt::encryptString($request->input('contrasena')),
        ]);

        return redirect()->route('contrasenas.index')
            ->with('success', "Contraseña asignada con éxito al área seleccionada.");
    }

    public function destroy(Request $request, $id)
    {
        $contrasena = ContrasenaArea::findOrFail($id);
        $contrasena->delete();

        return redirect()->route('contrasenas.index')->with('success', 'Registro eliminado correctamente.');
    }
}
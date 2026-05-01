<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use Illuminate\Http\Request;

class SedeController extends Controller
{
    public function index()
    {
        $sedes = Sede::all();
        $usuario = auth()->user()->load('persona');
        return view('sedes.index', compact('sedes', 'usuario'));
    }

    public function create()
    {
        $usuario = auth()->user()->load('persona');
        return view('sedes.create', compact('usuario'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        Sede::create($request->all());

        return redirect()->route('sedes.index')
            ->with('success', 'Sede registrada con éxito.');
    }

    public function edit($id)
    {
        $sede = Sede::findOrFail($id);
        $usuario = auth()->user()->load('persona');
        return view('sedes.edit', compact('sede', 'usuario'));
    }

    public function update(Request $request, string $id)
    {
        $sede = Sede::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:150',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        $sede->update($request->all());

        return redirect()->route('sedes.index')
            ->with('success', 'Sede actualizada correctamente.');
    }

    public function destroy(string $id)
    {
        $sede = Sede::findOrFail($id);
        if ($sede->personal()->count() > 0) {
            return redirect()->route('sedes.index')
                ->with('error', 'No se puede eliminar la sede porque tiene personal asignado.');
        }

        $sede->delete();

        return redirect()->route('sedes.index')
            ->with('success', 'Sede eliminada correctamente.');
    }
}
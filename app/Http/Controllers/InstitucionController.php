<?php

namespace App\Http\Controllers;

use App\Models\Institucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstitucionController extends Controller
{
    /**
     * Muestra la lista de instituciones.
     */
    public function index()
    {
        $instituciones = Institucion::all();
        $usuario = auth()->user()->load('persona');
        return view('institutions.index', compact('instituciones', 'usuario'));
    }

    public function create()
    {
        $usuario = auth()->user();
        return view('institutions.create', compact('usuario'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            // Se guarda en storage/app/public/instituciones
            $path = $request->file('imagen')->store('instituciones', 'public');
            $data['imagen'] = $path;
        }

        Institucion::create($data);

        return redirect()->route('institutions.index')
            ->with('success', 'Institución registrada con éxito.');
    }

    public function edit($id)
    {
        $institution = Institucion::findOrFail($id);
        $usuario = auth()->user();
        return view('institutions.edit', compact('institution', 'usuario'));
    }

    public function update(Request $request, string $id)
    {
        $institucion = Institucion::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:150',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            // Borramos la imagen antigua si existe para no llenar el server de basura
            if ($institucion->imagen) {
                Storage::disk('public')->delete($institucion->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('instituciones', 'public');
        }

        $institucion->update($data);

        return redirect()->route('institutions.index')
            ->with('success', 'Institución actualizada correctamente.');
    }

    public function destroy(string $id)
    {
        $institucion = Institucion::findOrFail($id);

        // Borrar imagen del storage antes de eliminar el registro
        if ($institucion->imagen) {
            Storage::disk('public')->delete($institucion->imagen);
        }

        $institucion->delete();

        return redirect()->route('institutions.index')
            ->with('success', 'Institución eliminada.');
    }

    public function show(string $id)
    {
        $institucion = Institucion::findOrFail($id);
        return view('institutions.show', compact('institucion'));
    }
}
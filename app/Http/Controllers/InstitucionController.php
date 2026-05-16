<?php

namespace App\Http\Controllers;

use App\Models\Institucion;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;

class InstitucionController extends Controller
{
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
                $request->file('imagen')->getRealPath(),
                [
                    'folder' => 'instituciones'
                ]
            );

            $data['imagen'] = $upload['secure_url'];
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
                $request->file('imagen')->getRealPath(),
                [
                    'folder' => 'instituciones'
                ]
            );

            $data['imagen'] = $upload['secure_url'];
        }

        $institucion->update($data);

        return redirect()->route('institutions.index')
            ->with('success', 'Institución actualizada correctamente.');
    }
    public function destroy(string $id)
    {
        $institucion = Institucion::findOrFail($id);

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
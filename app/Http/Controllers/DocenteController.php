<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Docente;
use App\Models\Pais;
use App\Models\GradoAcademico;
use App\Models\Profesion;
use App\Models\InstitucionEgreso;
use App\Models\InstitucionBancaria;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Cloudinary\Cloudinary;

class DocenteController extends Controller
{

    public function teachers(Request $request)
    {
        $query = Docente::with(['grado', 'profesion', 'ciudad', 'institucion', 'institucion_bancaria']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'ILIKE', "%{$request->search}%")
                    ->orWhere('apellido_p', 'ILIKE', "%{$request->search}%")
                    ->orWhere('apellido_m', 'ILIKE', "%{$request->search}%")
                    ->orWhere('ci', 'LIKE', "%{$request->search}%");
            });
        }

        if ($request->filled('area')) {
            $query->where('area', $request->area);
        }

        if ($request->filled('emite_factura')) {
            $query->where('emite_factura', $request->emite_factura);
        }

        if ($request->filled('id_ciudad')) {
            $query->where('id_ciudad', $request->id_ciudad);
        }

        $docentes = $query->get();

        $areas = Docente::select('area')->whereNotNull('area')->distinct()->pluck('area');
        $ciudades = \App\Models\Ciudad::all();

        $usuario = auth()->user()->load('persona');

        return view('personas.teachers', compact('docentes', 'usuario', 'areas', 'ciudades'));
    }

    public function create()
    {
        $paises = Pais::all();
        $grados = GradoAcademico::all();
        $profesiones = Profesion::all();
        $instituciones = InstitucionEgreso::all();
        $bancos = InstitucionBancaria::all();

        return view('personas.register_teacher', compact('paises', 'grados', 'profesiones', 'instituciones', 'bancos'));
    }

    public function store(Request $request)
    {

        $data = [];

        try {
            $request->validate([
                'nombre' => 'required|string|max:100',
                'apellido_p' => 'required|string|max:100',
                'ci' => 'required|numeric|unique:docente,ci',
                'extension_ci' => 'required|string|max:10',
                'correo_electronico' => 'required|email|unique:docente,correo_electronico',
                'id_ciudad' => 'required',
                'id_institucion_egreso' => 'required',
                'id_grado_academico' => 'required',
                'id_profesion' => 'required',
                'id_institucion_bancaria' => 'required',
                'emite_factura' => 'required',
            ]);

            $extensionFinal = $request->extension_select === 'OTRO'
                ? strtoupper(trim($request->extension_otro))
                : $request->extension_select;


            $data = $request->except(['_token', 'extension_select', 'extension_otro', 'id_pais', 'id_departamento']);

            $data['extension_ci'] = $extensionFinal;
            $data['emite_factura'] = ($request->emite_factura == '1') ? 'SI' : 'NO';

            $idArchivo = $request->ci . '_' . $extensionFinal;
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => ['secure' => true]
            ]);

            $idArchivo = $request->ci . '_' . $extensionFinal;

            if ($request->hasFile('curriculum')) {
                $upload = $cloudinary->uploadApi()->upload(
                    $request->file('curriculum')->getRealPath(),
                    [
                        'folder' => 'curriculums',
                        'resource_type' => 'auto',
                        'public_id' => "CV_$idArchivo",
                        'access_mode' => 'public'
                    ]
                );
                $data['curriculum'] = $upload['secure_url'];
            }

            if ($request->hasFile('fotocarnet')) {
                $upload = $cloudinary->uploadApi()->upload(
                    $request->file('fotocarnet')->getRealPath(),
                    [
                        'folder' => 'carnets',
                        'resource_type' => 'auto',
                        'public_id' => "CARNET_$idArchivo",
                        'access_mode' => 'public'
                    ]
                );
                $data['fotocarnet'] = $upload['secure_url'];
            }
            if ($request->hasFile('fotografia')) {
                $upload = $cloudinary->uploadApi()->upload(
                    $request->file('fotografia')->getRealPath(),
                    [
                        'folder' => 'fotografias',
                        'public_id' => "FOTO_$idArchivo"
                    ]
                );
                $data['fotografia'] = $upload['secure_url'];
            }

            Docente::create($data);

            return back()->with('success', "¡Docente " . $request->nombre . " registrado con éxito!");

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            dd([
                'Mensaje' => $e->getMessage(),
                'Linea' => $e->getLine(),
                'Datos_que_fallaron' => $data
            ]);
        }
    }

    public function show(Docente $docente)
    {
        $docente->load(['grado', 'profesion', 'ciudad', 'institucion', 'institucion_bancaria']);

        $usuario = auth()->user();

        return view('personas.show_doc', compact('docente', 'usuario'));
    }

    public function edit(Docente $docente)
    {
        if (auth()->user()->rol !== 'super_admin') {
            abort(403, 'No autorizado');
        }
        $paises = Pais::all();
        $profesiones = Profesion::all();
        $instituciones = InstitucionEgreso::all();
        $grados = GradoAcademico::all();
        $bancos = InstitucionBancaria::all();
        $ciudades = \App\Models\Ciudad::all();

        $usuario = auth()->user()->load('persona');

        return view('personas.edit_teacher', compact(
            'docente',
            'paises',
            'profesiones',
            'instituciones',
            'grados',
            'bancos',
            'ciudades',
            'usuario'
        ));
    }

    public function update(Request $request, Docente $docente)
    {
        if (auth()->user()->rol !== 'super_admin') {
            abort(403, 'No autorizado');
        }
        $data = [];
        try {
            $request->validate([
                'nombre' => 'required|string|max:100',
                'apellido_p' => 'required|string|max:100',
                'ci' => 'required|numeric|unique:docente,ci,' . $docente->id_docente . ',id_docente',
                'extension_select' => 'required',
                'correo_electronico' => 'required|email|unique:docente,correo_electronico,' . $docente->id_docente . ',id_docente',
                'id_ciudad' => 'required',
                'id_institucion_egreso' => 'required',
                'id_grado_academico' => 'required',
                'id_profesion' => 'required',
                'id_institucion_bancaria' => 'required',
                'emite_factura' => 'required',
            ]);

            $extensionFinal = $request->extension_select === 'OTRO'
                ? strtoupper(trim($request->extension_otro))
                : $request->extension_select;

            $data = $request->except(['_token', '_method', 'extension_select', 'extension_otro', 'id_pais', 'id_departamento']);

            $data['extension_ci'] = $extensionFinal;
            $data['emite_factura'] = ($request->emite_factura == '1') ? 'SI' : 'NO';

            $idArchivo = $request->ci . '_' . $extensionFinal;

            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => ['secure' => true]
            ]);

            $idArchivo = $request->ci . '_' . $extensionFinal;

            if ($request->hasFile('curriculum')) {
                $upload = $cloudinary->uploadApi()->upload(
                    $request->file('curriculum')->getRealPath(),
                    ['folder' => 'curriculums', 'resource_type' => 'auto', 'public_id' => "CV_$idArchivo"]
                );
                $data['curriculum'] = $upload['secure_url'];
            }

            if ($request->hasFile('fotocarnet')) {
                $upload = $cloudinary->uploadApi()->upload(
                    $request->file('fotocarnet')->getRealPath(),
                    ['folder' => 'carnets', 'resource_type' => 'auto', 'public_id' => "CARNET_$idArchivo"]
                );
                $data['fotocarnet'] = $upload['secure_url'];
            }

            if ($request->hasFile('fotografia')) {
                $upload = $cloudinary->uploadApi()->upload(
                    $request->file('fotografia')->getRealPath(),
                    ['folder' => 'fotografias', 'public_id' => "FOTO_$idArchivo"]
                );
                $data['fotografia'] = $upload['secure_url'];
            }

            $docente->update($data);

            return redirect()->route('teachers.index')->with('success', "Docente " . $request->nombre . " actualizado correctamente.");

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy(Request $request, Docente $docente)
    {
        if (auth()->user()->rol !== 'super_admin') {
            abort(403, 'No autorizado');
        }
        try {
            $request->validate([
                'password_confirm' => 'required',
            ]);

            if (!Hash::check($request->password_confirm, auth()->user()->password)) {
                return back()->withErrors(['password_confirm' => 'La contraseña es incorrecta.']);
            }


            $archivos = ['curriculum', 'fotocarnet', 'fotografia'];
            foreach ($archivos as $campo) {
                if ($docente->$campo && Storage::disk('public')->exists($docente->$campo)) {
                    Storage::disk('public')->delete($docente->$campo);
                }
            }

            if ($docente->cursos()->exists()) {
                $docente->cursos()->update(['id_docente' => null]);
            }

            $nombreCompleto = $docente->nombre . ' ' . $docente->apellido_p;

            $eliminado = $docente->delete();

            if ($eliminado) {
                return redirect()->route('teachers.index')->with('success', "El docente $nombreCompleto ha sido eliminado. Los cursos asociados se mantuvieron sin docente.");
            } else {
                return back()->withErrors(['error' => 'El modelo no pudo ser eliminado de la base de datos.']);
            }

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error de base de datos: ' . $e->getMessage()]);
        }
    }

}
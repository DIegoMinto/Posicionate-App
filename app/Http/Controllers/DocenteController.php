<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Docente;
use App\Models\Pais;
use App\Models\GradoAcademico;
use App\Models\Profesion;
use App\Models\InstitucionEgreso;
use App\Models\InstitucionBancaria;
use App\Models\AreaDocente;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\DB;

class DocenteController extends Controller
{


    public function teachers(Request $request)
    {
        $query = Docente::with([
            'grado',
            'profesion',
            'ciudad',
            'institucion',
            'institucion_bancaria',
            'areasDocente',
        ]);

        if ($request->filled('search')) {
            $busqueda = $request->search;

            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'ILIKE', "%{$busqueda}%")
                    ->orWhere('apellido_p', 'ILIKE', "%{$busqueda}%")
                    ->orWhere('apellido_m', 'ILIKE', "%{$busqueda}%")
                    ->orWhere('ci', 'LIKE', "%{$busqueda}%");
            });
        }

        if ($request->filled('area')) {
            $query->whereHas('areasDocente', function ($q) use ($request) {
                $q->where(
                    'area_docente.id_area_docente',
                    $request->area
                );
            });
        }

        if ($request->filled('emite_factura')) {
            $query->where(
                'emite_factura',
                $request->emite_factura
            );
        }

        if ($request->filled('id_ciudad')) {
            $query->where('id_ciudad', $request->id_ciudad);
        }

        $docentes = $query
            ->orderBy('docente.created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $areas = AreaDocente::orderBy('nombre')->get();
        $ciudades = \App\Models\Ciudad::all();

        $usuario = auth()->user()->load('persona');

        return view('personas.teachers', compact(
            'docentes',
            'usuario',
            'areas',
            'ciudades'
        ));
    }

    public function create()
    {
        $paises = Pais::all();
        $grados = GradoAcademico::all();
        $profesiones = Profesion::all();
        $instituciones = InstitucionEgreso::all();
        $bancos = InstitucionBancaria::all();

        $areasDocente = AreaDocente::orderBy('nombre')->get();

        return view('personas.register_teacher', compact(
            'paises',
            'grados',
            'profesiones',
            'instituciones',
            'bancos',
            'areasDocente'
        ));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido_p' => 'required|string|max:100',
            'apellido_m' => 'nullable|string|max:100',

            'fecha_nacimiento' => 'required|date|before:-18 years',

            'ci' => 'required|numeric|digits_between:5,10|unique:docente,ci',
            'extension_ci' => 'required|string|max:10',

            'correo_electronico' => 'required|email|unique:docente,correo_electronico',

            'id_pais' => 'required|exists:pais,id_pais',
            'id_departamento' => 'required|exists:departamento,id_departamento',

            'lugar_nacimiento' => 'required|string|max:150',
            'domicilio' => 'nullable|string|max:255',

            'genero' => 'required|in:M,F',

            'id_institucion_bancaria' => 'required',
            'numero_cuenta_bancaria' => 'required|string|max:30',

            'id_profesion' => 'required',
            'id_grado_academico' => 'required',
            'id_institucion_egreso' => 'required',

            'codigo_pais_movil' => 'required|string|max:5',
            'numero_movil' => 'required|numeric|digits_between:6,15',

            'programas_dar' => 'nullable|string',
            'emite_factura' => 'required|in:0,1',

            // El currículum es opcional.
            'curriculum' => 'nullable|file|mimes:pdf|max:10240',

            // El carnet escaneado sigue siendo obligatorio.
            'fotocarnet' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',

            // La fotografía es opcional.
            'fotografia' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',

            'areas_docente' => 'required|array|min:1',
            'areas_docente.*' => 'required|integer|distinct|exists:area_docente,id_area_docente',
        ], [
            'required' => 'Este campo es obligatorio.',
            'exists' => 'La opción seleccionada no es válida.',

            'nombre.required' => 'Ingresa tu nombre.',
            'apellido_p.required' => 'Ingresa tu primer apellido.',

            'fecha_nacimiento.required' => 'Ingresa tu fecha de nacimiento.',
            'fecha_nacimiento.before' => 'Debes tener al menos 18 años.',

            'ci.required' => 'Ingresa tu carnet de identidad.',
            'ci.numeric' => 'El carnet debe contener solo números.',
            'ci.digits_between' => 'El carnet debe tener entre 5 y 10 dígitos.',
            'ci.unique' => 'Este número de carnet ya está registrado.',

            'extension_ci.required' => 'Selecciona la extensión del carnet.',

            'correo_electronico.required' => 'Ingresa tu correo electrónico.',
            'correo_electronico.email' => 'Escribe un correo electrónico válido.',
            'correo_electronico.unique' => 'Este correo ya está registrado.',

            'id_pais.required' => 'Selecciona un país.',
            'id_departamento.required' => 'Selecciona un departamento.',

            'lugar_nacimiento.required' => 'Ingresa tu lugar de nacimiento.',

            'genero.required' => 'Selecciona tu género.',

            'id_institucion_bancaria.required' => 'Selecciona una entidad bancaria.',
            'numero_cuenta_bancaria.required' => 'Ingresa tu número de cuenta.',

            'id_profesion.required' => 'Selecciona tu profesión u ocupación.',
            'id_grado_academico.required' => 'Selecciona tu grado académico.',
            'id_institucion_egreso.required' => 'Selecciona tu institución de egreso.',

            'codigo_pais_movil.required' => 'Selecciona el código de país.',
            'numero_movil.required' => 'Ingresa tu número de celular.',
            'numero_movil.numeric' => 'El número de celular debe contener solo dígitos.',
            'numero_movil.digits_between' => 'El celular debe tener entre 6 y 15 dígitos.',

            'emite_factura.required' => 'Indica si emites factura.',

            'curriculum.mimes' => 'El currículum debe ser un archivo PDF.',
            'curriculum.max' => 'El currículum no puede superar los 10 MB.',

            'fotocarnet.required' => 'Debes adjuntar una foto o PDF de tu carnet.',
            'fotocarnet.mimes' => 'El carnet debe ser PDF, JPG o PNG.',
            'fotocarnet.max' => 'El archivo del carnet no puede superar los 10 MB.',

            'fotografia.image' => 'La fotografía debe ser una imagen válida.',
            'fotografia.mimes' => 'La fotografía debe ser JPG o PNG.',
            'fotografia.max' => 'La fotografía no puede superar los 10 MB.',
        ]);

        $data = [];

        try {
            $extensionFinal = strtoupper(trim($validated['extension_ci']));

            $data = $request->except([
                '_token',
                'id_pais',
                'id_departamento',
                'id_ciudad',
                'codigo_pais_movil',
                'numero_movil',
                'areas_docente',
                'curriculum',
                'fotocarnet',
                'fotografia',
            ]);

            $data['extension_ci'] = $extensionFinal;

            $data['telefono_movil'] = trim(
                $validated['codigo_pais_movil'] . ' ' . $validated['numero_movil']
            );

            $data['emite_factura'] =
                $validated['emite_factura'] === '1' ? 'SI' : 'NO';

            $idArchivo = $validated['ci'] . '_' . $extensionFinal;

            $cloudinary = new \Cloudinary\Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => [
                    'secure' => true,
                ],
            ]);

            if ($request->hasFile('curriculum')) {
                $upload = retry(
                    4,
                    fn() =>
                    $cloudinary->uploadApi()->upload(
                        $request->file('curriculum')->getRealPath(),
                        [
                            'folder' => 'curriculums',
                            'resource_type' => 'raw',
                            'public_id' => "CV_$idArchivo",
                        ]
                    ),
                    500
                );

                $data['curriculum'] = $upload['secure_url'];
            }

            $uploadCarnet = retry(
                4,
                fn() =>
                $cloudinary->uploadApi()->upload(
                    $request->file('fotocarnet')->getRealPath(),
                    [
                        'folder' => 'carnets',
                        'resource_type' => 'auto',
                        'public_id' => "CARNET_$idArchivo",
                    ]
                ),
                500
            );

            $data['fotocarnet'] = $uploadCarnet['secure_url'];
            if ($request->hasFile('fotografia')) {
                $uploadFoto = retry(
                    4,
                    fn() =>
                    $cloudinary->uploadApi()->upload(
                        $request->file('fotografia')->getRealPath(),
                        [
                            'folder' => 'fotografias',
                            'resource_type' => 'image',
                            'public_id' => "FOTO_$idArchivo",
                        ]
                    ),
                    500
                );

                $data['fotografia'] = $uploadFoto['secure_url'];
            }

            DB::transaction(function () use ($data, $validated) {
                $docente = Docente::create($data);

                $docente->areasDocente()->sync(
                    $validated['areas_docente']
                );
            });

            return redirect()
                ->route('docentes.create')
                ->with('success', "¡Docente {$validated['nombre']} registrado con éxito!");

        } catch (\Throwable $e) {
            \Log::error('Error al registrar docente', [
                'mensaje' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile(),
            ]);

            return back()
                ->withErrors([
                    'error' => 'No se pudo registrar al docente. Revisa los datos e inténtalo nuevamente.',
                ])
                ->withInput($request->except([
                    'curriculum',
                    'fotocarnet',
                    'fotografia',
                ]));
        }
    }


    public function show(Docente $docente)
    {
        $docente->load([
            'grado',
            'profesion',
            'ciudad',
            'institucion',
            'institucion_bancaria',
            'areasDocente',
        ]);

        $usuario = auth()->user();

        return view('personas.show_doc', compact(
            'docente',
            'usuario'
        ));
    }

    public function edit(Docente $docente)
    {
        if (auth()->user()->rol !== 'super_admin') {
            abort(403, 'No autorizado');
        }

        $docente->load('areasDocente');

        $paises = Pais::all();
        $profesiones = Profesion::all();
        $instituciones = InstitucionEgreso::all();
        $grados = GradoAcademico::all();
        $bancos = InstitucionBancaria::all();
        $ciudades = \App\Models\Ciudad::all();

        $areasDocente = AreaDocente::orderBy('nombre')->get();

        $usuario = auth()->user()->load('persona');

        return view('personas.edit_teacher', compact(
            'docente',
            'paises',
            'profesiones',
            'instituciones',
            'grados',
            'bancos',
            'ciudades',
            'areasDocente',
            'usuario'
        ));
    }

    public function update(Request $request, Docente $docente)
    {
        if (auth()->user()->rol !== 'super_admin') {
            abort(403, 'No autorizado');
        }

        try {
            // 1. Validación (se ajustó nullable en archivos para evitar requerirlos al editar)
            $validated = $request->validate([
                'nombre' => 'required|string|max:100',
                'apellido_p' => 'required|string|max:100',
                'ci' => 'required|numeric|unique:docente,ci,' . $docente->id_docente . ',id_docente',
                'extension_ci' => 'required|string|max:10',
                'correo_electronico' => 'required|email|unique:docente,correo_electronico,' . $docente->id_docente . ',id_docente',
                'id_ciudad' => 'required',
                'id_institucion_egreso' => 'required',
                'id_grado_academico' => 'required',
                'id_profesion' => 'required',
                'id_institucion_bancaria' => 'required',
                'emite_factura' => 'required',
                'numero_movil' => 'required|string|max:20',
                'codigo_pais_movil' => 'required|string|max:5',
                'curriculum' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
                'fotocarnet' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'fotografia' => 'nullable|image|max:5120',
                'areas_docente' => 'required|array|min:1',
                'areas_docente.*' => 'required|integer|distinct|exists:area_docente,id_area_docente',
            ]);

            // 2. Extraer solo los campos que van a la BD (excluyendo archivos e inputs auxiliares)
            $data = $request->except([
                '_token',
                '_method',
                'id_pais',
                'id_departamento',
                'codigo_pais_movil',
                'numero_movil',
                'curriculum',
                'fotocarnet',
                'fotografia',
                'areas_docente',
            ]);

            // 3. Formatear valores
            $extensionFinal = strtoupper(trim($request->extension_ci));
            $data['extension_ci'] = $extensionFinal;
            $data['telefono_movil'] = trim($request->codigo_pais_movil . ' ' . $request->numero_movil);
            $data['emite_factura'] = ($request->emite_factura == '1' || $request->emite_factura == 'SI') ? 'SI' : 'NO';

            // 4. Instanciar Cloudinary
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => ['secure' => true]
            ]);

            $idArchivo = $request->ci . '_' . $extensionFinal;

            // 5. Cargar archivos únicamente si fueron adjuntados
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

            // 6. Actualizar registro
            DB::transaction(function () use ($docente, $data, $validated) {
                $docente->update($data);

                $docente->areasDocente()->sync(
                    $validated['areas_docente']
                );
            });

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
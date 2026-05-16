<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Personal;
use App\Models\Sede;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Cloudinary\Cloudinary;

class UserController extends Controller
{
    public function create()
    {
        $usuario = auth()->user()->load('persona');
        $personas = Persona::whereDoesntHave('personal')->get();
        return view('creations.newuser', compact('personas', 'usuario'));
    }

    public function create_user($id)
    {
        $usuario = auth()->user()->load('persona');

        $persona = Persona::findOrFail($id);

        $sedes = Sede::all();

        return view('creations.adduser', compact('persona', 'usuario', 'sedes'));
    }

    public function store_user(Request $request)
    {
        if (auth()->user()->rol !== 'super_admin') {
            abort(403, 'No autorizado');
        }
        $request->validate([
            'id_persona' => 'required|exists:persona,id_persona',
            'user' => 'required|unique:personal,user',
            'password' => 'required|confirmed|min:6',
            'id_sede' => 'required|exists:sede,id_sede',
            'cargo' => [
                'required',
                Rule::in([
                    'gerente_marketing',
                    'supervisor_marketing',
                    'coordinador_marketing',
                    'asesor_marketing',
                    'supervisor_academico',
                    'coordinador_academico',
                    'asistente_academico',
                    'contador',
                    'asistente_contable'
                ])
            ],

            'rol' => 'required|in:super_admin,admin,user,viewer'
        ]);

        $persona = Persona::findOrFail($request->id_persona);
        $sede = Sede::findOrFail($request->id_sede);

        $prefijoSede = (str_contains(strtoupper($sede->nombre), 'LA PLATA')) ? 'PLP' : 'SED';

        $iniciales = strtoupper(substr($persona->nombre, 0, 1) . substr($persona->apellido_p, 0, 1));

        $numeroConFormato = str_pad($persona->id_persona, 2, '0', STR_PAD_LEFT);

        $codigoFinal = "{$prefijoSede}-{$iniciales}{$numeroConFormato}";

        Personal::create([
            'id_persona' => $request->id_persona,
            'codigo_personal' => $codigoFinal,
            'cargo' => $request->cargo,
            'user' => $request->user,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'rol' => $request->rol,
            'id_sede' => $request->id_sede,
            'es_vigente' => true
        ]);

        return redirect()->route('people.staff')->with('success', "Personal dado de alta con código: {$codigoFinal}");
    }

    public function destroy(Request $request, $id)
    {
        if (auth()->user()->rol !== 'super_admin') {
            abort(403, 'No autorizado');
        }
        if (!Hash::check($request->password_confirm, auth()->user()->password)) {
            return back()->withErrors(['password_confirm' => 'Contraseña incorrecta']);
        }

        $user = Personal::findOrFail($id); // o tu modelo real

        $user->delete();

        return redirect()->route('people.staff')->with('success', 'Personal eliminado correctamente');
    }

    public function toggle(Request $request, $id)
    {
        if (!Hash::check($request->password_confirm, auth()->user()->password)) {
            return back()->withErrors(['password_confirm' => 'Contraseña incorrecta']);
        }
        $personal = \App\Models\Personal::findOrFail($id);
        $personal->es_vigente = !$personal->es_vigente;
        $personal->save();

        return back()->with('success', 'Estado actualizado');
    }

    public function show($id)
    {
        $personal = \App\Models\Personal::with([
            'persona.ciudad.departamento',
            'persona.institucion',
            'persona.grado',
            'persona.profesion',
            'sede'
        ])->findOrFail($id);
        $usuario = auth()->user();
        return view('users.show', compact('personal', 'usuario'));
    }

    public function edit($id)
    {
        if (auth()->user()->rol !== 'super_admin') {
            abort(403, 'No autorizado');
        }
        $personal = Personal::with('persona')->findOrFail($id);
        $persona = $personal->persona;
        $usuario = auth()->user()->load('persona');
        $sedes = Sede::all();
        $paises = \App\Models\Pais::all();
        $grados = \App\Models\GradoAcademico::all();
        $profesiones = \App\Models\Profesion::all();
        $instituciones = \App\Models\InstitucionEgreso::all();
        $bancos = \App\Models\InstitucionBancaria::all();
        $ciudades = \App\Models\Ciudad::all();
        return view('personas.edit_staff', compact(
            'persona',
            'personal',
            'usuario',
            'sedes',
            'paises',
            'grados',
            'profesiones',
            'instituciones',
            'bancos',
            'ciudades'
        ));
    }

    public function update(Request $request, $id)
    {
        $personal = Personal::findOrFail($id);
        $persona = $personal->persona;

        $request->validate([
            // Validación Persona
            'nombre' => 'required|string|max:100',
            'apellido_p' => 'required|string|max:100',
            'ci' => 'required|unique:persona,ci,' . $persona->id_persona . ',id_persona',
            'correo_electronico' => 'required|email|unique:persona,correo_electronico,' . $persona->id_persona . ',id_persona',
            'user' => 'required|unique:personal,user,' . $personal->id_personal . ',id_personal',
            'id_sede' => 'required|exists:sede,id_sede',
            'cargo' => [
                'required',
                Rule::in([
                    'gerente_marketing',
                    'supervisor_marketing',
                    'asesora_marketing',
                    'supervisor_academico',
                    'coordinador_academico',
                    'asistente_academico',
                    'contador',
                    'asistente_contable'
                ])
            ],

            'rol' => 'required|in:super_admin,admin,user,viewer',
            'password' => 'nullable|confirmed|min:6',
        ]);

        try {
            DB::beginTransaction();

            $personaData = $request->only([
                'nombre',
                'apellido_p',
                'apellido_m',
                'correo_electronico',
                'id_ciudad',
                'id_institucion_egreso',
                'id_grado_academico',
                'id_profesion',
                'id_institucion_bancaria',
                'telefono_movil'
            ]);

            if ($request->hasFile('fotografia')) {

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

                $uploadFoto = $cloudinary->uploadApi()->upload(
                    $request->file('fotografia')->getRealPath(),
                    [
                        'folder' => 'fotografias'
                    ]
                );

                $personaData['fotografia'] = $uploadFoto['secure_url'];
            }
            $persona->update($personaData);

            $personalData = [
                'user' => $request->user,
                'id_sede' => $request->id_sede,
                'cargo' => $request->cargo,
                'rol' => $request->rol,
            ];

            if ($request->filled('password')) {
                $personalData['password'] = Hash::make($request->password);
            }

            $personal->update($personalData);

            DB::commit();
            return redirect()->route('people.staff')->with('success', 'Personal y datos personales actualizados.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage())->withInput();
        }
    }
}
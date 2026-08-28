<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Personal;
use App\Models\Sede;
use App\Models\Pais;
use App\Models\GradoAcademico;
use App\Models\Profesion;
use App\Models\InstitucionEgreso;
use App\Models\InstitucionBancaria;
use App\Models\Ciudad;
use App\Models\Rol;
use App\Models\Cargo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Cloudinary\Cloudinary;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StaffExport;

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

        $cargos = Cargo::all();
        $roles = Rol::all();

        $esSuperAdmin = $usuario->hasRole('super_admin') || $usuario->rol === 'super_admin';

        return view('creations.adduser', compact(
            'persona',
            'usuario',
            'sedes',
            'cargos',
            'roles',
            'esSuperAdmin'
        ));
    }

    public function store_user(Request $request)
    {
        // CORREGIDO: Homogenización de validación de SuperAdmin
        if (auth()->user()->rol !== 'super_admin') {
            abort(403, 'No autorizado');
        }

        $request->validate([
            'id_persona' => 'required|exists:persona,id_persona',
            'user' => 'required|unique:personal,user',
            'password' => 'required|confirmed|min:6',
            'id_sede' => 'required|exists:sede,id_sede',
            'cargos' => 'required|array|min:1',
            'cargos.*' => 'exists:cargos,id_cargo',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id_rol',
        ]);

        $persona = Persona::findOrFail($request->id_persona);
        $sede = Sede::findOrFail($request->id_sede);

        $prefijoSede = str_contains(strtoupper($sede->nombre), 'LA PLATA') ? 'PLP' : 'SED';
        $iniciales = strtoupper(substr($persona->nombre, 0, 1) . substr($persona->apellido_p, 0, 1));
        $numeroConFormato = str_pad($persona->id_persona, 2, '0', STR_PAD_LEFT);

        $codigoFinal = "{$prefijoSede}-{$iniciales}{$numeroConFormato}";

        $personal = Personal::create([
            'id_persona' => $request->id_persona,
            'codigo_personal' => $codigoFinal,
            'user' => $request->user,
            'password' => Hash::make($request->password),
            'id_sede' => $request->id_sede,
            'es_vigente' => true
        ]);

        $personal->cargos()->sync($request->cargos);
        $personal->roles()->sync($request->roles);

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

        $user = Personal::findOrFail($id);
        $user->delete();

        return redirect()->route('people.staff')->with('success', 'Personal eliminado correctamente');
    }

    public function toggle(Request $request, $id)
    {
        if (!Hash::check($request->password_confirm, auth()->user()->password)) {
            return back()->withErrors(['password_confirm' => 'Contraseña incorrecta']);
        }

        $personal = Personal::findOrFail($id);
        $personal->es_vigente = !$personal->es_vigente;
        $personal->save();

        return back()->with('success', 'Estado actualizado');
    }

    public function show($id)
    {
        $auth = auth()->user();

        // CORREGIDO: Redundancia eliminada
        $personal = Personal::with([
            'persona.ciudad.departamento',
            'persona.institucion',
            'persona.grado',
            'persona.profesion',
            'sede',
            'cargos',
            'roles'
        ])->findOrFail($id);

        $esSuperAdmin = $auth->rol === 'super_admin';
        $esMismoUsuario = $auth->id_personal === $personal->id_personal;

        if (!$esSuperAdmin && !$esMismoUsuario) {
            abort(403, 'No autorizado');
        }

        $usuario = $auth;

        return view('users.show', compact('personal', 'usuario'));
    }

    public function edit($id)
    {
        $auth = auth()->user();

        // CORREGIDO: Redundancia eliminada
        $personal = Personal::with('persona', 'cargos', 'roles')->findOrFail($id);

        $esSuperAdmin = $auth->rol === 'super_admin';
        $esMismoUsuario = $auth->id_personal === $personal->id_personal;

        if (!$esSuperAdmin && !$esMismoUsuario) {
            abort(403, 'No autorizado');
        }

        $persona = $personal->persona;
        $usuario = $auth->load('persona');
        $sedes = Sede::all();
        $paises = Pais::all();
        $grados = GradoAcademico::all();
        $profesiones = Profesion::all();
        $instituciones = InstitucionEgreso::all();
        $bancos = InstitucionBancaria::all();
        $ciudades = Ciudad::all();
        $roles = Rol::all();
        $cargos = Cargo::all();

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
            'ciudades',
            'roles',
            'cargos'
        ));
    }

    public function update(Request $request, $id)
    {
        $personal = Personal::findOrFail($id);
        $persona = $personal->persona;
        $auth = auth()->user();

        $esSuperAdmin = $auth->hasRole('super_admin') || $auth->rol === 'super_admin';
        $esMismoUsuario = $auth->id_personal === $personal->id_personal;

        if (!$esSuperAdmin && !$esMismoUsuario) {
            abort(403, 'No autorizado');
        }

        // Reglas generales
        $rules = [
            'direccion' => 'nullable|string|max:255',
            'id_ciudad' => 'nullable|exists:ciudad,id_ciudad',
            'id_institucion_bancaria' => 'nullable|exists:institucion_bancaria,id_institucion_bancaria',
            'numero_cuenta_bancaria' => 'nullable|string|max:50',
            'referencia_familiar_1' => 'nullable|string|max:100',
            'celular_familiar_1' => 'nullable|string|max:30',
            'referencia_familiar_2' => 'nullable|string|max:100',
            'celular_familiar_2' => 'nullable|string|max:30',
            'enlace_ubicacion_maps' => 'nullable|url',
            'curriculum' => 'nullable|mimes:pdf|max:10240',
            'foto_carnet' => 'nullable|mimes:pdf|max:5120',
            'fotografia' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        // Reglas exclusivas para SuperAdmin
        if ($esSuperAdmin) {
            $rules = array_merge($rules, [
                'nombre' => 'required|string|max:100',
                'apellido_p' => 'required|string|max:100',
                'correo_electronico' => 'required|email|unique:persona,correo_electronico,' . $persona->id_persona . ',id_persona',
                'ci' => 'required|unique:persona,ci,' . $persona->id_persona . ',id_persona',
                'user' => 'required|unique:personal,user,' . $personal->id_personal . ',id_personal',
                'id_sede' => 'required|exists:sede,id_sede',
                'cargos' => 'required|array|min:1',
                'cargos.*' => 'exists:cargos,id_cargo',
                'roles' => 'required|array|min:1',
                'roles.*' => 'exists:roles,id_rol',
            ]);
        }

        if ($request->filled('password')) {
            $rules['password'] = 'required|min:6|confirmed';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            // 1. Datos Persona
            $personaData = $request->only([
                'direccion',
                'id_ciudad',
                'id_institucion_bancaria',
                'numero_cuenta_bancaria',
                'referencia_familiar_1',
                'celular_familiar_1',
                'referencia_familiar_2',
                'celular_familiar_2',
                'enlace_ubicacion_maps'
            ]);

            if ($esSuperAdmin) {
                $personaData = array_merge($personaData, $request->only([
                    'nombre',
                    'apellido_p',
                    'apellido_m',
                    'correo_electronico',
                    'telefono_movil',
                    'id_grado_academico',
                    'id_profesion',
                    'id_institucion_egreso'
                ]));
            }

            // Subida a Cloudinary
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => ['secure' => true]
            ]);

            if ($request->hasFile('fotografia')) {
                $upload = $cloudinary->uploadApi()->upload($request->file('fotografia')->getRealPath(), ['folder' => 'fotografias']);
                $personaData['fotografia'] = $upload['secure_url'];
            }

            if ($request->hasFile('curriculum')) {
                $upload = $cloudinary->uploadApi()->upload($request->file('curriculum')->getRealPath(), ['folder' => 'curriculums', 'resource_type' => 'auto', 'access_mode' => 'public']);
                $personaData['curriculum'] = $upload['secure_url'];
            }

            if ($request->hasFile('foto_carnet')) {
                $upload = $cloudinary->uploadApi()->upload($request->file('foto_carnet')->getRealPath(), ['folder' => 'carnets', 'resource_type' => 'auto', 'access_mode' => 'public']);
                $personaData['foto_carnet'] = $upload['secure_url'];
            }

            $persona->update($personaData);

            // 2. Datos Personal
            $personalData = [];
            if ($esSuperAdmin) {
                $personalData = $request->only(['user', 'id_sede']);
            } else {
                $personalData['user'] = $request->user ?? $personal->user;
            }

            if ($request->filled('password')) {
                $personalData['password'] = Hash::make($request->password);
            }

            // Mantener la columna texto 'cargo' sincronizada con el primer cargo de la pivote
            if ($esSuperAdmin && $request->has('cargos')) {
                $cargosIds = $request->input('cargos', []);
                $primerCargo = Cargo::find($cargosIds[0] ?? null);
                if ($primerCargo) {
                    $personalData['cargo'] = $primerCargo->nombre;
                }
            }

            if (!empty($personalData)) {
                $personal->update($personalData);
            }

            // 3. Sincronización Pivote y Recarga de Memoria
            if ($esSuperAdmin) {
                $personal->cargos()->sync($request->input('cargos', []));
                $personal->roles()->sync($request->input('roles', []));

                // Refresca las relaciones en la instancia para limpiar la memoria PHP
                $personal->load(['cargos', 'roles']);
            }

            DB::commit();
            return redirect()->route('users.show', $personal->id_personal)->with('success', 'Personal actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('users.show', $personal->id_personal)->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }
    public function exportPdf(Request $request)
    {
        $personales = $this->buildStaffQuery($request)->get();

        $pdf = Pdf::loadView('exports.staff_pdf', compact('personales'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('personal_' . now()->format('Ymd_His') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new StaffExport($request),
            'personal_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    private function buildStaffQuery(Request $request)
    {
        $query = Personal::with('persona', 'sede', 'cargos');

        if ($request->filled('id_sede')) {
            $query->where('id_sede', $request->id_sede);
        }

        if ($request->filled('cargo')) {
            $query->whereHas('cargos', function ($q) use ($request) {
                $q->where('cargos.id_cargo', $request->cargo);
            });
        }

        if ($request->filled('estado')) {
            $query->where('es_vigente', $request->estado);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('persona', function ($q) use ($search) {
                $q->whereRaw("unaccent(nombre) ILIKE unaccent(?)", ["%$search%"])
                    ->orWhereRaw("unaccent(apellido_p) ILIKE unaccent(?)", ["%$search%"])
                    ->orWhereRaw("unaccent(apellido_m) ILIKE unaccent(?)", ["%$search%"]);
            });
        }

        return $query;
    }
}
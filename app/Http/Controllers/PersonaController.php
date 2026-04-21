<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Pais;
use App\Models\GradoAcademico;
use App\Models\Profesion;
use App\Models\InstitucionEgreso;
use App\Models\InstitucionBancaria;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PersonaController extends Controller
{
    public function create()
    {
        $paises = Pais::all();
        $grados = GradoAcademico::all();
        $profesiones = Profesion::all();
        $instituciones = InstitucionEgreso::all();
        $bancos = InstitucionBancaria::all();


        return view('personas.create', compact('paises', 'grados', 'profesiones', 'instituciones', 'bancos'));
    }

    public function store(Request $request)
    {
        $data = [];

        try {
            $request->validate([
                'nombre' => 'required|string|max:100',
                'apellido_p' => 'required|string|max:100',
                'ci' => 'required|unique:persona,ci', // Quitamos 'numeric' porque ahora concatenas la extensión
                'correo_electronico' => 'required|email|unique:persona,correo_electronico',
                'id_ciudad' => 'required',
                'id_institucion_egreso' => 'required',
                'id_grado_academico' => 'required',
                'id_profesion' => 'required',
                'id_institucion_bancaria' => 'required',
                'curriculum' => 'nullable|mimes:pdf|max:10240',
                'foto_carnet' => 'nullable|mimes:pdf|max:5120',
                'fotografia' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $extension = $request->extension_select;
            if ($extension === 'OTRO') {
                $extension = strtoupper(trim($request->extension_otro));
            }
            $ciFinal = $request->ci . ($extension ? ' ' . $extension : '');
            $data = $request->except([
                '_token',
                'extension_select',
                'extension_otro',
                'id_pais',
                'id_departamento'
            ]);

            $data['ci'] = $ciFinal;
            $data['telefono_movil'] = $request->telefono_movil;

            $folderCI = str_replace(' ', '_', $ciFinal);

            if ($request->hasFile('curriculum')) {
                $data['curriculum'] = $request->file('curriculum')->storeAs('uploads/cvs', "CV_$folderCI." . $request->file('curriculum')->extension(), 'public');
            }

            if ($request->hasFile('foto_carnet')) {
                $data['foto_carnet'] = $request->file('foto_carnet')->storeAs('uploads/carnets', "CARNET_$folderCI." . $request->file('foto_carnet')->extension(), 'public');
            }

            if ($request->hasFile('fotografia')) {
                $data['fotografia'] = $request->file('fotografia')->storeAs('uploads/fotos', "FOTO_$folderCI." . $request->file('fotografia')->extension(), 'public');
            }

            Persona::create($data);

            return back()->with('success', "¡Personal $ciFinal registrado con éxito!");

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            dd([
                'Error' => $e->getMessage(),
                'Linea' => $e->getLine(),
                'Datos_Enviados' => $data
            ]);
        }
    }
}
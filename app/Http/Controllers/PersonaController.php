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
use Cloudinary\Cloudinary;
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

            if ($request->hasFile('curriculum')) {

                $uploadCV = $cloudinary->uploadApi()->upload(
                    $request->file('curriculum')->getRealPath(),
                    [
                        'folder' => 'curriculums',
                        'resource_type' => 'raw',
                        'public_id' => "CV_$folderCI"
                    ]
                );

                $data['curriculum'] = $uploadCV['secure_url'];
            }

            if ($request->hasFile('foto_carnet')) {

                $uploadCarnet = $cloudinary->uploadApi()->upload(
                    $request->file('foto_carnet')->getRealPath(),
                    [
                        'folder' => 'carnets',
                        'resource_type' => 'raw',
                        'public_id' => "CARNET_$folderCI"
                    ]
                );

                $data['foto_carnet'] = $uploadCarnet['secure_url'];
            }

            if ($request->hasFile('fotografia')) {

                $uploadFoto = $cloudinary->uploadApi()->upload(
                    $request->file('fotografia')->getRealPath(),
                    [
                        'folder' => 'fotografias',
                        'public_id' => "FOTO_$folderCI"
                    ]
                );

                $data['fotografia'] = $uploadFoto['secure_url'];
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
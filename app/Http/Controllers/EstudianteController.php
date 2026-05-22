<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\Pais;
use App\Models\Departamento;
use App\Models\GradoAcademico;
use App\Models\Profesion;
use App\Models\InstitucionEgreso;

class EstudianteController extends Controller
{


    public function edit(Estudiante $estudiante)
    {
        if (auth()->user()->rol !== 'super_admin') {
            abort(403, 'No autorizado');
        }
        $paises = Pais::all();
        $departamento = Departamento::all();
        $profesiones = Profesion::all();
        $instituciones = InstitucionEgreso::all();
        $grados = GradoAcademico::all();

        $usuario = auth()->user()->load('persona');

        return view('students.edit', compact(
            'estudiante',
            'paises',
            'profesiones',
            'instituciones',
            'grados',
            'departamento',
            'usuario'
        ));
    }

    public function update(Request $request, Estudiante $estudiante)
    {
        if (auth()->user()->rol !== 'super_admin') {
            abort(403, 'No autorizado');
        }
        $data = [];
        try {
            $request->validate([
                'nombre' => 'required|string|max:100',
                'apellido_p' => 'required|string|max:100',
                'ci' => 'required|numeric|unique:estudiante,ci,' . $estudiante->id_estudiante . ',id_estudiante',
                'extension_select' => 'required',
                'correo_electronico' => 'required|email|unique:estudiante,correo_electronico,' . $estudiante->id_estudiante . ',id_estudiante',
                'id_departamento' => 'required',
                'ciudad_residencia' => 'required',
                'id_institucion_egreso' => 'required',
                'id_grado_academico' => 'required',
                'id_profesion' => 'required',
            ]);

            $extensionFinal = $request->extension_select === 'OTRO'
                ? strtoupper(trim($request->extension_otro))
                : $request->extension_select;

            $data = $request->except(['_token', '_method', 'extension_select', 'extension_otro', 'id_pais']);

            $data['extension_ci'] = $extensionFinal;

            $idArchivo = $request->ci . '_' . $extensionFinal;

            $estudiante->update($data);

            return redirect()->route('people.index')->with('success', "Estudiante " . $request->nombre . " actualizado correctamente.");

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
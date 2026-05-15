<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clase;
use App\Models\Personal;

class ClassController extends Controller
{

    public function classCreate(Request $request)
    {

        $id_curso = $request->query('id_curso');

        if (!$id_curso) {
            return redirect()->route('programs.index')->with('error', 'Debe seleccionar un curso.');
        }

        $usuario = auth()->user();

        return view('clases.create', compact('id_curso', 'usuario'));
    }


    public function classStore(Request $request)
    {
        // Validamos los datos que vienen del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'tipo' => 'required|in:Clase Regular,Webinar',
            'id_curso' => 'required|exists:curso,id_curso',
        ]);

        Clase::create([
            'nombre' => $request->nombre,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'tipo' => $request->tipo,
            'id_curso' => $request->id_curso,
        ]);

        return redirect()->route('programs.index')
            ->with('success', '¡Clase programada exitosamente!');
    }
}
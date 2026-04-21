<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clase; // Importamos el modelo Clase
use App\Models\Personal;

class ClassController extends Controller
{
    /**
     * Muestra el formulario para crear una clase vinculada a un curso.
     */
    public function classCreate(Request $request)
    {
        // Capturamos el ID del curso desde la URL (?id_curso=1)
        $id_curso = $request->query('id_curso');

        // Validamos que el ID exista para no entrar a un formulario vacío
        if (!$id_curso) {
            return redirect()->route('programs.index')->with('error', 'Debe seleccionar un curso.');
        }

        // Recuperamos al usuario para el layout-dashboard
        $usuario = auth()->user();

        return view('clases.create', compact('id_curso', 'usuario'));
    }

    /**
     * Guarda la nueva clase en la base de datos.
     */
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

        // Creamos la clase
        Clase::create([
            'nombre' => $request->nombre,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'tipo' => $request->tipo,
            'id_curso' => $request->id_curso,
        ]);

        // Redireccionamos con un mensaje de éxito
        return redirect()->route('programs.index')
            ->with('success', '¡Clase programada exitosamente!');
    }
}
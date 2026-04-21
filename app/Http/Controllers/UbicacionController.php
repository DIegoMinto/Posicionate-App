<?php
use App\Models\Departamento;
use App\Models\Ciudad;

class UbicacionController extends Controller
{
    public function getDepartamentos($id_pais)
    {
        return Departamento::where('id_pais', $id_pais)->get();
    }

    public function getCiudades($id_departamento)
    {
        return Ciudad::where('id_departamento', $id_departamento)->get();
    }
}

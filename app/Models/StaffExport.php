<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class StaffExport implements FromView
{
    public function __construct(protected Request $request)
    {
    }

    public function view(): View
    {
        $query = DB::table('personal')
            ->join('persona', 'personal.id_persona', '=', 'persona.id_persona')
            ->leftJoin('sede', 'personal.id_sede', '=', 'sede.id_sede')
            ->select(
                'personal.*',
                'persona.ci',
                'persona.extension_ci',
                'persona.nombre',
                'persona.apellido_p',
                'persona.apellido_m',
                'persona.telefono_movil',
                'persona.correo_electronico',
                'sede.nombre as sede_nombre'
            );

        if ($this->request->filled('id_sede')) {
            $query->where('personal.id_sede', $this->request->id_sede);
        }

        if ($this->request->filled('cargo')) {
            $query->where('personal.area', $this->request->cargo);
        }

        if ($this->request->filled('estado')) {
            $query->where('personal.es_vigente', $this->request->estado);
        }

        if ($this->request->filled('search')) {
            $search = $this->request->search;

            $query->where(function ($q) use ($search) {
                $q->whereRaw("unaccent(persona.nombre) ILIKE unaccent(?)", ["%$search%"])
                    ->orWhereRaw("unaccent(persona.apellido_p) ILIKE unaccent(?)", ["%$search%"])
                    ->orWhereRaw("unaccent(persona.apellido_m) ILIKE unaccent(?)", ["%$search%"])
                    ->orWhereRaw("unaccent(persona.ci) ILIKE unaccent(?)", ["%$search%"]);
            });
        }

        $personales = $query->orderBy('persona.apellido_p')->get();

        return view('exports.staff_excel', compact('personales'));
    }
}
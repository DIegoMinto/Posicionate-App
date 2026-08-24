<?php

namespace App\Exports;

use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StaffExport implements FromView
{
    public function __construct(protected Request $request)
    {
    }

    public function view(): View
    {
        $query = Personal::with('persona', 'sede', 'cargos');

        if ($this->request->filled('id_sede')) {
            $query->where('id_sede', $this->request->id_sede);
        }

        if ($this->request->filled('cargo')) {
            $query->whereHas('cargos', function ($q) {
                $q->where('cargos.id_cargo', $this->request->cargo);
            });
        }

        if ($this->request->filled('estado')) {
            $query->where('es_vigente', $this->request->estado);
        }

        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->whereHas('persona', function ($q) use ($search) {
                $q->whereRaw("unaccent(nombre) ILIKE unaccent(?)", ["%$search%"])
                    ->orWhereRaw("unaccent(apellido_p) ILIKE unaccent(?)", ["%$search%"])
                    ->orWhereRaw("unaccent(apellido_m) ILIKE unaccent(?)", ["%$search%"]);
            });
        }

        $personales = $query->get();

        return view('exports.staff_excel', compact('personales'));
    }
}
<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $request;
    protected $usuario;
    protected $idCurso;

    public function __construct(Request $request, $usuario, $idCurso = null)
    {
        $this->request = $request;
        $this->usuario = $usuario;
        $this->idCurso = $idCurso;
    }

    public function collection(): Collection
    {
        $query = DB::table('curso_estudiante')
            ->join('estudiante', 'curso_estudiante.id_estudiante', '=', 'estudiante.id_estudiante')
            ->join('curso', 'curso_estudiante.id_curso', '=', 'curso.id_curso')
            ->join('personal', 'curso_estudiante.id_personal', '=', 'personal.id_personal')
            ->join('persona', 'personal.id_persona', '=', 'persona.id_persona')
            ->select(
                'estudiante.*',
                'curso.nombre as curso_nombre',
                'curso_estudiante.estado',
                'curso_estudiante.created_at as fecha_inscripcion',
                'persona.nombre as asesor_nombre',
                'persona.apellido_p as asesor_apellido'
            );

        if ($this->idCurso) {
            $query->where('curso_estudiante.id_curso', $this->idCurso);
        }

        if ($this->usuario->rol === 'user') {
            $query->where('curso_estudiante.id_personal', $this->usuario->id_personal);
        }

        if ($this->request->filled('id_personal')) {
            $query->where('curso_estudiante.id_personal', $this->request->id_personal);
        }

        if ($this->request->filled('estado')) {
            $query->where('curso_estudiante.estado', $this->request->estado);
        }

        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('estudiante.nombre', 'ILIKE', "%$search%")
                    ->orWhere('estudiante.apellido_p', 'ILIKE', "%$search%")
                    ->orWhere('estudiante.apellido_m', 'ILIKE', "%$search%")
                    ->orWhere('estudiante.ci', 'ILIKE', "%$search%");
            });
        }

        if ($this->request->filled('fecha_inicio')) {
            $query->whereDate('curso_estudiante.created_at', '>=', $this->request->fecha_inicio);
        }

        if ($this->request->filled('fecha_fin')) {
            $query->whereDate('curso_estudiante.created_at', '<=', $this->request->fecha_fin);
        }

        return $query->orderBy('curso_estudiante.created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Curso',
            'CI',
            'Ext',
            'Nombre',
            'Apellido Paterno',
            'Apellido Materno',
            'Teléfono',
            'Correo',
            'Asesor',
            'Fecha de Registro',
            'Estado',
        ];
    }

    public function map($e): array
    {
        return [
            $e->curso_nombre,
            $e->ci,
            $e->extension_ci,
            $e->nombre,
            $e->apellido_p,
            $e->apellido_m,
            $e->telefono_movil ?? '-',
            $e->correo_electronico ?? '-',
            $e->asesor_nombre . ' ' . $e->asesor_apellido,
            Carbon::parse($e->fecha_inscripcion)->format('d/m/Y H:i'),
            $e->estado,
        ];
    }
}
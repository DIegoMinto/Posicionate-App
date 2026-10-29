<?php

namespace App\Http\Controllers;

use App\Models\LineaPosicionate;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class LineaPosicionateController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->input('buscar');

        $lineas = LineaPosicionate::with('persona')
            ->when($busqueda, function ($query, $busqueda) {
                $query->where(function ($q) use ($busqueda) {
                    $q->where('celular', 'ilike', "%{$busqueda}%")
                        ->orWhere('correo', 'ilike', "%{$busqueda}%")
                        ->orWhere('pertenencia_texto', 'ilike', "%{$busqueda}%")
                        ->orWhereHas('persona', function ($persona) use ($busqueda) {
                            $persona->where('nombre', 'ilike', "%{$busqueda}%")
                                ->orWhere('apellido_p', 'ilike', "%{$busqueda}%")
                                ->orWhere('apellido_m', 'ilike', "%{$busqueda}%");
                        });
                });
            })
            ->orderBy('celular')
            ->paginate(15)
            ->withQueryString();

        $totalLineas = LineaPosicionate::count();

        $porRecargar = LineaPosicionate::whereNotNull('fecha_siguiente_recarga')
            ->whereDate('fecha_siguiente_recarga', '<=', now()->toDateString())
            ->count();

        return view('lineas-posicionate.index', compact(
            'lineas',
            'busqueda',
            'totalLineas',
            'porRecargar'
        ));
    }

    public function create()
    {
        $personas = Persona::whereHas('personal')
            ->orderBy('nombre')
            ->get();

        return view('lineas-posicionate.create', compact('personas'));
    }

    public function store(Request $request)
    {
        $datos = $this->validarLinea($request);

        $datos['fecha_siguiente_recarga'] = !empty($datos['fecha_ultima_recarga'])
            ? \Carbon\Carbon::parse($datos['fecha_ultima_recarga'])->addDays(30)->toDateString()
            : null;

        LineaPosicionate::create($datos);

        return redirect()
            ->route('lineas-posicionate.index')
            ->with('success', 'La línea se registró correctamente.');
    }

    public function edit(LineaPosicionate $lineaPosicionate)
    {
        $personas = Persona::whereHas('personal')
            ->orderBy('nombre')
            ->get();

        return view('lineas-posicionate.edit', [
            'linea' => $lineaPosicionate,
            'personas' => $personas,
        ]);
    }

    public function update(Request $request, LineaPosicionate $lineaPosicionate)
    {
        $datos = $this->validarLinea($request, $lineaPosicionate);

        if (empty($datos['contrasena'])) {
            unset($datos['contrasena']);
        }

        $datos['fecha_siguiente_recarga'] = !empty($datos['fecha_ultima_recarga'])
            ? \Carbon\Carbon::parse($datos['fecha_ultima_recarga'])->addDays(30)->toDateString()
            : null;

        $lineaPosicionate->update($datos);

        return redirect()
            ->route('lineas-posicionate.index')
            ->with('success', 'La línea se actualizó correctamente.');
    }

    public function destroy(Request $request, LineaPosicionate $lineaPosicionate)
    {
        abort_unless(
            $request->user()?->hasRole('super_admin'),
            403
        );

        $request->validate([
            'password_confirm' => ['required', 'string'],
        ]);

        if (
            !Hash::check(
                $request->input('password_confirm'),
                $request->user()->getAuthPassword()
            )
        ) {
            return back()
                ->withErrors([
                    'password_confirm' => 'La contraseña de administrador es incorrecta.',
                ]);
        }

        $lineaPosicionate->delete();

        return redirect()
            ->route('lineas-posicionate.index')
            ->with('success', 'La línea se eliminó correctamente.');
    }

    private function validarLinea(
        Request $request,
        ?LineaPosicionate $linea = null
    ): array {
        return $request->validate([
            'celular' => [
                'required',
                'string',
                'max:20',
                Rule::unique('lineas_posicionate', 'celular')
                    ->ignore($linea?->id),
            ],
            'pertenencia_tipo' => ['required', Rule::in(['personal', 'area'])],
            'id_persona' => [
                'nullable',
                'required_if:pertenencia_tipo,personal',
                'exists:persona,id_persona',
            ],
            'pertenencia_texto' => [
                'nullable',
                'required_if:pertenencia_tipo,area',
                'string',
                'max:255',
            ],
            'correo' => ['nullable', 'email', 'max:255'],
            'contrasena' => ['nullable', 'string', 'max:1000'],
            'fecha_ultima_recarga' => ['nullable', 'date'],
        ]);
    }
}
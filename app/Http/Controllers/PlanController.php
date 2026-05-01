<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanesPago;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class PlanController extends controller
{

    public function store(Request $request)
    {
        $request->validate([
            'id_curso' => 'required',
            'nombre' => 'required|string|max:255',
            'precio_base' => 'required|numeric|min:0',
            'cuotas' => 'required|array|min:1',
            'cuotas.*.monto' => 'required|numeric',
            'cuotas.*.nro_cuota' => 'required',
            // Validamos la matrícula si viene
            'monto_matricula' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $plan = PlanesPago::create([
                'id_curso' => $request->id_curso,
                'nombre' => $request->nombre,
                'precio_base' => $request->precio_base,
                'incluye_matricula' => $request->has('incluye_matricula'),
                'estado' => 'ACTIVO'
            ]);

            // --- NUEVA LÓGICA PARA LA MATRÍCULA ---
            if ($request->has('incluye_matricula') && $request->monto_matricula > 0) {
                $plan->detalles()->create([
                    'id_planes_pago' => $plan->id_planes_pago,
                    'nro_cuota' => 0, // Usamos 0 para identificar que es la matrícula
                    'monto_cuota' => $request->monto_matricula,
                    'fecha_vencimiento' => now(), // Se paga al momento
                    'detalle' => 'PAGO DE MATRÍCULA'
                ]);
            }

            // Guardar el resto de las cuotas
            foreach ($request->cuotas as $detalle) {
                $plan->detalles()->create([
                    'id_planes_pago' => $plan->id_planes_pago,
                    'nro_cuota' => $detalle['nro_cuota'],
                    'monto_cuota' => $detalle['monto'],
                    'fecha_vencimiento' => !empty($detalle['fecha_vencimiento']) ? $detalle['fecha_vencimiento'] : null,
                    'detalle' => ($detalle['nro_cuota'] == 1) ? 'PAGO INICIAL' : 'CUOTA ' . $detalle['nro_cuota']
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', '¡Plan configurado correctamente!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function showInstallments($id)
    {
        $plan = PlanesPago::with('detalles')->findOrFail($id);
        $usuario = auth()->user()->load('persona');

        return view('dashboard.plans_details', compact('plan', 'usuario'));

    }

    public function destroy(Request $request, $id)
    {
        $request->validate([
            'password_confirm' => 'required',
        ]);

        if (!Hash::check($request->password_confirm, auth()->user()->password)) {
            return back()->withErrors(['password_confirm' => 'La contraseña es incorrecta.']);
        }

        $plan = PlanesPago::findOrFail($id);
        $nombrePlan = $plan->nombre;
        $plan->delete();
        return redirect()->back()->with('success', 'El plan "' . $nombrePlan . '" y sus cuotas han sido eliminados.');
    }
}
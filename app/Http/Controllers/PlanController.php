<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanesPago;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\PlanCuotaDetalle;
class PlanController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'id_curso' => 'required',
            'nombre' => 'required|string|max:255',
            'tipo_plan' => 'required|in:CONTADO,CUOTAS',
            'precio_base' => 'required|numeric|min:0',
            'cuotas' => 'required|array|min:1',
            'cuotas.*.monto' => 'required|numeric',
            'cuotas.*.nro_cuota' => 'required',
            'monto_matricula' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $plan = PlanesPago::create([
                'id_curso' => $request->id_curso,
                'nombre' => $request->nombre,
                'tipo_plan' => $request->tipo_plan,
                'precio_base' => $request->precio_base,
                'incluye_matricula' => $request->has('incluye_matricula'),
                'estado' => 'ACTIVO'
            ]);


            if ($request->has('incluye_matricula') && $request->monto_matricula > 0) {
                $plan->detalles()->create([
                    'id_planes_pago' => $plan->id_planes_pago,
                    'nro_cuota' => 0,
                    'monto_cuota' => $request->monto_matricula,
                    'detalle' => 'PAGO DE MATRÍCULA'
                ]);
            }

            foreach ($request->cuotas as $detalle) {
                $plan->detalles()->create([
                    'id_planes_pago' => $plan->id_planes_pago,
                    'nro_cuota' => $detalle['nro_cuota'],
                    'monto_cuota' => $detalle['monto'],
                    'detalle' => ($detalle['nro_cuota'] == 1)
                        ? 'PAGO INICIAL'
                        : 'CUOTA ' . $detalle['nro_cuota']
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
    public function edit($id)
    {
        $usuario = auth()->user();

        $plan = PlanesPago::with(['detalles', 'curso.sede'])
            ->findOrFail($id);

        return view('plans.edit', compact(
            'plan',
            'usuario'
        ));
    }
    public function update(Request $request, $id)
    {
        $plan = PlanesPago::findOrFail($id);

        $plan->update([
            'nombre' => $request->nombre,
            'precio_base' => $request->precio_base,
            'incluye_matricula' => $request->has('incluye_matricula'),
        ]);

        PlanCuotaDetalle::where(
            'id_planes_pago',
            $plan->id_planes_pago
        )->delete();

        if ($request->has('cuotas')) {

            foreach ($request->cuotas as $cuota) {

                PlanCuotaDetalle::create([
                    'id_planes_pago' => $plan->id_planes_pago,
                    'nro_cuota' => $cuota['nro_cuota'],
                    'monto_cuota' => $cuota['monto'],
                    'fecha_vencimiento' => $cuota['fecha_vencimiento'] ?: null,
                    'detalle' => ($cuota['nro_cuota'] == 1)
                        ? 'PAGO INICIAL'
                        : 'CUOTA ' . $cuota['nro_cuota']
                ]);
            }
        }

        return redirect()
            ->route('programs.payments.setup', $plan->id_curso)
            ->with(
                'success',
                'Plan actualizado correctamente'
            );
    }
}
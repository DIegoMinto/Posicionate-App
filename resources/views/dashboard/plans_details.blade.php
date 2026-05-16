<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Detalle - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">

        <x-page-header titulo="Detalle del Plan">
        </x-page-header>

        <div class="p-6">
            <div class="bg-white p-6 rounded-sm border-2 border-brand-gold shadow-md">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-brand-green uppercase tracking-tighter">Cronograma de Pagos
                        </h1>
                        <p class="font-sans font-bold">Plan: {{ $plan->nombre }}</p>
                    </div>
                    <button class="btn-gold">
                        Exportar PDF
                    </button>
                </div>

                <div class="overflow-x-auto rounded-xl border-brand-green border">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b-2 border-brand-gold uppercase text-[10px] font-black bg-brand-green text-white">
                                <th class="py-3 px-4 whitespace-nowrap">N° Cuota</th>
                                <th class="py-3 px-4 whitespace-nowrap">Detalle de Cuota</th>
                                <th class="py-3 px-4 whitespace-nowrap">Monto</th>
                                <th class="py-3 px-4 whitespace-nowrap text-left">Fecha Vencimiento</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-[11px] font-medium">
                            @forelse($plan->detalles as $detalle)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors text-black">
                                    <td class="py-3 px-4 font-sans font-bold text-brand-green">
                                        {{ $detalle->nro_cuota }}
                                    </td>
                                    <td class="py-3 px-4 font-sans whitespace-nowrap">
                                        {{ $detalle->detalle }}
                                    </td>
                                    <td class="py-3 px-4 font-sans">
                                        {{ number_format($detalle->monto_cuota, 2) }} <span class="text-[9px]">BS</span>
                                    </td>
                                    <td class="py-3 px-4 text-left font-sans whitespace-nowrap italic">
                                        {{ \Carbon\Carbon::parse($detalle->fecha_vencimiento)->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center text-gray-400 italic">
                                        No hay cuotas registradas para este plan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        @if($plan->detalles->isNotEmpty())
                            @php
                                $totalPlan = $plan->detalles->sum('monto_cuota');
                            @endphp
                            <tfoot>
                                <tr class="bg-brand-green text-white text-[11px] font-bold">
                                    <td colspan="2" class="py-3 px-4 text-right uppercase">
                                        Monto Total
                                    </td>
                                    <td class="py-3 px-4 font-sans whitespace-nowrap">
                                        {{ number_format($totalPlan, 2) }} Bs
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </x-layout-dashboard>
</body>

</html>
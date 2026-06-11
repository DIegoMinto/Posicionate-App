<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan de Pagos - {{ $curso->nombre }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">

        <x-page-header titulo="Plan de Pagos">
        </x-page-header>

        <div class="p-6">
            <div class="bg-white p-6 rounded-sm border-2 border-brand-gold shadow-md">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-brand-green uppercase tracking-tighter">
                        Plan de Pagos - {{ $curso->nombre }}
                    </h1>
                </div>
                <div class="text-sm mb-6 space-y-1 text-black">
                    <p><strong>Nombre del Estudiante:</strong> {{ $estudiante->nombre }} {{ $estudiante->apellido_p }}
                        {{ $estudiante->apellido_m }}
                    </p>
                    <p><strong>CI/NIT:</strong> {{ $estudiante->ci }}</p>
                    <p><strong>Teléfono:</strong> {{ $estudiante->telefono_movil ?? '-' }}</p>

                    @php
                        $total = $pagos->sum('monto_pagar');
                        $pagado = $pagos->sum('monto_pagado');
                        $pendiente = $total - $pagado;
                    @endphp

                    <p><strong>Saldo Total Adeudado:</strong> {{ number_format($total, 2) }} Bs</p>
                    <p><strong>Modalidad de Pago:</strong> {{ $inscripcion->plan->nombre ?? '-' }}</p>
                </div>
                <div class="overflow-x-auto rounded-xl border border-brand-green">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b-2 border-brand-gold uppercase text-[10px] font-black bg-brand-green text-white">
                                <th class="py-3 px-4 whitespace-nowrap">N°</th>
                                <th class="py-3 px-4 whitespace-nowrap">Concepto</th>
                                <th class="py-3 px-4 whitespace-nowrap text-right">Fecha Programada</th>
                                <th class="py-3 px-4 whitespace-nowrap text-right">Fecha de Pago</th>
                                <th class="py-3 px-4 whitespace-nowrap text-right">Monto</th>
                                <th class="py-3 px-4 whitespace-nowrap text-right">Monto Pagado</th>
                                <th class="py-3 px-4 whitespace-nowrap text-right">Saldo Pendiente</th>
                                <th class="py-3 px-4 text-center whitespace-nowrap">Estado</th>
                                <th class="py-3 px-4 text-center whitespace-nowrap">Adicionales</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-[11px] font-medium">

                            @forelse($pagos as $index => $pago)

                                @php
                                    $pagadoFila = $pago->monto_pagado;
                                    $saldoFila = $pago->monto_pagar - $pagadoFila;
                                @endphp

                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors text-black">

                                    <td class="py-3 px-4">{{ $index + 1 }}</td>

                                    <td class="py-3 px-4 whitespace-nowrap">
                                        {{ $pago->detalle }}
                                    </td>

                                    <td class="py-3 px-4 whitespace-nowrap text-right">
                                        {{ \Carbon\Carbon::parse($pago->fecha_programada)->format('d/m/Y') }}
                                    </td>

                                    <td class="py-3 px-4 whitespace-nowrap text-right">

                                        @if($pago->fecha_pagada)
                                            {{ \Carbon\Carbon::parse($pago->fecha_pagada)->format('d/m/Y') }}
                                        @else
                                            <span class="text-yellow-600 font-bold">
                                                Pendiente
                                            </span>
                                        @endif

                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap text-right">
                                        {{ number_format($pago->monto_pagar, 2) }} Bs
                                    </td>

                                    <td class="py-3 px-4 whitespace-nowrap text-right">
                                        {{ number_format($pago->monto_pagado, 2) }} Bs
                                    </td>

                                    <td class="py-3 px-4 whitespace-nowrap text-right">
                                        {{ number_format($saldoFila, 2) }} Bs
                                    </td>

                                    <td class="py-3 px-4 text-center">

                                        @php
                                            $claseEstado = match ($pago->estado) {
                                                'pagado' => 'bg-green-100 text-green-700',
                                                'revision' => 'bg-yellow-100 text-yellow-700',
                                                default => 'bg-red-100 text-red-700'
                                            };

                                            $textoEstado = match ($pago->estado) {
                                                'pagado' => 'Completo',
                                                'revision' => 'En revisión',
                                                default => 'Pendiente'
                                            };
                                        @endphp

                                        <span
                                            class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase {{ $claseEstado }}">
                                            {{ $textoEstado }}
                                        </span>

                                    </td>

                                    <td class="py-3 px-4 text-center">

                                        @if(
                                                in_array($usuario->cargo, [
                                                    'contador',
                                                    'asistente_contable',
                                                ])
                                                || $usuario->rol === 'super_admin'
                                            )

                                            <div class="flex items-center justify-center gap-3">

                                                {{-- Editar pago --}}
                                                <a href="{{ route('pagos.edit', $pago->id_pagos_estudiante) }}"
                                                    class="inline-flex items-center justify-center group relative">

                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-5 h-5 text-brand-green group-hover:text-brand-gold transition-colors"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M11 5h2M12 7v10m-7 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />

                                                    </svg>

                                                    <span
                                                        class="absolute -top-8 scale-0 transition-all rounded bg-gray-800 px-2 py-1 text-[10px] text-white group-hover:scale-100 whitespace-nowrap z-30 shadow-lg">
                                                        Editar Pago
                                                    </span>

                                                </a>

                                                @if($pago->estado == 'revision')

                                                    <a href="{{ route('pagos.validar', $pago->id_pagos_estudiante) }}"
                                                        class="inline-flex items-center justify-center group relative">

                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="w-5 h-5 text-brand-green hover:text-brand-gold transition-colors"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />

                                                        </svg>

                                                        <span
                                                            class="absolute -top-8 scale-0 transition-all rounded bg-gray-800 px-2 py-1 text-[10px] text-white group-hover:scale-100 whitespace-nowrap z-30 shadow-lg">
                                                            Validar Pago
                                                        </span>

                                                    </a>

                                                @endif

                                            </div>

                                        @endif

                                    </td>


                                </tr>

                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-gray-400 italic">
                                        No hay pagos registrados para este estudiante.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                        <tfoot>
                            <tr class="bg-brand-green text-white text-[11px] font-bold">
                                <td colspan="4" class="py-3 px-4 text-right uppercase">
                                    Totales
                                </td>
                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                    {{ number_format($total, 2) }} Bs
                                </td>
                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                    {{ number_format($pagado, 2) }} Bs
                                </td>
                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                    {{ number_format($pendiente, 2) }} Bs
                                </td>
                                <td colspan="2"></td>

                            </tr>
                        </tfoot>


                    </table>
                </div>

            </div>
        </div>

    </x-layout-dashboard>

</body>

</html>
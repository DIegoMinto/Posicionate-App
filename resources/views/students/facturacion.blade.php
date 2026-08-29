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
                                <th class="py-3 px-4 whitespace-nowrap text-right">N° Recibo</th>
                                <th class="py-3 px-4 text-center whitespace-nowrap">Adicionales</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-[11px] font-medium">

                            @forelse($grupos as $index => $grupo)

                                @php
                                    $raiz = $grupo->raiz;
                                    $movimientos = $grupo->movimientos;

                                    $saldoAnterior = $raiz->monto_pagar;
                                @endphp

                                @foreach($movimientos as $movIndex => $mov)

                                                        @php
                                                            $montoMovimiento = $mov->monto_pagar;

                                                            $saldoMovimiento = $saldoAnterior - $mov->monto_pagado;

                                                            if ($saldoMovimiento < 0 && abs($saldoMovimiento) < 0.01) {
                                                                $saldoMovimiento = 0;
                                                            }

                                                            $saldoMovimiento = max(0, $saldoMovimiento);

                                                            $saldoAnterior = $saldoMovimiento;

                                                            $esPrincipal = $movIndex === 0;
                                                        @endphp

                                                        <tr
                                                            class="border-b border-gray-100 hover:bg-gray-50 transition-colors text-black align-top">

                                                            <td class="py-3 px-4">
                                                                @if($esPrincipal)
                                                                    {{ $index + 1 }}
                                                                @endif
                                                            </td>

                                                            <td class="py-3 px-4 whitespace-nowrap">
                                                                @if($esPrincipal)
                                                                    {{ $raiz->detalle }}
                                                                @else
                                                                    <span class="text-gray-400">—</span>
                                                                @endif
                                                            </td>

                                                            <td class="py-3 px-4 whitespace-nowrap text-right">
                                                                @if($esPrincipal && $raiz->fecha_programada)
                                                                    {{ \Carbon\Carbon::parse($raiz->fecha_programada)->format('d/m/Y') }}
                                                                @else
                                                                    <span class="text-gray-400">-</span>
                                                                @endif
                                                            </td>

                                                            <td class="py-3 px-4 whitespace-nowrap text-right">
                                                                @if($mov->fecha_pagada)
                                                                    {{ \Carbon\Carbon::parse($mov->fecha_pagada)->format('d/m/Y') }}
                                                                @else
                                                                    <span class="text-yellow-600 font-bold">Pendiente</span>
                                                                @endif
                                                            </td>

                                                            <td class="py-3 px-4 whitespace-nowrap text-right">
                                                                {{ number_format($montoMovimiento, 2) }} Bs
                                                            </td>

                                                            <td class="py-3 px-4 whitespace-nowrap text-right">
                                                                {{ number_format($mov->monto_pagado, 2) }} Bs
                                                            </td>

                                                            <td class="py-3 px-4 whitespace-nowrap text-right font-semibold">
                                                                {{ number_format($saldoMovimiento, 2) }} Bs
                                                            </td>

                                                            <td class="py-3 px-4 text-center">
                                                                @php
                                                                    $claseEstado = match ($mov->estado) {
                                                                        'pagado' => 'bg-green-100 text-green-700',
                                                                        'revision' => 'bg-yellow-100 text-yellow-700',
                                                                        default => 'bg-red-100 text-red-700'
                                                                    };

                                                                    $textoEstado = match ($mov->estado) {
                                                                        'pagado' => 'Completo',
                                                                        'revision' => 'En revisión',
                                                                        default => 'Pendiente'
                                                                    };
                                                                @endphp

                                                                <span
                                                                    class="inline-block px-2 py-0.5 rounded-full text-[9px] font-black uppercase {{ $claseEstado }}">
                                                                    {{ $textoEstado }}
                                                                </span>
                                                            </td>

                                                            <td class="py-3 px-4 whitespace-nowrap text-right">
    @if($mov->estado === 'pagado' && $mov->numero_recibo)
        <span class="font-semibold text-brand-green">#{{ $mov->numero_recibo }}</span>
    @else
        <span class="text-gray-400">-</span>
    @endif
</td>

                                                            <td class="py-3 px-4 text-center">
                                                                <div class="flex items-center justify-center gap-2" x-data="{
    openVerify{{ $mov->id_pagos_estudiante }}: false,
    openRecibo{{ $mov->id_pagos_estudiante }}: false,
    reciboHtml{{ $mov->id_pagos_estudiante }}: '',
    cargando{{ $mov->id_pagos_estudiante }}: false,
    verRecibo{{ $mov->id_pagos_estudiante }}() {
        this.openRecibo{{ $mov->id_pagos_estudiante }} = true;
        if (this.reciboHtml{{ $mov->id_pagos_estudiante }}) return;
        this.cargando{{ $mov->id_pagos_estudiante }} = true;
        fetch('{{ route('pagos.recibo.html', $mov->id_pagos_estudiante) }}')
            .then(r => r.text())
            .then(html => {
                this.reciboHtml{{ $mov->id_pagos_estudiante }} = html;
                this.cargando{{ $mov->id_pagos_estudiante }} = false;
            });
    }
}">

                                                                    @if($mov->estado === 'pagado')
            <button type="button"
    @click="verRecibo{{ $mov->id_pagos_estudiante }}()"
    title="Ver Recibo" class="cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 text-brand-green hover:text-brand-gold transition-colors "
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </button>

            <div x-show="openRecibo{{ $mov->id_pagos_estudiante }}"
                class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm"
                x-cloak x-transition>

                                <div class="bg-white rounded-sm shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto border-t-4 border-brand-green"
                    @click.away="openRecibo{{ $mov->id_pagos_estudiante }} = false">

                    <div class="flex justify-between items-center px-5 py-3 border-b border-gray-200 sticky top-0 bg-white z-10">
                        <h3 class="text-[11px] font-black text-brand-green uppercase">
                            Recibo de Pago
                        </h3>
                        <div class="flex items-center gap-4">
                            <a href="{{ route('pagos.recibo', $mov->id_pagos_estudiante) }}" target="_blank"
                                class="text-[9px] font-black text-brand-green uppercase hover:text-brand-gold flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Descargar PDF
                            </a>
                            <button type="button"
    @click="openRecibo{{ $mov->id_pagos_estudiante }} = false"
    class="cursor-pointer text-gray-400 hover:text-gray-700 text-lg leading-none">
    &times;
</button>
                        </div>
                    </div>

                    <div class="p-6">
                        <div x-show="cargando{{ $mov->id_pagos_estudiante }}" class="text-center py-10 text-gray-400 text-xs">
                            Cargando recibo...
                        </div>
                        <div x-show="!cargando{{ $mov->id_pagos_estudiante }}" x-html="reciboHtml{{ $mov->id_pagos_estudiante }}"></div>
                    </div>

                </div>
            </div>
        @endif

                                                                    @if($usuario->hasAnyCargo(['asistente_contable', 'gerente_marketing']) || $usuario->rol === 'super_admin')

                                                                        @if($mov->estado === 'revision')

                                                                            <button @click="openVerify{{ $mov->id_pagos_estudiante }} = true" type="button"
                                                                                title="Validar Pago">

                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    class="w-5 h-5 text-brand-green hover:text-brand-gold transition-colors"
                                                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                                        d="M5 13l4 4L19 7" />
                                                                                </svg>
                                                                            </button>

                                                                            <div x-show="openVerify{{ $mov->id_pagos_estudiante }}"
                                                                                class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm"
                                                                                x-cloak x-transition>

                                                                                <div class="bg-white p-6 rounded-sm shadow-2xl w-85 text-left border-t-4 border-brand-green"
                                                                                    @click.away="openVerify{{ $mov->id_pagos_estudiante }} = false">

                                                                                    <h3 class="text-[11px] font-black text-brand-green uppercase mb-2">
                                                                                        Validación de Finanzas
                                                                                    </h3>

                                                                                    <p class="text-[10px] mb-4 text-gray-600">
                                                                                        Para pasar a estado <strong>COMPLETO</strong> el pago de
                                                                                        <span class="font-bold text-black">
                                                                                            {{ number_format($mov->monto_pagado, 2) }} Bs
                                                                                        </span>,
                                                                                        digite la clave maestra de Contabilidad.
                                                                                    </p>

                                                                                    <form action="{{ route('pagos.validar', $mov->id_pagos_estudiante) }}"
                                                                                        method="POST">

                                                                                        @csrf

                                                                                        <input type="password" name="password_contabilidad" required
                                                                                            class="w-full border-2 border-brand-gold p-2 text-xs mb-4 focus:outline-none bg-gray-50 uppercase tracking-widest placeholder:normal-case placeholder:tracking-normal"
                                                                                            placeholder="Contraseña de Contabilidad">

                                                                                        <div class="flex justify-end gap-3">

                                                                                            <button type="button"
                                                                                                @click="openVerify{{ $mov->id_pagos_estudiante }} = false"
                                                                                                class="text-[9px] font-bold text-gray-400 uppercase cursor-pointer">
                                                                                                Cancelar
                                                                                            </button>

                                                                                            <button type="submit"
                                                                                                class="bg-brand-green text-white px-4 py-2 rounded-sm text-[9px] font-black uppercase cursor-pointer hover:bg-opacity-90">
                                                                                                Aprobar Pago
                                                                                            </button>

                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>

                                                                        @endif

                                                                        @if($movIndex === count($movimientos) - 1 && $saldoMovimiento > 0)

                                                                            <a href="{{ route('pagos.edit', $mov->id_pagos_estudiante) }}"
                                                                                title="Registrar Pago">

                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    class="w-5 h-5 text-brand-green hover:text-brand-gold transition-colors"
                                                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                                        d="M11 5h2M12 7v10m-7 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                                </svg>
                                                                            </a>

                                                                        @endif

                                                                    @endif

                                                                </div>
                                                            </td>

                                                        </tr>

                                @endforeach

                            @empty

                                <tr>
                                    <td colspan="9" class="py-12 text-center text-gray-400 italic">
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
                                <td colspan="2"></td>

                            </tr>
                        </tfoot>


                    </table>
                </div>
                <br> <br>
                <a href="{{ route('pagos.pdf', $inscripcion->id) }}" target="_blank"
                    class="inline-flex items-center gap-2 bg-brand-green text-white px-4 py-2 rounded-sm text-xs font-black uppercase tracking-tighter hover:bg-opacity-90 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Exportar PDF
                </a>
                @if(in_array($usuario->cargo, ['contador', 'asistente_contable']) || $usuario->rol === 'super_admin')
                    <a href="{{ route('students.change', $estudiante->id_estudiante) }}?id_curso={{ $curso->id_curso }}"
                        onclick="return confirm('Esto eliminará TODAS las cuotas actuales del estudiante (incluyendo pagos ya registrados) y generará un plan nuevo desde cero. ¿Deseas continuar?');"
                        class="inline-flex items-center gap-2 bg-brand-gold text-white px-4 py-2 rounded-sm text-xs font-black uppercase tracking-tighter hover:bg-opacity-90 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Cambiar Plan de Pago
                    </a>
                @endif
            </div>
        </div>


    </x-layout-dashboard>

</body>

</html>
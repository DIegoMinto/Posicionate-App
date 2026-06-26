<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pago</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">

        <x-page-header titulo="Completar Pago"></x-page-header>

        <div class="p-6">

            <div class="bg-white p-6 rounded-sm border-2 border-brand-gold shadow-md max-w-3xl mx-auto">

                <!-- INFO -->
                <div class="mb-6 text-sm text-black space-y-1">
                    <p><strong>Estudiante:</strong> {{ $estudiante->nombre }} {{ $estudiante->apellido_p }}</p>
                    <p><strong>Curso:</strong> {{ $curso->nombre }}</p>
                    <p><strong>Concepto:</strong> {{ $pago->detalle }}</p>
                </div>

                @php
                    $saldo = $pago->monto_pagar - $pago->monto_pagado;
                @endphp

                <!-- CARD PAGO -->
                <div class="border border-brand-green rounded-lg p-5 space-y-4">

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="font-bold text-brand-green">Monto Total</p>
                            <p>{{ number_format($pago->monto_pagar, 2) }} Bs</p>
                        </div>

                        <div>
                            <p class="font-bold text-brand-green">Monto Pagado</p>
                            <p>{{ number_format($pago->monto_pagado, 2) }} Bs</p>
                        </div>

                        <div class="col-span-2">
                            <p class="font-bold text-red-600">Saldo Pendiente</p>
                            <p class="text-lg font-bold">{{ number_format($saldo, 2) }} Bs</p>
                        </div>
                    </div>

                    <!-- FORM -->
                    <form action="{{ route('pagos.update', $pago->id_pagos_estudiante) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">

                            <div>
                                <label class="block text-sm font-bold text-brand-green mb-1">
                                    Monto pagado (total corregido)
                                </label>
                                <input type="number" step="0.01" name="monto_pagado" min="0"
                                    max="{{ $pago->monto_pagar }}" value="{{ $pago->monto_pagado }}" required
                                    class="w-full border border-gray-300 rounded p-2 text-center focus:ring-1 focus:ring-brand-green outline-none">
                                <p class="text-xs text-gray-500 mt-1">Ingresa el total pagado real (0 para reiniciar)
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-brand-green mb-1">
                                    Fecha de pago
                                </label>
                                <input type="date" name="fecha_pagada"
                                    value="{{ $pago->fecha_pagada ? \Carbon\Carbon::parse($pago->fecha_pagada)->format('Y-m-d') : now()->format('Y-m-d') }}"
                                    ...>
                            </div>

                        </div>

                        <div class="mt-6 text-right">
                            <button type="submit" class="btn-gold">
                                Guardar Pago
                            </button>
                        </div>

                    </form>

                </div>

            </div>

        </div>

    </x-layout-dashboard>
</body>

</html>
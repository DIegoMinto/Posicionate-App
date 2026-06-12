@php

    $matricula = $plan->detalles->firstWhere('nro_cuota', 0);

    $titulacion = $plan->detalles->firstWhere('detalle', 'TITULACION');

    $cuotasNormales = $plan->detalles
        ->where('nro_cuota', '>', 0)
        ->where('detalle', '!=', 'TITULACION')
        ->sortBy('nro_cuota');

@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Plan de Pago - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">

    <x-layout-dashboard :usuario="$usuario">

        <x-page-header titulo="Editar Plan de Pago">

        </x-page-header>

        <div class="p-6">

            <div
                class="bg-white border-2 border-brand-gold p-6 shadow-md flex justify-between items-center rounded-sm mb-8">

                <div>
                    <h2 class="font-sans font-bold text-black uppercase">
                        {{ $plan->curso->nombre }}
                    </h2>

                    <div class="flex gap-3 mt-2">

                        <span class="px-4 py-1 text-brand-green text-[10px] font-bold uppercase">
                            Tipo: {{ $plan->curso->tipo }}
                        </span>

                        <span class="px-4 py-1 text-brand-green text-[10px] font-bold uppercase">
                            Sede: {{ $plan->curso->sede->nombre }}
                        </span>

                    </div>
                </div>

                <div class="text-right">

                    <p class="font-sans font-bold uppercase">
                        Inversión Total
                    </p>

                    <p class="font-sans font-bold text-brand-green text-xl">
                        {{ number_format($plan->precio_base, 2) }} Bs.
                    </p>

                </div>

            </div>

            <form action="{{ route('plans.update', $plan->id_planes_pago) }}" method="POST" class="space-y-6">

                @csrf
                @method('PUT')

                <div class="bg-white border-2 border-brand-gold p-6 shadow-xl rounded-sm">

                    <div class="flex items-center gap-2 mb-6">

                        <h3 class="font-bold text-black uppercase font-sans">
                            Configuración del Plan
                        </h3>

                    </div>

                    <div class="space-y-5">

                        <div>

                            <label class="form-label-bold text-black">
                                Nombre del Plan
                            </label>

                            <input type="text" name="nombre" value="{{ $plan->nombre }}" required
                                class="form-input-pill border-brand-gold border">

                        </div>
                        <div>

                            <label class="form-label-bold text-black">
                                Tipo de Plan
                            </label>

                            <select name="tipo_plan" class="form-input-pill border-brand-gold border">

                                <option value="CONTADO" {{ $plan->tipo_plan == 'CONTADO' ? 'selected' : '' }}>
                                    Contado
                                </option>

                                <option value="CUOTAS" {{ $plan->tipo_plan == 'CUOTAS' ? 'selected' : '' }}>
                                    Cuotas
                                </option>

                            </select>

                        </div>

                        <div>

                            <label class="form-label-bold text-black">
                                Inversión Total (Bs)
                            </label>

                            <input type="number" step="0.01" id="precio_base" name="precio_base"
                                value="{{ $plan->precio_base }}" oninput="recalcularCuotas()" required
                                class="form-input-pill border-brand-gold border">

                        </div>

                        <div class="flex items-center p-3">

                            <input type="checkbox" name="incluye_matricula" value="1" class="w-4 h-4 accent-brand-gold"
                                {{ $plan->incluye_matricula ? 'checked' : '' }}>

                            <label class="text-[10px] font-bold font-sans text-brand-green uppercase ml-2">

                                ¿Incluye matrícula?

                            </label>

                        </div>

                        <div class="flex items-center p-3">

                            <input type="checkbox" id="tiene_titulacion" name="tiene_titulacion" value="1"
                                class="w-4 h-4 accent-brand-gold" {{ $plan->tiene_titulacion ? 'checked' : '' }}>

                            <label class="text-[10px] font-bold font-sans text-brand-green uppercase ml-2">
                                ¿Incluye Titulación?
                            </label>

                        </div>

                        <div id="contenedor_titulacion" class="{{ !$plan->tiene_titulacion ? 'hidden' : '' }}">

                            <div class="bg-brand-gold/10 p-3 border-l-4 border-brand-gold rounded-r-sm">

                                <p class="text-[10px] font-bold uppercase text-brand-gold">
                                    Pago de Titulación
                                </p>

                                <input type="number" step="0.01" name="monto_titulacion"
                                    value="{{ $plan->monto_titulacion }}"
                                    class="w-40 bg-transparent border-b border-brand-gold outline-none">

                            </div>

                        </div>

                    </div>

                </div>

                <div class="bg-white border-2 border-brand-green shadow-md rounded-sm">

                    <div class="bg-brand-green p-4">

                        <h3
                            class="text-white font-sans font-bold uppercase text-sm tracking-widest flex items-center gap-2">

                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a5 5 0 00-10 0v2m-2 0h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2v-8a2 2 0 012-2z" />
                            </svg>

                            Cuotas Configuradas

                        </h3>

                    </div>

                    <div class="p-6 space-y-4">

                        {{-- MATRÍCULA --}}

                        @if($matricula)

                            <div class="bg-brand-green/5 border-l-4 border-brand-green p-4 rounded-r-sm">

                                <div class="flex justify-between items-center">

                                    <div>

                                        <p class="text-[10px] font-bold uppercase text-brand-green">
                                            Matrícula
                                        </p>

                                        <p class="text-[9px] text-gray-500">
                                            Pago inicial
                                        </p>

                                    </div>

                                    <div class="w-48">

                                        <input type="number" step="0.01" name="monto_matricula"
                                            value="{{ $matricula->monto_cuota }}"
                                            class="w-full bg-transparent border-b border-brand-green text-right font-bold outline-none">

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- CUOTAS NORMALES --}}

                        @foreach($cuotasNormales as $detalle)

                            <div
                                class="flex flex-col lg:flex-row lg:items-center gap-4 bg-gray-50 border-l-4 border-brand-green p-4 rounded-r-sm">

                                <div class="w-full lg:w-32">

                                    <p class="text-[10px] font-bold uppercase text-brand-green">
                                        Pago {{ $detalle->nro_cuota }}
                                    </p>

                                </div>

                                <div class="flex-1">

                                    <label class="text-[9px] uppercase font-bold text-gray-500">
                                        Monto
                                    </label>

                                    <input type="number" step="0.01" name="cuotas[{{ $loop->index }}][monto]"
                                        value="{{ $detalle->monto_cuota }}" required data-cuota="{{ $detalle->nro_cuota }}"
                                        class="cuota-input w-full bg-transparent border-b border-brand-gold font-sans text-sm outline-none">

                                </div>

                                <div class="flex-1">

                                    <label class="text-[9px] uppercase font-bold text-gray-500">
                                        Programación
                                    </label>

                                    @if($plan->tipo_plan === 'CONTADO')

                                        <p class="text-[11px] text-brand-green font-bold">
                                            Cada 30 días
                                        </p>

                                    @else

                                        <p class="text-[11px] text-brand-green font-bold">
                                            Día 15 de cada mes
                                        </p>

                                    @endif

                                </div>

                                <input type="hidden" name="cuotas[{{ $loop->index }}][nro_cuota]"
                                    value="{{ $detalle->nro_cuota }}">

                            </div>

                        @endforeach


                        {{-- TITULACIÓN --}}

                        @if($titulacion)

                            <div class="bg-brand-gold/10 border-l-4 border-brand-gold p-4 rounded-r-sm">

                                <div class="flex justify-between items-center">

                                    <div>

                                        <p class="text-[10px] font-bold uppercase text-brand-gold">
                                            Titulación
                                        </p>

                                        <p class="text-[9px] text-gray-500">
                                            15 días después de la última cuota
                                        </p>

                                    </div>

                                    <div class="w-48">

                                        <input type="number" step="0.01" name="monto_titulacion"
                                            value="{{ $titulacion->monto_cuota }}"
                                            class="w-full bg-transparent border-b border-brand-gold text-right font-bold outline-none">

                                    </div>

                                </div>

                            </div>

                        @endif

                    </div>

                </div>

                <div class="flex justify-end pt-4">

                    <button type="submit" class="btn-gold">
                        GUARDAR CAMBIOS
                    </button>

                </div>

            </form>

        </div>

    </x-layout-dashboard>
    <script>

        function recalcularCuotas() {

            const total = parseFloat(document.getElementById('precio_base').value) || 0;

            const cuotas = document.querySelectorAll('.cuota-input');

            let cuotasNormales = [];

            cuotas.forEach(input => {

                const nroCuota = parseInt(input.dataset.cuota);

                if (nroCuota !== 0) {
                    cuotasNormales.push(input);
                }

            });

            if (cuotasNormales.length === 0) return;

            const nuevoMonto = (total / cuotasNormales.length).toFixed(2);

            cuotasNormales.forEach(input => {
                input.value = nuevoMonto;
            });
        }
        const checkTitulacion =
            document.getElementById('tiene_titulacion');

        const contenedorTitulacion =
            document.getElementById('contenedor_titulacion');

        checkTitulacion.addEventListener('change', function () {

            if (this.checked) {

                contenedorTitulacion.classList.remove('hidden');

            } else {

                contenedorTitulacion.classList.add('hidden');

            }

        });

    </script>
</body>

</html>
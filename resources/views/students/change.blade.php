<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <x-page-header titulo="Inscripción de Estudiante"></x-page-header>
        <form id="form-inscripcion" action="{{ route('inscripcion.store_change', $estudiante->id_estudiante) }}"
            method="POST">

            @csrf
            <input type="hidden" name="id_curso" value="{{ $curso->id_curso }}">

            <div class="p-8">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-brand-green uppercase font-sans tracking-tight">
                        {{ $curso->nombre }}
                    </h1>
                    <p class="text-sm font-sans font-bold text-brand-green">
                        {{ $curso->codigo_curso }}
                    </p>
                </div>

                <div class="flex flex-wrap lg:flex-nowrap gap-6 justify-center">

                    <div class="w-full lg:w-1/2 border border-brand-green rounded-lg p-5">
                        <h2 class="text-brand-green font-bold font-sans text-lg mb-3">Datos del Estudiante</h2>
                        <div class="text-sm space-y-1 text-black">
                            <p><span class="font-sans font-medium">Nombre del Participante:</span>
                                {{ $estudiante->nombre }}
                                {{ $estudiante->apellido_p }} {{ $estudiante->apellido_m }}
                            </p>
                            <p><span class="font-sans font-medium">CI/NIT:</span> {{ $estudiante->ci }}</p>
                            <p><span class="font-sans font-medium">Teléfono:</span> {{ $estudiante->elefono_movil }}</p>
                            <p>
                                <span class="font-medium">Saldo Total Adeudado:</span>
                                <span id="saldo-display" class="font-sans text-lg">0</span> Bs
                            </p>
                        </div>
                    </div>

                    <div class="w-full lg:w-1/2 border border-brand-green rounded-lg p-5">
                        <h2 class="text-brand-green font-bold font-sans text-lg mb-4">Datos de Inscripción</h2>

                        <div class="space-y-4">

                            <div class="flex items-center justify-between gap-4">
                                <label class="text-sm font-medium text-black">Seleccionar el Tipo de Plan de
                                    Pagos</label>
                                <select id="select-plan" name="id_plan"
                                    class="bg-brand-green text-white text-xs font-bold px-4 py-2 rounded-sm outline-none cursor-pointer text-left">
                                    <option value="0" data-precio="0">Seleccionar Plan</option>
                                    @foreach($planes as $plan)
                                        <option value="{{ $plan->id_planes_pago }}" data-precio="{{ $plan->precio_base }}">
                                            {{$plan->nombre}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="mt-8 border border-brand-green rounded-lg p-8 bg-white">
                <h2 class="text-brand-green font-bold font-sans text-xl mb-6">Plan de Pagos del Estudiante</h2>

                <div id="contenedor-cuotas" class="space-y-6"></div>

                <div
                    class="mt-6 p-4 bg-brand-green/10 border-t-2 border-brand-green flex justify-between items-center rounded-b-lg">
                    <span class="text-brand-green font-sans font-bold uppercase text-sm">Monto Total del Plan:</span>
                    <span class="text-sm font-sans font-bold text-brand-green">
                        <span id="total-plan-pago">0.00</span> Bs
                    </span>
                </div>

                <div class="mt-12 text-center">
                    <p class="text-sm text-gray-600 mb-2 font-medium">Registrar al Estudiante en:</p>
                    <h3 class="text-brand-green font-bold text-lg uppercase mb-6">
                        {{ $curso->nombre }} ({{ $curso->codigo_curso }})
                    </h3>

                    <button type="submit" class="btn-gold">
                        Registrar
                    </button>
                </div>
            </div>

        </form>
    </x-layout-dashboard>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectPlan = document.getElementById('select-plan');
            const selectDescuento = document.getElementById('select-descuento');
            const saldoDisplay = document.getElementById('saldo-display');
            const contenedorCuotas = document.getElementById('contenedor-cuotas');

            async function actualizarInterfaz() {
                const optionPlan = selectPlan.options[selectPlan.selectedIndex];
                const planId = selectPlan.value;
                const precioBase = parseFloat(optionPlan.getAttribute('data-precio')) || 0;
                const optionDesc = selectDescuento.options[selectDescuento.selectedIndex];
                const porcentaje = parseFloat(optionDesc.getAttribute('data-porcentaje')) || 0;

                const total = precioBase - (precioBase * (porcentaje / 100));
                saldoDisplay.innerText = total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                contenedorCuotas.innerHTML = '<p class="text-gray-400 animate-pulse">Cargando plan de pagos...</p>';

                if (planId !== "0") {
                    try {
                        const response = await fetch(`/api/plan-detalles/${planId}`);
                        if (!response.ok) throw new Error('Error en la red');

                        const cuotas = await response.json();
                        renderizarCuotas(cuotas, porcentaje);
                    } catch (error) {
                        console.error("Error:", error);
                        contenedorCuotas.innerHTML = '<p class="text-red-500 italic">Error al conectar con el servidor.</p>';
                    }
                } else {
                    contenedorCuotas.innerHTML = '<p class="text-gray-400 italic">Seleccione un plan para ver el cronograma de pagos.</p>';
                }
            }

            function renderizarCuotas(cuotas, porcentajeDescuento) {
                contenedorCuotas.innerHTML = '';
                let sumaTotal = 0;

                if (cuotas.length === 0) {
                    contenedorCuotas.innerHTML = '<p class="text-orange-500">Este plan no tiene cuotas configuradas.</p>';
                    document.getElementById('total-plan-pago').innerText = "0.00";
                    return;
                }

                cuotas.forEach((cuota, index) => {
                    const montoOriginal = parseFloat(cuota.monto_cuota);
                    const montoConDescuento = montoOriginal - (montoOriginal * (porcentajeDescuento / 100));
                    sumaTotal += montoConDescuento;

                    const html = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end border-b border-gray-100 pb-4">
                <div>
                    <label class="block font-sans font-bold text-brand-green mb-1">${cuota.nro_cuota == 0 ? 'MATRÍCULA' : cuota.detalle}</label>
                    <div class="bg-gray-100 p-2 rounded text-sm text-gray-700 border border-gray-200">
                        ${montoConDescuento.toFixed(2)} Bs (Sugerido)
                    </div>
                    

                </div>
                <div>
                    <label class="block font-sans font-bold text-brand-green mb-1 text-center uppercase">Monto a pagar</label>
                    <input type="number"
                        name="cuotas[${index}][monto_pagado]"
                        step="0.01"
                        value="0.00"
                        class="w-full border border-gray-300 rounded p-2 text-center focus:ring-1 focus:ring-brand-green outline-none font-sans">
                </div>

                <div>
                    <label class="block font-sans font-bold text-brand-green mb-1 uppercase text-center">Fecha Programada</label>
                    <input type="date"
                        name="cuotas[${index}][fecha_pagada]"
                        value="${cuota.fecha_vencimiento || ''}"
                        class="w-full border border-gray-300 rounded p-2 text-center focus:ring-1 focus:ring-brand-green outline-none">

                    <input type="hidden" name="cuotas[${index}][detalle]" value="${cuota.nro_cuota == 0 ? 'MATRÍCULA' : cuota.detalle}">
                </div>

            </div>`;
                    contenedorCuotas.insertAdjacentHTML('beforeend', html);
                });

                document.getElementById('total-plan-pago').innerText = sumaTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            selectPlan.addEventListener('change', actualizarInterfaz);
            selectDescuento.addEventListener('change', actualizarInterfaz);
            if (selectPlan.value !== "0") actualizarInterfaz();
        });
    </script>

</body>

</html>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar Planes de Pago - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">
    <x-layout-dashboard :usuario="$usuario">
        <x-page-header titulo="Configuración de Inversión y Planes"><a href="{{ route('programs.index') }}"
                class="btn-back">
                ← Volver
            </a></x-page-header>

        <div class="p-6 space-y-6">
            <div class="bg-white border-2 border-brand-gold p-6 shadow-md flex justify-between items-center rounded-sm">
                <div>
                    <h2 class="font-sans font-bold text-black uppercase">{{ $curso->nombre }}</h2>
                    <div class="flex gap-3 mt-2">
                        <span class="px-4 py-1 text-brand-green text-[10px] font-bold uppercase">
                            Tipo: {{ $curso->tipo }}
                        </span>
                        <span class="px-4 py-1 text-brand-green text-[10px] font-bold uppercase"">
                            Sede: {{ $curso->sede->nombre }}
                        </span>
                    </div>
                </div>
                <div class=" text-right">
                            <p class="font-sans font-bold uppercase">Matrícula Base</p>
                            <p class=" font-sans font-bold text-brand-green">
                                {{ number_format($curso->costo_matricula, 2) }} Bs.
                            </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                    <div class="lg:col-span-5 bg-white p-6 border-2 border-brand-gold shadow-xl rounded-sm h-fit">
                        <div class="flex items-center gap-2 mb-6">
                            <h3 class="font-bold text-black uppercase font-sans">Definir Nuevo Plan</h3>
                        </div>

                        <form action="{{ route('plans.store') }}" method="POST" class="space-y-5">
                            @csrf
                            <input type="hidden" name="id_curso" value="{{ $curso->id_curso }}">

                            <div class="space-y-4">
                                <div>
                                    <label class="form-label-bold text-black">Nombre del
                                        Plan</label>
                                    <input type="text" name="nombre" placeholder="Ej: Plan Corporativo o Promocional"
                                        required class="form-input-pill border-brand-gold border">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="form-label-bold text-black">Número
                                            de
                                            Pagos</label>


                                        <select id="select_nro_cuotas" name="nro_cuotas"
                                            onchange="generarFilasDeCuotas()"
                                            class="form-select-pill border-brand-gold border">
                                            <option value="1">1 Pago (Contado)</option>
                                            <option value="2">2 Pagos</option>
                                            <option value="3">3 Pagos</option>
                                            <option value="4">4 Pagos</option>
                                            <option value="5">5 Pagos</option>
                                            <option value="6">6 Pagos</option>
                                            <option value="12">12 Pagos</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="form-label-bold text-black">
                                            Tipo de Plan
                                        </label>

                                        <select name="tipo_plan" id="tipo_plan" onchange="generarFilasDeCuotas()"
                                            class="form-select-pill border-brand-gold border">

                                            <option value="CONTADO">
                                                Contado
                                            </option>

                                            <option value="CUOTAS">
                                                Cuotas Mensuales
                                            </option>

                                        </select>
                                    </div>

                                    <div>
                                        <label class="form-label-bold text-black">Inversión
                                            Total (Bs)</label>
                                        <input type="number" id="monto_total" name="precio_base"
                                            oninput="recalcularCuotas()" required
                                            class="form-input-pill border-brand-gold border">
                                    </div>
                                </div>

                                <div class="flex items-center p-3">
                                    <input type="checkbox" name="incluye_matricula" value="1" id="check_mat"
                                        onchange="generarFilasDeCuotas()" class="w-4 h-4 accent-brand-gold">
                                    <label for="check_mat"
                                        class="text-[10px] font-bold font-sans text-brand-green uppercase cursor-pointer ml-2">
                                        ¿Este plan requiere pago de matrícula aparte?
                                    </label>
                                </div>

                                <div class="space-y-2">
                                    <label class="form-label-bold text-black">Desglose de Pagos
                                        Sugerido</label>
                                    <div id="cuotas_dinamicas_container"
                                        class="space-y-2 max-h-[400px] overflow-y-auto pr-2">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="block mx-auto btn-gold">
                                GUARDAR Y ACTIVAR PLAN
                            </button>
                        </form>
                    </div>

                    <div class="lg:col-span-7">
                        <div class="flex items-center gap-2 mb-6">
                            <h3 class="font-bold text-black uppercase font-sans">
                                Planes
                                Configurados</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            @forelse($curso->planes as $plan)
                                <div
                                    class="bg-white border-2 border-gray-100 p-5 flex justify-between items-center hover:border-brand-gold transition shadow-sm">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="p-3 bg-gray-50 rounded-full group-hover:bg-brand-gold/10 text-brand-gold">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-sans font-bold text-black">
                                                {{ $plan->nombre }}
                                            </h4>
                                            <div class="flex gap-3 text-[10px] font-sans uppercase mt-1">
                                                <span>Total: {{ number_format($plan->precio_base, 2) }} Bs.</span>
                                                <span
                                                    class="{{ $plan->incluye_matricula ? 'text-brand-green' : 'text-red-600' }}">
                                                    Matrícula: {{ $plan->incluye_matricula ? 'SÍ' : 'NO' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <a href="{{ route('plans.installments', $plan->id_planes_pago) }}" class="btn-gold">
                                            Ver Detalle
                                        </a>
                                        <a href="{{ route('plans.edit', $plan->id_planes_pago) }}"
                                            class="group relative flex items-center justify-center text-black hover:text-brand-gold transition">

                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>

                                            <span
                                                class="absolute -top-8 scale-0 transition-all rounded bg-gray-800 px-2 py-1 text-[10px] text-white group-hover:scale-100 whitespace-nowrap z-30 shadow-lg font-sans">
                                                Editar Plan
                                            </span>
                                        </a>
                                        <div x-data="{ openDelete: false }">
                                            <button @click="openDelete = true"
                                                class="group relative flex items-center justify-center pb-1 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="w-7 h-7 group-hover:text-red-600 transition-colors" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span
                                                    class="absolute -top-8 scale-0 transition-all rounded bg-red-600 px-2 py-1 text-[10px] text-white group-hover:scale-100 whitespace-nowrap z-30 shadow-lg font-sans">
                                                    Eliminar Plan de Pagos
                                                </span>
                                            </button>

                                            <div x-show="openDelete"
                                                class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm"
                                                x-cloak>
                                                <div class="bg-white p-6 rounded-sm shadow-2xl w-80 text-left border-t-4 border-red-600 font-sans"
                                                    @click.away="openDelete = false">
                                                    <h3 class="text-red-600 uppercase mb-2 font-bold">Confirmar Eliminación
                                                    </h3>
                                                    <p class="text-[10px] mb-4 text-gray-600">
                                                        Vas a eliminar el plan:<br>
                                                        <span
                                                            class="text-black font-bold uppercase">{{ $plan->nombre }}</span>
                                                    </p>

                                                    <form action="{{ route('plans.destroy', $plan->id_planes_pago) }}"
                                                        method="POST">
                                                        @csrf @method('DELETE')
                                                        <input type="password" name="password_confirm" required
                                                            class="w-full border border-gray-200 p-2 text-xs mb-4 focus:outline-none focus:border-red-500 bg-gray-50"
                                                            placeholder="Tu contraseña de administrador">

                                                        <div class="flex justify-end gap-3">
                                                            <button type="button" @click="openDelete = false"
                                                                class="text-[9px] uppercase hover:text-red-600">Cancelar</button>
                                                            <button type="submit"
                                                                class="bg-red-600 text-white px-4 py-2 rounded-sm text-[9px] uppercase hover:bg-red-700">Eliminar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="bg-gray-100/50 border-2 border-dashed border-gray-200 py-20 text-center rounded-lg">
                                    <p class="text-gray-400 font-bold uppercase text-xs tracking-widest italic">No hay
                                        planes
                                        para este curso</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="flex mt-10 items-center pt-6 justify-center">
                            <a href="{{ route('programs.index') }}" class="btn-gold">FINALIZAR
                                CONFIGURACIÓN</a>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                const montoMatBase = {{ $curso->costo_matricula ?? 0 }};

                function generarFilasDeCuotas() {
                    const total = parseFloat(document.getElementById('monto_total').value) || 0;
                    const nroCuotas = parseInt(document.getElementById('select_nro_cuotas').value);
                    const container = document.getElementById('cuotas_dinamicas_container');
                    const incluyeMat = document.getElementById('check_mat').checked;

                    container.innerHTML = '';



                    if (incluyeMat) {
                        const divMat = document.createElement('div');
                        divMat.className = "bg-brand-green/5 p-3 border-l-4 border-brand-green mb-4 rounded-r-sm";
                        divMat.innerHTML = `
            <div class="flex justify-between items-end">
                <div>
                    <p class="text-[9px] font-sans font-bold text-brand-green uppercase">Matrícula (Pago Inicial)</p>
                    <input type="number" name="monto_matricula" value="${montoMatBase}" step="0.01"
                           class="w-32 bg-transparent font-sans text-sm outline-none border-b-2 border-brand-green/20 focus:border-brand-green">
                </div>
            </div>
        `;
                        container.appendChild(divMat);
                    }

                    const montoDividido = (total / nroCuotas).toFixed(2);

                    for (let i = 1; i <= nroCuotas; i++) {
                        const esPrimera = (i === 1);
                        const row = document.createElement('div');
                        row.className = "flex items-center gap-4 bg-white p-3 border-l-4 border-brand-green shadow-sm mb-2 animate-fade-in";

                        row.innerHTML = `
                <div class="w-12 text-center">
                    <span class="text-[10px] font-sans font-bold text-brand-green uppercase"">PAGO ${i}</span>
                </div>
                <div class="flex-1">
                    <span class="text-[8px] font-sans font-bold uppercase">Monto</span>
                    <input type="number" name="cuotas[${i}][monto]" value="${montoDividido}" step="0.01" 
                           class="w-full bg-transparent border-b font-sans text-sm outline-none focus:border-brand-gold">
                </div>
                <div class="flex-1 text-right">
                    <span class="text-[8px] font-sans font-bold uppercase">Vencimiento</span>
                    <div class="flex-1 text-right">
    <span class="text-[8px] font-sans font-bold uppercase">
        Programación
    </span>

    ${esPrimera
                                ? `<p class="text-[10px] font-bold font-sans text-brand-green mt-1 uppercase">
                Día de inscripción
           </p>`
                                : `<p class="text-[10px] font-bold font-sans text-brand-green mt-1 uppercase">
                Automático
           </p>`
                            }
</div>
                </div>
                <input type="hidden" name="cuotas[${i}][nro_cuota]" value="${i}">
            `;
                        container.appendChild(row);
                    }
                }

                function recalcularCuotas() {
                    if (parseFloat(document.getElementById('monto_total').value) >= 0) {
                        generarFilasDeCuotas();
                    }
                }

                window.onload = () => generarFilasDeCuotas();
            </script>
    </x-layout-dashboard>
</body>

</html>
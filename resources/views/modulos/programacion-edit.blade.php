<x-layout-dashboard :usuario="$usuario">

    @php
        $dias = [
            'lunes' => 'Lunes',
            'martes' => 'Martes',
            'miercoles' => 'Miércoles',
            'jueves' => 'Jueves',
            'viernes' => 'Viernes',
            'sabado' => 'Sábado',
            'domingo' => 'Domingo',
        ];

        $diasSeleccionados = old(
            'dias_clase',
            $programacion?->dias_clase ?? []
        );

        $cantidadModulos = (int) old(
            'cantidad_modulos',
            $programacion?->cantidad_modulos ?? 1
        );

        $modulosIniciales = old('modulos');

        if ($modulosIniciales === null) {
            $modulosIniciales = $curso->modulos
                ->sortBy('orden')
                ->map(function ($modulo) {
                    return [
                        'nombre' => $modulo->nombre,
                        'orden' => $modulo->orden,
                        'fecha_inicio' => $modulo->fecha_inicio?->format('Y-m-d'),
                        'fecha_fin' => $modulo->fecha_fin?->format('Y-m-d'),
                        'id_docente' => $modulo->id_docente,
                        'fecha_inicio_descanso' => $modulo->fecha_inicio_descanso?->format('Y-m-d'),
                        'fecha_fin_descanso' => $modulo->fecha_fin_descanso?->format('Y-m-d'),
                    ];
                })
                ->values()
                ->toArray();
        }
    @endphp

    <div class="mx-auto">

        <x-page-header titulo="Configuración académica" />

        <div class="p-6">

            <div class="mb-6">
                <a href="{{ route('programacion.show', $curso->id_curso) }}"
                   class="text-sm text-gray-500 hover:text-brand-green">
                    ← Volver a la programación
                </a>

                <h2 class="text-xl font-black text-brand-green mt-4">
                    {{ $curso->nombre }}
                </h2>
            </div>

            @if(session('success'))
                <div class="mb-5 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                    <p class="font-bold mb-2">Revisa los siguientes campos:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('programacion.store', $curso->id_curso) }}"
                  method="POST"
                  id="form-programacion">

                @csrf

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">

                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                                <h3 class="text-lg font-bold text-brand-green">
                                    Configuración general
                                </h3>
                        </div>
                    </div>

                    <div class="p-6 space-y-7">
                        <div>
                            <h4 class="text-sm font-bold text-gray-700 mb-4">
                                Duración y estructura académica
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-1 gap-5">

                                <div>
                                    <label class="form-label-bold text-brand-green">
                                        Duración del diplomado (meses) *
                                    </label>

                                    <input
                                        type="number"
                                        name="duracion_meses"
                                        min="1"
                                        max="60"
                                        value="{{ old('duracion_meses', $programacion?->duracion_meses) }}"
                                        required
                                        placeholder="Ej. 6"
                                        class="form-input-pill border-brand-green border w-20"
                                    >
                                </div>

                                <div>
                                    <label class="form-label-bold text-brand-green">
                                        Cantidad de módulos *
                                    </label>

                                    <input
                                        type="number"
                                        id="cantidad_modulos"
                                        name="cantidad_modulos"
                                        min="1"
                                        max="100"
                                        value="{{ $cantidadModulos }}"
                                        required
                                        placeholder="Ej. 5"
                                        class="form-input-pill border-brand-green border w-20"
                                    >
                                </div>

                            </div>
                        </div>

                        <hr class="border-gray-100">
                        <div class="space-y-5">

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div>
                                    <h4 class="text-lg font-bold text-brand-green">
                                        Planificación de módulos
                                    </h4>
                                </div>

                                <span id="contador-modulos"
                                      class="text-xs font-semibold bg-green-50 text-brand-green px-3 py-2 rounded-lg">
                                    {{ $cantidadModulos }} módulos
                                </span>
                            </div>

                            <div id="contenedor-modulos" class="space-y-4">

                                @for($i = 0; $i < $cantidadModulos; $i++)

                                    @php
                                        $modulo = $modulosIniciales[$i] ?? [];

                                        $docenteSeleccionado = old(
                                            "modulos.$i.id_docente",
                                            data_get($modulo, 'id_docente')
                                        );

                                        $inicioDescanso = old(
                                            "modulos.$i.fecha_inicio_descanso",
                                            data_get($modulo, 'fecha_inicio_descanso')
                                        );

                                        $finDescanso = old(
                                            "modulos.$i.fecha_fin_descanso",
                                            data_get($modulo, 'fecha_fin_descanso')
                                        );
                                    @endphp

                                    <div class="modulo-item border border-gray-200 rounded-xl p-5 space-y-5">

                                        <div class="flex items-center justify-between">
                                            <h5 class="font-bold text-brand-green">
                                                Módulo {{ $i + 1 }}
                                            </h5>

                                            <span class="text-xs text-gray-400">
                                                Módulo {{ $i + 1 }}
                                            </span>
                                        </div>

                                        {{-- NOMBRE --}}
                                        <div>
                                            <label class="form-label-bold text-brand-green"">
                                                Nombre del módulo *
                                            </label>

                                            <input
                                                type="text"
                                                name="modulos[{{ $i }}][nombre]"
                                                value="{{ data_get($modulo, 'nombre') }}"
                                                required
                                                maxlength="255"
                                                placeholder="Ej. Fundamentos y conceptos generales"
                                                class="form-input-pill border-brand-green border"
                                            >

                                            <input
                                                type="hidden"
                                                name="modulos[{{ $i }}][orden]"
                                                value="{{ $i + 1 }}"
                                            >
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                            <div>
                                                <label class="form-label-bold text-brand-green"">
                                                    Fecha de inicio *
                                                </label>

                                                <input
                                                    type="date"
                                                    name="modulos[{{ $i }}][fecha_inicio]"
                                                    value="{{ data_get($modulo, 'fecha_inicio') }}"
                                                    required
                                                    class="form-input-pill border-brand-green border w-sm"
                                                >
                                            </div>

                                            <div>
                                                <label class="form-label-bold text-brand-green"">
                                                    Fecha de fin *
                                                </label>

                                                <input
                                                    type="date"
                                                    name="modulos[{{ $i }}][fecha_fin]"
                                                    value="{{ data_get($modulo, 'fecha_fin') }}"
                                                    required
                                                    class="form-input-pill border-brand-green border w-sm"
                                                >
                                            </div>

                                        </div>
                                        <div>
                                            <label class="form-label-bold text-brand-green"">
                                                Docente responsable del módulo
                                            </label>

                                            <select
                                                name="modulos[{{ $i }}][id_docente]"
                                                class="form-input-pill border-brand-green border w-xl"
                                            >
                                                <option value="">
                                                    Sin docente asignado
                                                </option>

                                                @foreach($docentes as $docente)
                                                    <option
                                                        value="{{ $docente->id_docente }}"
                                                        {{ (string) $docenteSeleccionado === (string) $docente->id_docente ? 'selected' : '' }}
                                                    >
                                                        {{ $docente->apellido_p }}
                                                        {{ $docente->apellido_m }}
                                                        {{ $docente->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- PERIODO DE DESCANSO --}}
                                        <div class="border-t border-gray-100 pt-4">

                                            <div class="flex items-center gap-2 mb-3">
                                                <div>
                                                    <h6 class="text-sm font-bold text-gray-700">
                                                        Periodo de descanso
                                                    </h6>
                                                    <p class="text-xs text-gray-400">
                                                        Opcional. Define cuándo no habrá clases.
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                                <div>
                                                    <label class="form-label-bold text-brand-green"">
                                                        Inicio del descanso
                                                    </label>

                                                    <input
                                                        type="date"
                                                        name="modulos[{{ $i }}][fecha_inicio_descanso]"
                                                        value="{{ $inicioDescanso }}"
                                                        class="form-input-pill border-brand-green border w-sm"
                                                    >
                                                </div>

                                                <div>
                                                    <label class="form-label-bold text-brand-green"">
                                                        Fin del descanso
                                                    </label>

                                                    <input
                                                        type="date"
                                                        name="modulos[{{ $i }}][fecha_fin_descanso]"
                                                        value="{{ $finDescanso }}"
                                                        min="{{ $inicioDescanso }}"
                                                        class="form-input-pill border-brand-green border w-sm"
                                                    >
                                                </div>

                                            </div>

                                            <p class="text-xs text-gray-400 mt-3">
                                                Las sesiones que coincidan con este intervalo se omitirán al generar las clases.
                                            </p>

                                        </div>

                                    </div>

                                @endfor

                            </div>

                            <p class="text-xs text-gray-400">
                                La programación detallada de sesiones se realizará posteriormente dentro de cada módulo.
                            </p>

                        </div>

                        <hr class="border-gray-100">
                        <div>
                            <h4 class="text-sm font-bold text-gray-700 mb-4">
                                Días y horarios habituales
                            </h4>

                            <div class="mb-5">
                                <label class="form-label-bold text-brand-green"">
                                    Días de clases
                                </label>

                                <div class="flex flex-wrap gap-3">

                                    @foreach($dias as $valor => $nombre)

                                        <label class="form-label-bold text-brand-green"">

                                            <input
                                                type="checkbox"
                                                name="dias_clase[]"
                                                value="{{ $valor }}"
                                                {{ in_array($valor, $diasSeleccionados ?? []) ? 'checked' : '' }}
                                                class="rounded text-brand-green focus:ring-brand-green"
                                            >

                                            <span class="text-sm text-gray-700">
                                                {{ $nombre }}
                                            </span>

                                        </label>

                                    @endforeach

                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                <div>
                                    <label class="form-label-bold text-brand-green"">
                                        Hora de inicio
                                    </label>

                                    <input
                                        type="time"
                                        name="hora_inicio"
                                        value="{{ old('hora_inicio', $programacion?->hora_inicio ? substr($programacion->hora_inicio, 0, 5) : '') }}"
                                        class="form-input-pill border-brand-green border w-30"
                                    >
                                </div>

                                <div>
                                    <label class="form-label-bold text-brand-green"">
                                        Hora de finalización
                                    </label>

                                    <input
                                        type="time"
                                        name="hora_fin"
                                        value="{{ old('hora_fin', $programacion?->hora_fin ? substr($programacion->hora_fin, 0, 5) : '') }}"
                                        class="form-input-pill border-brand-green border w-30"
                                    >
                                </div>

                            </div>
                        </div>

                        <hr class="border-gray-100">

                        {{-- CARGA HORARIA --}}
                        <div>
                            <h4 class="text-sm font-bold text-gray-700 mb-4">
                                Carga académica
                            </h4>

                            <div class="max-w-md">
                                <label class="form-label-bold text-brand-green"">
                                    Carga horaria total (horas)
                                </label>

                                <input
                                    type="number"
                                    name="carga_horaria"
                                    min="0"
                                    step="0.5"
                                    value="{{ old('carga_horaria', $programacion?->carga_horaria) }}"
                                    placeholder="Ej. 400"
                                    class="form-input-pill border-brand-green border w-sm"
                                >

                                <p class="text-xs text-gray-400 mt-2">
                                    Ingresa la cantidad total de horas académicas del diplomado.
                                </p>
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        {{-- COORDINADOR --}}
                        <div>
                            <h4 class="text-sm font-bold text-gray-700 mb-4">
                                Coordinación académica
                            </h4>

                            <div class="max-w-xl">
                                <label class="form-label-bold text-brand-green"">
                                    Docente coordinador
                                </label>

                                <select
                                    name="id_docente_coordinador"
                                    class="form-input-pill border-brand-green border w-sm"
                                >
                                    <option value="">
                                        Sin coordinador designado
                                    </option>

                                    @foreach($docentes as $docente)
                                        <option
                                            value="{{ $docente->id_docente }}"
                                            {{ (string) old('id_docente_coordinador', $programacion?->id_docente_coordinador) === (string) $docente->id_docente ? 'selected' : '' }}
                                        >
                                            {{ $docente->apellido_p }}
                                            {{ $docente->apellido_m }}
                                            {{ $docente->nombre }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                    </div>

                    {{-- BOTONES --}}
                    <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-3">

                        <a
                            href="{{ route('programacion.show', $curso->id_curso) }}"
                            class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 text-center"
                        >
                            Cancelar
                        </a>

                        <button
                            type="submit"
                            class="px-6 py-2.5 text-sm font-bold text-white bg-brand-green rounded-lg hover:opacity-90 transition"
                        >
                            <i class="fas fa-save mr-2"></i>
                            {{ $programacion ? 'Actualizar programación' : 'Guardar programación' }}
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- GENERACIÓN DINÁMICA DE MÓDULOS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const cantidadInput = document.getElementById('cantidad_modulos');
            const contenedor = document.getElementById('contenedor-modulos');
            const contador = document.getElementById('contador-modulos');

            const docentes = @json(
                $docentes->map(function ($docente) {
                    return [
                        'id' => $docente->id_docente,
                        'nombre' => trim(
                            $docente->apellido_p . ' ' .
                            $docente->apellido_m . ' ' .
                            $docente->nombre
                        ),
                    ];
                })->values()
            );

            const datosModulos = {};

            function guardarDatosActuales() {
                contenedor.querySelectorAll('.modulo-item').forEach((item, index) => {
                    datosModulos[index] = {
                        nombre: item.querySelector('[name$="[nombre]"]')?.value || '',
                        fecha_inicio: item.querySelector('[name$="[fecha_inicio]"]')?.value || '',
                        fecha_fin: item.querySelector('[name$="[fecha_fin]"]')?.value || '',
                        id_docente: item.querySelector('[name$="[id_docente]"]')?.value || '',
                        fecha_inicio_descanso: item.querySelector('[name$="[fecha_inicio_descanso]"]')?.value || '',
                        fecha_fin_descanso: item.querySelector('[name$="[fecha_fin_descanso]"]')?.value || ''
                    };
                });
            }

            function escaparHTML(valor) {
                return String(valor ?? '').replace(/[&<>"']/g, function (caracter) {
                    return {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    }[caracter];
                });
            }

            function generarOpcionesDocentes(idSeleccionado) {
                let opciones = `
                    <option value="">
                        Sin docente asignado
                    </option>
                `;

                docentes.forEach(function (docente) {
                    const seleccionado =
                        String(docente.id) === String(idSeleccionado)
                            ? 'selected'
                            : '';

                    opciones += `
                        <option value="${escaparHTML(docente.id)}" ${seleccionado}>
                            ${escaparHTML(docente.nombre)}
                        </option>
                    `;
                });

                return opciones;
            }

            function generarModulos() {

                guardarDatosActuales();

                let cantidad = parseInt(cantidadInput.value) || 1;
                cantidad = Math.min(100, Math.max(1, cantidad));

                contador.textContent = cantidad + (cantidad === 1 ? ' módulo' : ' módulos');

                contenedor.innerHTML = '';

                for (let i = 0; i < cantidad; i++) {

                    const datos = datosModulos[i] || {};

                    const moduloHTML = `
                        <div class="modulo-item border border-gray-200 rounded-xl p-5 space-y-5">

                            <div class="flex items-center justify-between">
                                <h5 class="font-bold text-brand-green">
                                    Módulo ${i + 1}
                                </h5>

                                <span class="text-xs text-gray-400">
                                    Módulo ${i + 1}
                                </span>
                            </div>

                            <div>
                                <label class="form-label-bold text-brand-green"">
                                    Nombre del módulo *
                                </label>

                                <input
                                    type="text"
                                    name="modulos[${i}][nombre]"
                                    value="${escaparHTML(datos.nombre || '')}"
                                    required
                                    maxlength="255"
                                    placeholder="Ej. Fundamentos y conceptos generales"
                                    class="form-input-pill border-brand-green border"
                                >

                                <input
                                    type="hidden"
                                    name="modulos[${i}][orden]"
                                    value="${i + 1}"
                                >
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                <div>
                                    <label class="form-label-bold text-brand-green"">
                                        Fecha de inicio *
                                    </label>

                                    <input
                                        type="date"
                                        name="modulos[${i}][fecha_inicio]"
                                        value="${escaparHTML(datos.fecha_inicio || '')}"
                                        required
                                        class="form-input-pill border-brand-green border w-sm"
                                    >
                                </div>

                                <div>
                                    <label class="form-label-bold text-brand-green"">
                                        Fecha de fin *
                                    </label>

                                    <input
                                        type="date"
                                        name="modulos[${i}][fecha_fin]"
                                        value="${escaparHTML(datos.fecha_fin || '')}"
                                        required
                                        class="form-input-pill border-brand-green border w-sm"
                                    >
                                </div>

                            </div>

                            <div>
                                <label class="form-label-bold text-brand-green"">
                                    Docente responsable del módulo
                                </label>

                                <select
                                    name="modulos[${i}][id_docente]"
                                    class="w-full rounded-lg border-gray-300 focus:border-brand-green focus:ring-brand-green"
                                >
                                    ${generarOpcionesDocentes(datos.id_docente || '')}
                                </select>
                            </div>

                            <div class="border-t border-gray-100 pt-4">

                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                                        <i class="fas fa-calendar-minus"></i>
                                    </div>

                                    <div>
                                        <h6 class="text-sm font-bold text-gray-700">
                                            Periodo de descanso
                                        </h6>
                                        <p class="text-xs text-gray-400">
                                            Opcional. Define cuándo no habrá clases.
                                        </p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                    <div>
                                        <label class="form-label-bold text-brand-green"">
                                            Inicio del descanso
                                        </label>

                                        <input
                                            type="date"
                                            name="modulos[${i}][fecha_inicio_descanso]"
                                            value="${escaparHTML(datos.fecha_inicio_descanso || '')}"
                                            class="form-input-pill border-brand-green border w-sm"
                                        >
                                    </div>

                                    <div>
                                        <label class="form-label-bold text-brand-green"">
                                            Fin del descanso
                                        </label>

                                        <input
                                            type="date"
                                            name="modulos[${i}][fecha_fin_descanso]"
                                            value="${escaparHTML(datos.fecha_fin_descanso || '')}"
                                            min="${escaparHTML(datos.fecha_inicio_descanso || '')}"
                                            class="form-input-pill border-brand-green border w-sm"
                                        >
                                    </div>

                                </div>

                                <p class="text-xs text-gray-400 mt-3">
                                    Las sesiones que coincidan con este intervalo se omitirán al generar las clases.
                                </p>

                            </div>

                        </div>
                    `;

                    contenedor.insertAdjacentHTML('beforeend', moduloHTML);
                }

                configurarFechasDescanso();
            }

            function configurarFechasDescanso() {

                contenedor.querySelectorAll('.modulo-item').forEach(function (item) {

                    const inicio = item.querySelector('.fecha-inicio-descanso');
                    const fin = item.querySelector('.fecha-fin-descanso');

                    if (!inicio || !fin) return;

                    function actualizarMinimo() {
                        fin.min = inicio.value || '';

                        if (inicio.value && fin.value && fin.value < inicio.value) {
                            fin.value = '';
                        }
                    }

                    inicio.addEventListener('change', actualizarMinimo);
                    actualizarMinimo();
                });
            }

            // Conserva los datos iniciales que llegaron desde Laravel.
            guardarDatosActuales();

            configurarFechasDescanso();

            cantidadInput.addEventListener('input', generarModulos);
            cantidadInput.addEventListener('change', generarModulos);

        });
    </script>

</x-layout-dashboard>
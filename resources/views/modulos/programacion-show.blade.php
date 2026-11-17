<x-layout-dashboard :usuario="$usuario">

    <div class="mx-auto">

        <x-page-header titulo="Programación académica">
        </x-page-header>
        <div class="p-6">
            <div class="mt-6">

                <a href="{{ route('programs.show', $curso->id_curso) }}"
                    class="text-sm text-white bg-brand-green hover:text-brand-gold p-2 rounded-sm">
                    ← Volver
                </a>

                <br> <br>
                <div class="flex flex-wrap gap-3">

                    <a href="{{ route('programacion.pdf', $curso->id_curso) }}" class="btn-gold">
                        <i class="fas fa-file-pdf"></i>
                        Generar PDF
                    </a>

                    <a href="{{ route('programacion.edit', $curso->id_curso) }}" class="btn-gold">
                        <i class="fas fa-edit"></i>
                        Editar configuración
                    </a>

                </div>


                @if(session('success'))
                    <div class="mt-5 p-4 rounded-lg bg-green-50 text-green-700 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if($programacion)
                            <h3 class="mt-6 font-bold text-gray-900">
                                1. INFORMACIÓN GENERAL
                            </h3>
                            {{-- INFORMACIÓN GENERAL --}}
                            <ul class="mt-4 space-y-3 text-sm text-gray-700 list-disc pl-6">

                                <li>
                                    <strong>Nombre del Programa:</strong>
                                    {{ $curso->nombre }}
                                </li>

                                <li>
                                    <strong>Coordinador del programa:</strong>
                                    {{ $programacion->coordinador
                    ? $programacion->coordinador->nombre . ' ' .
                    $programacion->coordinador->apellido_p . ' ' .
                    $programacion->coordinador->apellido_m
                    : 'Sin coordinador asignado' }}
                                </li>

                                <li>
                                    <strong>Carga Horaria Total:</strong>
                                    {{ $programacion->carga_horaria ?? 'No definida' }}
                                    @if($programacion->carga_horaria) horas @endif
                                </li>

                                <li>
                                    <strong>Horario General:</strong>
                                    @if($programacion->hora_inicio && $programacion->hora_fin)
                                        {{ substr($programacion->hora_inicio, 0, 5) }}
                                        a {{ substr($programacion->hora_fin, 0, 5) }}
                                    @else
                                        No definido
                                    @endif
                                </li>

                                <li>
                                    <strong>Duración:</strong>
                                    {{ $programacion->duracion_meses }} meses -
                                    {{ $programacion->cantidad_modulos }} módulos
                                </li>

                                <li>
                                    <strong>Certificado por:</strong>
                                    {{ $curso->institucion->nombre ?? 'No definida' }}
                                </li>

                                <li>
                                    <strong>Días de Clase:</strong>
                                    {{ collect($programacion->dias_clase ?? [])
                    ->map(fn($dia) => $dias[$dia] ?? ucfirst($dia))
                    ->implode(', ') ?: 'No definidos' }}
                                </li>

                            </ul>

                            @php
                                $modulos = $curso->modulos
                                    ->whereNotNull('orden')
                                    ->sortBy('orden')
                                    ->values();
                            @endphp

                            @php
                                $aRomanos = function ($numero) {
                                    $valores = [
                                        1000 => 'M',
                                        900 => 'CM',
                                        500 => 'D',
                                        400 => 'CD',
                                        100 => 'C',
                                        90 => 'XC',
                                        50 => 'L',
                                        40 => 'XL',
                                        10 => 'X',
                                        9 => 'IX',
                                        5 => 'V',
                                        4 => 'IV',
                                        1 => 'I',
                                    ];

                                    $resultado = '';

                                    foreach ($valores as $valor => $simbolo) {
                                        while ($numero >= $valor) {
                                            $resultado .= $simbolo;
                                            $numero -= $valor;
                                        }
                                    }

                                    return $resultado;
                                };
                            @endphp
                            <h3 class="mt-6 font-bold text-gray-900">
                                2. CRONOGRAMA ACADÉMICO DEL DIPLOMADO
                            </h3>
                            <div class="mt-7 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

                                @if($modulos->isNotEmpty())

                                    <div class="overflow-x-auto">

                                        <table class="w-full text-sm text-left">

                                            <thead class="bg-brand-green text-white text-xs uppercase">
                                                <tr>
                                                    <th class="px-5 py-4 whitespace-nowrap">Módulo</th>
                                                    <th class="px-5 py-4 min-w-64">Contenido académico</th>
                                                    <th class="px-5 py-4 whitespace-nowrap">Fecha de inicio</th>
                                                    <th class="px-5 py-4 whitespace-nowrap">Fecha de fin</th>
                                                    <th class="px-5 py-4 whitespace-nowrap">Descanso modular</th>
                                                    <th class="px-5 py-4 min-w-48">Docente asignado</th>
                                                </tr>
                                            </thead>

                                            <tbody class="divide-y divide-gray-100">

                                                @foreach($modulos as $modulo)

                                                    <tr class="hover:bg-gray-50 transition">

                                                        <td class="px-5 py-4 font-semibold text-gray-800 whitespace-nowrap">
                                                            <span class="text-brand-green mr-2 font-black">
                                                                {{ $aRomanos($modulo->orden) }}.
                                                            </span>
                                                        </td>

                                                        <td class="px-5 py-4 text-gray-600">
                                                            <span class="font-medium text-gray-800">
                                                                {{ $modulo->nombre ?: 'Pendiente' }}
                                                            </span>
                                                        </td>

                                                        <td class="px-5 py-4 text-gray-600 whitespace-nowrap">
                                                            {{ $modulo->fecha_inicio ? \Carbon\Carbon::parse($modulo->fecha_inicio)->format('d/m/Y') : 'Pendiente' }}
                                                        </td>

                                                        <td class="px-5 py-4 text-gray-600 whitespace-nowrap">
                                                            {{ $modulo->fecha_fin ? \Carbon\Carbon::parse($modulo->fecha_fin)->format('d/m/Y') : 'Pendiente' }}
                                                        </td>

                                                        <td class="px-5 py-4 text-gray-600 whitespace-nowrap">
                                                            @if($modulo->fecha_inicio_descanso && $modulo->fecha_fin_descanso)
                                                                <div class="font-medium text-gray-800">
                                                                    {{ $modulo->fecha_inicio_descanso->format('d/m/Y') }}
                                                                </div>
                                                                <div class="text-xs text-gray-400 my-1">hasta</div>
                                                                <div class="font-medium text-gray-800">
                                                                    {{ $modulo->fecha_fin_descanso->format('d/m/Y') }}
                                                                </div>
                                                            @else
                                                                <span class="text-gray-400 italic">Sin descanso</span>
                                                            @endif
                                                        </td>

                                                        <td class="px-5 py-4 text-gray-600">
                                                            @if($modulo->docente)
                                                                <span class="font-medium text-gray-800">
                                                                    {{ $modulo->docente->nombre }}
                                                                    {{ $modulo->docente->apellido_p }}
                                                                    {{ $modulo->docente->apellido_m }}
                                                                </span>
                                                            @else
                                                                <span class="text-gray-400 italic">
                                                                    Sin asignar
                                                                </span>
                                                            @endif
                                                        </td>

                                                    </tr>

                                                @endforeach

                                            </tbody>

                                        </table>

                                    </div>

                                @else

                                    <div class="p-10 text-center">
                                        <div class="text-gray-300 text-4xl mb-3">
                                            <i class="fas fa-layer-group"></i>
                                        </div>

                                        <p class="font-semibold text-gray-700">
                                            Todavía no hay módulos registrados.
                                        </p>

                                        <p class="text-sm text-gray-500 mt-1">
                                            La estructura modular aparecerá aquí cuando se registre.
                                        </p>
                                    </div>

                                @endif

                            </div>



                            <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                                <h3 class="font-bold text-gray-900">
                                    3. CRONOGRAMA DE LAS MASTERCLASS
                                </h3>

                                <a href="{{ route('programacion.masterclass.index', $curso->id_curso) }}"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-brand-green text-white hover:opacity-90 font-semibold text-sm transition">

                                    <i class="fas fa-cog"></i>
                                    Gestionar Masterclass

                                </a>

                            </div>


                            <div class="mt-7 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

                                @if($masterclasses->isNotEmpty())

                                    <div class="overflow-x-auto">

                                        <table class="w-full text-sm text-left">

                                            <thead class="bg-brand-green text-white text-xs uppercase">
                                                <tr>
                                                    <th class="px-5 py-4 whitespace-nowrap">N.º</th>
                                                    <th class="px-5 py-4 min-w-64">Título Masterclass</th>
                                                    <th class="px-5 py-4 whitespace-nowrap">Fecha de inicio</th>
                                                    <th class="px-5 py-4 whitespace-nowrap">Día</th>
                                                    <th class="px-5 py-4 min-w-48">Docente asignado</th>
                                                </tr>
                                            </thead>

                                            <tbody class="divide-y divide-gray-100">

                                                @foreach($masterclasses as $masterclass)

                                                    @php
                                                        $fechaMasterclass = \Carbon\Carbon::parse($masterclass->fecha_inicio)
                                                            ->locale('es');
                                                    @endphp

                                                    <tr class="hover:bg-gray-50 transition">

                                                        <td class="px-5 py-4 font-black text-brand-green whitespace-nowrap">
                                                            {{ $loop->iteration }}
                                                        </td>

                                                        <td class="px-5 py-4 font-semibold text-gray-800">
                                                            {{ $masterclass->titulo }}
                                                        </td>

                                                        <td class="px-5 py-4 text-gray-600 whitespace-nowrap">
                                                            {{ $fechaMasterclass->format('d/m/Y') }}
                                                        </td>

                                                        <td class="px-5 py-4 text-gray-600">
                                                            {{ ucfirst($fechaMasterclass->translatedFormat('l')) }}
                                                        </td>

                                                        <td class="px-5 py-4 text-gray-600">
                                                            @if($masterclass->docente)
                                                                <span class="font-medium text-gray-800">
                                                                    {{ $masterclass->docente->nombre }}
                                                                    {{ $masterclass->docente->apellido_p }}
                                                                    {{ $masterclass->docente->apellido_m }}
                                                                </span>
                                                            @else
                                                                <span class="text-gray-400 italic">
                                                                    Sin asignar
                                                                </span>
                                                            @endif
                                                        </td>

                                                    </tr>

                                                @endforeach

                                            </tbody>

                                        </table>

                                    </div>

                                @else

                                    <div class="p-10 text-center">

                                        <div class="text-gray-300 text-4xl mb-3">
                                            <i class="fas fa-chalkboard-teacher"></i>
                                        </div>

                                        <p class="font-semibold text-gray-700">
                                            Todavía no hay Masterclass registradas.
                                        </p>

                                        <p class="text-sm text-gray-500 mt-1">
                                            El cronograma de clases magistrales aparecerá aquí cuando se registren.
                                        </p>

                                    </div>

                                @endif

                            </div>
                @else

                    <div class="mt-7 bg-white border border-gray-200 rounded-xl p-10 text-center shadow-sm">

                        <div class="text-4xl text-gray-300 mb-4">
                            <i class="fas fa-calendar-alt"></i>
                        </div>

                        <h3 class="text-lg font-bold text-gray-800">
                            Programación académica pendiente
                        </h3>

                        <p class="text-sm text-gray-500 mt-2 max-w-md mx-auto">
                            Este diplomado todavía no cuenta con una configuración académica.
                            Crea su programación para comenzar a organizar los módulos y las clases.
                        </p>

                        <a href="{{ route('programacion.edit', $curso->id_curso) }}"
                            class="inline-flex mt-6 px-5 py-3 rounded-lg bg-brand-green text-white font-semibold text-sm hover:opacity-90">
                            Crear programación
                        </a>

                    </div>

                @endif

            </div>
        </div>

    </div>
    <style>
        @media print {

            /* Ocultar navegación y elementos del dashboard */
            aside,
            nav,
            header,
            .sidebar,
            .no-print {
                display: none !important;
            }

            /* Mostrar el contenido sin alterar su diseño */
            body {
                background: white !important;
            }

            .p-6 {
                padding: 0 !important;
            }

            .overflow-x-auto {
                overflow: visible !important;
            }

            table {
                width: 100% !important;
            }

            a[href*="programacion.pdf"],
            a[href*="programacion/editar"],
            a[href*="masterclass"] {
                display: none !important;
            }

            .shadow-sm {
                box-shadow: none !important;
            }

            @page {
                size: A4 landscape;
                margin: 12mm;
            }
        }
    </style>

</x-layout-dashboard>
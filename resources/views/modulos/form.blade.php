
@php
    $editando = isset($modulo);
    $programacion = $curso->programacionAcademica;

    $diasSemana = [
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
        $editando
            ? ($modulo->dias_clase ?? [])
            : ($programacion?->dias_clase ?? [])
    );

    $fechaInicioDescanso = old(
        'fecha_inicio_descanso',
        $editando && $modulo->fecha_inicio_descanso
            ? $modulo->fecha_inicio_descanso->format('Y-m-d')
            : ''
    );

    $fechaFinDescanso = old(
        'fecha_fin_descanso',
        $editando && $modulo->fecha_fin_descanso
            ? $modulo->fecha_fin_descanso->format('Y-m-d')
            : ''
    );
@endphp

<x-layout-dashboard :usuario="$usuario">
    <div class="max-w-5xl mx-auto space-y-6">

        <x-page-header titulo="{{ $editando ? 'Editar módulo' : 'Crear módulo' }}" />

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <p class="text-sm text-gray-500">Programa académico</p>
            <h2 class="text-xl font-bold text-brand-green">
                {{ $curso->nombre }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Configura el contenido y la planificación de este módulo.
            </p>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-lg bg-red-50 text-red-700 border border-red-200">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ $editando
                ? route('programacion.modulos.update', [$curso->id_curso, $modulo->id_modulo])
                : route('programacion.modulos.store', $curso->id_curso) }}"
            class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-6"
        >
            @csrf

            @if($editando)
                @method('PUT')
            @endif

            <div>
                <h3 class="font-bold text-brand-green text-lg">
                    Información del módulo
                </h3>
                <p class="text-sm text-gray-500 mt-1">
                    Datos generales y contenido académico.
                </p>
            </div>

            {{-- INFORMACIÓN GENERAL --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Orden del módulo *
                    </label>

                    <input
                        type="number"
                        name="orden"
                        min="1"
                        max="{{ $programacion?->cantidad_modulos ?? 100 }}"
                        value="{{ old('orden', $modulo->orden ?? ($curso->modulos->count() + 1)) }}"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-brand-green focus:ring-brand-green"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre del módulo *
                    </label>

                    <input
                        type="text"
                        name="nombre"
                        value="{{ old('nombre', $modulo->nombre ?? '') }}"
                        required
                        maxlength="255"
                        placeholder="Escribe el nombre del módulo"
                        class="w-full rounded-lg border-gray-300 focus:border-brand-green focus:ring-brand-green"
                    >
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Contenido académico
                    </label>

                    <textarea
                        name="contenido_academico"
                        rows="5"
                        placeholder="Describe los temas y contenidos que se desarrollarán..."
                        class="w-full rounded-lg border-gray-300 focus:border-brand-green focus:ring-brand-green"
                    >{{ old('contenido_academico', $modulo->contenido_academico ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Docente responsable
                    </label>

                    <select
                        name="id_docente"
                        class="w-full rounded-lg border-gray-300 focus:border-brand-green focus:ring-brand-green"
                    >
                        <option value="">Sin asignar</option>

                        @foreach($docentes as $docente)
                            <option
                                value="{{ $docente->id_docente }}"
                                @selected(old('id_docente', $modulo->id_docente ?? '') == $docente->id_docente)
                            >
                                {{ $docente->nombre }}
                                {{ $docente->apellido_p }}
                                {{ $docente->apellido_m }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            {{-- PLANIFICACIÓN DE SESIONES --}}
            <div class="border-t border-gray-100 pt-6">
                <h3 class="font-bold text-brand-green text-lg">
                    Planificación de sesiones
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Define cuándo comenzará el módulo y cómo se distribuirán sus clases.
                </p>
            </div>

            @if(!$editando)

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de inicio *
                        </label>

                        <input
                            type="date"
                            name="fecha_inicio"
                            value="{{ old('fecha_inicio') }}"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-brand-green focus:ring-brand-green"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Cantidad de sesiones *
                        </label>

                        <input
                            type="number"
                            name="cantidad_sesiones"
                            min="1"
                            max="100"
                            value="{{ old('cantidad_sesiones', 4) }}"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-brand-green focus:ring-brand-green"
                        >
                    </div>

                </div>

                {{-- DÍAS DE CLASE --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Días de clase *
                    </label>

                    <div class="flex flex-wrap gap-3">

                        @foreach($diasSemana as $valor => $etiqueta)
                            <label class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">

                                <input
                                    type="checkbox"
                                    name="dias_clase[]"
                                    value="{{ $valor }}"
                                    @checked(in_array($valor, $diasSeleccionados))
                                    class="rounded text-brand-green focus:ring-brand-green"
                                >

                                <span class="text-sm text-gray-700">
                                    {{ $etiqueta }}
                                </span>

                            </label>
                        @endforeach

                    </div>
                </div>

                {{-- HORARIOS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Hora de inicio *
                        </label>

                        <input
                            type="time"
                            name="hora_inicio"
                            value="{{ old('hora_inicio', $programacion?->hora_inicio ? substr($programacion->hora_inicio, 0, 5) : '19:00') }}"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-brand-green focus:ring-brand-green"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Hora de finalización *
                        </label>

                        <input
                            type="time"
                            name="hora_fin"
                            value="{{ old('hora_fin', $programacion?->hora_fin ? substr($programacion->hora_fin, 0, 5) : '21:00') }}"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-brand-green focus:ring-brand-green"
                        >
                    </div>

                </div>

            @else

                <div class="rounded-lg bg-amber-50 border border-amber-200 p-4 text-sm text-amber-800">
                    Estás editando la información general del módulo. Las sesiones existentes
                    se conservan para no perder posibles modificaciones individuales de fechas y horarios.
                    La reprogramación de sesiones la implementaremos por separado.
                </div>

            @endif

            {{-- PERIODO DE DESCANSO --}}
            <div class="border-t border-gray-100 pt-6">

                <div class="flex items-start gap-3 mb-5">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 shrink-0">
                        <i class="fas fa-calendar-minus"></i>
                    </div>

                    <div>
                        <h3 class="font-bold text-brand-green text-lg">
                            Periodo de descanso
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Establece un único intervalo en el que no se programarán clases.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Inicio del descanso
                        </label>

                        <input
                            type="date"
                            id="fecha_inicio_descanso"
                            name="fecha_inicio_descanso"
                            value="{{ $fechaInicioDescanso }}"
                            class="w-full rounded-lg border-gray-300 focus:border-brand-green focus:ring-brand-green"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Fin del descanso
                        </label>

                        <input
                            type="date"
                            id="fecha_fin_descanso"
                            name="fecha_fin_descanso"
                            value="{{ $fechaFinDescanso }}"
                            min="{{ $fechaInicioDescanso }}"
                            class="w-full rounded-lg border-gray-300 focus:border-brand-green focus:ring-brand-green"
                        >
                    </div>

                </div>

                <div class="mt-4 rounded-lg bg-gray-50 border border-gray-100 p-3">
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1 text-brand-green"></i>
                        Puedes dejar ambas fechas vacías si el módulo no tendrá descanso.
                        Las sesiones que coincidan con este periodo se omitirán al generar las clases.
                    </p>
                </div>

            </div>

            {{-- BOTONES --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-gray-100 pt-6">

                <a
                    href="{{ route('programacion.modulos.index', $curso->id_curso) }}"
                    class="px-5 py-3 rounded-lg border border-gray-200 text-gray-700 text-center hover:bg-gray-50"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="px-6 py-3 rounded-lg bg-brand-green text-white font-semibold hover:opacity-90 transition"
                >
                    {{ $editando ? 'Guardar cambios' : 'Crear módulo y generar sesiones' }}
                </button>

            </div>

        </form>
    </div>

    {{-- VALIDACIÓN VISUAL DE FECHAS DE DESCANSO --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const inicio = document.getElementById('fecha_inicio_descanso');
            const fin = document.getElementById('fecha_fin_descanso');

            function actualizarFechaMinima() {
                fin.min = inicio.value || '';

                if (inicio.value && fin.value && fin.value < inicio.value) {
                    fin.value = '';
                }
            }

            inicio.addEventListener('change', actualizarFechaMinima);

            actualizarFechaMinima();

        });
    </script>

</x-layout-dashboard>

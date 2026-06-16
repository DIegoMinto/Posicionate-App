<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edición de Programa - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <x-page-header titulo="Editar Programa: {{ $curso->nombre }}">
            <a href="{{ route('programs.index') }}" class="btn-back">
                &larr; Volver
            </a>
        </x-page-header>

        <div class="p-6">
            <form action="{{ route('programs.update', $curso->id_curso) }}" method="POST" enctype="multipart/form-data"
                class="space-y-8">
                @csrf
                @method('PATCH')

                <div class="bg-white rounded-sm border-1 border-brand-green shadow-md overflow-hidden">
                    <div class="bg-brand-green p-3">
                        <h3
                            class="text-white font-sans font-bold uppercase text-sm tracking-widest flex items-center gap-2">
                            <i class="fas fa-graduation-cap"></i> Información General del Curso
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label class="form-label-bold text-black">Nombre del Curso</label>
                            <input type="text" name="nombre" value="{{ $curso->nombre }}"
                                class="form-input-pill border-2 border-brand-gold w-full" required>
                        </div>
                        <div>
                            <label class="form-label-bold text-black">Estado</label>
                            <select name="estado" class="form-select-pill border-2 border-brand-gold">
                                <option value="Activo" {{ $curso->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Pendiente" {{ $curso->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente
                                </option>
                                <option value="Finalizado" {{ $curso->estado == 'Finalizado' ? 'selected' : '' }}>
                                    Finalizado</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label-bold text-black">Código Curso</label>
                            <input type="text" name="codigo_curso" value="{{ $curso->codigo_curso }}"
                                class="form-input-pill border-2 border-brand-gold w-full">
                        </div>
                        <div>
                            <label class="form-label-bold text-black">Docente Responsable</label>
                            <select name="id_docente" class="form-select-pill border-2 border-brand-gold mb-2">
                                <option value="" {{ is_null($curso->id_docente) ? 'selected' : '' }}>
                                    -- Sin Docente Asignado --
                                </option>
                                @foreach($docentes as $docente)
                                    <option value="{{ $docente->id_docente }}" {{ $curso->id_docente == $docente->id_docente ? 'selected' : '' }}>
                                        {{ $docente->nombre }} {{ $docente->apellido_p }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Docentes adicionales ya guardados --}}
                            <div id="docentes-adicionales-container" class="space-y-2">
                                @foreach($curso->docentesAdicionales as $da)
                                    <div class="flex gap-2 items-center">
                                        <select name="docentes_adicionales[]"
                                            class="form-select-pill border-2 border-brand-gold flex-1 mt-2">
                                            <option value="">Seleccionar docente adicional...</option>
                                            @foreach($docentes as $doc)
                                                <option value="{{ $doc->id_docente }}" {{ $da->id_docente == $doc->id_docente ? 'selected' : '' }}>
                                                    {{ $doc->nombre }} {{ $doc->apellido_p }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" onclick="this.parentElement.remove()"
                                            class="text-red-400 font-bold px-2 mt-2">✕</button>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" onclick="agregarDocente()"
                                class="text-[10px] font-bold text-black uppercase hover:text-brand-gold transition-colors cursor-pointer mt-2">
                                + Agregar Docente Adicional
                            </button>
                        </div>
                        <div>
                            <label class="form-label-bold text-black">Sede</label>
                            <select name="id_sede" class="form-select-pill border-2 border-brand-gold">
                                <option value="" {{ is_null($curso->id_sede) ? 'selected' : '' }}>

                                </option>
                                @foreach($sedes as $sede)
                                    <option value="{{ $sede->id_sede }}" {{ $curso->id_sede == $sede->id_sede ? 'selected' : '' }}>
                                        {{ $sede->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label-bold text-black">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" value="{{ $curso->fecha_inicio }}"
                                class="form-input-pill border-2 border-brand-gold w-full">
                        </div>
                        <div>
                            <label class="form-label-bold text-black">Fecha Fin</label>
                            <input type="date" name="fecha_fin" value="{{ $curso->fecha_fin }}"
                                class="form-input-pill border-2 border-brand-gold w-full">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-sm border-1 border-brand-green space-y-4 shadow-md ">
                        <div class="bg-brand-green p-3">
                            <h3
                                class="text-white font-sans font-bold uppercase text-sm tracking-widest flex items-center gap-2">
                                <i class="fas fa-university"></i> Datos de la Institución
                            </h3>
                        </div>
                        <div class="p-4">
                            <div>
                                <label class="form-label-bold font-sans font-bold text-black">Institución
                                    Perteneciente:</label>
                                <select name="id_institucion" class="form-select-pill border-1 border-brand-gold">
                                    <option value="" {{ is_null($curso->id_institucion) ? 'selected' : '' }}></option>
                                    @foreach($instituciones as $inst)
                                        <option value="{{ $inst->id_institucion }}" {{ $curso->id_institucion == $inst->id_institucion ? 'selected' : '' }}>
                                            {{ $inst->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex justify-center items-center mt-6">
                                @if($curso->institucion && $curso->institucion->imagen)
                                    <div class="flex flex-col items-center">
                                        <p class="text-[9px] uppercase text-gray-400 mb-2 font-bold tracking-tighter">Logo
                                            Institucional</p>
                                        <img src="{{ $curso->institucion->imagen }}"
                                            class="h-20 object-contain border border-gray-100 p-2 bg-gray-50 rounded">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-sm border-1 border-brand-green shadow-md">
                        <div class="bg-brand-green p-3">
                            <h3
                                class="text-white font-sans font-bold uppercase text-sm tracking-widest flex items-center gap-2">
                                <i class="fas fa-image"></i> Imagen del Formulario Promocional
                            </h3>
                        </div>
                        <div class="p-4">
                            <div class="grid gap-4">
                                <div class="flex justify-center items-center">
                                    @if($curso->imagen_formulario)
                                        <img src="{{ $curso->imagen_formulario }}"
                                            class="h-24 w-full object-cover border border-gray-100 p-1 rounded shadow-sm">
                                    @else
                                        <div
                                            class="h-24 w-full bg-gray-50 flex items-center justify-center border border-dashed border-gray-200 rounded">
                                            <span class="text-xs text-gray-400 uppercase font-sans">Sin imagen
                                                asignada</span>
                                        </div>
                                    @endif
                                </div>
                                <div
                                    class="text-center p-3 border-2 border-dashed border-gray-100 rounded flex flex-col justify-center items-center bg-gray-50/50">
                                    <p class="font-sans font-bold text-xs mb-1 text-gray-700">Actualizar Imagen</p>
                                    <label class="cursor-pointer w-full text-center">
                                        <span id="image-text"
                                            class="text-[11px] text-brand-green font-medium block mb-1 underline">Seleccionar
                                            archivo nuevo</span>
                                        <input type="file" name="imagen_formulario" class="hidden" accept="image/*"
                                            onchange="document.getElementById('image-text').innerText = this.files[0].name">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-sm border-2 border-gray-800 shadow-md">
                    <div class="bg-brand-green p-3">
                        <h3
                            class="text-white font-sans font-bold uppercase text-sm tracking-widest flex items-center gap-2">
                            <i class="fas fa-cubes"></i> Cronograma de Módulos
                        </h3>
                    </div>
                    <div class="overflow-x-auto p-4">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] uppercase border-b text-left text-gray-500">
                                    <th class="pb-2 pl-3">Nombre del Módulo</th>
                                    <th class="pb-2">Fecha Inicio</th>
                                    <th class="pb-2">Fecha Fin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-left">
                                @forelse($curso->modulos as $modulo)
                                    <tr>
                                        <td class="p-3">
                                            <input type="text" name="modulos[{{ $modulo->id_modulo }}][nombre]"
                                                value="{{ $modulo->nombre }}"
                                                class="form-input-pill py-1 w-full border-brand-gold border-2" required>
                                        </td>
                                        <td class="p-3">
                                            <input type="date" name="modulos[{{ $modulo->id_modulo }}][fecha_inicio]"
                                                value="{{ $modulo->fecha_inicio ? \Carbon\Carbon::parse($modulo->fecha_inicio)->format('Y-m-d') : '' }}"
                                                class="form-input-pill py-1 w-full border-brand-gold border-2">
                                        </td>
                                        <td class="p-3">
                                            <input type="date" name="modulos[{{ $modulo->id_modulo }}][fecha_fin]"
                                                value="{{ $modulo->fecha_fin ? \Carbon\Carbon::parse($modulo->fecha_fin)->format('Y-m-d') : '' }}"
                                                class="form-input-pill py-1 w-full border-brand-gold border-2">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-6 text-center text-gray-400 italic text-xs uppercase">
                                            No hay módulos registrados para este curso.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="btn-gold">
                        Guardar Cambios Totales
                    </button>
                </div>
            </form>
        </div>
    </x-layout-dashboard>
    <script>
        function agregarDocente() {
            const container = document.getElementById('docentes-adicionales-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2 items-center';
            div.innerHTML = `
            <select name="docentes_adicionales[]"
                class="form-select-pill border-2 border-brand-gold flex-1 mt-2">
                <option value="">Seleccionar docente adicional...</option>
                @foreach($docentes as $doc)
                    <option value="{{ $doc->id_docente }}">
                        {{ $doc->nombre }} {{ $doc->apellido_p }}
                    </option>
                @endforeach
            </select>
            <button type="button" onclick="this.parentElement.remove()"
                class="text-red-400 font-bold px-2 mt-2">✕</button>
        `;
            container.appendChild(div);
        }
    </script>
</body>

</html>
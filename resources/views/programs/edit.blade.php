<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edición de Programa - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <x-page-header titulo="Editar Programa: {{ $curso->nombre }}">
            <a href="{{ route('programs.show', ['id' => $curso->id_curso]) }}"
                class="text-gray-500 hover:text-brand-gold font-bold text-xs uppercase flex items-center gap-2">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </x-page-header>

        <div class="p-6">
            <form action="{{ route('programs.update', $curso->id_curso) }}" method="POST" enctype="multipart/form-data"
                class="space-y-8">
                @csrf
                @method('PUT')

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
                                <option value="activo" {{ $curso->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="en proceso" {{ $curso->estado == 'en proceso' ? 'selected' : '' }}>En
                                    Proceso</option>
                                <option value="finalizado" {{ $curso->estado == 'finalizado' ? 'selected' : '' }}>
                                    Finalizado</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label-bold text-black">Código Curso</label>
                            <input type="text" name="codigo_curso" value="{{ $curso->codigo_curso }}"
                                class="form-input-pill border-2 border-brand-gold w-full">
                        </div>
                        <div>
                            <label class="form-label-bold text-black">Asignar Docente</label>
                            <select name="id_docente" class="form-select-pill border-2 border-brand-gold">
                                <option value="" {{ is_null($curso->id_docente) ? 'selected' : '' }}>
                                    -- Sin Docente Asignado --
                                </option>
                                @foreach($docentes as $docente)
                                    <option value="{{ $docente->id_docente }}" {{ $curso->id_docente == $docente->id_docente ? 'selected' : '' }}>
                                        {{ $docente->nombre }} {{ $docente->apellido }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label-bold text-black">Sede</label>
                            <select name="id_sede" class="form-select-pill border-2 border-brand-gold">
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
                                <i class="fas fa-graduation-cap"></i> Datos de la Institución
                            </h3>
                        </div>
                        <div class="p-4">
                            <div>
                                <label class="form-label-bold font-sans font-bold text-black">Institución
                                    Perteneciente:</label>
                                <select name="id_institucion" class="form-select-pill border-1 border-brand-gold">
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

                    <div class="rounded-sm border-1 border-brand-green shadow-md">
                        <div class="bg-brand-green p-3">
                            <h3
                                class="text-white font-sans font-bold uppercase text-sm tracking-widest flex items-center gap-2">
                                <i class="fas fa-qrcode"></i> Código QR
                            </h3>
                        </div>
                        <div class="p-4">
                            <div class="grid gap-4">
                                <div class="flex justify-center items-center">
                                    @if($curso->codigo_qr)
                                        <img src="{{ asset('storage/' . $curso->codigo_qr) }}"
                                            class="h-16 w-16 object-contain border border-gray-100 p-1">
                                    @else
                                        <div
                                            class="h-16 w-16 bg-gray-50 flex items-center justify-center border border-dashed border-gray-200">
                                            <span class="text-[8px] text-gray-400">SIN QR</span>
                                        </div>
                                    @endif
                                </div>
                                <div
                                    class="text-center p-2 border-2 border-dashed border-gray-100 rounded flex flex-col justify-center items-center">
                                    <p class="font-sans font-bold text-xs mb-2">Cambiar QR</p>
                                    <label class="cursor-pointer">
                                        <span id="qr-text" class="text-[10px] text-gray-500 block mb-1">Seleccionar
                                            archivo</span>
                                        <input type="file" name="codigo_qr" class="text-[10px] w-full"
                                            onchange="document.getElementById('qr-text').innerText = this.files[0].name">
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
                                <tr class="text-[10px] uppercase border-b text-left">
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
</body>

</html>
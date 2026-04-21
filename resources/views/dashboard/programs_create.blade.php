<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Programas - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <x-page-header titulo="Nuevo Programa"></x-page-header>

        <div class="p-6">
            <div class="w-full max-w-4xl bg-white p-8 rounded-sm border-2 border-brand-gold shadow-md">
                <form action="{{ route('programs.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="form-label-bold text-black">Nombre del
                                    Programa</label>
                                <input type="text" name="nombre" required
                                    class="form-input-pill border-2 border-brand-gold">
                            </div>

                            <div>
                                <label class="form-label-bold text-black">Institución</label>
                                <select name="id_institucion" required
                                    class="form-select-pill border-2 border-brand-gold">
                                    <option value="">Seleccionar institución...</option>
                                    @foreach($instituciones as $ins)
                                        <option value="{{ $ins->id_institucion }}">{{ $ins->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label-bold text-black">Sede</label>
                                <select name="id_sede" required class="form-select-pill border-2 border-brand-gold">
                                    <option value="">Seleccionar sede...</option>
                                    @foreach($sedes as $sede)
                                        <option value="{{ $sede->id_sede }}">{{ $sede->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label-bold text-black">Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" required
                                        class="form-input-pill border-2 border-brand-gold">
                                </div>
                                <div>
                                    <label class="form-label-bold text-black">Fecha Fin</label>
                                    <input type="date" name="fecha_fin"
                                        class="form-input-pill border-2 border-brand-gold">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="form-label-bold text-black">Docente
                                    Responsable</label>
                                <select name="id_docente" class="form-select-pill border-2 border-brand-gold">
                                    <option value="">Asignar docente...</option>
                                    @foreach($docentes as $doc)
                                        <option value="{{ $doc->id_docente }}">{{ $doc->nombre }}
                                            {{ $doc->apellido_p }} {{ $doc->apellido_m }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label-bold text-black">Código del Programa</label>
                                <input type="text" name="codigo_curso" required
                                    class="form-input-pill border-2 border-brand-gold">
                            </div>
                            <div>
                                <label class="form-label-bold text-black font-sans uppercase text-[10px]">Imagen Código
                                    QR</label>
                                <div
                                    class="mt-1 flex flex-col items-center justify-center px-6 pt-5 pb-6 border-2 border-gray-100 border-dashed rounded-md bg-gray-50">
                                    <div class="space-y-1 text-center">
                                        <div id="preview-container" class="mb-3 hidden">
                                            <img id="qr-preview" src="#" alt="Vista previa"
                                                class="mx-auto h-32 w-32 object-contain border-2 border-brand-gold p-1 bg-white">
                                        </div>

                                        <svg id="placeholder-icon" class="mx-auto h-12 w-12 text-gray-300"
                                            stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                        <div class="flex text-xs text-gray-600 justify-center">
                                            <label
                                                class="relative cursor-pointer bg-white rounded-md font-sans font-bold text-brand-gold hover:text-brand-green tracking-tighter uppercase">
                                                <span id="btn-text">Seleccionar Código QR</span>
                                                <input id="qr-input" name="codigo_qr" type="file" class="sr-only"
                                                    accept="image/*" onchange="previewQR(event)">
                                            </label>
                                        </div>
                                        <p class="text-[8px] text-gray-400 uppercase mt-1">PNG, JPG</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t flex justify-end gap-4">
                        <a href="{{ route('programs.index') }}"
                            class="text-[15px] font-sans text-gray-400 uppercase py-2">Cancelar</a>
                        <button type="submit" class="btn-gold">
                            CREAR PROGRAMA
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </x-layout-dashboard>
</body>

</html>
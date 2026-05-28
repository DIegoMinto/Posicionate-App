<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Programa - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <x-page-header titulo="Nuevo Programa">
            <a href="{{ route('programs.index') }}" class="btn-back">← Volver</a>
        </x-page-header>

        <div class="p-6 flex justify-center">
            <div class="w-full max-w-4xl bg-white rounded-sm border-2 border-brand-green shadow-md">
                <div class="bg-brand-green p-8">
                    <h1 class="text-2xl font-bold text-white uppercase tracking-tighter">CREACIÓN DE PROGRAMAS</h1>
                </div>
                <div class="p-8">
                    <form action="{{ route('programs.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded p-3">
                                <ul class="text-xs text-red-600 list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Columna izquierda --}}
                            <div class="space-y-4">
                                <div>
                                    <label class="form-label-bold text-black">Nombre del Programa</label>
                                    <input type="text" name="nombre" value="{{ old('nombre') }}" required
                                        class="form-input-pill border-2 border-brand-gold">
                                </div>
                                <div>
                                    <label class="form-label-bold text-black">Código del Programa</label>
                                    <input type="text" name="codigo_curso" value="{{ old('codigo_curso') }}" required
                                        class="form-input-pill border-2 border-brand-gold">
                                </div>
                                <div>
                                    <label class="form-label-bold text-black">Institución</label>
                                    <select name="id_institucion" required
                                        class="form-select-pill border-2 border-brand-gold">
                                        <option value="">Seleccionar institución...</option>
                                        @foreach($instituciones as $ins)
                                            <option value="{{ $ins->id_institucion }}" {{ old('id_institucion') == $ins->id_institucion ? 'selected' : '' }}>
                                                {{ $ins->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label-bold text-black">Sede</label>
                                    <select name="id_sede" required class="form-select-pill border-2 border-brand-gold">
                                        <option value="">Seleccionar sede...</option>
                                        @foreach($sedes as $sede)
                                            <option value="{{ $sede->id_sede }}" {{ old('id_sede') == $sede->id_sede ? 'selected' : '' }}>
                                                {{ $sede->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="form-label-bold text-black">Fecha Inicio</label>
                                        <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}"
                                            required class="form-input-pill border-2 border-brand-gold">
                                    </div>
                                    <div>
                                        <label class="form-label-bold text-black">Fecha Fin</label>
                                        <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}"
                                            class="form-input-pill border-2 border-brand-gold">
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label-bold text-black">Tipo de Oferta</label>
                                    <select name="tipo" id="tipo_oferta" required onchange="toggleMatricula()"
                                        class="form-select-pill border-2 border-brand-gold">
                                        <option value="CURSO" {{ old('tipo') == 'CURSO' ? 'selected' : '' }}>Curso
                                        </option>
                                        <option value="PROGRAMA" {{ old('tipo') == 'PROGRAMA' ? 'selected' : '' }}>
                                            Programa</option>
                                        <option value="DIPLOMADO" {{ old('tipo') == 'DIPLOMADO' ? 'selected' : '' }}>
                                            Diplomado</option>
                                    </select>
                                </div>
                                <div id="campo_matricula"
                                    class="{{ in_array(old('tipo'), ['DIPLOMADO', 'PROGRAMA']) ? '' : 'hidden' }}">
                                    <label class="form-label-bold text-black">Costo de Matrícula (Bs)</label>
                                    <input type="number" name="costo_matricula" value="{{ old('costo_matricula', 0) }}"
                                        class="form-input-pill border-2 border-brand-gold">
                                </div>
                            </div>

                            {{-- Columna derecha --}}
                            <div class="space-y-4">
                                <div>
                                    <label class="form-label-bold text-black">Docente Responsable</label>
                                    <select name="id_docente" class="form-select-pill border-2 border-brand-gold mb-2">
                                        <option value="">Sin docente asignado...</option>
                                        @foreach($docentes as $doc)
                                            <option value="{{ $doc->id_docente }}" {{ old('id_docente') == $doc->id_docente ? 'selected' : '' }}>
                                                {{ $doc->nombre }} {{ $doc->apellido_p }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="docentes-adicionales-container" class="space-y-2"></div>
                                    <button type="button" onclick="agregarDocente()"
                                        class="text-[10px] font-bold text-black uppercase hover:text-brand-gold transition-colors cursor-pointer mt-1">
                                        + Agregar Docente Adicional
                                    </button>
                                </div>

                                {{-- Imagen formulario promocional --}}
                                <div>
                                    <label class="form-label-bold text-black">Imagen Formulario Promocional</label>
                                    <div
                                        class="mt-1 border-2 border-dashed border-gray-200 rounded-md bg-gray-50 p-4 flex flex-col items-center gap-3">
                                        <div id="preview-container" class="hidden">
                                            <img id="img-preview" src="#" alt="Vista previa"
                                                class="h-32 w-full object-cover rounded border border-gray-200">
                                        </div>
                                        <div id="placeholder-icon" class="flex flex-col items-center gap-1">
                                            <svg class="h-10 w-10 text-gray-300" stroke="currentColor" fill="none"
                                                viewBox="0 0 48 48">
                                                <path
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span class="text-[10px] text-gray-400 uppercase">Sin imagen</span>
                                        </div>
                                        <label class="cursor-pointer">
                                            <span id="img-btn-text"
                                                class="text-[11px] text-brand-green font-bold underline uppercase">
                                                Seleccionar imagen
                                            </span>
                                            <input type="file" name="imagen_formulario" class="hidden" accept="image/*"
                                                onchange="previewImagen(event)">
                                        </label>
                                        <p class="text-[9px] text-gray-400 uppercase">JPG, PNG, WEBP — máx 2MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-4 pt-2">
                            <a href="{{ route('programs.index') }}"
                                class="text-[13px] font-sans text-gray-400 uppercase py-2">Cancelar</a>
                            <button type="submit" class="btn-gold">CREAR PROGRAMA</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-layout-dashboard>

    <script>
        function agregarDocente() {
            const container = document.getElementById('docentes-adicionales-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2 items-center';
            div.innerHTML = `
                <select name="docentes_adicionales[]" class="form-select-pill border-2 border-brand-gold flex-1 mt-2">
                    <option value="">Seleccionar docente adicional...</option>
                    @foreach($docentes as $doc)
                        <option value="{{ $doc->id_docente }}">{{ $doc->nombre }} {{ $doc->apellido_p }}</option>
                    @endforeach
                </select>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-400 font-bold px-2 mt-2">✕</button>
            `;
            container.appendChild(div);
        }

        function toggleMatricula() {
            const tipo = document.getElementById('tipo_oferta').value;
            const campo = document.getElementById('campo_matricula');
            campo.classList.toggle('hidden', !['DIPLOMADO', 'PROGRAMA'].includes(tipo));
        }

        function previewImagen(event) {
            const file = event.target.files[0];
            if (!file) return;
            document.getElementById('img-btn-text').innerText = file.name;
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('img-preview').src = e.target.result;
                document.getElementById('preview-container').classList.remove('hidden');
                document.getElementById('placeholder-icon').classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    </script>
</body>

</html>
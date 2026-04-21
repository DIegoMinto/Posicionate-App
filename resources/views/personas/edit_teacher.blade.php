<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edición de Datos - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <div class="p-6">
            <div class="bg-white rounded-sm border-2 border-brand-gold shadow-lg p-8">
                <div class="flex justify-between items-center mb-6 border-b-2 border-brand-gold pb-4">
                    <h1 class="text-2xl font-sans font-bold text-brand-green tracking-wider">
                        Editar Docente: <span class="font-sans">{{ $docente->nombre }}
                            {{ $docente->apellido_p }} {{ $docente->apellido_m }}</span>
                    </h1>
                </div>

                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                        <p class="font-bold">Por favor corrige los siguientes errores:</p>
                        <ul class="text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('docentes.update', $docente->id_docente) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-4">
                            <h3 class="font-sans font-bold text-brand-green text-sm uppercase border-b border-gray-100">
                                Datos Personales</h3>

                            <div>
                                <label class="form-label-bold font-sans text-brand-green">Nombre(s)</label>
                                <input type="text" name="nombre" value="{{ old('nombre', $docente->nombre) }}"
                                    class="form-input-pill w-full border-2 border-brand-gold">
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="form-label-bold font-sans text-brand-green">Apellido
                                        Paterno</label>
                                    <input type="text" name="apellido_p"
                                        value="{{ old('apellido_p', $docente->apellido_p) }}"
                                        class="form-input-pill w-full border-2 border-brand-gold">
                                </div>
                                <div>
                                    <label class="form-label-bold font-sans text-brand-green">Apellido
                                        Materno</label>
                                    <input type="text" name="apellido_m"
                                        value="{{ old('apellido_m', $docente->apellido_m) }}"
                                        class="form-input-pill w-full border-2 border-brand-gold">
                                </div>
                            </div>

                            <div x-data="{ ext: '{{ old('extension_select', $docente->extension_ci) }}' }">
                                <label class="form-label-bold font-sans text-brand-green">Documento de
                                    Identidad</label>
                                <div class="flex gap-1">
                                    <input type="number" name="ci" value="{{ old('ci', $docente->ci) }}"
                                        class="flex-1 form-input-pill w-full border-2 border-brand-gold">
                                    <select name="extension_select" x-model="ext"
                                        class="w-20 form-select-pill border-2 border-brand-gold">
                                        @foreach(['CH', 'LP', 'CB', 'OR', 'PT', 'TJ', 'SC', 'BE', 'PD', 'OTRO'] as $ext)
                                            <option value="{{ $ext }}">{{ $ext }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="form-label-bold font-sans text-brand-green">Teléfono /
                                    Móvil</label>
                                <input type="text" name="telefono_movil"
                                    value="{{ old('telefono_movil', $docente->telefono_movil) }}"
                                    class="form-input-pill w-full border-2 border-brand-gold">
                            </div>

                            <div>
                                <label class="form-label-bold font-sans text-brand-green">Correo
                                    Electrónico</label>
                                <input type="email" name="correo_electronico"
                                    value="{{ old('correo_electronico', $docente->correo_electronico) }}"
                                    class="form-input-pill w-full border-2 border-brand-gold">
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="font-sans font-bold text-brand-green text-sm uppercase border-b border-gray-100">
                                Académicos y Ubicación</h3>

                            <div>
                                <label class="form-label-bold font-sans text-brand-green">Área</label>
                                <select name="area" class="form-select-pill border-2 border-brand-gold">
                                    <option value="">Sin Definir</option>
                                    <option value="ingenieria" {{ $docente->area == 'ingenieria' ? 'selected' : '' }}>
                                        Ingeniería</option>
                                    <option value="derecho" {{ $docente->area == 'derecho' ? 'selected' : '' }}>
                                        Derecho</option>
                                    <option value="economia" {{ $docente->area == 'economia' ? 'selected' : '' }}>Economía
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class=" form-label-bold font-sans text-brand-green">Título / Grado
                                    Académico</label>
                                <select name="id_grado_academico" class="form-select-pill border-2 border-brand-gold">
                                    @foreach($grados as $grado)
                                        <option value="{{ $grado->id_grado_academico }}" {{ $docente->id_grado_academico == $grado->id_grado_academico ? 'selected' : '' }}>
                                            {{ $grado->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="form-label-bold font-sans text-brand-green">Profesión</label>
                                <select name="id_profesion" class="form-select-pill border-2 border-brand-gold">
                                    @foreach($profesiones as $profesion)
                                        <option value="{{ $profesion->id_profesion }}" {{ $docente->id_profesion == $profesion->id_profesion ? 'selected' : '' }}>
                                            {{ $profesion->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="form-label-bold font-sans text-brand-green">Institución de
                                    Egreso</label>
                                <select name="id_institucion_egreso"
                                    class="form-select-pill border-2 border-brand-gold">
                                    @foreach($instituciones as $inst)
                                        <option value="{{ $inst->id_institucion_egreso }}" {{ $docente->id_institucion_egreso == $inst->id_institucion_egreso ? 'selected' : '' }}>
                                            {{ $inst->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="form-label-bold font-sans text-brand-green">Ciudad de
                                    Residencia</label>
                                <select name="id_ciudad" class="form-select-pill border-2 border-brand-gold">
                                    @foreach($ciudades as $ciudad)
                                        <option value="{{ $ciudad->id_ciudad }}" {{ $docente->id_ciudad == $ciudad->id_ciudad ? 'selected' : '' }}>
                                            {{ $ciudad->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="form-label-bold font-sans text-brand-green">¿Emite
                                    Factura?</label>
                                <select name="emite_factura" class="form-select-pill border-2 border-brand-gold">
                                    <option value="1" {{ $docente->emite_factura == 'SI' ? 'selected' : '' }}>SÍ
                                        FACTURA</option>
                                    <option value="0" {{ $docente->emite_factura == 'NO' ? 'selected' : '' }}>NO
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="font-sans font-bold text-brand-green text-sm uppercase border-b border-gray-100">
                                Datos Bancarios y Archivos</h3>

                            <div>
                                <label class="form-label-bold font-sans text-brand-green">Institución
                                    Bancaria</label>
                                <select name="id_institucion_bancaria"
                                    class="form-select-pill border-2 border-brand-gold">
                                    @foreach($bancos as $banco)
                                        <option value="{{ $banco->id_institucion_bancaria }}" {{ $docente->id_institucion_bancaria == $banco->id_institucion_bancaria ? 'selected' : '' }}>
                                            {{ $banco->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="form-label-bold font-sans text-brand-green">Número de
                                    Cuenta</label>
                                <input type="text" name="numero_cuenta_bancaria"
                                    value="{{ old('numero_cuenta_bancaria', $docente->numero_cuenta_bancaria) }}"
                                    class="form-input-pill w-full border-2 border-brand-gold">
                            </div>
                            <div class="space-y-4 pt-6 border-t border-gray-100">
                                <h3 class="font-sans font-bold text-brand-green text-sm uppercase">
                                    Documentación Digitial
                                </h3>

                                <div class="flex border-2 border-brand-gold">
                                    <div class="flex-1 p-3 border border-dashed border-gray-300 rounded-sm bg-gray-50">
                                        <label class="form-label-bold font-sans text-brand-green mb-1">
                                            Actualizar Fotografía (JPG/PNG)
                                        </label>
                                        <input type="file" name="fotografia" class="text-[10px] w-full cursor-pointer">
                                        @if($docente->fotografia)
                                            <p class="text-[9px] text-green-600 mt-1 italic">
                                                Archivo actual: {{ basename($docente->fotografia) }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="flex-1 p-3 border border-dashed border-gray-300 rounded-sm bg-gray-50">
                                        <label class="form-label-bold font-sans text-brand-green mb-1">
                                            Actualizar CV (PDF)
                                        </label>
                                        <input type="file" name="curriculum" class="text-[10px] w-full cursor-pointer">
                                        @if($docente->curriculum)
                                            <p class="text-[9px] text-green-600 mt-1 italic">
                                                CV cargado correctamente.
                                            </p>
                                        @endif
                                    </div>

                                    <div class="flex-1 p-3 border border-dashed border-gray-300 rounded-sm bg-gray-50">
                                        <label class="form-label-bold font-sans text-brand-green mb-1">
                                            Actualizar Fotocarnet (PDF)
                                        </label>
                                        <input type="file" name="fotocarnet" class="text-[10px] w-full cursor-pointer">
                                        @if($docente->fotocarnet)
                                            <p class="text-[9px] text-green-600 mt-1 italic">
                                                Fotocarnet cargado correctamente.
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-5 flex justify-center">
                        <button type="submit" class="btn-gold">
                            Actualizar
                        </button>
                    </div>
            </div>
            </form>
        </div>
        </div>
    </x-layout-dashboard>
</body>

</html>
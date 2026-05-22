<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edición de Datos de Estudiante - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <div class="p-6">
            <div class="bg-white rounded-sm border-2 border-brand-green shadow-lg">
                <div class="bg-brand-green p-8">
                    <h1 class="text-2xl font-sans font-bold text-white tracking-wider">
                        Editar al Estudiante: <span class="font-sans">{{ $estudiante->nombre }}
                            {{ $estudiante->apellido_p }} {{ $estudiante->apellido_m }}</span>
                    </h1>
                </div>
                <div class="flex justify-between items-center mb-6 p-8">


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

                    <form action="{{ route('estudiantes.update', $estudiante->id_estudiante) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-4">
                                <h3
                                    class="font-sans font-bold text-brand-green text-sm uppercase border-b border-gray-100">
                                    Datos Personales</h3>

                                <div>
                                    <label class="form-label-bold font-sans text-brand-green">Nombre(s)</label>
                                    <input type="text" name="nombre" value="{{ old('nombre', $estudiante->nombre) }}"
                                        class="form-input-pill w-full border-2 border-brand-gold">
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="form-label-bold font-sans text-brand-green">Apellido
                                            Paterno</label>
                                        <input type="text" name="apellido_p"
                                            value="{{ old('apellido_p', $estudiante->apellido_p) }}"
                                            class="form-input-pill w-full border-2 border-brand-gold">
                                    </div>
                                    <div>
                                        <label class="form-label-bold font-sans text-brand-green">Apellido
                                            Materno</label>
                                        <input type="text" name="apellido_m"
                                            value="{{ old('apellido_m', $estudiante->apellido_m) }}"
                                            class="form-input-pill w-full border-2 border-brand-gold">
                                    </div>
                                </div>

                                <div x-data="{ ext: '{{ old('extension_select', $estudiante->extension_ci) }}' }">
                                    <label class="form-label-bold font-sans text-brand-green">Documento de
                                        Identidad</label>
                                    <div class="flex gap-1">
                                        <input type="number" name="ci" value="{{ old('ci', $estudiante->ci) }}"
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
                                        value="{{ old('telefono_movil', $estudiante->telefono_movil) }}"
                                        class="form-input-pill w-full border-2 border-brand-gold">
                                </div>

                                <div>
                                    <label class="form-label-bold font-sans text-brand-green">Correo
                                        Electrónico</label>
                                    <input type="email" name="correo_electronico"
                                        value="{{ old('correo_electronico', $estudiante->correo_electronico) }}"
                                        class="form-input-pill w-full border-2 border-brand-gold">
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3
                                    class="font-sans font-bold text-brand-green text-sm uppercase border-b border-gray-100">
                                    Académicos y Ubicación</h3>

                                <div>
                                    <label class=" form-label-bold font-sans text-brand-green">Título / Grado
                                        Académico</label>
                                    <select name="id_grado_academico"
                                        class="form-select-pill border-2 border-brand-gold">
                                        @foreach($grados as $grado)
                                            <option value="{{ $grado->id_grado_academico }}" {{ $estudiante->id_grado_academico == $grado->id_grado_academico ? 'selected' : '' }}>
                                                {{ $grado->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label-bold font-sans text-brand-green">Profesión</label>
                                    <select name="id_profesion" class="form-select-pill border-2 border-brand-gold">
                                        @foreach($profesiones as $profesion)
                                            <option value="{{ $profesion->id_profesion }}" {{ $estudiante->id_profesion == $profesion->id_profesion ? 'selected' : '' }}>
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
                                            <option value="{{ $inst->id_institucion_egreso }}" {{ $estudiante->id_institucion_egreso == $inst->id_institucion_egreso ? 'selected' : '' }}>
                                                {{ $inst->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label-bold font-sans text-brand-green">Departamento de
                                        Residencia</label>
                                    <select name="id_departamento" class="form-select-pill border-2 border-brand-gold">
                                        @foreach($departamento as $dept)
                                            <option value="{{ $dept->id_departamento }}" {{ $estudiante->id_departamento == $dept->id_departamento ? 'selected' : '' }}>
                                                {{ $dept->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label-bold font-sans text-brand-green">Ciudad de
                                        Residencia</label>
                                    <input type="text" name="ciudad_residencia"
                                        value="{{ old('ciudad_residencia', $estudiante->ciudad_residencia) }}"
                                        class="form-input-pill w-full border-2 border-brand-gold">
                                </div>
                            </div>

                        </div>
                        <div class="p-5 flex justify-center">
                            <button type="submit" class="btn-gold">
                                Actualizar
                            </button>
                        </div>
                </div>
            </div>
            </form>
        </div>
        </div>
    </x-layout-dashboard>
</body>

</html>
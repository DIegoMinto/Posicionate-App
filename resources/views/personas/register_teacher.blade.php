<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Docente - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body class="bg-slate-100 font-sans min-h-screen flex items-center justify-center p-4 md:p-8">

    <div class="max-w-5xl w-full bg-white shadow-2xl rounded-2xl overflow-hidden border border-slate-200">

        <header class="bg-brand-green p-8 text-center border-b-4 border-brand-gold">
            <h1 class="text-white text-xl md:text-2xl font-bold tracking-widest uppercase">
                Formulario de Registro de Docente
            </h1>

            <div class="inline-block w-48 mt-5">
                <img src="/img/logoblanco.png" alt="Posicionate"
                    class="w-full object-contain">
            </div>
        </header>

        <main class="p-6 md:p-10">

            @if ($errors->any())
                <div class="mb-8 p-5 bg-red-100 border-l-4 border-red-600 text-slate-900 rounded-r-xl shadow-sm">
                    <strong class="font-extrabold text-red-800 uppercase text-sm">
                        Por favor, corrige los siguientes errores:
                    </strong>

                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-8 p-5 bg-emerald-100 border-l-4 border-emerald-600 text-emerald-900 rounded-r-xl text-center font-bold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('docentes.store') }}" method="POST"
                enctype="multipart/form-data" class="space-y-12">

                @csrf

                {{-- SECCIÓN 1: DATOS PERSONALES --}}

                <section class="space-y-6">

                    <div class="flex items-center gap-3 rounded-lg p-3">
                        <div class="w-1 h-6 bg-brand-gold rounded"></div>
                        <h2 class="text-brand-green text-lg font-black uppercase tracking-wider">
                            1. Datos personales
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <div>
                            <label for="nombre" class="form-label-bold text-brand-green uppercase">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input id="nombre" type="text" name="nombre"
                                value="{{ old('nombre') }}" required
                                class="form-input-pill border-brand-green border w-full @error('nombre') border-red-500 @enderror">
                            @error('nombre')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="apellido_p" class="form-label-bold text-brand-green uppercase">
                                Primer apellido <span class="text-red-500">*</span>
                            </label>
                            <input id="apellido_p" type="text" name="apellido_p"
                                value="{{ old('apellido_p') }}" required
                                class="form-input-pill border-brand-green border w-full @error('apellido_p') border-red-500 @enderror">
                            @error('apellido_p')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="apellido_m" class="form-label-bold text-brand-green uppercase">
                                Segundo apellido
                            </label>
                            <input id="apellido_m" type="text" name="apellido_m"
                                value="{{ old('apellido_m') }}"
                                class="form-input-pill border-brand-green border w-full @error('apellido_m') border-red-500 @enderror">
                            @error('apellido_m')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <div>
                            <label for="fecha_nacimiento" class="form-label-bold text-brand-green uppercase">
                                Fecha de nacimiento <span class="text-red-500">*</span>
                            </label>
                            <input id="fecha_nacimiento" type="date" name="fecha_nacimiento"
                                value="{{ old('fecha_nacimiento') }}" required
                                class="form-input-pill border-brand-green border w-full @error('fecha_nacimiento') border-red-500 @enderror">
                            @error('fecha_nacimiento')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ci" class="form-label-bold text-brand-green uppercase">
                                Carnet de identidad <span class="text-red-500">*</span>
                            </label>
                            <input id="ci" type="text" name="ci" value="{{ old('ci') }}"
                                required
                                class="form-input-pill border-brand-green border w-full @error('ci') border-red-500 @enderror">
                            @error('ci')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="select-extension" class="form-label-bold text-brand-green uppercase">
                                Extensión del carnet <span class="text-red-500">*</span>
                            </label>

                            <div class="flex flex-col gap-2">
                                <select id="select-extension" name="extension_ci" required
                                    class="form-select-pill border-brand-green border w-full @error('extension_ci') border-red-500 @enderror">
                                    <option value="">Seleccione extensión</option>
                                    @foreach (['LP', 'SC', 'CB', 'CH', 'OR', 'PT', 'TJ', 'BE', 'PD'] as $ext)
                                        <option value="{{ $ext }}"
                                            {{ old('extension_ci') === $ext ? 'selected' : '' }}>
                                            {{ $ext }}
                                        </option>
                                    @endforeach
                                    <option value="OTRO" {{ old('extension_ci') === 'OTRO' ? 'selected' : '' }}>
                                        OTRO (Escribir...)
                                    </option>
                                </select>

                                <input type="text" id="input-extension-otro"
                                    class="hidden form-input-pill border-brand-green border w-full"
                                    placeholder="Escriba la extensión (ej. EXT)"
                                    value="{{ old('extension_ci') !== null && !in_array(old('extension_ci'), ['LP', 'SC', 'CB', 'CH', 'OR', 'PT', 'TJ', 'BE', 'PD', 'OTRO']) ? old('extension_ci') : '' }}">
                            </div>

                            @error('extension_ci')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <div>
                            <label for="select-pais" class="form-label-bold text-brand-green uppercase">
                                País <span class="text-red-500">*</span>
                            </label>

                            <select name="id_pais" id="select-pais" required
                                class="form-select-pill border-brand-green border w-full @error('id_pais') border-red-500 @enderror">
                                <option value="">Seleccione país</option>
                                @foreach ($paises as $pais)
                                    <option value="{{ $pais->id_pais }}"
                                        {{ old('id_pais') == $pais->id_pais ? 'selected' : '' }}>
                                        {{ $pais->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            @error('id_pais')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="select-departamento" class="form-label-bold text-brand-green uppercase">
                                Departamento <span class="text-red-500">*</span>
                            </label>

                            <select name="id_departamento" id="select-departamento" required
                                data-old="{{ old('id_departamento') }}"
                                class="form-select-pill border-brand-green border w-full @error('id_departamento') border-red-500 @enderror">
                                <option value="">Seleccione primero un país</option>
                            </select>

                            @error('id_departamento')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="lugar_nacimiento" class="form-label-bold text-brand-green uppercase">
                                Lugar de nacimiento <span class="text-red-500">*</span>
                            </label>

                            <input type="text" name="lugar_nacimiento" id="lugar_nacimiento"
                                value="{{ old('lugar_nacimiento') }}"
                                placeholder="Ej. Sucre, Chuquisaca" required
                                class="form-input-pill border-brand-green border w-full @error('lugar_nacimiento') border-red-500 @enderror">

                            @error('lugar_nacimiento')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label for="domicilio" class="form-label-bold text-brand-green uppercase">
                                Domicilio
                            </label>

                            <input type="text" name="domicilio" id="domicilio"
                                value="{{ old('domicilio') }}"
                                placeholder="Calle, número, zona"
                                class="form-input-pill border-brand-green border w-full @error('domicilio') border-red-500 @enderror">

                            @error('domicilio')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- ARCHIVOS --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">

                        <div class="bg-slate-50 p-5 rounded-xl border-2 {{ $errors->has('curriculum') ? 'border-red-500' : 'border-slate-300' }} hover:border-brand-green transition-all shadow-sm">
                            <label for="curriculum" class="form-label-bold text-brand-green uppercase">
                                Currículum vitae
                                <span class="text-slate-500 normal-case">(opcional)</span>
                            </label>
                            <p class="text-xs font-bold text-slate-500 mb-3">
                                Formato PDF, máximo 10 MB.
                            </p>
                            <input id="curriculum" type="file" name="curriculum"
                                accept=".pdf,application/pdf"
                                class="form-input-pill border-brand-green border w-full">
                            @error('curriculum')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-slate-50 p-5 rounded-xl border-2 {{ $errors->has('fotocarnet') ? 'border-red-500' : 'border-slate-300' }} hover:border-brand-green transition-all shadow-sm">
                            <label for="fotocarnet" class="form-label-bold text-brand-green uppercase">
                                Carnet de identidad <span class="text-red-500">*</span>
                            </label>
                            <p class="text-xs font-bold text-slate-500 mb-3">
                                PDF, JPG o PNG. Máximo 10 MB.
                            </p>
                            <input id="fotocarnet" type="file" name="fotocarnet"
                                accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png"
                                required
                                class="form-input-pill border-brand-green border w-full">
                            @error('fotocarnet')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-slate-50 p-5 rounded-xl border-2 {{ $errors->has('fotografia') ? 'border-red-500' : 'border-slate-300' }} hover:border-brand-green transition-all shadow-sm">
                            <label for="fotografia" class="form-label-bold text-brand-green uppercase">
                                Fotografía de perfil
                            </label>
                            <p class="text-xs font-bold text-slate-500 mb-3">
                                JPG o PNG. Máximo 10 MB.
                            </p>
                            <input id="fotografia" type="file" name="fotografia"
                                accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                                class="form-input-pill border-brand-green border w-full">
                            @error('fotografia')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-4 flex items-start gap-2 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3">
                        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>

                        <div>
                            <p class="text-xs font-bold text-amber-800 uppercase">
                                Importante: requisitos de los archivos
                            </p>
                            <p class="text-xs text-slate-700 mt-1 leading-relaxed">
                                El currículum es opcional. Los demás archivos deben respetar
                                los formatos indicados y tener un tamaño máximo de
                                <strong>10 MB por archivo</strong>.
                            </p>
                        </div>
                    </div>

                    {{-- GÉNERO Y DATOS BANCARIOS --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">

                        <div>
                            <label class="form-label-bold text-brand-green uppercase">
                                Género <span class="text-red-500">*</span>
                            </label>

                            <div class="flex gap-4 mt-1">
                                @foreach (['M' => 'Masculino', 'F' => 'Femenino'] as $valor => $texto)
                                    <label class="flex items-center gap-2 cursor-pointer text-sm font-bold bg-slate-50 px-4 py-2.5 rounded-lg border border-brand-green w-1/2 justify-center">
                                        <input type="radio" name="genero" value="{{ $valor }}"
                                            class="w-4 h-4 accent-brand-green"
                                            {{ old('genero') === $valor ? 'checked' : '' }} required>
                                        <span>{{ $texto }}</span>
                                    </label>
                                @endforeach
                            </div>

                            @error('genero')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="select-banco" class="form-label-bold text-brand-green uppercase">
                                Entidad bancaria <span class="text-red-500">*</span>
                            </label>

                            <select name="id_institucion_bancaria" id="select-banco" required
                                class="form-select-pill border-brand-green border w-full @error('id_institucion_bancaria') border-red-500 @enderror">
                                <option value="">Seleccione banco</option>
                                @foreach ($bancos as $banco)
                                    <option value="{{ $banco->id_institucion_bancaria }}"
                                        {{ old('id_institucion_bancaria') == $banco->id_institucion_bancaria ? 'selected' : '' }}>
                                        {{ $banco->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            @error('id_institucion_bancaria')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="numero_cuenta_bancaria" class="form-label-bold text-brand-green uppercase">
                                Número de cuenta <span class="text-red-500">*</span>
                            </label>

                            <input type="text" name="numero_cuenta_bancaria"
                                id="numero_cuenta_bancaria"
                                value="{{ old('numero_cuenta_bancaria') }}" required
                                placeholder="1234567890"
                                class="form-input-pill border-brand-green border w-full @error('numero_cuenta_bancaria') border-red-500 @enderror">

                            @error('numero_cuenta_bancaria')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                </section>

                {{-- SECCIÓN 2: FORMACIÓN ACADÉMICA --}}

                <section class="space-y-6">

                    <div class="flex items-center gap-3 rounded-lg p-3">
                        <div class="w-1 h-6 bg-brand-gold rounded"></div>
                        <h2 class="text-brand-green text-lg font-black uppercase tracking-wider">
                            2. Formación académica
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <div>
                            <label for="select-profesion" class="form-label-bold text-brand-green uppercase">
                                Profesión / Ocupación <span class="text-red-500">*</span>
                            </label>

                            <select name="id_profesion" id="select-profesion" required
                                class="form-select-pill border-brand-green border w-full @error('id_profesion') border-red-500 @enderror">
                                <option value="">Seleccione profesión</option>
                                @foreach ($profesiones as $prof)
                                    <option value="{{ $prof->id_profesion }}"
                                        {{ old('id_profesion') == $prof->id_profesion ? 'selected' : '' }}>
                                        {{ $prof->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            @error('id_profesion')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="select-grado" class="form-label-bold text-brand-green uppercase">
                                Grado académico <span class="text-red-500">*</span>
                            </label>

                            <select name="id_grado_academico" id="select-grado" required
                                class="form-select-pill border-brand-green border w-full @error('id_grado_academico') border-red-500 @enderror">
                                <option value="">Seleccione grado</option>
                                @foreach ($grados as $grado)
                                    <option value="{{ $grado->id_grado_academico }}"
                                        {{ old('id_grado_academico') == $grado->id_grado_academico ? 'selected' : '' }}>
                                        {{ $grado->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            @error('id_grado_academico')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="select-institucion" class="form-label-bold text-brand-green uppercase">
                                Institución de egreso <span class="text-red-500">*</span>
                            </label>

                            <select name="id_institucion_egreso" id="select-institucion" required
                                class="form-select-pill border-brand-green border w-full @error('id_institucion_egreso') border-red-500 @enderror">
                                <option value="">Seleccione institución</option>
                                @foreach ($instituciones as $inst)
                                    <option value="{{ $inst->id_institucion_egreso }}"
                                        {{ old('id_institucion_egreso') == $inst->id_institucion_egreso ? 'selected' : '' }}>
                                        {{ $inst->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            @error('id_institucion_egreso')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

    
<div class="md:col-span-3">
    <div class="mb-3">
        <label class="form-label-bold text-brand-green">
            Áreas de especialización
            <span class="text-red-500">*</span>
        </label>

        <p class="mt-1 text-sm text-gray-500">
            Selecciona las áreas en las que el docente tiene experiencia.
            Puedes elegir más de una.
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach ($areasDocente as $area)
            <label
                for="area_docente_{{ $area->id_area_docente }}"
                class="flex items-start gap-3 rounded-xl border border-gray-200
                       bg-white p-4 cursor-pointer transition-all duration-200
                       hover:border-brand-green hover:bg-gray-50
                       has-[:checked]:border-brand-green
                       has-[:checked]:bg-brand-green/5"
            >
                <input
                    type="checkbox"
                    id="area_docente_{{ $area->id_area_docente }}"
                    name="areas_docente[]"
                    value="{{ $area->id_area_docente }}"
                    class="mt-0.5 h-4 w-4 shrink-0 rounded
                           border-gray-300 accent-brand-green
                           focus:ring-brand-green"
                    {{ in_array(
                        $area->id_area_docente,
                        old('areas_docente', [])
                    ) ? 'checked' : '' }}
                >

                <span class="text-sm font-medium text-gray-700 leading-5">
                    {{ $area->nombre }}
                </span>
            </label>
        @endforeach
    </div>

    <div class="mt-3 flex items-center justify-between gap-3">
        <p class="text-xs text-gray-500">
            Marca todas las áreas que correspondan.
        </p>

        <span
            id="contador-areas-docente"
            class="shrink-0 rounded-full bg-brand-green/10
                   px-3 py-1 text-xs font-semibold text-brand-green"
        >
            0 seleccionadas
        </span>
    </div>

    @error('areas_docente')
        <p class="mt-2 text-sm font-medium text-red-600">
            {{ $message }}
        </p>
    @enderror

    @error('areas_docente.*')
        <p class="mt-2 text-sm font-medium text-red-600">
            {{ $message }}
        </p>
    @enderror
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll(
            'input[name="areas_docente[]"]'
        );

        const contador = document.getElementById(
            'contador-areas-docente'
        );

        function actualizarContador() {
            const seleccionadas = document.querySelectorAll(
                'input[name="areas_docente[]"]:checked'
            ).length;

            contador.textContent =
                `${seleccionadas} ${seleccionadas === 1
                    ? 'seleccionada'
                    : 'seleccionadas'}`;
        }

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', actualizarContador);
        });

        actualizarContador();
    });
</script>


                </section>

                {{-- SECCIÓN 3: CONTACTO Y ADICIONALES --}}

                <section class="space-y-6">

                    <div class="flex items-center gap-3 rounded-lg p-3">
                        <div class="w-1 h-6 bg-brand-gold rounded"></div>
                        <h2 class="text-brand-green text-lg font-black uppercase tracking-wider">
                            3. Contacto y datos adicionales
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <div>
                            <label for="select-codigo-manual" class="form-label-bold text-brand-green uppercase">
                                Teléfono móvil <span class="text-red-500">*</span>
                            </label>

                            <div class="flex gap-2">
                                <select id="select-codigo-manual" name="codigo_pais_movil" required
                                    class="form-select-pill border-brand-green border w-1/2 px-1 @error('codigo_pais_movil') border-red-500 @enderror">
                                    @foreach ([
                                            '+591' => '🇧🇴',
                                            '+54' => '🇦🇷',
                                            '+56' => '🇨🇱',
                                            '+51' => '🇵🇪',
                                            '+57' => '🇨🇴',
                                            '+1' => '🇺🇸',
                                            '+34' => '🇪🇸'
                                        ] as $codigo => $bandera)
                                            <option value="{{ $codigo }}"
                                                {{ old('codigo_pais_movil', '+591') === $codigo ? 'selected' : '' }}>
                                                {{ $bandera }} {{ $codigo }}
                                            </option>
                                    @endforeach
                                </select>

                                <input type="tel" id="input-numero-movil" name="numero_movil"
                                    value="{{ old('numero_movil') }}" required inputmode="numeric"
                                    placeholder="70000000"
                                    class="form-input-pill border-brand-green border w-2/3 @error('numero_movil') border-red-500 @enderror">
                            </div>

                            @error('codigo_pais_movil')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror

                            @error('numero_movil')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="correo_electronico" class="form-label-bold text-brand-green uppercase">
                                Correo electrónico <span class="text-red-500">*</span>
                            </label>

                            <input type="email" name="correo_electronico" id="correo_electronico"
                                value="{{ old('correo_electronico') }}" required
                                placeholder="correo@ejemplo.com"
                                class="form-input-pill border-brand-green border w-full @error('correo_electronico') border-red-500 @enderror">

                            @error('correo_electronico')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">

                        <div>
                            <label for="programas_dar" class="form-label-bold text-brand-green uppercase">
                                Programas adicionales que le gustaría impartir o impartió
                            </label>

                            <textarea name="programas_dar" id="programas_dar"
                                class="w-full px-4 py-3 rounded-lg border border-brand-green focus:ring-4 focus:ring-brand-green/10 outline-none bg-slate-50 text-slate-900 font-medium h-28 resize-none"
                                placeholder="Mencione los programas">{{ old('programas_dar') }}</textarea>

                            @error('programas_dar')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label-bold text-brand-green uppercase mb-3">
                                ¿Emite factura?
                            </label>

                            <div class="flex gap-4">
                                @foreach (['1' => 'SÍ', '0' => 'NO'] as $valor => $texto)
                                    <label class="flex-1 flex items-center justify-center gap-3 p-3 rounded-lg border border-brand-green bg-slate-50 cursor-pointer">
                                        <input type="radio" name="emite_factura" value="{{ $valor }}"
                                            class="w-5 h-5 accent-brand-green"
                                            {{ old('emite_factura', '0') === $valor ? 'checked' : '' }}>
                                        <span class="text-brand-green font-bold">{{ $texto }}</span>
                                    </label>
                                @endforeach
                            </div>

                            @error('emite_factura')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                </section>

                @error('error')
                    <div class="p-4 bg-red-50 border border-red-400 rounded-xl text-red-700 text-sm font-bold text-center">
                        {{ $message }}
                    </div>
                @enderror

                <div class="flex justify-center pt-6 pb-4">
                    <button type="submit" class="btn-gold">
                        Registrar docente
                    </button>
                </div>

            </form>

        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const paisSelect = document.getElementById('select-pais');
            const deptoSelect = document.getElementById('select-departamento');
            const extensionSelect = document.getElementById('select-extension');
            const extensionOtroInput = document.getElementById('input-extension-otro');
            const codigoManual = document.getElementById('select-codigo-manual');
            const numeroMovilInput = document.getElementById('input-numero-movil');

            // Extensión personalizada del carnet
            function actualizarExtension() {
                if (extensionSelect.value === 'OTRO') {
                    extensionOtroInput.classList.remove('hidden');
                    extensionOtroInput.name = 'extension_ci';
                    extensionOtroInput.required = true;

                    extensionSelect.removeAttribute('name');
                    extensionOtroInput.focus();
                } else {
                    extensionOtroInput.classList.add('hidden');
                    extensionOtroInput.removeAttribute('name');
                    extensionOtroInput.required = false;
                    extensionOtroInput.value = '';
                    extensionSelect.name = 'extension_ci';
                }
            }

            extensionSelect.addEventListener('change', actualizarExtension);
            actualizarExtension();

            // Si se seleccionó OTRO previamente, recuperar su valor
            const extensionAnterior = @json(old('extension_ci'));

            if (
                extensionAnterior &&
                !['LP', 'SC', 'CB', 'CH', 'OR', 'PT', 'TJ', 'BE', 'PD', 'OTRO'].includes(extensionAnterior)
            ) {
                extensionSelect.value = 'OTRO';
                extensionOtroInput.value = extensionAnterior;
                actualizarExtension();
            }

            // Cargar departamentos del país seleccionado
            async function cargarDepartamentos(paisId, departamentoAnterior = '') {
                deptoSelect.innerHTML =
                    '<option value="">Cargando departamentos...</option>';
                deptoSelect.disabled = true;

                try {
                    const response = await fetch(
                        `/api/paises/${encodeURIComponent(paisId)}/departamentos`,
                        {
                            headers: {
                                'Accept': 'application/json'
                            }
                        }
                    );

                    if (!response.ok) {
                        throw new Error(`Error HTTP ${response.status}`);
                    }

                    const departamentos = await response.json();

                    console.log('Departamentos recibidos:', departamentos);

                    if (!Array.isArray(departamentos)) {
                        throw new Error('La respuesta no es una lista de departamentos.');
                    }

                    deptoSelect.innerHTML =
                        '<option value="">Seleccione departamento</option>';

                    if (departamentos.length === 0) {
                        deptoSelect.innerHTML =
                            '<option value="">No hay departamentos disponibles</option>';
                        return;
                    }

                    departamentos.forEach((departamento) => {
                        const opcion = document.createElement('option');

                        opcion.value = departamento.id_departamento;
                        opcion.textContent = departamento.nombre;

                        if (
                            String(departamento.id_departamento) ===
                            String(departamentoAnterior)
                        ) {
                            opcion.selected = true;
                        }

                        deptoSelect.appendChild(opcion);
                    });

                } catch (error) {
                    console.error('Error al cargar departamentos:', error);

                    deptoSelect.innerHTML =
                        '<option value="">No se pudieron cargar los departamentos</option>';
                } finally {
                    deptoSelect.disabled = false;
                }
            }

            paisSelect.addEventListener('change', () => {
                const paisId = paisSelect.value;

                if (!paisId) {
                    deptoSelect.innerHTML =
                        '<option value="">Seleccione primero un país</option>';
                    deptoSelect.disabled = false;
                    return;
                }

                cargarDepartamentos(paisId);
            });
           const paisAnterior = @json(old('id_pais'));
            const departamentoAnterior = deptoSelect.dataset.old;

            if (paisAnterior && paisSelect.value) {
                cargarDepartamentos(paisSelect.value, departamentoAnterior);
            }

            numeroMovilInput.addEventListener('input', () => {
                numeroMovilInput.value = numeroMovilInput.value.replace(/\D/g, '');
            });
        });

        $(document).ready(function () {
            $('#select-profesion, #select-grado, #select-institucion, #select-banco').select2({
                placeholder: 'Seleccione una opción',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
</body>
</html>
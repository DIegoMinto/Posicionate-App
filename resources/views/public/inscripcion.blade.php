<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - {{ $curso->nombre }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body class="bg-brand-green font-sans overflow-hidden">

    <div id="slider" class="w-[200vw] h-[100dvh] flex transition-transform duration-700 ease-in-out">
        <section class="w-screen h-[100dvh] flex-shrink-0 bg-brand-green relative overflow-hidden flex items-center justify-center px-4 py-4">

            <div class="relative z-10 w-full max-w-[340px]">
                <div class="hero-card">

                    <div class="aspect-square overflow-hidden">
                        <img
                            src="{{ $curso->imagen_formulario }}"
                            class="w-full h-full object-cover"
                            alt="Imagen del curso">
                    </div>

                    <div class="p-4 text-center">

                        <h1 class="text-xl md:text-2xl font-bold text-brand-green mt-2 leading-tight uppercase">
                            {{ $curso->nombre }}
                        </h1>

                        <button
                            type="button"
                            onclick="abrirFormulario()"
                            class="mt-5 w-12 h-12 md:w-14 md:h-14 rounded-full bg-brand-gold flex items-center justify-center mx-auto hover:scale-110 active:scale-95 transition-all duration-300 shadow-xl cursor-pointer"
                            aria-label="Continuar al formulario">

                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-black" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>

                        </button>

                        <p class="text-xs text-gray-400 mt-3">
                            Toca para continuar
                        </p>

                    </div>
                </div>
            </div>
        </section>

        <section
            id="formulario"
            class="w-screen h-[100dvh] flex-shrink-0 overflow-y-auto bg-[#f7f7f5] px-4 py-10">

            <div class="max-w-5xl mx-auto">

                <button
                    type="button"
                    onclick="cerrarFormulario()"
                    class="mb-6 flex items-center gap-2 text-brand-green font-bold hover:text-brand-gold transition-all cursor-pointer">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 19l-7-7 7-7" />
                    </svg>

                    Volver
                </button>

                <div class="text-center mb-10">

                    <p class="text-brand-gold uppercase tracking-[0.3em] text-xs font-bold">
                        Ficha de Inscripción
                    </p>

                    <h2 class="text-3xl md:text-4xl font-bold text-brand-green mt-3 uppercase">
                        {{ $curso->nombre }}
                    </h2>

                    <div class="mt-3">
                        <span class="text-brand-gold text-xs font-bold uppercase tracking-wider">
                            Asesor:
                        </span>

                        <span class="text-brand-green text-xs font-semibold uppercase">
                            {{ $asesor->persona->nombre }}
                            {{ $asesor->persona->apellido_p }}
                        </span>
                    </div>
                </div>

                @if (session('success'))

                    <div
                        id="success-alert"
                        role="status"
                        class="mb-8 p-5 rounded-[2rem] bg-emerald-50 border border-emerald-200 shadow-sm">

                        <div class="flex items-start gap-4">

                            <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-emerald-700 font-bold text-lg">
                                    ¡Registro exitoso!
                                </h3>

                                <p class="text-emerald-600 text-sm mt-1">
                                    {{ session('success') }}
                                </p>
                            </div>

                        </div>
                    </div>

                @endif

                @if ($errors->any())

                    <div
                        id="error-summary"
                        role="alert"
                        aria-live="assertive"
                        class="mb-8 p-5 md:p-6 rounded-3xl bg-red-50 border border-red-200 text-red-800 shadow-sm">

                        <div class="flex items-start gap-3">

                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01M10.3 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.7 3.86a2 2 0 00-3.4 0z" />
                                </svg>
                            </div>

                            <div class="flex-1">
                                <h3 class="font-bold text-lg">
                                    No pudimos completar tu inscripción
                                </h3>

                                <p class="text-sm mt-1">
                                    Encontramos
                                    <strong>{{ $errors->count() }}
                                        {{ $errors->count() === 1 ? 'campo que necesita' : 'campos que necesitan' }}
                                        revisión.</strong>
                                    Revisa los mensajes en rojo debajo de cada campo y corrige la información indicada.
                                </p>

                                <ul class="mt-3 space-y-2 text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li class="flex items-start gap-2">
                                            <span class="font-bold">•</span>
                                            <span>{{ $error }}</span>
                                        </li>
                                    @endforeach
                                </ul>

                                <p class="text-xs text-red-700 mt-4">
                                    Tus datos ingresados se conservaron siempre que el campo lo permita.
                                    No necesitas llenar nuevamente todo el formulario.
                                </p>
                            </div>

                        </div>
                    </div>

                @endif

                <form
                    id="form-inscripcion"
                    action="{{ route('inscripcion.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="space-y-4 md:space-y-6">

                    @csrf

                    <input type="hidden" name="id_curso" value="{{ $curso->id_curso }}">
                    <input type="hidden" name="id_personal" value="{{ $asesor->id_personal }}">

                    <div class="form-section-card">

                        <div class="mb-7">
                            <p class="text-brand-gold uppercase tracking-[0.25em] text-xs font-bold mb-2">
                                Sección 1
                            </p>

                            <h3 class="text-2xl font-bold text-brand-green">
                                Datos Personales
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                            <div>
                                <label for="nombre" class="form-label-bold-2">
                                    Nombre <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="nombre"
                                    name="nombre"
                                    value="{{ old('nombre') }}"
                                    autocomplete="given-name"
                                    class="form-input-pill-2 w-full {{ $errors->has('nombre') ? '!border-red-500 !ring-1 !ring-red-200' : '' }}"
                                    required
                                    aria-invalid="{{ $errors->has('nombre') ? 'true' : 'false' }}">

                                @error('nombre')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="apellido_p" class="form-label-bold-2">
                                    Primer Apellido <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="apellido_p"
                                    name="apellido_p"
                                    value="{{ old('apellido_p') }}"
                                    autocomplete="family-name"
                                    class="form-input-pill-2 w-full {{ $errors->has('apellido_p') ? '!border-red-500 !ring-1 !ring-red-200' : '' }}"
                                    required
                                    aria-invalid="{{ $errors->has('apellido_p') ? 'true' : 'false' }}">

                                @error('apellido_p')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="apellido_m" class="form-label-bold-2">
                                    Segundo Apellido
                                </label>

                                <input
                                    type="text"
                                    id="apellido_m"
                                    name="apellido_m"
                                    value="{{ old('apellido_m') }}"
                                    class="form-input-pill-2 w-full {{ $errors->has('apellido_m') ? '!border-red-500 !ring-1 !ring-red-200' : '' }}"
                                    aria-invalid="{{ $errors->has('apellido_m') ? 'true' : 'false' }}">

                                @error('apellido_m')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-5">

                            <div>
                                <label for="fecha_nacimiento" class="form-label-bold-2">
                                    Fecha de Nacimiento <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="date"
                                    id="fecha_nacimiento"
                                    name="fecha_nacimiento"
                                    value="{{ old('fecha_nacimiento') }}"
                                    class="form-input-pill-2 text-black w-full {{ $errors->has('fecha_nacimiento') ? '!border-red-500 !ring-1 !ring-red-200' : '' }}"
                                    required
                                    aria-invalid="{{ $errors->has('fecha_nacimiento') ? 'true' : 'false' }}">

                                @error('fecha_nacimiento')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="ci" class="form-label-bold-2">
                                    Carnet de Identidad <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="ci"
                                    name="ci"
                                    value="{{ old('ci') }}"
                                    autocomplete="off"
                                    class="form-input-pill-2 w-full {{ $errors->has('ci') ? '!border-red-500 !ring-1 !ring-red-200' : '' }}"
                                    required
                                    aria-invalid="{{ $errors->has('ci') ? 'true' : 'false' }}">

                                @error('ci')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="extension_ci" class="form-label-bold-2">
                                    Extensión <span class="text-red-500">*</span>
                                </label>

                                <select
                                    id="extension_ci"
                                    name="extension_ci"
                                    class="form-select-pill-2 w-full {{ $errors->has('extension_ci') ? '!border-red-500' : '' }}"
                                    required>

                                    <option value="" disabled {{ old('extension_ci') ? '' : 'selected' }}>
                                        Seleccione
                                    </option>

                                    @foreach (['LP', 'SC', 'CB', 'CH', 'OR', 'PT', 'TJ', 'BE', 'PD'] as $extension)
                                        <option
                                            value="{{ $extension }}"
                                            {{ old('extension_ci') === $extension ? 'selected' : '' }}>
                                            {{ $extension }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('extension_ci')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-5">

                            <div>
                                <label for="select-pais" class="form-label-bold-2">
                                    País
                                </label>

                                <select
                                    name="id_pais"
                                    id="select-pais"
                                    class="form-select-pill-2 w-full {{ $errors->has('id_pais') ? '!border-red-500' : '' }}">

                                    <option value="">Seleccione País</option>

                                    @foreach ($paises as $pais)
                                        <option
                                            value="{{ $pais->id_pais }}"
                                            {{ (string) old('id_pais') === (string) $pais->id_pais ? 'selected' : '' }}>
                                            {{ $pais->nombre }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('id_pais')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="select-departamento" class="form-label-bold-2">
                                    Departamento
                                </label>

                                <select
                                    name="id_departamento"
                                    id="select-departamento"
                                    data-old="{{ old('id_departamento') }}"
                                    class="form-select-pill-2 w-full {{ $errors->has('id_departamento') ? '!border-red-500' : '' }}">

                                    <option value="">
                                        {{ old('id_pais') ? 'Cargando departamentos...' : 'Seleccione un país primero' }}
                                    </option>

                                </select>

                                @error('id_departamento')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="ciudad_residencia" class="form-label-bold-2">
                                    Lugar de Nacimiento <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="ciudad_residencia"
                                    name="ciudad_residencia"
                                    value="{{ old('ciudad_residencia') }}"
                                    class="form-input-pill-2 w-full {{ $errors->has('ciudad_residencia') ? '!border-red-500 !ring-1 !ring-red-200' : '' }}"
                                    required
                                    aria-invalid="{{ $errors->has('ciudad_residencia') ? 'true' : 'false' }}">

                                @error('ciudad_residencia')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">

                            <div>
                                <label for="domicilio" class="form-label-bold-2">
                                    Domicilio Actual
                                </label>

                                <input
                                    type="text"
                                    id="domicilio"
                                    name="domicilio"
                                    value="{{ old('domicilio') }}"
                                    autocomplete="street-address"
                                    class="form-input-pill-2 w-full {{ $errors->has('domicilio') ? '!border-red-500 !ring-1 !ring-red-200' : '' }}">

                                @error('domicilio')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="estado_civil" class="form-label-bold-2">
                                    Estado Civil <span class="text-red-500">*</span>
                                </label>

                                <select
                                    id="estado_civil"
                                    name="estado_civil"
                                    class="form-select-pill-2 w-full {{ $errors->has('estado_civil') ? '!border-red-500' : '' }}"
                                    required>

                                    <option value="" disabled {{ old('estado_civil') ? '' : 'selected' }}>
                                        Seleccione
                                    </option>

                                    @foreach ([
                                        'soltero' => 'Soltero/a',
                                        'casado' => 'Casado/a',
                                        'divorciado' => 'Divorciado/a',
                                        'viudo' => 'Viudo/a',
                                        'union_libre' => 'Unión libre',
                                    ] as $valor => $etiqueta)
                                        <option
                                            value="{{ $valor }}"
                                            {{ old('estado_civil') === $valor ? 'selected' : '' }}>
                                            {{ $etiqueta }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('estado_civil')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label-bold-2">
                                    Género
                                </label>

                                <div class="flex flex-wrap gap-6 mt-4">

                                    <label class="flex items-center gap-3 text-sm text-gray-700 cursor-pointer">
                                        <input
                                            type="radio"
                                            name="genero"
                                            value="M"
                                            class="accent-brand-gold w-5 h-5"
                                            {{ old('genero') === 'M' ? 'checked' : '' }}>
                                        Masculino
                                    </label>

                                    <label class="flex items-center gap-3 text-sm text-gray-700 cursor-pointer">
                                        <input
                                            type="radio"
                                            name="genero"
                                            value="F"
                                            class="accent-brand-gold w-5 h-5"
                                            {{ old('genero') === 'F' ? 'checked' : '' }}>
                                        Femenino
                                    </label>

                                </div>

                                @error('genero')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                    </div>

                    <div class="form-section-card">

                        <div class="mb-7">
                            <p class="text-brand-gold uppercase tracking-[0.25em] text-xs font-bold mb-2">
                                Sección 2
                            </p>

                            <h3 class="text-2xl font-bold text-brand-green">
                                Información Académica
                            </h3>

                            <p class="text-sm text-gray-500 mt-2">
                                Completa tus datos académicos y selecciona la opción que corresponda.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                            <div>
                                <label for="select-profesion" class="form-label-bold-2">
                                    Profesión <span class="text-red-500">*</span>
                                </label>

                                <select
                                    name="id_profesion"
                                    id="select-profesion"
                                    class="form-select-pill-2 w-full {{ $errors->has('id_profesion') ? '!border-red-500' : '' }}"
                                    required>

                                    <option value="">Seleccione Profesión</option>

                                    @foreach ($profesiones as $prof)
                                        <option
                                            value="{{ $prof->id_profesion }}"
                                            {{ (string) old('id_profesion') === (string) $prof->id_profesion ? 'selected' : '' }}>
                                            {{ $prof->nombre }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('id_profesion')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="select-grado" class="form-label-bold-2">
                                    Grado Académico <span class="text-red-500">*</span>
                                </label>

                                <select
                                    name="id_grado_academico"
                                    id="select-grado"
                                    class="form-select-pill-2 w-full {{ $errors->has('id_grado_academico') ? '!border-red-500' : '' }}"
                                    required>

                                    <option value="">Seleccione Grado</option>

                                    @foreach ($grados as $grado)
                                        <option
                                            value="{{ $grado->id_grado_academico }}"
                                            {{ (string) old('id_grado_academico') === (string) $grado->id_grado_academico ? 'selected' : '' }}>
                                            {{ $grado->nombre }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('id_grado_academico')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="select-institucion" class="form-label-bold-2">
                                    Institución <span class="text-red-500">*</span>
                                </label>

                                <select
                                    name="id_institucion_egreso"
                                    id="select-institucion"
                                    class="form-select-pill-2 w-full {{ $errors->has('id_institucion_egreso') ? '!border-red-500' : '' }}"
                                    required>

                                    <option value="">Seleccione Institución</option>

                                    @foreach ($instituciones as $inst)
                                        <option
                                            value="{{ $inst->id_institucion_egreso }}"
                                            {{ (string) old('id_institucion_egreso') === (string) $inst->id_institucion_egreso ? 'selected' : '' }}>
                                            {{ $inst->nombre }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('id_institucion_egreso')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                    </div>

                    <div class="form-section-card">

                        <div class="mb-7">
                            <p class="text-brand-gold uppercase tracking-[0.25em] text-xs font-bold mb-2">
                                Sección 3
                            </p>

                            <h3 class="text-2xl font-bold text-brand-green">
                                Datos de Contacto
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <div>
                                <label for="input-numero-movil" class="form-label-bold-2">
                                    Teléfono Móvil
                                </label>

                                <div class="flex gap-3">

                                    <select
                                        id="select-codigo-manual"
                                        class="form-select-pill-2 w-[35%]">

                                        <option value="+591">🇧🇴 +591</option>
                                        <option value="+54">🇦🇷 +54</option>

                                    </select>

                                    <input
                                        type="tel"
                                        id="input-numero-movil"
                                        class="form-input-pill-2 w-[65%]"
                                        placeholder="70000000"
                                        autocomplete="tel-national"
                                        value="{{ old('telefono_movil') ? preg_replace('/^\+\d+\s*/', '', old('telefono_movil')) : '' }}">

                                </div>

                                <input
                                    type="hidden"
                                    name="telefono_movil"
                                    id="telefono_movil_hidden"
                                    value="{{ old('telefono_movil') }}">

                                @error('telefono_movil')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="correo_electronico" class="form-label-bold-2">
                                    Correo Electrónico <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="email"
                                    id="correo_electronico"
                                    name="correo_electronico"
                                    value="{{ old('correo_electronico') }}"
                                    autocomplete="email"
                                    class="form-input-pill-2 w-full {{ $errors->has('correo_electronico') ? '!border-red-500 !ring-1 !ring-red-200' : '' }}"
                                    required
                                    aria-invalid="{{ $errors->has('correo_electronico') ? 'true' : 'false' }}">

                                @error('correo_electronico')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                    </div>

                    <div class="flex flex-col items-center pt-4 pb-14">

                        <p class="text-sm text-gray-500 text-center mb-5">
                            Los campos marcados con <span class="text-red-500 font-bold">*</span> son obligatorios.
                            Verifica tus datos antes de finalizar.
                        </p>

                        <button
                            type="submit"
                            id="btn-inscripcion"
                            class="btn-gold w-auto min-w-[220px] md:min-w-0 text-xs md:text-sm px-5 md:px-8 py-3 md:py-4">

                            Finalizar Inscripción
                        </button>

                    </div>

                </form>

            </div>
        </section>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        function abrirFormulario() {
            document.getElementById('slider').style.transform = 'translateX(-100vw)';
        }

        function cerrarFormulario() {
            document.getElementById('slider').style.transform = 'translateX(0)';
        }

        document.addEventListener('DOMContentLoaded', async () => {

            const paisSelect = document.getElementById('select-pais');
            const deptoSelect = document.getElementById('select-departamento');

            const codigoManual = document.getElementById('select-codigo-manual');
            const numeroMovilInput = document.getElementById('input-numero-movil');
            const telefonoHidden = document.getElementById('telefono_movil_hidden');

            function actualizarTelefonoCompleto() {
                const codigo = codigoManual.value;
                const numero = numeroMovilInput.value.trim();

                telefonoHidden.value = numero ? `${codigo} ${numero}` : '';
            }

            codigoManual.addEventListener('change', actualizarTelefonoCompleto);
            numeroMovilInput.addEventListener('input', actualizarTelefonoCompleto);

            const telefonoAnterior = @json(old('telefono_movil', ''));

            if (telefonoAnterior) {
                const codigoEncontrado = Array.from(codigoManual.options)
                    .find(option => telefonoAnterior.startsWith(option.value));

                if (codigoEncontrado) {
                    codigoManual.value = codigoEncontrado.value;
                }
            }

            actualizarTelefonoCompleto();

            async function cargarDepartamentos(paisId, departamentoSeleccionado = '') {

                if (!paisId) {
                    deptoSelect.innerHTML = '<option value="">Seleccione un país primero</option>';
                    return;
                }

                deptoSelect.innerHTML = '<option value="">Cargando departamentos...</option>';
                deptoSelect.disabled = true;

                try {
                    const response = await fetch(`/api/paises/${encodeURIComponent(paisId)}/departamentos`);

                    if (!response.ok) {
                        throw new Error('No se pudieron obtener los departamentos.');
                    }

                    const departamentos = await response.json();

                    deptoSelect.innerHTML = '<option value="">Seleccione Departamento</option>';

                    departamentos.forEach(departamento => {
                        const option = document.createElement('option');

                        option.value = departamento.id_departamento;
                        option.textContent = departamento.nombre;

                        if (String(departamentoSeleccionado) === String(departamento.id_departamento)) {
                            option.selected = true;
                        }

                        deptoSelect.appendChild(option);
                    });

                } catch (error) {
                    console.error(error);
                    deptoSelect.innerHTML = '<option value="">No se pudieron cargar los departamentos</option>';
                } finally {
                    deptoSelect.disabled = false;
                }
            }

            paisSelect.addEventListener('change', async () => {
                await cargarDepartamentos(paisSelect.value);
            });

            const paisAnterior = @json(old('id_pais', ''));
            const departamentoAnterior = deptoSelect.dataset.old || '';

            if (paisAnterior) {
                await cargarDepartamentos(paisAnterior, departamentoAnterior);
            }

            $('#select-profesion').select2({
                placeholder: 'Seleccione Profesión',
                allowClear: true,
                width: '100%'
            });

            $('#select-grado').select2({
                placeholder: 'Seleccione Grado',
                allowClear: true,
                width: '100%'
            });

            $('#select-institucion').select2({
                placeholder: 'Seleccione Institución',
                allowClear: true,
                width: '100%'
            });

            const tieneErrores = @json($errors->any());
            const registroExitoso = @json(session()->has('success'));

            if (tieneErrores || registroExitoso) {
                abrirFormulario();
            }

            if (tieneErrores) {
                requestAnimationFrame(() => {
                    document.getElementById('error-summary')?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                });
            }

            document.getElementById('form-inscripcion').addEventListener('submit', () => {
                const boton = document.getElementById('btn-inscripcion');

                boton.disabled = true;
                boton.textContent = 'Procesando inscripción...';
                boton.classList.add('opacity-70', 'cursor-not-allowed');
            });

        });
    </script>

</body>
</html>
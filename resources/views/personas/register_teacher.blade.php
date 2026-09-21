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

        <div class="bg-brand-green p-8 md:p-8 text-center relative border-b-4 border-brand-gold">
            <h2 class="text-white text-xl md:text-2xl font-sans font-bold tracking-widest uppercase">
                Formulario de Registro de Docente
            </h2>
            <div class="inline-block w-50 mt-5">
                <img src="/img/logoblanco.png" alt="" class="w-full h-full object-contain">
            </div>
        </div>

        <div class="p-6 md:p-10">
            @if ($errors->any())
                <div class="mb-8 p-5 bg-red-100 border-l-4 border-red-600 text-slate-900 rounded-r-xl shadow-sm">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-red-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <strong class="font-extrabold text-red-800 uppercase tracking-wide text-sm">¡Por favor corrige los
                            siguientes errores!</strong>
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1 pl-1 font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div
                    class="mb-8 p-5 bg-emerald-100 border-l-4 border-emerald-600 text-emerald-900 rounded-r-xl text-center font-bold text-base shadow-sm flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('docentes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-12">
                @csrf

                {{-- ==================== SECCIÓN 1: DATOS PERSONALES ==================== --}}
                <div class="space-y-6">
                    <div class="flex items-center space-x-3 rounded-lg p-3">
                        <div class="w-1 h-6 bg-brand-gold rounded"></div>
                        <h3 class="text-brand-green text-lg font-black uppercase tracking-wider">
                            1. Datos Personales
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" required
                                class="form-input-pill border-brand-green border @error('nombre') border-red-500 @enderror">
                            @error('nombre')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">
                                Primer Apellido <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="apellido_p" value="{{ old('apellido_p') }}" required
                                class="form-input-pill border-brand-green border @error('apellido_p') border-red-500 @enderror">
                            @error('apellido_p')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Segundo Apellido</label>
                            <input type="text" name="apellido_m" value="{{ old('apellido_m') }}"
                                class="form-input-pill border-brand-green border @error('apellido_m') border-red-500 @enderror">
                            @error('apellido_m')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">
                                Fecha de Nacimiento <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required
                                class="form-input-pill border-brand-green border @error('fecha_nacimiento') border-red-500 @enderror">
                            @error('fecha_nacimiento')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">
                                Carnet de Identidad <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="ci" value="{{ old('ci') }}" required inputmode="numeric"
                                class="form-input-pill border-brand-green border @error('ci') border-red-500 @enderror">
                            @error('ci')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">
                                Extensión del Carnet <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-col gap-2">
                                <select id="select-extension" name="extension_ci"
                                    class="form-select-pill border-brand-green border @error('extension_ci') border-red-500 @enderror">
                                    <option value="" disabled {{ old('extension_ci') ? '' : 'selected' }}>Seleccione extensión</option>
                                    @foreach(['LP', 'SC', 'CB', 'CH', 'OR', 'PT', 'TJ', 'BE', 'PD'] as $ext)
                                        <option value="{{ $ext }}" {{ old('extension_ci') === $ext ? 'selected' : '' }}>{{ $ext }}</option>
                                    @endforeach
                                    <option value="OTRO">OTRO (Escribir...)</option>
                                </select>
                                <input type="text" id="input-extension-otro"
                                    class="hidden form-input-pill border-brand-green border"
                                    placeholder="Escriba la extensión (ej: EXT)">
                            </div>
                            @error('extension_ci')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">
                                País de Residencia <span class="text-red-500">*</span>
                            </label>
                            <select name="id_pais" id="select-pais"
                                class="form-select-pill border-brand-green border @error('id_pais') border-red-500 @enderror">
                                <option value="">Seleccione País</option>
                                @foreach($paises as $pais)
                                    <option value="{{ $pais->id_pais }}" {{ old('id_pais') == $pais->id_pais ? 'selected' : '' }}>
                                        {{ $pais->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_pais')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">
                                Departamento de Residencia <span class="text-red-500">*</span>
                            </label>
                            <select name="id_departamento" id="select-departamento"
                                class="form-select-pill border-brand-green border @error('id_departamento') border-red-500 @enderror">
                                <option value="">Seleccione un país primero</option>
                            </select>
                            @error('id_departamento')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">
                                Ciudad de Residencia <span class="text-red-500">*</span>
                            </label>
                            <select name="id_ciudad" id="select-ciudad"
                                class="form-select-pill border-brand-green border @error('id_ciudad') border-red-500 @enderror">
                                <option value="">Seleccione un depto primero</option>
                            </select>
                            @error('id_ciudad')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Domicilio</label>
                            <input type="text" name="domicilio" value="{{ old('domicilio') }}"
                                class="form-input-pill border-brand-green border @error('domicilio') border-red-500 @enderror"
                                placeholder="Calle, número, zona">
                            @error('domicilio')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">

                        <div
                            class="bg-slate-50 p-5 rounded-xl border-2 {{ $errors->has('curriculum') ? 'border-red-500' : 'border-slate-300' }} hover:border-brand-green transition-all shadow-sm">
                            <label class="form-label-bold text-brand-green uppercase">
                                Curriculum Vitae (Documentado) <span class="text-red-500">*</span>
                            </label>
                            <p class="text-[11px] font-bold text-slate-500 mb-3">Requerido: Formato PDF</p>
                            <input type="file" name="curriculum" accept=".pdf" required
                                class="form-input-pill border-brand-green border">
                            @error('curriculum')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div
                            class="bg-slate-50 p-5 rounded-xl border-2 {{ $errors->has('fotocarnet') ? 'border-red-500' : 'border-slate-300' }} hover:border-brand-green transition-all shadow-sm">
                            <label class="form-label-bold text-brand-green uppercase">
                                Carnet de Identidad <span class="text-red-500">*</span>
                            </label>
                            <p class="text-[11px] font-bold text-slate-500 mb-3">Requerido: PDF, JPG o PNG</p>
                            <input type="file" name="fotocarnet" accept=".pdf,.jpg,.jpeg,.png" required
                                class="form-input-pill border-brand-green border">
                            @error('fotocarnet')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div
                            class="bg-slate-50 p-5 rounded-xl border-2 {{ $errors->has('fotografia') ? 'border-red-500' : 'border-slate-300' }} hover:border-brand-green transition-all shadow-sm">
                            <label class="form-label-bold text-brand-green uppercase">Fotografía de Perfil</label>
                            <p class="text-[11px] font-bold text-slate-500 mb-3">Formatos: JPG o PNG</p>
                            <input type="file" name="fotografia" accept=".jpg,.jpeg,.png"
                                class="form-input-pill border-brand-green border">
                            @error('fotografia')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">
                                Género <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-6 mt-1 text-slate-900">
                                <label
                                    class="flex items-center gap-2.5 cursor-pointer group text-sm font-bold bg-slate-50 px-4 py-2.5 rounded-lg border border-brand-green w-1/2 justify-center transition-all">
                                    <input type="radio" name="genero" value="M" class="w-4 h-4 accent-brand-green"
                                        {{ old('genero') === 'M' ? 'checked' : '' }}>
                                    <span>Masculino</span>
                                </label>
                                <label
                                    class="flex items-center gap-2.5 cursor-pointer group text-sm font-bold bg-slate-50 px-4 py-2.5 rounded-lg border border-brand-green w-1/2 justify-center transition-all">
                                    <input type="radio" name="genero" value="F" class="w-4 h-4 accent-brand-green"
                                        {{ old('genero') === 'F' ? 'checked' : '' }}>
                                    <span>Femenino</span>
                                </label>
                            </div>
                            @error('genero')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label-bold text-brand-green uppercase">
                                Entidad Bancaria <span class="text-red-500">*</span>
                            </label>
                            <select name="id_institucion_bancaria" id="select-banco"
                                class="form-select-pill border-brand-green border @error('id_institucion_bancaria') border-red-500 @enderror">
                                <option value="">Seleccione banco</option>
                                @foreach($bancos as $banco)
                                    <option value="{{ $banco->id_institucion_bancaria }}" {{ old('id_institucion_bancaria') == $banco->id_institucion_bancaria ? 'selected' : '' }}>
                                        {{ $banco->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_institucion_bancaria')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label-bold text-brand-green uppercase">
                                Número de Cuenta <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="numero_cuenta_bancaria" value="{{ old('numero_cuenta_bancaria') }}"
                                required
                                class="form-input-pill border-brand-green border @error('numero_cuenta_bancaria') border-red-500 @enderror"
                                placeholder="1234567890">
                            @error('numero_cuenta_bancaria')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ==================== SECCIÓN 2: FORMACIÓN ACADÉMICA ==================== --}}
                <div class="space-y-6">
                    <div class="flex items-center space-x-3 rounded-lg p-3">
                        <div class="w-1 h-6 bg-brand-gold rounded"></div>
                        <h3 class="text-brand-green text-lg font-black uppercase tracking-wider">
                            2. Formación Académica
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">
                                Profesión / Ocupación <span class="text-red-500">*</span>
                            </label>
                            <select name="id_profesion" id="select-profesion"
                                class="form-select-pill border-brand-green border @error('id_profesion') border-red-500 @enderror">
                                <option value="">Seleccione Profesión</option>
                                @foreach($profesiones as $prof)
                                    <option value="{{ $prof->id_profesion }}" {{ old('id_profesion') == $prof->id_profesion ? 'selected' : '' }}>
                                        {{ $prof->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_profesion')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label-bold text-brand-green uppercase">
                                Grado Académico <span class="text-red-500">*</span>
                            </label>
                            <select name="id_grado_academico" id="select-grado"
                                class="form-select-pill border-brand-green border @error('id_grado_academico') border-red-500 @enderror">
                                <option value="">Seleccione Grado</option>
                                @foreach($grados as $grado)
                                    <option value="{{ $grado->id_grado_academico }}" {{ old('id_grado_academico') == $grado->id_grado_academico ? 'selected' : '' }}>
                                        {{ $grado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_grado_academico')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label-bold text-brand-green uppercase">
                                Institución de Egreso <span class="text-red-500">*</span>
                            </label>
                            <select name="id_institucion_egreso" id="select-institucion"
                                class="form-select-pill border-brand-green border @error('id_institucion_egreso') border-red-500 @enderror">
                                <option value="">Seleccione Institución</option>
                                @foreach($instituciones as $inst)
                                    <option value="{{ $inst->id_institucion_egreso }}" {{ old('id_institucion_egreso') == $inst->id_institucion_egreso ? 'selected' : '' }}>
                                        {{ $inst->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_institucion_egreso')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ==================== SECCIÓN 3: CONTACTO Y ADICIONALES ==================== --}}
                <div class="space-y-6">
                    <div class="flex items-center space-x-3 rounded-lg p-3">
                        <div class="w-1 h-6 bg-brand-gold rounded"></div>
                        <h3 class="text-brand-green text-lg font-black uppercase tracking-wider">
                            3. Datos de Contacto y Adicionales
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">
                                Teléfono Móvil <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-2">
                                <select id="select-codigo-manual" name="codigo_pais_movil"
                                    class="form-select-pill border-brand-green border w-1/2 text-center px-1 @error('codigo_pais_movil') border-red-500 @enderror">
                                    @foreach(['+591' => '🇧🇴', '+54' => '🇦🇷', '+56' => '🇨🇱', '+51' => '🇵🇪', '+57' => '🇨🇴', '+1' => '🇺🇸', '+34' => '🇪🇸'] as $codigo => $bandera)
                                        <option value="{{ $codigo }}" {{ old('codigo_pais_movil', '+591') === $codigo ? 'selected' : '' }}>
                                            {{ $bandera }} {{ $codigo }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" id="input-numero-movil" name="numero_movil"
                                    value="{{ old('numero_movil') }}" required inputmode="numeric"
                                    class="form-input-pill border-brand-green border w-2/3 @error('numero_movil') border-red-500 @enderror"
                                    placeholder="70000000">
                            </div>
                            @error('codigo_pais_movil')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                            @error('numero_movil')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="correo_electronico" value="{{ old('correo_electronico') }}"
                                required
                                class="form-input-pill border-brand-green border @error('correo_electronico') border-red-500 @enderror"
                                placeholder="correo@ejemplo.com">
                            @error('correo_electronico')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Programas adicionales que le
                                gustaría impartir o impartió</label>
                            <textarea name="programas_dar"
                                class="w-full px-4 py-3 rounded-lg border border-brand-green focus:border-brand-green focus:ring-4 focus:ring-brand-green/10 outline-none transition duration-200 bg-slate-50 text-slate-900 font-medium h-28 resize-none"
                                placeholder="Mencione los programas">{{ old('programas_dar') }}</textarea>
                            @error('programas_dar')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex flex-col justify-center">
                            <label class="form-label-bold text-brand-green uppercase mb-3">¿Emite Factura?</label>
                            <div class="flex gap-4">
                                <label
                                    class="flex-1 flex items-center justify-center gap-3 p-3 rounded-lg border border-brand-green bg-slate-50 cursor-pointer">
                                    <input type="radio" name="emite_factura" value="1"
                                        class="w-5 h-5 accent-brand-green" {{ old('emite_factura', '0') === '1' ? 'checked' : '' }}>
                                    <span class="text-brand-green font-bold">SÍ</span>
                                </label>
                                <label
                                    class="flex-1 flex items-center justify-center gap-3 p-3 rounded-lg border border-brand-green bg-slate-50 cursor-pointer">
                                    <input type="radio" name="emite_factura" value="0"
                                        class="w-5 h-5 accent-brand-green" {{ old('emite_factura', '0') === '0' ? 'checked' : '' }}>
                                    <span class="text-brand-green font-bold">NO</span>
                                </label>
                            </div>
                            @error('emite_factura')
                                <p class="text-red-600 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                @error('error')
                    <div class="p-4 bg-red-50 border border-red-400 rounded-xl text-red-700 text-sm font-bold text-center">
                        {{ $message }}
                    </div>
                @enderror

                <div class="flex justify-center pt-6 pb-4">
                    <button type="submit" class="btn-gold">
                        Registrar Docente
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const paisSelect = document.getElementById('select-pais');
            const deptoSelect = document.getElementById('select-departamento');
            const ciudadSelect = document.getElementById('select-ciudad');
            const extensionSelect = document.getElementById('select-extension');
            const extensionOtroInput = document.getElementById('input-extension-otro');
            const codigoManual = document.getElementById('select-codigo-manual');
            const numeroMovilInput = document.getElementById('input-numero-movil');

            extensionSelect.addEventListener('change', function () {
                if (this.value === 'OTRO') {
                    extensionOtroInput.classList.remove('hidden');
                    extensionOtroInput.name = "extension_ci";
                    this.removeAttribute('name');
                    extensionOtroInput.focus();
                } else {
                    extensionOtroInput.classList.add('hidden');
                    extensionOtroInput.value = '';
                    extensionOtroInput.removeAttribute('name');
                    this.name = "extension_ci";
                }
            });

            // Evento para cargar departamentos cuando se selecciona un país
            paisSelect.addEventListener('change', async (e) => {
                const paisId = e.target.value;

                deptoSelect.innerHTML = '<option value="">Seleccione Departamento</option>';
                ciudadSelect.innerHTML = '<option value="">Seleccione un depto primero</option>';

                if (!paisId) {
                    deptoSelect.innerHTML = '<option value="">Seleccione un país primero</option>';
                    return;
                }

                deptoSelect.innerHTML = '<option value="">Cargando departamentos...</option>';

                try {
                    const response = await fetch(`/api/paises/${paisId}/departamentos`);
                    const departamentos = await response.json();

                    deptoSelect.innerHTML = '<option value="">Seleccione Departamento</option>';

                    if (departamentos.length === 0) {
                        deptoSelect.innerHTML = '<option value="">No hay departamentos disponibles</option>';
                    } else {
                        departamentos.forEach(d => {
                            deptoSelect.innerHTML += `<option value="${d.id_departamento}">${d.nombre}</option>`;
                        });
                    }
                } catch (error) {
                    console.error("Error cargando departamentos:", error);
                    deptoSelect.innerHTML = '<option value="">Error al cargar departamentos</option>';
                }
            });

            // Evento para cargar ciudades cuando se selecciona un departamento
            deptoSelect.addEventListener('change', async (e) => {
                const deptoId = e.target.value;

                ciudadSelect.innerHTML = '<option value="">Seleccione Ciudad</option>';

                if (!deptoId) {
                    ciudadSelect.innerHTML = '<option value="">Seleccione un depto primero</option>';
                    return;
                }

                ciudadSelect.innerHTML = '<option value="">Cargando ciudades...</option>';

                try {
                    const response = await fetch(`/api/departamentos/${deptoId}/ciudades`);
                    const ciudades = await response.json();

                    ciudadSelect.innerHTML = '<option value="">Seleccione Ciudad</option>';

                    if (ciudades.length === 0) {
                        ciudadSelect.innerHTML = '<option value="">No hay ciudades disponibles</option>';
                    } else {
                        ciudades.forEach(c => {
                            ciudadSelect.innerHTML += `<option value="${c.id_ciudad}">${c.nombre}</option>`;
                        });
                    }
                } catch (error) {
                    console.error("Error cargando ciudades:", error);
                    ciudadSelect.innerHTML = '<option value="">Error al cargar ciudades</option>';
                }
            });
        });

        // Inicializar Select2
        $(document).ready(function () {
            $('#select-profesion, #select-grado, #select-institucion, #select-banco').each(function () {
                $(this).select2({
                    placeholder: $(this).find('option:first').text(),
                    allowClear: true,
                    width: '100%'
                });
            });
        });
    </script>
</body>

</html>
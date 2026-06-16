<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body class="bg-slate-100 font-sans min-h-screen flex items-center justify-center p-4 md:p-8">

    <div class="max-w-5xl w-full bg-white shadow-2xl rounded-2xl overflow-hidden border border-slate-200">

        <div class="bg-brand-green p-8 md:p-8 text-center relative border-b-4 border-brand-gold">
            <h2 class="text-white text-xl md:text-2xl font-sans font-bold tracking-widest uppercase">
                Formulario de Registro de Personal
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

            <form action="{{ route('personas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-12">
                @csrf

                <div class="space-y-6">
                    <div class="flex items-center space-x-3 rounded-lg p-3">
                        <div class="w-1 h-6 bg-brand-gold rounded"></div>
                        <h3 class="text-brand-green text-lg font-black uppercase tracking-wider">
                            1. Datos Personales
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Nombre</label>
                            <input type="text" name="nombre" class="form-input-pill border-brand-green border">
                        </div>
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Primer
                                Apellido</label>
                            <input type="text" name="apellido_p" class="form-input-pill border-brand-green border">
                        </div>
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Segundo
                                Apellido</label>
                            <input type="text" name="apellido_m" class="form-input-pill border-brand-green border">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Fecha
                                de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento"
                                class="form-input-pill border-brand-green border">
                        </div>
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Carnet
                                de Identidad</label>
                            <input type="text" name="ci" class="form-input-pill border-brand-green border">
                        </div>
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Extensión
                                del Carnet</label>
                            <div class="flex flex-col gap-2">
                                <select id="select-extension" name="extension_ci"
                                    class="form-select-pill border-brand-green border">
                                    <option value="" selected disabled>Seleccione extensión</option>
                                    <option value="LP">LP</option>
                                    <option value="SC">SC</option>
                                    <option value="CB">CB</option>
                                    <option value="CH">CH</option>
                                    <option value="OR">OR</option>
                                    <option value="PT">PT</option>
                                    <option value="TJ">TJ</option>
                                    <option value="BE">BE</option>
                                    <option value="PD">PD</option>
                                    <option value="OTRO">OTRO (Escribir...)</option>
                                </select>
                                <input type="text" id="input-extension-otro"
                                    class="hidden form-input-pill border-brand-green border"
                                    placeholder="Escriba la extensión (ej: EXT)">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">País de
                                Residencia</label>
                            <select name="id_pais" id="select-pais" class="form-select-pill border-brand-green border">
                                <option value="">Seleccione País</option>
                                @foreach($paises as $pais)
                                    <option value="{{ $pais->id_pais }}">{{ $pais->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Departamento
                                de Residencia</label>
                            <select name="id_departamento" id="select-departamento"
                                class="form-select-pill border-brand-green border">
                                <option value="">Seleccione un país primero</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Ciudad
                                de Residencia</label>
                            <select name="id_ciudad" id="select-ciudad"
                                class="form-select-pill border-brand-green border">
                                <option value="">Seleccione un depto primero</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Domicilio</label>
                            <input type="text" name="domicilio" class="form-input-pill border-brand-green border"
                                placeholder="Calle, número, zona">
                        </div>
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Ubicación
                                (Google Maps)</label>
                            <input type="url" name="enlace_ubicacion_maps"
                                class="form-input-pill border-brand-green border"
                                placeholder="http://maps.google.com/...">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">

                        <div
                            class="bg-slate-50 p-5 rounded-xl border-2 border-slate-300 hover:border-brand-green transition-all shadow-sm">
                            <label class="form-label-bold text-brand-green uppercase">Curriculum
                                Vitae</label>
                            <p class="text-[11px] font-bold text-slate-500 mb-3">Requerido: Formato PDF</p>
                            <input type="file" name="curriculum" class="form-input-pill border-brand-green border">
                        </div>

                        <div
                            class="bg-slate-50 p-5 rounded-xl border-2 border-slate-300 hover:border-brand-green transition-all shadow-sm">
                            <label class="form-label-bold text-brand-green uppercase">Carnet
                                de Identidad</label>
                            <p class="text-[11px] font-bold text-slate-500 mb-3">Requerido: Formato PDF</p>
                            <input type="file" name="foto_carnet" class="form-input-pill border-brand-green border">
                        </div>

                        <div
                            class="bg-slate-50 p-5 rounded-xl border-2 border-slate-300 hover:border-brand-green transition-all shadow-sm">
                            <label class="form-label-bold text-brand-green uppercase">Fotografía
                                de Perfil</label>
                            <p class="text-[11px] font-bold text-slate-500 mb-3">Formatos: JPG o PNG</p>
                            <input type="file" name="fotografia" class="form-input-pill border-brand-green border">
                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Género</label>
                            <div class="flex gap-6 mt-1 text-slate-900">
                                <label
                                    class="flex items-center gap-2.5 cursor-pointer group text-sm font-bold bg-slate-50 px-4 py-2.5 rounded-lg border border-brand-green  w-1/2 justify-center transition-all">
                                    <input type="radio" name="genero" value="M" class="w-4 h-4 accent-brand-green">
                                    <span>Masculino</span>
                                </label>
                                <label
                                    class="flex items-center gap-2.5 cursor-pointer group text-sm font-bold bg-slate-50 px-4 py-2.5 rounded-lg border border-brand-green  w-1/2 justify-center transition-all">
                                    <input type="radio" name="genero" value="F" class="w-4 h-4 accent-brand-green">
                                    <span>Femenino</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Entidad
                                Bancaria</label>
                            <select name="id_institucion_bancaria" id="select-banco"
                                class="form-select-pill border-brand-green border">
                                <option value="">Seleccione banco</option>
                                @foreach($bancos as $banco)
                                    <option value="{{ $banco->id_institucion_bancaria }}">
                                        {{ $banco->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Número
                                de Cuenta</label>
                            <input type="text" name="numero_cuenta_bancaria"
                                class="form-input-pill border-brand-green border" placeholder="1234567890">
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center space-x-3 rounded-lg p-3">
                        <div class="w-1 h-6 bg-brand-gold rounded"></div>
                        <h3 class="text-brand-green text-lg font-black uppercase tracking-wider">
                            2. Habilidades Profesionales
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Habilidades
                                Técnicas</label>
                            <textarea name="habilidades_tecnicas"
                                class="w-full px-4 py-3 rounded-lg border border-brand-green focus:border-brand-green focus:ring-4 focus:ring-brand-green/10 outline-none transition duration-200 bg-slate-50 text-slate-900 font-medium h-28 resize-none"></textarea>
                        </div>
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Habilidades
                                Blandas</label>
                            <textarea name="habilidades_blandas"
                                class="w-full px-4 py-3 rounded-lg border border-brand-green focus:border-brand-green focus:ring-4 focus:ring-brand-green/10 outline-none transition duration-200 bg-slate-50 text-slate-900 font-medium h-28 resize-none"></textarea>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center space-x-3 rounded-lg p-3">
                        <div class="w-1 h-6 bg-brand-gold rounded"></div>
                        <h3 class="text-brand-green text-lg font-black uppercase tracking-wider">
                            3. Formación Académica
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Profesión
                                / Ocupación</label>
                            <select name="id_profesion" id="select-profesion"
                                class="form-select-pill border-brand-green border">
                                <option value="">Seleccione Profesión</option>
                                @foreach($profesiones as $prof)
                                    <option value="{{ $prof->id_profesion }}">{{ $prof->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Grado
                                Académico</label>
                            <select name="id_grado_academico" id="select-grado"
                                class="form-select-pill border-brand-green border">
                                <option value="">Seleccione Grado</option>
                                @foreach($grados as $grado)
                                    <option value="{{ $grado->id_grado_academico }}">{{ $grado->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Institución
                                de Egreso</label>
                            <select name="id_institucion_egreso" id="select-institucion"
                                class="form-select-pill border-brand-green border">
                                <option value="">Seleccione Institución</option>
                                @foreach($instituciones as $inst)
                                    <option value="{{ $inst->id_institucion_egreso }}">{{ $inst->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center space-x-3 rounded-lg p-3">
                        <div class="w-1 h-6 bg-brand-gold rounded"></div>
                        <h3 class="text-brand-green text-lg font-black uppercase tracking-wider">
                            4. Datos de Contacto y Referencias
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="form-label-bold text-brand-green uppercase">Teléfono
                                Móvil</label>
                            <div class="flex gap-2">
                                <select id="select-codigo-manual" class="form-select-pill border-brand-green border">
                                    <option value="+591">🇧🇴 +591</option>
                                    <option value="+54">🇦🇷 +54</option>
                                    <option value="+56">🇨🇱 +56</option>
                                    <option value="+51">🇵🇪 +51</option>
                                    <option value="+1">🇺🇸 +1</option>
                                </select>
                                <input type="text" id="input-numero-movil"
                                    class="form-input-pill border-brand-green border" placeholder="70000000">
                                <input type="hidden" name="telefono_movil" id="telefono_movil_hidden">
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="form-label-bold text-brand-green uppercase">Correo
                                Electrónico</label>
                            <input type="email" name="correo_electronico"
                                class="form-input-pill border-brand-green border" placeholder="correo@ejemplo.com">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">

                        <div class="bg-slate-50 p-5 rounded-xl border-2 border-slate-200 space-y-4 shadow-sm">
                            <div
                                class="text-xs font-black text-brand-green tracking-wide uppercase border-b-2 border-slate-300 pb-2 flex items-center">
                                <span class="form-label-bold text-brand-green uppercase">
                                    Referencia Familiar 1
                                </span>
                            </div>
                            <div>
                                <label class="form-label-bold text-brand-green uppercase">Nombre
                                    Completo</label>
                                <input type="text" name="referencia_familiar_1"
                                    class="form-input-pill border-brand-green border">
                            </div>
                            <div>
                                <label class="form-label-bold text-brand-green uppercase">Teléfono
                                    Celular</label>
                                <input type="text" name="celular_familiar_1"
                                    class="form-input-pill border-brand-green border" placeholder="Ej: 70000000">
                            </div>
                        </div>

                        <div class="bg-slate-50 p-5 rounded-xl border-2 border-slate-200 space-y-4 shadow-sm">
                            <div
                                class="text-xs font-black text-brand-green tracking-wide uppercase border-b-2 border-slate-300 pb-2 flex items-center">
                                <span class="form-label-bold text-brand-green uppercase">
                                    Referencia Familiar 2
                                </span>
                            </div>
                            <div>
                                <label class="form-label-bold text-brand-green uppercase">Nombre
                                    Completo</label>
                                <input type="text" name="referencia_familiar_2"
                                    class="form-input-pill border-brand-green border">
                            </div>
                            <div>
                                <label class="form-label-bold text-brand-green uppercase">Teléfono
                                    Celular</label>
                                <input type="text" name="celular_familiar_2"
                                    class="form-input-pill border-brand-green border" placeholder="Ej: 70000000">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="flex justify-center pt-6 pb-4">
                    <button type="submit" class="btn-gold">
                        Registrar Personal
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
            const telefonoHidden = document.getElementById('telefono_movil_hidden');

            const actualizarTelefonoCompleto = () => {
                const codigo = codigoManual.value;
                const numero = numeroMovilInput.value.trim();
                if (numero !== "") {
                    telefonoHidden.value = `${codigo} ${numero}`;
                } else {
                    telefonoHidden.value = "";
                }
            };

            codigoManual.addEventListener('change', actualizarTelefonoCompleto);
            numeroMovilInput.addEventListener('input', actualizarTelefonoCompleto);

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

                // Limpiar selects dependientes
                deptoSelect.innerHTML = '<option value="">Seleccione Departamento</option>';
                ciudadSelect.innerHTML = '<option value="">Seleccione un depto primero</option>';

                if (!paisId) {
                    deptoSelect.innerHTML = '<option value="">Seleccione un país primero</option>';
                    return;
                }

                deptoSelect.innerHTML = '<option value="">Cargando departamentos...</option>';

                try {
                    // Obtener departamentos desde la API
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
                    // Obtener ciudades desde la API
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

            actualizarTelefonoCompleto();
        });

        // Inicializar Select2
        $(document).ready(function () {
            $('#select-profesion').select2({
                placeholder: "Seleccione Profesión",
                allowClear: true,
                width: '100%'
            });

            $('#select-grado').select2({
                placeholder: "Seleccione Grado",
                allowClear: true,
                width: '100%'
            });

            $('#select-institucion').select2({
                placeholder: "Seleccione Institución",
                allowClear: true,
                width: '100%'
            });

            $('#select-banco').select2({
                placeholder: "Seleccione banco",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
</body>

</html>
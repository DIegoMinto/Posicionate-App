<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-green font-sans min-h-screen flex">

    <div class="hidden lg:block lg:w-1/3 h-screen border-r-4 border-brand-gold overflow-hidden"
        style="background-color: white;">
        PONER IMAGENES AQUÍ
    </div>
    <div class="w-full lg:w-2/3 p-6 md:p-8 overflow-y-auto">
        <h2 class="text-white text-3xl font-bold text-center mb-10 tracking-widest uppercase">
            Formulario de Registro de Personal
        </h2>
        {{-- Mensajes de Error de Validación --}}
        @if ($errors->any())
            <div class="max-w-5xl mx-auto mb-6 p-4 bg-red-500/20 border-2 border-red-500 text-white rounded-2xl">
                <strong class="block font-bold mb-2 text-red-400 uppercase tracking-wide">¡Ups! Algo salió mal:</strong>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Mensaje de Éxito (opcional) --}}
        @if (session('success'))
            <div
                class="max-w-5xl mx-auto mb-6 p-4 bg-green-500/20 border-2 border-green-500 text-white rounded-2xl text-center font-bold">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('personas.store') }}" method="POST" enctype="multipart/form-data"
            class="max-w-5xl mx-auto space-y-6">
            @csrf

            <div class="space-y-6">
                <h3 class="text-white text-xl font-bold border-b border-brand-gold pb-2">
                    DATOS PERSONALES
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="form-label-bold">Nombre</label>
                        <input type="text" name="nombre" class="form-input-pill">
                    </div>
                    <div>
                        <label class="form-label-bold">Primer Apellido</label>
                        <input type="text" name="apellido_p" class="form-input-pill">
                    </div>
                    <div>
                        <label class="form-label-bold">Segundo Apellido</label>
                        <input type="text" name="apellido_m" class="form-input-pill">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label-bold">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-input-pill text-gray-400">
                    </div>
                    <div>
                        <label class="form-label-bold">Carnet de Identidad</label>
                        <input type="text" name="ci" class="form-input-pill">
                    </div>
                    <div>
                        <label class="form-label-bold">Extensión del Carnet</label>
                        <div class="flex flex-col gap-2">
                            <select id="select-extension" name="extension_ci" class="form-select-pill">
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
                                class="hidden form-input-pill border-brand-gold"
                                placeholder="Escriba la extensión (ej: EXT)">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="form-label-bold">País</label>
                        <select name="id_pais" id="select-pais" class="form-select-pill">
                            <option value="">Seleccione País</option>
                            @foreach($paises as $pais)
                                <option value="{{ $pais->id_pais }}">{{ $pais->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label-bold">Departamento</label>
                        <select name="id_departamento" id="select-departamento" class="form-select-pill">
                            <option value="">Seleccione un país primero</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label-bold">Ciudad</label>
                        <select name="id_ciudad" id="select-ciudad" class="form-select-pill">
                            <option value="">Seleccione un depto primero</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label-bold tracking-wide">Domicilio</label>
                        <input type="text" name="domicilio" class="form-input-pill" placeholder="Calle, número, zona">
                    </div>

                    <div>
                        <label class="form-label-bold tracking-wide">Ubicación (Google Maps)</label>
                        <input type="url" name="enlace_ubicacion_maps" class="form-input-pill"
                            placeholder="https://maps.google.com/...">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div
                        class="bg-black/20 p-4 rounded-2xl border border-white/10 hover:border-brand-gold transition duration-300 group">
                        <label
                            class="form-label-bold text-brand-gold group-hover:tracking-wider transition-all">Curriculum</label>
                        <p class="text-xs text-white/70 mb-2">Archivo PDF</p>
                        <input type="file" name="curriculum"
                            class="w-full text-sm text-white file:bg-brand-gold file:text-black file:font-bold file:px-3 file:py-1 file:rounded-lg file:border-0 cursor-pointer">
                    </div>

                    <div
                        class="bg-black/20 p-4 rounded-2xl border border-white/10 hover:border-brand-gold transition duration-300 group">
                        <label class="form-label-bold text-brand-gold group-hover:tracking-wider transition-all">Carnet
                            de Identidad</label>
                        <p class="text-xs text-white/70 mb-2">Archivo PDF</p>
                        <input type="file" name="foto_carnet"
                            class="w-full text-sm text-white file:bg-brand-gold file:text-black file:font-bold file:px-3 file:py-1 file:rounded-lg file:border-0 cursor-pointer">
                    </div>

                    <div
                        class="bg-black/20 p-4 rounded-2xl border border-white/10 hover:border-brand-gold transition duration-300 group">
                        <label
                            class="form-label-bold text-brand-gold group-hover:tracking-wider transition-all">Fotografía</label>
                        <p class="text-xs text-white/70 mb-2">JPG o PNG</p>
                        <input type="file" name="fotografia"
                            class="w-full text-sm text-white file:bg-brand-gold file:text-black file:font-bold file:px-3 file:py-1 file:rounded-lg file:border-0 cursor-pointer">
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div>
                        <label class="form-label-bold">Género</label>
                        <div class="flex gap-6 mt-2 text-white">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="genero" value="M" class="w-5 h-5 accent-brand-gold"> Masculino
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="genero" value="F" class="w-5 h-5 accent-brand-gold"> Femenino
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="form-label-bold">Entidad Bancaria</label>
                        <select name="id_institucion_bancaria" class="form-select-pill">
                            <option value="">Seleccione banco</option>
                            @foreach($bancos as $banco)
                                <option value="{{ $banco->id_institucion_bancaria }}">
                                    {{ $banco->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label-bold">Número de Cuenta</label>
                        <input type="text" name="numero_cuenta_bancaria" class="form-input-pill"
                            placeholder="1234567890">
                    </div>

                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-white text-xl font-bold border-b border-brand-gold pb-2">
                    HABILIDADES
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="form-label-bold">Habilidades Técnicas</label>
                        <textarea name="habilidades_tecnicas" class="form-input-pill rounded-2xl h-28 py-3"></textarea>
                    </div>

                    <div>
                        <label class="form-label-bold">Habilidades Blandas</label>
                        <textarea name="habilidades_blandas" class="form-input-pill rounded-2xl h-28 py-3"></textarea>
                    </div>

                </div>
            </div>

            <div class="space-y-6">
                <h3 class="text-white text-xl font-bold border-b border-brand-gold pb-2 uppercase">
                    Información Académica
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="form-label-bold">Profesión / Ocupación</label>
                        <select name="id_profesion" class="form-select-pill">
                            <option value="">Seleccione Profesión</option>
                            @foreach($profesiones as $prof)
                                <option value="{{ $prof->id_profesion }}">{{ $prof->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label-bold">Grado Académico</label>
                        <select name="id_grado_academico" class="form-select-pill">
                            <option value="">Seleccione Grado</option>
                            @foreach($grados as $grado)
                                <option value="{{ $grado->id_grado_academico }}">{{ $grado->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label-bold">Institución de Egreso</label>
                        <select name="id_institucion_egreso" class="form-select-pill">
                            <option value="">Seleccione Institución</option>
                            @foreach($instituciones as $inst)
                                <option value="{{ $inst->id_institucion_egreso }}">{{ $inst->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <h3 class="text-white text-xl font-bold border-b border-brand-gold pb-2">
                    DATOS DE CONTACTO
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="form-label-bold">Teléfono Móvil</label>
                        <div class="flex gap-2">
                            <select id="select-codigo-manual" class="form-select-pill w-1/2 text-center px-1">
                                <option value="+591">🇧🇴 +591</option>
                                <option value="+54">🇦🇷 +54</option>
                                <option value="+56">🇨🇱 +56</option>
                                <option value="+51">🇵🇪 +51</option>
                                <option value="+1">🇺🇸 +1</option>
                            </select>

                            <input type="text" id="input-numero-movil" class="form-input-pill w-2/3"
                                placeholder="70000000">

                            <input type="hidden" name="telefono_movil" id="telefono_movil_hidden">
                        </div>
                    </div>
                    <div>
                        <label class="form-label-bold">Correo Electrónico</label>
                        <input type="email" name="correo_electronico" class="form-input-pill">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div
                        class="bg-black/20 p-5 rounded-2xl border border-white/10 space-y-3 hover:border-brand-gold transition">
                        <label class="form-label-bold text-white">Referencia Familiar 1</label>
                        <input type="text" name="referencia_familiar_1" class="form-input-pill"
                            placeholder="Nombre completo">
                    </div>
                    <div
                        class="bg-black/20 p-5 rounded-2xl border border-white/10 space-y-3 hover:border-brand-gold transition">
                        <label class="form-label-bold text-white">Celular Referencia Familiar 1</label>
                        <input type="text" name="celular_familiar_1" class="form-input-pill" placeholder="Teléfono">
                    </div>

                    <div
                        class="bg-black/20 p-5 rounded-2xl border border-white/10 space-y-3 hover:border-brand-gold transition">
                        <label class="form-label-bold text-white">Referencia Familiar 2</label>
                        <input type="text" name="referencia_familiar_2" class="form-input-pill"
                            placeholder="Nombre completo">
                    </div>
                    <div
                        class="bg-black/20 p-5 rounded-2xl border border-white/10 space-y-3 hover:border-brand-gold transition">
                        <label class="form-label-bold text-white">Celular Referencia Familiar 2</label>
                        <input type="text" name="celular_familiar_2" class="form-input-pill" placeholder="Teléfono">
                    </div>

                </div>
            </div>

            <div class="flex justify-center pt-6 pb-12">
                <button type="submit" class="btn-gold w-40">
                    Registrar
                </button>
            </div>

        </form>


    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- SELECTORES ---
            const paisSelect = document.getElementById('select-pais');
            const deptoSelect = document.getElementById('select-departamento');
            const ciudadSelect = document.getElementById('select-ciudad');
            const extensionSelect = document.getElementById('select-extension');
            const extensionOtroInput = document.getElementById('input-extension-otro');
            const codigoManual = document.getElementById('select-codigo-manual');
            const numeroMovilInput = document.getElementById('input-numero-movil');
            const telefonoHidden = document.getElementById('telefono_movil_hidden');

            // --- 1. LÓGICA DE TELÉFONO (CONCATENACIÓN) ---
            const actualizarTelefonoCompleto = () => {
                const codigo = codigoManual.value; // Ej: +591
                const numero = numeroMovilInput.value.trim(); // Ej: 75780041

                // Si hay un número escrito, concatenamos con espacio, si no, lo dejamos vacío o solo el código
                if (numero !== "") {
                    telefonoHidden.value = `${codigo} ${numero}`; // Fíjate en el espacio aquí: "+591 75780041"
                } else {
                    telefonoHidden.value = "";
                }
            };

            codigoManual.addEventListener('change', actualizarTelefonoCompleto);
            numeroMovilInput.addEventListener('input', actualizarTelefonoCompleto);

            // --- 2. LÓGICA DE EXTENSIÓN DE CARNET (INDEPENDIENTE) ---
            extensionSelect.addEventListener('change', function () {
                if (this.value === 'OTRO') {
                    extensionOtroInput.classList.remove('hidden');
                    extensionOtroInput.name = "extension_ci"; // El input de texto enviará el dato
                    this.removeAttribute('name'); // El select deja de enviar dato para no duplicar
                    extensionOtroInput.focus();
                } else {
                    extensionOtroInput.classList.add('hidden');
                    extensionOtroInput.value = '';
                    extensionOtroInput.removeAttribute('name');
                    this.name = "extension_ci"; // El select vuelve a ser el dueño del dato
                }
            });

            const departamentosBolivia = [
                { id: 1, nombre: 'Chuquisaca' },
                { id: 2, nombre: 'La Paz' },
                { id: 3, nombre: 'Santa Cruz' },
                { id: 4, nombre: 'Cochabamba' },
                { id: 5, nombre: 'Oruro' },
                { id: 6, nombre: 'Potosí' },
                { id: 7, nombre: 'Tarija' },
                { id: 8, nombre: 'Beni' },
                { id: 9, nombre: 'Pando' }
            ];

            paisSelect.addEventListener('change', (e) => {
                const paisId = e.target.value;
                deptoSelect.innerHTML = '<option value="">Seleccione Departamento</option>';
                ciudadSelect.innerHTML = '<option value="">Seleccione depto primero</option>';

                if (paisId == "1") { // Suponiendo que 1 es Bolivia
                    departamentosBolivia.forEach(d => {
                        deptoSelect.innerHTML += `<option value="${d.id}">${d.nombre}</option>`;
                    });
                } else if (paisId) {
                    deptoSelect.innerHTML = '<option value="">Solo disponible Bolivia</option>';
                }
            });

            // --- 4. LÓGICA DE CIUDADES DINÁMICAS (SIN TOCAR CI) ---
            deptoSelect.addEventListener('change', async (e) => {
                const deptoId = e.target.value;
                if (!deptoId) return;

                ciudadSelect.innerHTML = '<option value="">Cargando ciudades...</option>';
                try {
                    const response = await fetch(`/api/departamentos/${deptoId}/ciudades`);
                    const ciudades = await response.json();

                    ciudadSelect.innerHTML = '<option value="">Seleccione Ciudad</option>';
                    ciudades.forEach(c => {
                        ciudadSelect.innerHTML += `<option value="${c.id_ciudad}">${c.nombre}</option>`;
                    });
                } catch (error) {
                    console.error("Error cargando ciudades:", error);
                    ciudadSelect.innerHTML = '<option value="">Error al cargar</option>';
                }
            });

            // Inicialización
            actualizarTelefonoCompleto();
        });
    </script>
</body>

</html>
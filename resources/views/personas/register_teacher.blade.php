<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Docente - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body class="bg-brand-green font-sans min-h-screen flex">

    <div class="hidden lg:block lg:w-1/3 h-full border-r-4 border-brand-gold overflow-hidden"
        style="background-color: white;">
        <img src="/img/fondo_arbol.jpeg" class="w-full h-full object-cover" alt="">
    </div>
    <div class="w-full lg:w-2/3 p-6 md:p-8 overflow-y-auto">
        <h2 class="text-white text-3xl font-bold text-center mb-10 tracking-widest uppercase">
            Formulario de Registro de Docente
        </h2>
        @if ($errors->any())
            <div class="max-w-5xl mx-auto mb-6 p-4 bg-red-500/20 border border-red-500 text-white rounded-2xl">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('docentes.store') }}" method="POST" enctype="multipart/form-data"
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
                        <input type="date" name="fecha_nacimiento" class="form-input-pill text-black">
                    </div>
                    <div>
                        <label class="form-label-bold">Carnet de Identidad</label>
                        <input type="text" name="ci" class="form-input-pill">
                    </div>
                    <div>
                        <label class="form-label-bold">Extensión del Carnet</label>
                        <div class="flex flex-col gap-2">
                            <select id="select-extension" name="extension_select" class="form-select-pill">
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

                            <input type="text" id="input-extension-otro" name="extension_otro"
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
                        <input type="file" name="fotocarnet"
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



            <div class="space-y-6">
                <h3 class="text-white text-xl font-bold border-b border-brand-gold pb-2 uppercase">
                    Información Académica
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="form-label-bold">Profesión / Ocupación</label>
                        <select name="id_profesion" id="select-profesion" class="form-select-pill">
                            <option value="">Seleccione Profesión</option>
                            @foreach($profesiones as $prof)
                                <option value="{{ $prof->id_profesion }}">{{ $prof->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label-bold">Grado Académico</label>
                        <select name="id_grado_academico" id="select-grado" class="form-select-pill">
                            <option value="">Seleccione Grado</option>
                            @foreach($grados as $grado)
                                <option value="{{ $grado->id_grado_academico }}">{{ $grado->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label-bold">Institución de Egreso</label>
                        <select name="id_institucion_egreso" id="select-institucion" class="form-select-pill">
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
                                <option value="+57">🇨🇴 +57</option>
                                <option value="+1">🇺🇸 +1</option>
                                <option value="+34">🇪🇸 +34</option>
                            </select>

                            <input type="text" id="input-numero-movil" class="form-input-pill w-2/3">

                            <input type="hidden" name="telefono_movil" id="telefono_movil_hidden">
                        </div>
                    </div>

                    <div>
                        <label class="form-label-bold">Correo Electrónico</label>
                        <input type="email" name="correo_electronico" class="form-input-pill">
                    </div>
                </div>


            </div>

            <div class="space-y-4">
                <h3 class="text-white text-xl font-bold border-b border-brand-gold pb-2 uppercase">
                    Adicionales
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label-bold">Programas adicionales que le gustaría impartir o impartió</label>
                        <textarea name="programas_dar" class="form-input-pill rounded-2xl h-28 py-3"
                            placeholder="Mencione los programas"></textarea>
                    </div>

                    <div class="flex flex-col justify-center">
                        <label class="form-label-bold mb-3">¿Emite Factura?</label>
                        <div class="flex gap-4">
                            <label
                                class="flex-1 flex items-center justify-center gap-3 p-3 rounded-pill bg-black/20 border border-white/10 cursor-pointer hover:border-brand-gold transition group">
                                <input type="radio" name="emite_factura" value="1" class="w-5 h-5 accent-brand-gold">
                                <span class="text-white font-bold group-hover:text-brand-gold">SÍ</span>
                            </label>

                            <label
                                class="flex-1 flex items-center justify-center gap-3 p-3 rounded-pill bg-black/20 border border-white/10 cursor-pointer hover:border-brand-gold transition group">
                                <input type="radio" name="emite_factura" value="0" class="w-5 h-5 accent-brand-gold"
                                    checked>
                                <span class="text-white font-bold group-hover:text-brand-gold">NO</span>
                            </label>
                        </div>
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
                    extensionOtroInput.focus();
                } else {
                    extensionOtroInput.classList.add('hidden');
                    extensionOtroInput.value = '';
                }
            });

            const departamentosBolivia = [
                { id: 1, nombre: 'Chuquisaca', ext: 'CH' },
                { id: 2, nombre: 'La Paz', ext: 'LP' },
                { id: 3, nombre: 'Santa Cruz', ext: 'SC' },
                { id: 4, nombre: 'Cochabamba', ext: 'CB' },
                { id: 5, nombre: 'Oruro', ext: 'OR' },
                { id: 6, nombre: 'Potosí', ext: 'PT' },
                { id: 7, nombre: 'Tarija', ext: 'TJ' },
                { id: 8, nombre: 'Beni', ext: 'BE' },
                { id: 9, nombre: 'Pando', ext: 'PD' }
            ];

            paisSelect.addEventListener('change', (e) => {
                const paisId = e.target.value;

                deptoSelect.innerHTML = '<option value="">Seleccione Departamento</option>';
                ciudadSelect.innerHTML = '<option value="">Seleccione depto primero</option>';

                if (!paisId) return;

                if (paisId == "1") {
                    departamentosBolivia.forEach(d => {
                        deptoSelect.innerHTML += `<option value="${d.id}" data-ext="${d.ext}">${d.nombre}</option>`;
                    });
                } else {
                    deptoSelect.innerHTML = '<option value="">Solo disponible Bolivia</option>';
                }
            });

            deptoSelect.addEventListener('change', async (e) => {
                const deptoId = e.target.value;
                const selectedOption = e.target.options[e.target.selectedIndex];

                if (selectedOption.dataset.ext) {
                    extensionSelect.value = selectedOption.dataset.ext;
                    extensionSelect.dispatchEvent(new Event('change'));
                }

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
            actualizarTelefonoCompleto();
        });
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
    </script>
</body>

</html>h
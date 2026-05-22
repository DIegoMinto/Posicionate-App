<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - {{ $curso->nombre }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body class="bg-brand-green font-sans min-h-screen flex">

    <div class="hidden lg:block lg:w-1/3 h-full border-r-4 border-brand-gold overflow-hidden"
        style="background-color: white;">
        <img src="/img/sky_fondo.jpg" class="w-full h-full object-cover" alt="">
    </div>

    <div class="w-full lg:w-2/3 p-6 md:p-8 overflow-y-auto">
        <h2 class="text-white text-3xl font-bold text-center mb-5 tracking-widest uppercase">
            Ficha de Inscripción
        </h2>
        <h3 class="text-white text-3xl text-center font-bold uppercase tracking-widest mb-4 ">
            {{ $curso->nombre }}
        </h3>

        <div class="flex flex-wrap justify-center gap-4 md:gap-8 mb-10">
            <div class="flex items-center gap-2 text-white/80 text-xs uppercase font-bold">
                <span class="text-brand-gold">Asesor:</span>
                <span class="text-white"> {{ $asesor->persona->nombre }} {{ $asesor->persona->apellido_p }}</span>
            </div>
        </div>

        @if (session('success'))
            <div
                class="max-w-5xl mx-auto mb-6 p-4 bg-emerald-500/20 border-2 border-emerald-500 text-white rounded-2xl flex items-center gap-3">
                <svg class="w-6 h-6 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm font-medium tracking-wide">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="max-w-5xl mx-auto mb-6 p-4 bg-red-500/20 border-2 border-red-500 text-white rounded-2xl">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('inscripcion.store') }}" method="POST" enctype="multipart/form-data"
            class="max-w-5xl mx-auto space-y-6">
            @csrf
            <input type="hidden" name="id_curso" value="{{ $curso->id_curso }}">
            <input type="hidden" name="id_personal" value="{{ $asesor->id_personal }}">
            <div class="space-y-6">
                <h3 class="text-white text-xl font-bold border-b border-brand-gold pb-2">
                    DATOS PERSONALES
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="form-label-bold">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-input-pill" required>
                    </div>
                    <div>
                        <label class="form-label-bold">Primer Apellido</label>
                        <input type="text" name="apellido_p" value="{{ old('apellido_p') }}" class="form-input-pill"
                            required>
                    </div>
                    <div>
                        <label class="form-label-bold">Segundo Apellido</label>
                        <input type="text" name="apellido_m" value="{{ old('apellido_m') }}" class="form-input-pill">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label-bold">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                            class="form-input-pill text-black" required>
                    </div>
                    <div>
                        <label class="form-label-bold">Carnet de Identidad</label>
                        <input type="text" name="ci" value="{{ old('ci') }}" class="form-input-pill" required>
                    </div>
                    <div>
                        <label class="form-label-bold">Extensión</label>
                        <select id="select-extension" name="extension_ci" class="form-select-pill" required>
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
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="form-label-bold">País de Residencia</label>
                        <select name="id_pais" id="select-pais" class="form-select-pill">
                            <option value="">Seleccione País</option>
                            @foreach($paises as $pais)
                                <option value="{{ $pais->id_pais }}">{{ $pais->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label-bold">Departamento de Residencia</label>
                        <select name="id_departamento" id="select-departamento" class="form-select-pill">
                            <option value="">Seleccione un país primero</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label-bold">Ciudad de Residencia</label>
                        <input type="text" name="ciudad_residencia" value="{{ old('ciudad_residencia') }}"
                            class="form-input-pill" placeholder="Ingrese su ciudad" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label-bold">Domicilio</label>
                        <input type="text" name="domicilio" value="{{ old('domicilio') }}" class="form-input-pill"
                            placeholder="Calle, número, zona">
                    </div>
                    <div>
                        <label class="form-label-bold">Género</label>
                        <div class="flex gap-6 mt-2 text-white">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="genero" value="M" {{ old('genero') == 'M' ? 'checked' : '' }}
                                    class="w-5 h-5 accent-brand-gold"> Masculino
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="genero" value="F" {{ old('genero') == 'F' ? 'checked' : '' }}
                                    class="w-5 h-5 accent-brand-gold"> Femenino
                            </label>
                        </div>
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
                                <option value="{{ $inst->id_institucion_egreso }}">
                                    {{ $inst->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <h3 class="text-white text-xl font-bold border-b border-brand-gold pb-2">
                    DATOS DE CONTACTO
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label-bold">Teléfono Móvil</label>
                        <div class="flex gap-2">
                            <select id="select-codigo-manual" class="form-select-pill w-1/3 text-center">
                                <option value="+591">🇧🇴 +591</option>
                                <option value="+54">🇦🇷 +54</option>
                            </select>
                            <input type="text" id="input-numero-movil" class="form-input-pill w-2/3"
                                placeholder="70000000">
                            <input type="hidden" name="telefono_movil" id="telefono_movil_hidden">
                        </div>
                    </div>
                    <div>
                        <label class="form-label-bold">Correo Electrónico</label>
                        <input type="email" name="correo_electronico" value="{{ old('correo_electronico') }}"
                            class="form-input-pill">
                    </div>
                </div>
            </div>

            <div class="flex justify-center pt-6 pb-12">
                <button type="submit" class="btn-gold">
                    Registrar
                </button>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
<script>
    document.addEventListener('DOMContentLoaded', () => {

        const paisSelect = document.getElementById('select-pais');
        const deptoSelect = document.getElementById('select-departamento');

        const codigoManual = document.getElementById('select-codigo-manual');
        const numeroMovilInput = document.getElementById('input-numero-movil');
        const telefonoHidden = document.getElementById('telefono_movil_hidden');

        // --- TELÉFONO ---
        const actualizarTelefonoCompleto = () => {
            const codigo = codigoManual.value;
            const numero = numeroMovilInput.value.trim();

            telefonoHidden.value = numero ? `${codigo} ${numero}` : "";
        };

        codigoManual.addEventListener('change', actualizarTelefonoCompleto);
        numeroMovilInput.addEventListener('input', actualizarTelefonoCompleto);

        // --- PAÍS -> DEPARTAMENTOS ---
        paisSelect.addEventListener('change', async (e) => {

            const paisId = e.target.value;

            deptoSelect.innerHTML = '<option value="">Cargando...</option>';

            if (!paisId) return;

            try {

                const response = await fetch(`/api/paises/${paisId}/departamentos`);
                const departamentos = await response.json();

                deptoSelect.innerHTML =
                    '<option value="">Seleccione Departamento</option>';

                departamentos.forEach(d => {
                    deptoSelect.innerHTML +=
                        `<option value="${d.id_departamento}">${d.nombre}</option>`;
                });

            } catch (error) {

                console.error(error);

                deptoSelect.innerHTML =
                    '<option value="">Error al cargar</option>';
            }
        });

        actualizarTelefonoCompleto();

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

    });
</script>

</html>
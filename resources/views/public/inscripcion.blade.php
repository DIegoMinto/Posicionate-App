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
    <div id="slider" class="w-[200vw] h-screen flex transition-transform duration-700 ease-in-out">
        <section
            class="w-screen h-screen flex-shrink-0 bg-brand-green relative overflow-hidden flex items-center justify-center px-4 py-8">

            <div class="relative z-10 w-full max-w-[380px]">

                <div class="hero-card">

                    <div class="aspect-square overflow-hidden">
                        <img src="{{ $curso->imagen_formulario }}" class="w-full h-full object-cover"
                            alt="Imagen del curso">
                    </div>

                    <div class="p-6 text-center">

                        <h1 class="text-2xl md:text-3xl font-bold text-brand-green mt-3 leading-tight uppercase">
                            {{ $curso->nombre }}
                        </h1>


                        <button type="button" onclick="abrirFormulario()"
                            class="mt-7 w-14 h-14 md:w-16 md:h-16 rounded-full bg-brand-gold flex items-center justify-center mx-auto hover:scale-110 active:scale-95 transition-all duration-300 shadow-xl cursor-pointer">

                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-black" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />

                            </svg>

                        </button>

                        <p class="text-xs text-gray-400 mt-4">
                            Toca para continuar
                        </p>

                    </div>
                </div>
            </div>
        </section>

        <section id="formulario" class="w-screen h-screen flex-shrink-0 overflow-y-auto bg-[#f7f7f5] px-4 py-10">

            <div class="max-w-5xl mx-auto">
                <button type="button" onclick="cerrarFormulario()"
                    class="mb-6 flex items-center gap-2 text-brand-green font-bold hover:text-brand-gold transition-all cursor-pointer">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />

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
                    <span class="text-brand-gold text-xs font-bold uppercase tracking-wider">
                        Asesor:
                    </span>

                    <span class="text-brand-green text-xs font-semibold uppercase ">
                        {{ $asesor->persona->nombre }}
                        {{ $asesor->persona->apellido_p }}
                    </span>
                </div>

                @if (session('success'))

                    <script>
                        window.onload = () => {

                            abrirFormulario();

                            setTimeout(() => {

                                const alerta = document.getElementById('success-alert');

                                if (alerta) {

                                    alerta.classList.remove('opacity-0', 'translate-y-5');

                                }

                            }, 300);
                        };
                    </script>

                    <div id="success-alert" class="mb-8 opacity-0 translate-y-5 transition-all duration-700
                               p-5 rounded-[2rem]
                               bg-emerald-50 border border-emerald-200 shadow-sm">

                        <div class="flex items-start gap-4">

                            <div
                                class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">

                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>

                            </div>

                            <div>

                                <h3 class="text-emerald-700 font-bold text-lg">
                                    ¡Registro exitoso!
                                </h3>

                                <p class="text-emerald-600 text-sm mt-1">
                                    Tu registro fue realizado correctamente.
                                </p>

                            </div>

                        </div>

                    </div>

                @endif

                @if ($errors->any())
                    <div class="mb-8 p-5 rounded-3xl bg-red-50 border border-red-200 text-red-700">

                        <ul class="space-y-1 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>
                @endif

                <form action="{{ route('inscripcion.store') }}" method="POST" enctype="multipart/form-data"
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
                                <label class="form-label-bold-2">
                                    Nombre
                                </label>

                                <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-input-pill-2"
                                    required>
                            </div>

                            <div>
                                <label class="form-label-bold-2">
                                    Primer Apellido
                                </label>

                                <input type="text" name="apellido_p" value="{{ old('apellido_p') }}"
                                    class="form-input-pill-2" required>
                            </div>

                            <div>
                                <label class="form-label-bold-2">
                                    Segundo Apellido
                                </label>

                                <input type="text" name="apellido_m" value="{{ old('apellido_m') }}"
                                    class="form-input-pill-2">
                            </div>

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-5">

                            <div>
                                <label class="form-label-bold-2">
                                    Fecha de Nacimiento
                                </label>

                                <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                                    class="form-input-pill-2 text-black" required>
                            </div>

                            <div>
                                <label class="form-label-bold-2">
                                    Carnet de Identidad
                                </label>

                                <input type="text" name="ci" value="{{ old('ci') }}" class="form-input-pill-2" required>
                            </div>

                            <div>
                                <label class="form-label-bold-2">
                                    Extensión
                                </label>

                                <select name="extension_ci" class="form-select-pill-2" required>

                                    <option value="" selected disabled>
                                        Seleccione
                                    </option>

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

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-5">

                            <div>
                                <label class="form-label-bold-2">
                                    País
                                </label>

                                <select name="id_pais" id="select-pais" class="form-select-pill-2">

                                    <option value="">
                                        Seleccione País
                                    </option>

                                    @foreach($paises as $pais)
                                        <option value="{{ $pais->id_pais }}">
                                            {{ $pais->nombre }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div>
                                <label class="form-label-bold-2">
                                    Departamento
                                </label>

                                <select name="id_departamento" id="select-departamento" class="form-select-pill-2">

                                    <option value="">
                                        Seleccione un país primero
                                    </option>

                                </select>
                            </div>

                            <div>
                                <label class="form-label-bold-2">
                                    Ciudad
                                </label>

                                <input type="text" name="ciudad_residencia" value="{{ old('ciudad_residencia') }}"
                                    class="form-input-pill-2" required>
                            </div>

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">

                            <div>
                                <label class="form-label-bold-2">
                                    Domicilio
                                </label>

                                <input type="text" name="domicilio" value="{{ old('domicilio') }}"
                                    class="form-input-pill-2">
                            </div>

                            <div>

                                <label class="form-label-bold-2">
                                    Género
                                </label>

                                <div class="flex gap-6 mt-4">

                                    <label class="flex items-center gap-3 text-sm text-gray-700 cursor-pointer">

                                        <input type="radio" name="genero" value="M" class="accent-brand-gold w-5 h-5">

                                        Masculino

                                    </label>

                                    <label class="flex items-center gap-3 text-sm text-gray-700 cursor-pointer">

                                        <input type="radio" name="genero" value="F" class="accent-brand-gold w-5 h-5">

                                        Femenino

                                    </label>

                                </div>

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

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                            <div>
                                <label class="form-label-bold-2">
                                    Profesión
                                </label>

                                <select name="id_profesion" id="select-profesion" class="form-select-pill-2">

                                    <option value="">
                                        Seleccione Profesión
                                    </option>

                                    @foreach($profesiones as $prof)
                                        <option value="{{ $prof->id_profesion }}">
                                            {{ $prof->nombre }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div>
                                <label class="form-label-bold-2">
                                    Grado Académico
                                </label>

                                <select name="id_grado_academico" id="select-grado" class="form-select-pill-2">

                                    <option value="">
                                        Seleccione Grado
                                    </option>

                                    @foreach($grados as $grado)
                                        <option value="{{ $grado->id_grado_academico }}">
                                            {{ $grado->nombre }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div>
                                <label class="form-label-bold-2">
                                    Institución
                                </label>

                                <select name="id_institucion_egreso" id="select-institucion" class="form-select-pill"
                                    -2>

                                    <option value="">
                                        Seleccione Institución
                                    </option>

                                    @foreach($instituciones as $inst)
                                        <option value="{{ $inst->id_institucion_egreso }}">
                                            {{ $inst->nombre }}
                                        </option>
                                    @endforeach

                                </select>
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

                                <label class="form-label-bold-2">
                                    Teléfono Móvil
                                </label>

                                <div class="flex gap-3">

                                    <select id="select-codigo-manual" class="form-select-pill-2 w-[35%]">

                                        <option value="+591">
                                            🇧🇴 +591
                                        </option>

                                        <option value="+54">
                                            🇦🇷 +54
                                        </option>

                                    </select>

                                    <input type="text" id="input-numero-movil" class="form-input-pill-2 w-[65%]"
                                        placeholder="70000000">

                                </div>

                                <input type="hidden" name="telefono_movil" id="telefono_movil_hidden">

                            </div>

                            <div>

                                <label class="form-label-bold-2">
                                    Correo Electrónico
                                </label>

                                <input type="email" name="correo_electronico" value="{{ old('correo_electronico') }}"
                                    class="form-input-pill-2">

                            </div>

                        </div>

                    </div>

                    <div class="flex justify-center pt-4 pb-14">

                        <button type="submit" class="btn-gold
                            w-auto
                            min-w-[220px]
                            md:min-w-0
                            text-xs
                            md:text-sm
                            px-5
                            md:px-8
                            py-3
                            md:py-4">

                            Finalizar Inscripción

                        </button>

                    </div>

                </form>

            </div>

        </section>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {

                const paisSelect = document.getElementById('select-pais');
                const deptoSelect = document.getElementById('select-departamento');

                const codigoManual = document.getElementById('select-codigo-manual');

                const numeroMovilInput = document.getElementById('input-numero-movil');

                const telefonoHidden = document.getElementById('telefono_movil_hidden');

                const actualizarTelefonoCompleto = () => {

                    const codigo = codigoManual.value;

                    const numero = numeroMovilInput.value.trim();

                    telefonoHidden.value = numero
                        ? `${codigo} ${numero}`
                        : "";
                };

                codigoManual.addEventListener('change', actualizarTelefonoCompleto);

                numeroMovilInput.addEventListener('input', actualizarTelefonoCompleto);

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

                            deptoSelect.innerHTML += `
                            <option value="${d.id_departamento}">
                                ${d.nombre}
                            </option>
                        `;
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
        <script>

            function abrirFormulario() {

                document.getElementById('slider')
                    .style.transform = 'translateX(-100vw)';
            }

            function cerrarFormulario() {

                document.getElementById('slider')
                    .style.transform = 'translateX(0)';
            }

        </script>
    </div>
</body>

</html>
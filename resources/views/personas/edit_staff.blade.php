<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edición de Personal - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>

<body>
    @php
        $esSuperAdmin = auth()->user()->rol === 'super_admin';
    @endphp

    <x-layout-dashboard :usuario="$usuario">
        <div class="p-6">
            <div class="bg-white rounded-sm border-2 border-brand-green shadow-lg">

                <!-- Encabezado -->
                <div class="flex justify-between items-center mb-6 p-8 bg-brand-green">
                    <h1 class="text-2xl font-sans font-bold text-white tracking-wider">
                        Editar Personal:
                        <span class="font-sans">
                            {{ $persona->nombre }} {{ $persona->apellido_p }} {{ $persona->apellido_m }}
                        </span>
                    </h1>
                </div>

                <!-- Alertas de Errores -->
                @if($errors->any())
                    <div class="mx-8 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                        <p class="font-bold">Por favor corrige los siguientes errores:</p>
                        <ul class="text-sm list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Formulario Principal (Estructura HTML corregida) -->
                <form action="{{ route('personal.update', $personal->id_personal) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6 p-8 pt-0">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <!-- COLUMNA 1: Datos Personales -->
                        <div class="space-y-4">
                            <h3 class="font-bold text-brand-green text-sm uppercase border-b border-brand-gold/30 pb-1">Datos Personales</h3>

                            <div>
                                <label class="form-label-bold !text-brand-green">Nombre(s)</label>
                                <input type="text" name="nombre" value="{{ old('nombre', $persona->nombre) }}"
                                    class="form-input-pill border-2 border-brand-gold {{ !$esSuperAdmin ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}"
                                    {{ !$esSuperAdmin ? 'readonly' : '' }}>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="form-label-bold !text-brand-green">Ap. Paterno</label>
                                    <input type="text" name="apellido_p" value="{{ old('apellido_p', $persona->apellido_p) }}"
                                        class="form-input-pill border-2 border-brand-gold {{ !$esSuperAdmin ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}"
                                        {{ !$esSuperAdmin ? 'readonly' : '' }}>
                                </div>
                                <div>
                                    <label class="form-label-bold !text-brand-green">Ap. Materno</label>
                                    <input type="text" name="apellido_m" value="{{ old('apellido_m', $persona->apellido_m) }}"
                                        class="form-input-pill border-2 border-brand-gold {{ !$esSuperAdmin ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}"
                                        {{ !$esSuperAdmin ? 'readonly' : '' }}>
                                </div>
                            </div>

                            <div x-data="{ ext: '{{ old('extension_ci', $persona->extension_ci) }}' }">
                                <label class="form-label-bold !text-brand-green">Carnet de Identidad</label>
                                <div class="flex gap-1">
                                    <input type="number" name="ci" value="{{ old('ci', $persona->ci) }}"
                                        class="flex-1 form-input-pill border-2 border-brand-gold {{ !$esSuperAdmin ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}"
                                        {{ !$esSuperAdmin ? 'readonly' : '' }}>
                                    
                                    <select name="extension_ci" x-model="ext"
                                        class="w-20 form-select-pill border-2 border-brand-gold {{ !$esSuperAdmin ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}"
                                        {{ !$esSuperAdmin ? 'disabled' : '' }}>
                                        @foreach(['CH','LP','CB','OR','PT','TJ','SC','BE','PD','OTRO'] as $ext)
                                            <option value="{{ $ext }}">{{ $ext }}</option>
                                        @endforeach
                                    </select>
                                    @if(!$esSuperAdmin) <input type="hidden" name="extension_ci" value="{{ $persona->extension_ci }}"> @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="form-label-bold !text-brand-green">F. Nacimiento</label>
                                    <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $persona->fecha_nacimiento) }}"
                                        class="form-input-pill border-2 border-brand-gold {{ !$esSuperAdmin ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}"
                                        {{ !$esSuperAdmin ? 'readonly' : '' }}>
                                </div>
                                <div>
                                    <label class="form-label-bold !text-brand-green">Género</label>
                                    <select name="genero" class="form-select-pill border-2 border-brand-gold {{ !$esSuperAdmin ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}"
                                        {{ !$esSuperAdmin ? 'disabled' : '' }}>
                                        <option value="M" {{ old('genero', $persona->genero) == 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ old('genero', $persona->genero) == 'F' ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                    @if(!$esSuperAdmin) <input type="hidden" name="genero" value="{{ $persona->genero }}"> @endif
                                </div>
                            </div>

                            <div>
                                <label class="form-label-bold !text-brand-green">Teléfono / Móvil</label>
                                <input type="text" name="telefono_movil" value="{{ old('telefono_movil', $persona->telefono_movil) }}"
                                    class="form-input-pill border-2 border-brand-gold">
                            </div>

                            <div>
                                <label class="form-label-bold !text-brand-green">Correo Personal</label>
                                <input type="email" name="correo_electronico" value="{{ old('correo_electronico', $persona->correo_electronico) }}"
                                    class="form-input-pill border-2 border-brand-gold {{ !$esSuperAdmin ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}"
                                    {{ !$esSuperAdmin ? 'readonly' : '' }}>
                            </div>

                            <div>
                                <label class="form-label-bold !text-brand-green">Domicilio Actual</label>
                                <input type="text" name="direccion" value="{{ old('direccion', $persona->direccion ?? $persona->domicilio) }}"
                                    class="form-input-pill border-2 border-brand-gold">
                            </div>

                            <div>
                                <label class="form-label-bold !text-brand-green">Profesión</label>
                                <select name="id_profesion" class="select2-select form-select-pill border-2 border-brand-gold" {{ !$esSuperAdmin ? 'disabled' : '' }}>
                                    <option value="">Seleccione</option>
                                    @foreach($profesiones as $prof)
                                        <option value="{{ $prof->id_profesion }}"
                                            {{ old('id_profesion', $persona->id_profesion) == $prof->id_profesion ? 'selected' : '' }}>
                                            {{ $prof->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(!$esSuperAdmin)<input type="hidden" name="id_profesion" value="{{ $persona->id_profesion }}">@endif
                            </div>

                            <div>
                                <label class="form-label-bold !text-brand-green">Grado Académico</label>
                                <select name="id_grado_academico" class="select2-select form-select-pill border-2 border-brand-gold" {{ !$esSuperAdmin ? 'disabled' : '' }}>
                                    <option value="">Seleccione</option>
                                    @foreach($grados as $grado)
                                        <option value="{{ $grado->id_grado_academico }}"
                                            {{ old('id_grado_academico', $persona->id_grado_academico) == $grado->id_grado_academico ? 'selected' : '' }}>
                                            {{ $grado->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(!$esSuperAdmin)<input type="hidden" name="id_grado_academico" value="{{ $persona->id_grado_academico }}">@endif
                            </div>

                            <div>
                                <label class="form-label-bold !text-brand-green">Institución de Egreso</label>
                                <select name="id_institucion_egreso" class="select2-select form-select-pill border-2 border-brand-gold" {{ !$esSuperAdmin ? 'disabled' : '' }}>
                                    <option value="">Seleccione</option>
                                    @foreach($instituciones as $inst)
                                        <option value="{{ $inst->id_institucion_egreso }}"
                                            {{ old('id_institucion_egreso', $persona->id_institucion_egreso) == $inst->id_institucion_egreso ? 'selected' : '' }}>
                                            {{ $inst->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(!$esSuperAdmin)<input type="hidden" name="id_institucion_egreso" value="{{ $persona->id_institucion_egreso }}">@endif
                            </div>
                        </div>

                        <!-- COLUMNA 2: Configuración de Sistema -->
                        <div class="space-y-4">
                            <h3 class="font-bold text-brand-green text-sm uppercase border-b border-brand-gold/30 pb-1">Configuración de Sistema</h3>

                            <div>
                                <label class="form-label-bold !text-brand-green">Usuario de Acceso</label>
                                <input type="text" name="user" value="{{ old('user', $personal->user) }}"
                                    class="form-input-pill border-2 border-brand-gold font-semibold {{ !$esSuperAdmin ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}"
                                    {{ !$esSuperAdmin ? 'readonly' : '' }}>
                            </div>

                            <div>
                                <label class="form-label-bold !text-brand-green">Nueva Contraseña</label>
                                <input type="password" name="password"
                                    class="form-input-pill border-2 border-brand-gold {{ !$esSuperAdmin ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}" 
                                    placeholder="Dejar en blanco para no cambiar"
                                    {{ !$esSuperAdmin ? 'readonly' : '' }}>
                            </div>

                            <div>
                                <label class="form-label-bold !text-brand-green">Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation"
                                    class="form-input-pill border-2 border-brand-gold {{ !$esSuperAdmin ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}"
                                    placeholder="Repite la nueva contraseña"
                                    {{ !$esSuperAdmin ? 'readonly' : '' }}>
                            </div>

                            <div>
                                <label class="form-label-bold !text-brand-green">Sede Asignada</label>
                                <select name="id_sede"
                                    class="form-select-pill border-2 border-brand-gold {{ !$esSuperAdmin ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}"
                                    {{ !$esSuperAdmin ? 'disabled' : '' }}>
                                    <option value="" {{ is_null(old('id_sede', $personal->id_sede)) ? 'selected' : '' }}>
                                        -- Sin Sede Asignada --
                                    </option>
                                    @foreach($sedes as $sede)
                                        <option value="{{ $sede->id_sede }}"
                                            {{ old('id_sede', $personal->id_sede) == $sede->id_sede ? 'selected' : '' }}>
                                            {{ $sede->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(!$esSuperAdmin)
                                    <input type="hidden" name="id_sede" value="{{ $personal->id_sede }}">
                                @endif
                            </div>

                            <div>
                                <label class="form-label-bold !text-brand-green">Roles de Usuario</label>
                                <div class="flex flex-col gap-1 p-2 rounded-md border-2 border-brand-gold max-h-36 overflow-y-auto {{ !$esSuperAdmin ? 'bg-gray-100' : '' }}">
                                    @foreach($roles as $rol)
                                        <label class="text-xs flex items-center gap-2">
                                            <input type="checkbox" name="roles[]" value="{{ $rol->id_rol }}"
                                                {{ $personal->roles->contains('id_rol', $rol->id_rol) ? 'checked' : '' }}
                                                {{ !$esSuperAdmin ? 'disabled' : '' }}>
                                            {{ $rol->nombre_visible ?? $rol->nombre }}
                                        </label>
                                    @endforeach
                                </div>
                                @if(!$esSuperAdmin)
                                    @foreach($personal->roles as $rol)
                                        <input type="hidden" name="roles[]" value="{{ $rol->id_rol }}">
                                    @endforeach
                                @endif
                            </div>

                            <div>
                                <label class="form-label-bold !text-brand-green">Cargos Institucionales</label>
                                <div class="flex flex-col gap-1 p-2 rounded-md border-2 border-brand-gold max-h-36 overflow-y-auto {{ !$esSuperAdmin ? 'bg-gray-100' : '' }}">
                                    @foreach($cargos as $cargo)
                                        <label class="text-xs flex items-center gap-2">
                                            <input type="checkbox" name="cargos[]" value="{{ $cargo->id_cargo }}"
                                                {{ $personal->cargos->contains('id_cargo', $cargo->id_cargo) ? 'checked' : '' }}
                                                {{ !$esSuperAdmin ? 'disabled' : '' }}>
                                            {{ $cargo->nombre_visible ?? $cargo->nombre }}
                                        </label>
                                    @endforeach
                                </div>
                                @if(!$esSuperAdmin)
                                    @foreach($personal->cargos as $cargo)
                                        <input type="hidden" name="cargos[]" value="{{ $cargo->id_cargo }}">
                                    @endforeach
                                @endif
                            </div>

                            <div>
                                <label class="form-label-bold !text-brand-green">Ubicación Maps (Link)</label>
                                <input type="url" name="enlace_ubicacion_maps" value="{{ old('enlace_ubicacion_maps', $persona->enlace_ubicacion_maps) }}"
                                    class="form-input-pill border-2 border-brand-gold" placeholder="https://maps.google.com/...">
                            </div>
                        </div>

                        <!-- COLUMNA 3: Datos Financieros, Referencias y Archivos Cloudinary -->
                        <div class="space-y-4">
                            <h3 class="font-bold text-brand-green text-sm uppercase border-b border-brand-gold/30 pb-1">Otros Datos y Archivos</h3>

                            <div>
                                <label class="form-label-bold !text-brand-green">Ciudad de Residencia</label>
                                <select name="id_ciudad" class="form-select-pill border-2 border-brand-gold">
                                    @foreach($ciudades as $ciudad)
                                        <option value="{{ $ciudad->id_ciudad }}"
                                            {{ old('id_ciudad', $persona->id_ciudad) == $ciudad->id_ciudad ? 'selected' : '' }}>
                                            {{ $ciudad->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="form-label-bold !text-brand-green">Banco para Depósitos</label>
                                <select name="id_institucion_bancaria" class="form-select-pill border-2 border-brand-gold">
                                    <option value="">Seleccione Banco</option>
                                    @foreach($bancos as $banco)
                                        <option value="{{ $banco->id_institucion_bancaria }}"
                                            {{ old('id_institucion_bancaria', $persona->id_institucion_bancaria) == $banco->id_institucion_bancaria ? 'selected' : '' }}>
                                            {{ $banco->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="form-label-bold !text-brand-green">N° de Cuenta Bancaria</label>
                                <input type="text" name="numero_cuenta_bancaria" value="{{ old('numero_cuenta_bancaria', $persona->numero_cuenta_bancaria) }}"
                                    class="form-input-pill border-2 border-brand-gold">
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="form-label-bold !text-brand-green">Ref. Familiar 1</label>
                                    <input type="text" name="referencia_familiar_1" value="{{ old('referencia_familiar_1', $persona->referencia_familiar_1) }}"
                                        class="form-input-pill border-2 border-brand-gold text-xs">
                                </div>
                                <div>
                                    <label class="form-label-bold !text-brand-green">Celular Ref. 1</label>
                                    <input type="text" name="celular_familiar_1" value="{{ old('celular_familiar_1', $persona->celular_familiar_1) }}"
                                        class="form-input-pill border-2 border-brand-gold text-xs">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="form-label-bold !text-brand-green">Ref. Familiar 2</label>
                                    <input type="text" name="referencia_familiar_2" value="{{ old('referencia_familiar_2', $persona->referencia_familiar_2) }}"
                                        class="form-input-pill border-2 border-brand-gold text-xs">
                                </div>
                                <div>
                                    <label class="form-label-bold !text-brand-green">Celular Ref. 2</label>
                                    <input type="text" name="celular_familiar_2" value="{{ old('celular_familiar_2', $persona->celular_familiar_2) }}"
                                        class="form-input-pill border-2 border-brand-gold text-xs">
                                </div>
                            </div>

                            <!-- Archivos y Previsualización Cloudinary -->
                            <div class="pt-2">
                                <label class="form-label-bold !text-brand-green">Actualizar Documentos</label>
                                <div class="space-y-3 p-3 rounded-xl border-2 border-brand-gold bg-gray-50">
                                    
                                    <!-- Fotografía -->
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-[10px] font-bold uppercase text-brand-green">Fotografía Perfil:</span>
                                            @if($persona->fotografia)
                                                <a href="{{ $persona->fotografia }}" target="_blank" class="text-[10px] text-blue-600 underline font-semibold">Ver Actual</a>
                                            @endif
                                        </div>
                                        <input type="file" name="fotografia" accept="image/*" class="text-xs w-full bg-white p-1 rounded border">
                                    </div>

                                    <!-- CV -->
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-[10px] font-bold uppercase text-brand-green">Currículum Vitae (PDF):</span>
                                            @if($persona->curriculum)
                                                <a href="{{ $persona->curriculum }}" target="_blank" class="text-[10px] text-blue-600 underline font-semibold">Ver PDF</a>
                                            @endif
                                        </div>
                                        <input type="file" name="curriculum" accept="application/pdf" class="text-xs w-full bg-white p-1 rounded border">
                                    </div>

                                    <!-- Carnet -->
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-[10px] font-bold uppercase text-brand-green">Fotocopia Carnet (PDF):</span>
                                            @if($persona->foto_carnet)
                                                <a href="{{ $persona->foto_carnet }}" target="_blank" class="text-[10px] text-blue-600 underline font-semibold">Ver Documento</a>
                                            @endif
                                        </div>
                                        <input type="file" name="foto_carnet" accept="application/pdf" class="text-xs w-full bg-white p-1 rounded border">
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="flex justify-center pt-4">
                        <button type="submit" class="btn-gold">
                            Actualizar Informacion
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </x-layout-dashboard>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('select[name="id_profesion"]').select2({
                placeholder: "Seleccione Profesión",
                allowClear: true,
                width: '100%'
            });

            $('select[name="id_grado_academico"]').select2({
                placeholder: "Seleccione Grado",
                allowClear: true,
                width: '100%'
            });

            $('select[name="id_institucion_egreso"]').select2({
                placeholder: "Seleccione Institución",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
</body>

</html>
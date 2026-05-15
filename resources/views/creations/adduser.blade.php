<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Usuarios - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <x-page-header titulo="Configuración de Credenciales" />

        <div class="p-6 font-sans">
            <div class="max-w-3xl mx-auto bg-white rounded-sm border-2 border-brand-gold shadow-md">
                <div class="mb-6 border-b border-gray-100 pb-4 bg-brand-green text-white p-8 rounded-sm">
                    <h2 class="text-lg font-black uppercase">Asignar Acceso a Sistema</h2>
                    <p class="text-[11px] text-white">Persona: <span class="font-bold">{{ $persona->nombre }}
                            {{ $persona->apellido_p }}</span></p>
                </div>

                {{-- CONTROL DE ERRORES DE VALIDACIÓN --}}
                @if ($errors->any())
                    <div class="mb-4 mx-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-sm">
                        <p class="text-xs font-black uppercase mb-1">⚠️ Error en los datos ingresados:</p>
                        <ul class="list-disc list-inside text-[11px] space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('users.store_user') }}" method="POST" class="space-y-4 p-4">
                    @csrf
                    <input type="hidden" name="id_persona" value="{{ $persona->id_persona }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-black text-brand-green uppercase mb-1 ">Nombre de
                                Usuario</label>
                            <input type="text" name="user" required value="{{ old('user') }}"
                                class="form-input-pill text-xs border-1 border-brand-green outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-black text-brand-green uppercase mb-1">Sede
                                Asignada</label>
                            <select name="id_sede" required
                                class="w-full form-select-pill text-xs border-1 border-brand-green outline-none">
                                @foreach($sedes as $sede)
                                    <option value="{{ $sede->id_sede }}" {{ old('id_sede') == $sede->id_sede ? 'selected' : '' }}>{{ $sede->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-brand-green uppercase mb-1">Cargo
                                Laboral</label>
                            <select name="cargo" required
                                class="form-select-pill text-xs border-1 border-brand-green outline-none">
                                <option value="">Seleccionar</option>

                                <option value="gerente_marketing" {{ old('cargo') == 'gerente_marketing' ? 'selected' : '' }}>Gerente de marketing</option>
                                <option value="supervisor_marketing" {{ old('cargo') == 'supervisor_marketing' ? 'selected' : '' }}>Supervisor de marketing</option>
                                <option value="coordinador_marketing" {{ old('cargo') == 'coordinador_marketing' ? 'selected' : '' }}>Coordinador de marketing</option>
                                <option value="asesor_marketing" {{ old('cargo') == 'asesor_marketing' ? 'selected' : '' }}>Asesor de marketing</option>

                                <option value="supervisor_academico" {{ old('cargo') == 'supervisor_academico' ? 'selected' : '' }}>Supervisor académico</option>
                                <option value="coordinador_academico" {{ old('cargo') == 'coordinador_academico' ? 'selected' : '' }}>Coordinador académico</option>
                                <option value="asistente_academico" {{ old('cargo') == 'asistente_academico' ? 'selected' : '' }}>Asistente académico</option>

                                <option value="contador" {{ old('cargo') == 'contador' ? 'selected' : '' }}>Contador
                                </option>
                                <option value="asistente_contable" {{ old('cargo') == 'asistente_contable' ? 'selected' : '' }}>Asistente contable</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-brand-green uppercase mb-1">Rol de
                                Sistema</label>
                            <select name="rol" required
                                class="form-select-pill text-xs border-1 border-brand-green outline-none">
                                <option value="">Seleccionar</option>
                                <option value="super_admin" {{ old('rol') == 'super_admin' ? 'selected' : '' }}>Super
                                    Administrador</option>
                                <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="user" {{ old('rol') == 'user' ? 'selected' : '' }}>Usuario</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-brand-green uppercase mb-1">Contraseña</label>
                            <input type="password" name="password" required
                                class="form-input-pill text-xs border-1 border-brand-green outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-black text-brand-green uppercase mb-1">Confirmar
                                Contraseña</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full form-input-pill text-xs border-1 border-brand-green outline-none">
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-4">
                        <a href="{{ route('people.index') }}"
                            class="px-6 py-2 text-[10px] font-black text-gray-400 uppercase hover:text-gray-600 transition-colors">Cancelar</a>
                        <button type="submit" class="btn-gold text-xs">
                            Finalizar Alta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </x-layout-dashboard>
</body>

</html>
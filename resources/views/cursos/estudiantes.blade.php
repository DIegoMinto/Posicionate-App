<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes - {{ $curso->nombre }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">

        <x-page-header titulo="Lista de Estudiantes">

            <form method="GET" action="{{ route('curso.estudiantes', $curso->id_curso) }}"
                class="flex flex-col lg:flex-row lg:items-end gap-3 flex-wrap">
                @if(in_array($usuario->rol, ['admin', 'super_admin']))
                    <select name="id_personal" onchange="this.form.submit()"
                        class="bg-white text-black text-[10px] font-bold px-3 py-1.5 rounded-md">

                        <option value="">ASESOR: TODOS</option>

                        @foreach($personales as $per)
                            <option value="{{ $per->id_personal }}" {{ request('id_personal') == $per->id_personal ? 'selected' : '' }}>

                                {{ strtoupper($per->persona->nombre . ' ' . $per->persona->apellido_p) }}

                            </option>
                        @endforeach
                    </select>
                @endif
                <select name="estado" onchange="this.form.submit()"
                    class="bg-white text-black text-[10px] font-bold px-3 py-1.5 rounded-md">

                    <option value="">ESTADO: TODOS</option>

                    <option value="pre_inscrito" {{ request('estado') == 'pre_inscrito' ? 'selected' : '' }}>
                        PRE INSCRITO
                    </option>

                    <option value="inscrito" {{ request('estado') == 'inscrito' ? 'selected' : '' }}>
                        INSCRITO
                    </option>
                </select>
                <div>
                    <div class="font-sans text-[12px]">Fecha inicio</div>
                    <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}"
                        onchange="this.form.submit()" class="bg-white text-[10px] px-2 py-1.5 rounded-md">
                </div>

                <div>
                    <div class="font-sans text-[12px]">Fecha fin</div>
                    <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" onchange="this.form.submit()"
                        class="bg-white text-[10px] px-2 py-1.5 rounded-md">
                </div>

            </form>
            <x-slot name="search">
                <form method="GET" action="{{ route('curso.estudiantes', $curso->id_curso) }}"
                    class="relative bg-white rounded-full">

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Buscar por nombre o CI..." class="pl-10 pr-4 py-1.5 text-xs w-64 outline-none">

                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-black">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                </form>
            </x-slot>

        </x-page-header>

        <div class="p-6">
            <div class="bg-white p-6 rounded-sm border-2 border-brand-gold shadow-md">

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-brand-green uppercase tracking-tighter">
                        Lista de Estudiantes
                    </h1>
                </div>

                <div class="overflow-x-auto rounded-xl border-brand-green border-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b-2 border-brand-gold uppercase text-[10px] font-black bg-brand-green text-white">
                                <th class="py-3 px-4 whitespace-nowrap">N°</th>
                                <th class="py-3 px-4 whitespace-nowrap">CI</th>
                                <th class="py-3 px-4 whitespace-nowrap">Ext</th>
                                <th class="py-3 px-4 whitespace-nowrap">Nombre</th>
                                <th class="py-3 px-4 whitespace-nowrap">Apellido Paterno</th>
                                <th class="py-3 px-4 whitespace-nowrap">Apellido Materno</th>
                                <th class="py-3 px-4 whitespace-nowrap">Teléfono</th>
                                <th class="py-3 px-4 whitespace-nowrap">Correo</th>
                                <th class="py-3 px-4 whitespace-nowrap">Asesor</th>
                                <th class="py-3 px-4 whitespace-nowrap">Fecha de Registro</th>
                                <th class="py-3 px-4 text-center whitespace-nowrap">Estado</th>
                                <th class="py-3 px-4 text-right sticky right-0 bg-brand-green">Operaciones</th>
                            </tr>
                        </thead>

                        <tbody class="text-gray-700 text-[11px] font-medium">
                            @forelse($estudiantes as $index => $e)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors text-black">

                                    <td class="py-3 px-4">{{ $index + 1 }}</td>

                                    <td class="py-3 px-4 whitespace-nowrap">
                                        {{ $e->ci }}
                                    </td>

                                    <td class="py-3 px-4 whitespace-nowrap">
                                        {{ $e->extension_ci }}
                                    </td>

                                    <td class="py-3 px-4 whitespace-nowrap">
                                        {{ $e->nombre }}
                                    </td>

                                    <td class="py-3 px-4">
                                        {{ $e->apellido_p }}
                                    </td>

                                    <td class="py-3 px-4">
                                        {{ $e->apellido_m }}
                                    </td>

                                    <td class="py-3 px-4 font-mono whitespace-nowrap">
                                        {{ $e->telefono_movil ?? '-' }}
                                    </td>

                                    <td class="py-3 px-4 lowercase whitespace-nowrap">
                                        {{ $e->correo_electronico ?? '-' }}
                                    </td>

                                    <td class="py-3 px-4 whitespace-nowrap italic">
                                        {{ $e->asesor_nombre }} {{ $e->asesor_apellido }}
                                    </td>

                                    <td class="py-3 px-4 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($e->fecha_inscripcion)->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="py-3 px-4 text-center">
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase
                                                                                                                                                                                                                    {{ $e->estado == 'pre_inscrito' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                                                                                                                                                                                    {{ $e->estado == 'inscrito' ? 'bg-green-100 text-green-700' : '' }}">
                                            {{ $e->estado }}
                                        </span>
                                    </td>

                                    <td class="px-4 text-right sticky right-0 bg-white">
                                        <div class="flex justify-center items-center gap-2">

                                            @if($e->estado != 'inscrito')
                                                <a href="{{ route('students.change', $e->id_estudiante) }}?id_curso={{ $curso->id_curso }}"
                                                    class="group relative flex items-center justify-center"
                                                    title="Cambiar Estado">
                                                    <div>
                                                        <img src="/img/change_icon.png" class="w-5 h-5 object-contain"
                                                            alt="Cambiar Estado">
                                                    </div>

                                                    <span
                                                        class="absolute -top-8 scale-0 transition-all rounded bg-gray-800 px-2 py-1 text-[10px] text-white group-hover:scale-100 whitespace-nowrap z-30 shadow-lg">
                                                        Cambiar de Estado
                                                    </span>
                                                </a>
                                            @endif

                                            <a href="{{ route('students.facturacion', $e->id_estudiante) }}?id_curso={{ $curso->id_curso }}"
                                                class="group relative flex items-center justify-center" title="Facturación">
                                                <div>
                                                    <img src="/img/bill_icon.png" class="w-5 h-5 object-contain"
                                                        alt="Facturación">
                                                </div>

                                                <span
                                                    class="absolute -top-8 scale-0 transition-all rounded bg-gray-800 px-2 py-1 text-[10px] text-white group-hover:scale-100 whitespace-nowrap z-30 shadow-lg">
                                                    Ver Facturación
                                                </span>
                                            </a>
                                            @if($usuario->rol === 'super_admin')
                                                <div x-data="{ openDelete: false }">
                                                    <button @click="openDelete = true"
                                                        class="group relative flex items-center justify-center pb-1 cursor-pointer">

                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="w-5 h-5 group-hover:text-red-600 transition-colors"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="2">

                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>

                                                        <span
                                                            class="absolute -top-8 scale-0 transition-all rounded bg-red-600 px-2 py-1 text-[10px] text-white group-hover:scale-100 whitespace-nowrap z-30 shadow-lg font-sans">
                                                            Eliminar Estudiante
                                                        </span>
                                                    </button>

                                                    <div x-show="openDelete"
                                                        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm"
                                                        x-cloak>

                                                        <div class="bg-white p-6 rounded-sm shadow-2xl w-80 text-left border-t-4 border-red-600 font-sans"
                                                            @click.away="openDelete = false">

                                                            <h3 class="text-red-600 uppercase mb-2 font-bold">
                                                                Confirmar Eliminación
                                                            </h3>

                                                            <p class="text-[10px] mb-4 text-gray-600">
                                                                Vas a eliminar a:<br>
                                                                <span class="text-black font-bold uppercase">
                                                                    {{ $e->nombre }} {{ $e->apellido_p }}
                                                                </span>
                                                            </p>

                                                            <form action="{{ route('students.destroy', $e->id_estudiante) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')

                                                                <input type="hidden" name="id_curso"
                                                                    value="{{ $curso->id_curso }}">

                                                                <input type="password" name="password_confirm" required
                                                                    class="w-full border border-gray-200 p-2 text-xs mb-4 focus:outline-none focus:border-red-500 bg-gray-50"
                                                                    placeholder="Tu contraseña de administrador">

                                                                <div class="flex justify-end gap-3">
                                                                    <button type="button" @click="openDelete = false"
                                                                        class="text-[9px] font-sans cursor-pointer uppercase hover:text-red-600 transition-colors">
                                                                        Cancelar
                                                                    </button>

                                                                    <button type="submit"
                                                                        class="bg-red-600 text-white px-4 py-2 rounded-sm text-[9px] font-sans uppercase cursor-pointer hover:bg-red-700 transition-colors">
                                                                        Eliminar
                                                                    </button>

                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </td>


                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="py-12 text-center text-gray-400 italic">
                                        No hay estudiantes registrados en este curso.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </x-layout-dashboard>
</body>

</html>
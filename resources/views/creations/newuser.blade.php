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

        <x-page-header titulo="Alta de Usuarios">
            <x-slot name="search">
                <form action="{{ route('people.staff') }}" method="GET" class="relative bg-white rounded-full">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar"
                        class="pl-10 pr-4 py-1.5  text-xs  w-64 outline-none">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-black ">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </form>
            </x-slot>
            <div class="flex items-center gap-3">

                <a href="{{ route('people.staff') }}" class="btn-back">
                    ← Volver
                </a>

                <x-slot name="search">
                    <form action="{{ route('people.staff') }}" method="GET" class="relative bg-white rounded-full">

                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar"
                            class="pl-10 pr-4 py-1.5 text-xs w-64 outline-none rounded-full">

                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-black">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
                                </path>

                            </svg>
                        </div>

                    </form>
                </x-slot>

            </div>
        </x-page-header>

        <div class="p-6">
            <div class="bg-white p-6 rounded-sm border-2 border-brand-gold shadow-md">

                <div class="overflow-x-auto rounded-xl border-brand-green border-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b-2 border-brand-gold uppercase text-[10px] font-black bg-brand-green text-white">
                                <th class="py-3 px-4 whitespace-nowrap">N°</th>
                                <th class="py-3 px-4 whitespace-nowrap">CI</th>
                                <th class="py-3 px-4 whitespace-nowrap">Extensión</th>
                                <th class="py-3 px-4 whitespace-nowrap">Nombres</th>
                                <th class="py-3 px-4 whitespace-nowrap">Apellido Paterno</th>
                                <th class="py-3 px-4 whitespace-nowrap">Apellido Materno</th>
                                <th class="py-3 px-4 whitespace-nowrap">Fecha de Nacimiento</th>
                                <th class="py-3 px-4 whitespace-nowrap">Domicilio</th>
                                <th class="py-3 px-4 whitespace-nowrap">Enlace de Maps</th>
                                <th class="py-3 px-4 text-center whitespace-nowrap">Teléfono</th>
                                <th class="py-3 px-4 whitespace-nowrap">Corre Electrónico</th>
                                <th class="py-3 px-4 whitespace-nowrap">Género</th>
                                <th class="py-3 px-4 whitespace-nowrap">Curriculum</th>
                                <th class="py-3 px-4 whitespace-nowrap">Foto de Carnet</th>
                                <th class="py-3 px-4 whitespace-nowrap">Fotografía</th>
                                <th class="py-3 px-4 whitespace-nowrap">Número de Cuenta Bancaria</th>
                                <th class="py-3 px-4 whitespace-nowrap">Referencia Familiar 1</th>
                                <th class="py-3 px-4 whitespace-nowrap">Teléfono Familiar 1</th>
                                <th class="py-3 px-4 whitespace-nowrap">Referencia Familiar 2</th>
                                <th class="py-3 px-4 whitespace-nowrap">Teléfono Familiar 2</th>
                                <th class="py-3 px-4 whitespace-nowrap">Habilidades Técnicas</th>
                                <th class="py-3 px-4 whitespace-nowrap">Habilidades Blandas</th>
                                <th class="py-3 px-4 text-right sticky right-0 bg-brand-green">Operaciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-[11px] font-medium">
                            @forelse($personas as $index => $p)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors text-black">
                                    <td class="py-3 px-4">{{ $index + 1 }}</td>
                                    <td class="py-3 px-4">{{ $p->ci ?? 'S/CI' }}</td>
                                    <td class="py-3 px-4">{{ $p->extension_ci ?? 'S/CI' }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $p->nombre }}</td>
                                    <td class="py-3 px-4">{{ $p->apellido_p }}</td>
                                    <td class="py-3 px-4">{{ $p->apellido_m }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $p->fecha_nacimiento }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $p->domicilio }}</td>
                                    <td class="py-3 px-4">
                                        @if($p->enlace_ubicacion_maps)
                                            <a href="{{ $p->enlace_ubicacion_maps }}" target="_blank"
                                                class="text-blue-500 underline">Ver
                                                mapa</a>
                                        @else - @endif
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">{{ $p->telefono_movil ?? '-' }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $p->correo_electronico ?? '-' }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        {{ $p->genero === 'M' ? 'Masculino' : ($p->genero === 'F' ? 'Femenino' : $p->genero) }}
                                    </td>

                                    <td class="py-3 px-4">
                                        @if($p->curriculum)
                                            <a href="{{ $p->curriculum }}" target="_blank" class="text-red-500 font-bold">PDF
                                                CV</a>
                                        @else - @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($p->foto_carnet)
                                            <a href="{{ $p->foto_carnet }}" target="_blank"
                                                class="text-brand-green underline">Ver CI</a>
                                        @else - @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($p->fotografia)
                                            <img src="{{ $p->fotografia }}" class="w-10 h-10 rounded-full object-cover border">
                                        @else - @endif
                                    </td>

                                    <td class="py-3 px-4 whitespace-nowrap">{{ $p->numero_cuenta_bancaria ?? '-' }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $p->referencia_familiar_1 ?? '-' }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $p->celular_familiar_1 ?? '-' }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $p->referencia_familiar_2 ?? '-' }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $p->celular_familiar_2 ?? '-' }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ Str::limit($p->habilidades_tecnicas, 20) }}
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ Str::limit($p->habilidades_blandas, 20) }}
                                    </td>
                                    <td class="py-3 px-4 text-right sticky right-0 bg-white" x-data="{ openDelete: false }">
                                        <div class="flex justify-end gap-2 items-center">

                                            @if($usuario->rol === 'super_admin')
                                                <a href="{{ route('users.create_user', $p->id_persona) }}"
                                                    class="btn-adduser text-xs">
                                                    Alta
                                                </a>
                                            @endif

                                            @if($usuario->rol === 'super_admin')
                                                <button @click="openDelete = true"
                                                    class="text-red-600 hover:text-red-800 transition cursor-pointer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-1 12a2 2 0 01-2 2H8a2 2 0 01-2-2L5 
                                                                                                                                                                                                                                7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 
                                                                                                                                                                                                                                1 0 00-1-1m-4 0h4" />
                                                    </svg>
                                                </button>
                                            @endif

                                        </div>

                                        <div x-show="openDelete"
                                            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm"
                                            x-cloak>

                                            <div class="bg-white p-6 rounded-sm shadow-2xl w-80 text-left border-t-4 border-red-600"
                                                @click.away="openDelete = false">

                                                <h3 class="text-[11px] font-black text-red-600 uppercase mb-2">
                                                    Confirmar Eliminación
                                                </h3>

                                                <p class="text-[10px] mb-4 text-gray-600">
                                                    Vas a eliminar a:<br>
                                                    <span class="font-bold text-black uppercase">
                                                        {{ $p->nombre }} {{ $p->apellido_p }}
                                                    </span>
                                                </p>

                                                <form action="{{ route('personas.destroy', $p->id_persona) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <input type="password" name="password_confirm" required
                                                        class="w-full border border-gray-200 p-2 text-xs mb-4 focus:outline-none focus:border-red-500 bg-gray-50"
                                                        placeholder="Tu contraseña de administrador">

                                                    <div class="flex justify-end gap-3">
                                                        <button type="button" @click="openDelete = false"
                                                            class="text-[9px] font-bold text-gray-400 uppercase cursor-pointer">
                                                            Cancelar
                                                        </button>

                                                        <button type="submit"
                                                            class="bg-red-600 text-white px-4 py-2 rounded-sm text-[9px] font-black uppercase cursor-pointer">
                                                            Eliminar
                                                        </button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>

                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="23" class="py-12 text-center text-gray-400 italic">No hay registros de
                                        personas disponibles.</td>
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
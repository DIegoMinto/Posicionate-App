<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">

        <x-page-header titulo="Personal">

            <form action="{{ route('people.staff') }}" method="GET" class="flex items-center gap-3">
                <input type="hidden" name="search" value="{{ request('search') }}">

                <select name="id_sede" onchange="this.form.submit()"
                    class="bg-white text-black text-[10px] font-bold px-3 py-1.5 rounded-md uppercase">
                    <option value="">SEDE: TODAS</option>
                    @foreach($sedes as $sede)
                        <option value="{{ $sede->id_sede }}" {{ request('id_sede') == $sede->id_sede ? 'selected' : '' }}>
                            {{ strtoupper($sede->nombre) }}
                        </option>
                    @endforeach
                </select>

                <select name="cargo" onchange="this.form.submit()"
                    class="bg-white text-black text-[10px] font-bold px-3 py-1.5 rounded-md uppercase">
                    <option value="">ÁREA: TODAS</option>
                    @foreach($areas as $area)
                        <option value="{{ $area }}" {{ request('cargo') == $area ? 'selected' : '' }}>
                            {{ strtoupper($area) }}
                        </option>
                    @endforeach
                </select>

                <select name="estado" onchange="this.form.submit()"
                    class="bg-white text-black text-[10px] font-bold px-3 py-1.5 rounded-md uppercase">
                    <option value="">ESTADO: TODOS</option>
                    <option value="1" {{ request('estado') === '1' ? 'selected' : '' }}>VIGENTE</option>
                    <option value="0" {{ request('estado') === '0' ? 'selected' : '' }}>NO VIGENTE</option>
                </select>

            </form>

            <x-slot name="search">
                <form action="{{ route('people.staff') }}" method="GET" class="relative bg-white rounded-full">

                    @foreach(request()->except('search') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar..."
                        class="pl-10 pr-4 py-1.5 text-xs w-64 outline-none"
                        onkeydown="if(event.key === 'Enter'){ this.form.submit(); }">

                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-black">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </form>
            </x-slot>

        </x-page-header>



        <div class="p-6">
            <div class="bg-white p-6 rounded-sm border-2 border-brand-gold shadow-md">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-brand-green uppercase tracking-tighter">Gestión de Personal</h1>
                    <a href="{{ route('users.create') }}">
                        <button class="btn-gold">
                            VER SOLICITUDES
                        </button>
                    </a>
                </div>

                <div class="overflow-x-auto rounded-xl border-brand-green border-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b-2 border-brand-gold uppercase text-[10px] font-black bg-brand-green text-white">
                                <th class="py-3 px-4 whitespace-nowrap">N°</th>
                                <th class="py-3 px-4 whitespace-nowrap">ID</th>
                                <th class="py-3 px-4 whitespace-nowrap">CI</th>
                                <th class="py-3 px-4 whitespace-nowrap">Extensión</th>
                                <th class="py-3 px-4 whitespace-nowrap">Nombres</th>
                                <th class="py-3 px-4 whitespace-nowrap">Apellido Paterno</th>
                                <th class="py-3 px-4 whitespace-nowrap">Apellido Materno</th>
                                <th class="py-3 px-4 whitespace-nowrap">Teléfono</th>
                                <th class="py-3 px-4 whitespace-nowrap">Correo</th>
                                <th class="py-3 px-4 whitespace-nowrap">Cargo</th>
                                <th class="py-3 px-4 whitespace-nowrap">Área</th>
                                <th class="py-3 px-4 whitespace-nowrap">Sede</th>
                                @if($usuario->rol === 'super_admin')
                                    <th class="py-3 px-4 text-center whitespace-nowrap">Estado</th>
                                @endif
                                <th class="py-3 px-4 text-right sticky right-0 bg-brand-green">Operaciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-[11px] font-medium">
                            @forelse($personales as $index => $p)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors text-black">
                                    <td class="py-3 px-4 ">{{ $personales->firstItem() + $index }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        {{ $p->codigo_personal }}
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $p->persona->ci ?? 'S/CI' }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $p->persona->extension_ci ?? 'S/CI' }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $p->persona->nombre }}</td>
                                    <td class="py-3 px-4 ">{{ $p->persona->apellido_p }}</td>
                                    <td class="py-3 px-4 ">{{ $p->persona->apellido_m }}</td>
                                    <td class="py-3 px-4 font-mono whitespace-nowrap">
                                        {{ $p->persona->telefono_movil ?? '-' }}
                                    </td>
                                    <td class="py-3 px-4 lowercase whitespace-nowrap">
                                        {{ $p->persona->correo_electronico ?? '-' }}
                                    </td>
                                    <td class="py-3 px-4 italic whitespace-nowrap">{{ $p->cargo_nombre ?? 'No definido' }}
                                    </td>
                                    <td class="py-3 px-4 italic whitespace-nowrap">{{ $p->area ?? 'No definido' }}</td>

                                    <td class="py-3 px-4">
                                        <span
                                            class="text-brand-green font-bold whitespace-nowrap"">{{ $p->sede->nombre ?? 'N/A' }}</span>
                                                                                                                                                                                                                                                                        </td>
                                                                                                                    @if($usuario->rol === 'super_admin')                                                                                                                                                        <td class="
                                                                                                                                py-3 px-4 text-center" x-data="{ open: false }">
                                                                                                                                <button @click="open = true"
                                                                                                                                    class="cursor-pointer px-2 py-0.5 rounded-full text-[9px] font-black uppercase transition-transform hover:scale-110 {{ $p->es_vigente == 1 ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100' }}">
                                                                                                                                    {{ $p->es_vigente == 1 ? 'VIGENTE' : 'NO VIGENTE' }}
                                                                                                                                </button>

                                                                                                                                <div x-show="open"
                                                                                                                                    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm"
                                                                                                                                    x-cloak>

                                                                                                                                    <div class="bg-white p-6 rounded-sm shadow-2xl w-80 text-left border-t-4 border-brand-gold"
                                                                                                                                        @click.away="open = false">
                                                                                                                                        <h3
                                                                                                                                            class="font-sans text-brand-green uppercase mb-2 flex items-center gap-2 uppercase">
                                                                                                                                            <svg class="w-4 h-4 text-brand-gold" fill="currentColor"
                                                                                                                                                viewBox="0 0 20 20">
                                                                                                                                                <path fill-rule="evenodd"
                                                                                                                                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                                                                                                                    clip-rule="evenodd" />
                                                                                                                                            </svg>
                                                                                                                                            Confirmar Autorización
                                                                                                                                        </h3>

                                                                                                                                        <p class="mb-4">
                                                                                                                                            Vas a cambiar el estado de: <br>
                                                                                                                                            <span class="font-sans text-black">{{ $p->persona->nombre }}
                                                                                                                                                {{ $p->persona->apellido_p }}</span>
                                                                                                                                        </p>

                                                                                                                                        <form action="{{ route('users.toggle', $p->id_personal) }}"
                                                                                                                                            method="POST">
                                                                                                                                            @csrf
                                                                                                                                            <input type="password" name="password_confirm" required
                                                                                                                                                class="w-full border border-gray-200 p-2 text-xs mb-4 focus:outline-none focus:border-brand-gold bg-gray-50 uppercase placeholder:normal-case"
                                                                                                                                                placeholder="Tu contraseña de administrador">

                                                                                                                                            <div class="flex justify-end gap-3">
                                                                                                                                                <button type="button" @click="open = false"
                                                                                                                                                    class="text-[9px] font-sans uppercase cursor-pointer">
                                                                                                                                                    Cancelar
                                                                                                                                                </button>
                                                                                                                                                <button type="submit"
                                                                                                                                                    class="bg-brand-gold text-black cursor-pointer px-4 py-2 rounded-sm font-sans uppercase">
                                                                                                                                                    Actualizar Estado
                                                                                                                                                </button>
                                                                                                                                            </div>
                                                                                                                                        </form>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                        </td>
                                                                                                                    @endif
                                    <td class="px-4 text-right sticky right-0 bg-white" x-data="{ openDelete: false }">
                                        <div class="flex justify-end gap-2 items-center h-full">


                                            <a href="{{ route('users.show', $p->id_personal) }}"
                                                class="hover:text-brand-green transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            @if($usuario->rol === 'super_admin')
                                                <a href="{{ route('users.edit', $p->id_personal) }}"
                                                    class="hover:text-brand-green transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>

                                                <button @click="openDelete = true"
                                                    class="group relative flex items-center justify-center pb-1 cursor-pointer">

                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-5 h-5 text-black group-hover:text-red-600 transition-colors"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>

                                                    <span
                                                        class="absolute -top-8 scale-0 transition-all rounded bg-red-600 px-2 py-1 text-[10px] text-white group-hover:scale-100 whitespace-nowrap z-30 shadow-lg font-sans">
                                                        Eliminar Personal
                                                    </span>
                                                </button>
                                            @endif
                                            <div x-show="openDelete"
                                                class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm"
                                                x-cloak>

                                                <div class="bg-white p-6 rounded-sm shadow-2xl w-80 text-left border-t-4 border-red-600"
                                                    @click.away="openDelete = false font-sans">

                                                    <h3 class=" text-red-600 uppercase mb-2">
                                                        Confirmar Eliminación
                                                    </h3>

                                                    <p class="text-[10px] mb-4">
                                                        Vas a eliminar a:<br>
                                                        <span class=" text-black">
                                                            {{ $p->persona->nombre }} {{ $p->persona->apellido_p }}
                                                            {{ $p->persona->apellido_m }}
                                                        </span>
                                                    </p>

                                                    <form action="{{ route('users.destroy', $p->id_personal) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')

                                                        <input type="password" name="password_confirm" required
                                                            class="w-full border border-gray-200 p-2 text-xs mb-4 focus:outline-none focus:border-red-500 bg-gray-50"
                                                            placeholder="Tu contraseña de administrador">

                                                        <div class="flex justify-end gap-3">
                                                            <button type="button" @click="openDelete = false"
                                                                class="text-[9px] font-sans cursor-pointer">
                                                                Cancelar
                                                            </button>

                                                            <button type="submit"
                                                                class="bg-red-600 text-white px-4 py-2 rounded-sm text-[9px] font-sans uppercase cursor-pointer">
                                                                Eliminar
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="py-12 text-center text-gray-400 italic">No hay registros de
                                        personal.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-center">
                    <nav class="flex items-center gap-1 font-sans text-[11px]">

                        @if ($personales->onFirstPage())
                            <span class="px-3 py-1.5 rounded-md text-gray-300 border border-gray-200 cursor-not-allowed">
                                Anterior
                            </span>
                        @else
                            <a href="{{ $personales->previousPageUrl() }}"
                                class="px-3 py-1.5 rounded-md border border-brand-green text-brand-green font-bold hover:bg-brand-green hover:text-white transition-colors">
                                Anterior
                            </a>
                        @endif

                        @foreach ($personales->getUrlRange(1, $personales->lastPage()) as $page => $url)
                            @if ($page == $personales->currentPage())
                                <span class="px-3 py-1.5 rounded-md bg-brand-green text-white font-bold">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                    class="px-3 py-1.5 rounded-md border border-brand-green text-brand-green font-bold hover:bg-brand-green hover:text-white transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if ($personales->hasMorePages())
                            <a href="{{ $personales->nextPageUrl() }}"
                                class="px-3 py-1.5 rounded-md border border-brand-green text-brand-green font-bold hover:bg-brand-green hover:text-white transition-colors">
                                Siguiente
                            </a>
                        @else
                            <span class="px-3 py-1.5 rounded-md text-gray-300 border border-gray-200 cursor-not-allowed">
                                Siguiente
                            </span>
                        @endif

                    </nav>
                </div>

            </div>
        </div>
    </x-layout-dashboard>

</body>

</html>
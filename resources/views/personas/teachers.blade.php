<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Docentes - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">

        <x-page-header titulo="Registro de Docentes">

            <form action="{{ route('teachers.index') }}" method="GET" class="flex items-center gap-3">

                <select name="area" onchange="this.form.submit()"
                    class="bg-white text-black text-[10px] font-bold px-3 py-1.5 rounded-md uppercase">
                    <option value="">ÁREA: TODAS</option>
                    @foreach($areas as $area)
                        <option value="{{ $area }}" {{ request('area') == $area ? 'selected' : '' }}>
                            {{ strtoupper($area) }}
                        </option>
                    @endforeach
                </select>

                <select name="emite_factura" onchange="this.form.submit()"
                    class="bg-white text-black text-[10px] font-bold px-3 py-1.5 rounded-md uppercase">
                    <option value="">FACTURA: TODOS</option>
                    <option value="SI" {{ request('emite_factura') == 'SI' ? 'selected' : '' }}>SÍ</option>
                    <option value="NO" {{ request('emite_factura') == 'NO' ? 'selected' : '' }}>NO</option>
                </select>

                <select name="id_ciudad" onchange="this.form.submit()"
                    class="bg-white text-black text-[10px] font-bold px-3 py-1.5 rounded-md uppercase">
                    <option value="">CIUDAD: TODAS</option>
                    @foreach($ciudades as $ciudad)
                        <option value="{{ $ciudad->id_ciudad }}" {{ request('id_ciudad') == $ciudad->id_ciudad ? 'selected' : '' }}>
                            {{ strtoupper($ciudad->nombre) }}
                        </option>
                    @endforeach
                </select>
                <x-slot name="search">
                    <form action="{{ route('teachers.index') }}" method="GET" class="relative bg-white rounded-full">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar docente..."
                            class="pl-10 pr-4 py-1.5 text-xs w-64 outline-none">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-black">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </form>
                </x-slot>

            </form>

        </x-page-header>


        <div class="p-6">
            <div class="bg-white p-6 rounded-sm border-2 border-brand-gold shadow-md">

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-brand-green uppercase tracking-tighter">Listado de Docentes</h1>
                    <a href="{{ route('docentes.create') }}" target="_blank" rel="noopener noreferrer">
                        <button class="btn-gold">
                            + REGISTRAR DOCENTE
                        </button>
                    </a>
                </div>

                <div class="overflow-x-auto rounded-xl border-brand-green border-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b-2 border-brand-gold uppercase text-[10px] font-black bg-brand-green text-white">
                                <th class="py-3 px-4 whitespace-nowrap">N°</th>
                                <th class="py-3 px-4 whitespace-nowrap">CI</th>
                                <th class="py-3 px-4 whitespace-nowrap">Ext.</th>
                                <th class="py-3 px-4 whitespace-nowrap">Nombres</th>
                                <th class="py-3 px-4 whitespace-nowrap">Apellidos</th>
                                <th class="py-3 px-4 whitespace-nowrap">Título/Grado</th>
                                <th class="py-3 px-4 whitespace-nowrap">Profesión</th>
                                <th class="py-3 px-4 whitespace-nowrap">Área</th>
                                <th class="py-3 px-4 whitespace-nowrap">Teléfono</th>
                                <th class="py-3 px-4 whitespace-nowrap">Correo</th>
                                <th class="py-3 px-4 whitespace-nowrap">Factura</th>
                                <th class="py-3 px-4 whitespace-nowrap">Ciudad</th>
                                <th class="py-3 px-4 whitespace-nowrap">Institución Egreso</th>
                                <th class="py-3 px-4 whitespace-nowrap">Institución Bancaria</th>
                                <th class="py-3 px-4 whitespace-nowrap">Número de Cuenta</th>
                                <th class="py-3 px-4 whitespace-nowrap">Documentos</th>
                                <th class="py-3 px-4 text-center sticky right-0 bg-brand-green">Operaciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-[11px] font-medium">
                            @forelse($docentes as $index => $d)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors text-black">
                                    <td class="py-3 px-4">{{ $docentes->firstItem() + $index }}</td>
                                    <td class="py-3 px-4">{{ $d->ci }}</td>
                                    <td class="py-3 px-4">{{ $d->extension_ci }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $d->nombre }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $d->apellido_p }}
                                        {{ $d->apellido_m }}
                                    </td>
                                    <td class="py-3 px-4">{{ $d->grado->nombre ?? 'S/G' }}</td>
                                    <td class="py-3 px-4">{{ $d->profesion->nombre ?? 'No def.' }}</td>
                                    <td class="py-3 px-4">
                                        <span>
                                            {{ $d->area ?? 'Sin Área' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $d->telefono_movil ?? '-' }}</td>
                                    <td class="py-3 px-4 lowercase whitespace-nowrap">{{ $d->correo_electronico ?? '-' }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span
                                            class="{{ $d->emite_factura === 'SI' ? 'text-green-600' : 'text-red-600' }} font-bold">
                                            {{ $d->emite_factura }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">{{ $d->ciudad->nombre ?? '-' }}</td>
                                    <td class="py-3 px-4">{{ $d->institucion->nombre ?? '-' }}</td>
                                    <td class="py-3 px-4">
                                        <div class="flex flex-col">
                                            <span class="">{{ $d->institucion_bancaria->nombre}}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex flex-col">
                                            <span>{{ $d->numero_cuenta_bancaria ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <div class="flex gap-2">
                                            @if($d->curriculum)
                                                <a href="{{ $d->curriculum }}" target="_blank" title="Ver CV">
                                                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                                                    </svg>
                                                </a>
                                            @endif
                                            @if($d->fotografia)
                                                <a href="{{ $d->fotografia }}" target="_blank" title="Ver Foto">
                                                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            @endif
                                            @if($d->fotocarnet)
                                                <a href="{{ $d->fotocarnet }}" target="_blank" title="Ver Carnet">
                                                    <svg class="w-4 h-4 text-yellow-600" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 text-right sticky right-0 bg-white" x-data="{ openDelete: false }">
                                        <div class="flex justify-end gap-2 items-center">
                                            <a href="{{ route('docentes.show', $d->id_docente) }}"
                                                class="text-brand-green hover:text-brand-gold transition"
                                                title="Ver Perfil Completo">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            @if($usuario->rol === 'super_admin')
                                                <a href="{{ route('docentes.edit', $d->id_docente) }}"
                                                    class="text-brand-green hover:text-brand-gold transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>

                                                <button @click="openDelete = true"
                                                    class="text-red-600 hover:text-red-800 transition cursor-pointer">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-1 12a2 2 0 01-2 2H8a2 2 0 01-2-2L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4">
                                                        </path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>

                                        <div x-show="openDelete"
                                            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm font-sans"
                                            x-cloak>
                                            <div class="bg-white p-6 rounded-sm shadow-2xl w-80 text-left border-t-4 border-red-600"
                                                @click.away="openDelete = false">
                                                <h3 class="text-[11px] text-red-600 uppercase mb-2">Confirmar
                                                    Eliminación</h3>
                                                <p class="text-[10px] mb-4">Vas a eliminar al docente:<br>
                                                    <span class="text-black">{{ $d->nombre }}
                                                        {{ $d->apellido_p }}</span>
                                                </p>
                                                <form action="{{ route('docentes.destroy', $d->id_docente) }}" method="POST"
                                                    class="font-sans">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="password" name="password_confirm" required
                                                        class="w-full border border-gray-200 p-2 text-xs mb-4 focus:outline-none focus:border-red-500 bg-gray-50"
                                                        placeholder="Contraseña Administrador">
                                                    <div class="flex justify-end gap-3">
                                                        <button type="button" @click="openDelete = false"
                                                            class="text-[9px]  text-gray-400 uppercase cursor-pointer">Cancelar</button>
                                                        <button type="submit"
                                                            class="bg-red-600 text-white px-4 py-2 rounded-sm text-[9px] uppercase cursor-pointer">Eliminar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="15" class="py-12 text-center text-gray-400 italic">No hay docentes
                                        registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 flex justify-center">
                    <nav class="flex items-center gap-1 font-sans text-[11px]">

                        @if ($docentes->onFirstPage())
                            <span class="px-3 py-1.5 rounded-md text-gray-300 border border-gray-200 cursor-not-allowed">
                                Anterior
                            </span>
                        @else
                            <a href="{{ $docentes->previousPageUrl() }}"
                                class="px-3 py-1.5 rounded-md border border-brand-green text-brand-green font-bold hover:bg-brand-green hover:text-white transition-colors">
                                Anterior
                            </a>
                        @endif

                        @foreach ($docentes->getUrlRange(1, $docentes->lastPage()) as $page => $url)
                            @if ($page == $docentes->currentPage())
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

                        @if ($docentes->hasMorePages())
                            <a href="{{ $docentes->nextPageUrl() }}"
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
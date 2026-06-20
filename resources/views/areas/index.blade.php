<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Áreas - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <x-page-header titulo="Áreas Organizacionales">
            <a href="{{ route('creations.index') }}" class="btn-back">
                ← Volver
            </a>
        </x-page-header>

        <div class="p-6">
            @if (session('success'))
                <div
                    class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 text-xs font-bold uppercase rounded-sm shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-6 rounded-sm border-2 border-brand-gold shadow-md">
                <div class="flex justify-between items-center mb-6 border-b-2 border-brand-green pb-4">
                    <h1 class="text-2xl font-bold text-brand-green uppercase tracking-tighter">
                        Gestión de Áreas
                    </h1>
                    <a href="{{ route('areas.create') }}">
                        <button class="btn-gold">
                            + AÑADIR ÁREA
                        </button>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse border border-brand-green">
                        <thead>
                            <tr class="bg-brand-green text-white text-[11px] uppercase tracking-wider">
                                <th class="p-3 font-bold">ID</th>
                                <th class="p-3 font-bold">Nombre del Área</th>
                                <th class="p-3 font-bold text-center">Contraseñas Custodiadas</th>
                                <th class="p-3 font-bold text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-xs uppercase font-medium text-gray-700">
                            @forelse ($areas as $area)
                                <tr class="hover:bg-gray-50 transition-colors text-black">
                                    <td class="p-3 text-gray-400">#{{ $area->id }}</td>
                                    <td class="p-3 font-bold text-brand-green">{{ $area->nombre }}</td>
                                    <td class="p-3 text-center">
                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $area->contrasenas_count > 0 ? 'bg-gold/20 text-black border border-brand-gold' : 'bg-gray-100 text-gray-400' }}">
                                            {{ $area->contrasenas_count }} CLAVES
                                        </span>
                                    </td>
                                    <td class="p-3 flex justify-center gap-2">
                                        <a href="{{ route('contrasenas.index', ['area_id' => $area->id]) }}"
                                            class="text-[10px] bg-brand-green text-white font-bold px-3 py-1.5 rounded-sm hover:bg-opacity-90 tracking-tighter transition-all">
                                            VER CREDENCIALES
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        class="p-8 text-center text-gray-400 uppercase text-xs font-bold tracking-tight">
                                        No hay áreas registradas en el sistema todavía.
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
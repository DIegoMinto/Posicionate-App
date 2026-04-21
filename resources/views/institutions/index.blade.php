<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instituciones - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <x-page-header titulo="Configuración">
            <x-slot name="search">
                <form action="{{ route('institutions.index') }}" method="GET" class="relative bg-white rounded-full">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar institución..."
                        class="pl-10 pr-4 py-1.5 text-xs w-64 outline-none rounded-full">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-black ">
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
                    <h1 class="text-2xl font-bold text-brand-green uppercase tracking-tighter">Gestión de Instituciones</h1>
                    <a href="{{ route('institutions.create') }}">
                        <button class="btn-gold">
                            + AÑADIR INSTITUCIÓN
                        </button>
                    </a>
                </div>

                <div class="overflow-x-auto rounded-xl border-brand-green border-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b-2 border-brand-gold uppercase text-[10px] font-black bg-brand-green text-white">
                                <th class="py-3 px-4 whitespace-nowrap">N°</th>
                                <th class="py-3 px-4 whitespace-nowrap">Logo</th>
                                <th class="py-3 px-4 whitespace-nowrap">Nombre de la Institución</th>
                                <th class="py-3 px-4 whitespace-nowrap">Dirección</th>
                                <th class="py-3 px-4 whitespace-nowrap">Teléfono</th>
                                <th class="py-3 px-4 text-center sticky right-0 bg-brand-green z-10">Operaciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-[11px] font-medium">
                            @forelse($instituciones as $index => $ins)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors text-black">
                                    <td class="py-3 px-4">{{ $index + 1 }}</td>
                                    <td class="py-2 px-4">
                                        @if($ins->imagen)
                                            <img src="{{ asset('storage/' . $ins->imagen) }}" alt="Logo" class="w-10 h-10 object-contain rounded-md border border-gray-200 bg-white">
                                        @else
                                            <div class="w-10 h-10 bg-gray-100 rounded-md flex items-center justify-center text-[8px] text-gray-400 uppercase">S/L</div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 tracking-tight">{{ $ins->nombre }}</td>
                                    <td class="py-3 px-4">{{ $ins->direccion ?? 'No especificada' }}</td>
                                    <td class="py-3 px-4 ">{{ $ins->telefono ?? '-' }}</td>
                                    
                                    <td class="px-4 py-3 sticky right-0 bg-white border-l border-gray-50" x-data="{ openDelete: false }">
                                        <div class="flex items-center justify-center gap-3 h-full">
                                            
                                            <a href="{{ route('institutions.edit', $ins->id_institucion) }}" 
                                               class="text-brand-green hover:text-brand-gold transition flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            <button @click="openDelete = true" class="text-red-600 hover:text-red-800 transition cursor-pointer flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-1 12a2 2 0 01-2 2H8a2 2 0 01-2-2L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4" />
                                                </svg>
                                            </button>

                                            <div x-show="openDelete" 
                                                 class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm" 
                                                 x-cloak 
                                                 x-transition:enter="transition ease-out duration-300"
                                                 x-transition:enter-start="opacity-0"
                                                 x-transition:enter-end="opacity-100">
                                                
                                                <div class="bg-white p-6 rounded-sm shadow-2xl w-80 text-left border-t-4 border-red-600" @click.away="openDelete = false">
                                                    <h3 class="text-[11px] font-black text-red-600 uppercase mb-2">Confirmar Eliminación</h3>
                                                    <p class="text-[10px] mb-4 text-gray-600">
                                                        ¿Estás seguro de eliminar esta institución?<br>
                                                        <span class="font-bold text-black uppercase">{{ $ins->nombre }}</span>
                                                    </p>

                                                    <form action="{{ route('institutions.destroy', $ins->id_institucion) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        
                                                        <input type="password" name="password_confirm" required 
                                                            class="w-full border border-gray-200 p-2 text-xs mb-4 focus:outline-none focus:border-red-500 bg-gray-50" 
                                                            placeholder="Contraseña de administrador">

                                                        <div class="flex justify-end gap-3">
                                                            <button type="button" @click="openDelete = false" class="text-[9px] font-bold text-gray-400 uppercase cursor-pointer">Cancelar</button>
                                                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-sm text-[9px] font-black uppercase cursor-pointer">Eliminar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-gray-400 italic uppercase text-[10px]">No hay instituciones registradas.</td>
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
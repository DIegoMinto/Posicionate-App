<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Curso - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <x-page-header titulo="Detalles del Programa">
            <a href="{{ route('programs.index') }}" class="btn-back">
                ← Volver
            </a>
        </x-page-header>

        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 bg-white rounded-xl border-2 border-brand-green shadow-md overflow-hidden">
                    <div class="p-4 flex justify-between items-center bg-brand-green">
                        <h2 class="font-sans font-bold uppercase tracking-widest text-lg text-white">
                            {{ $curso->nombre }}
                        </h2>

                        <span class="px-3 py-1 font-sans rounded-full uppercase text-[10px] font-bold shadow-sm border-2 transition-all
    {{ $curso->estado == 'activo' || is_null($curso->estado) ? 'border-brand-green text-white bg-brand-green' : '' }}
    {{ $curso->estado == 'en proceso' ? 'border-brand-gold text-black bg-brand-gold' : '' }}
    {{ $curso->estado == 'finalizado' ? 'border-gray-500 text-gray-500 bg-gray-50' : '' }}">
                            {{ $curso->estado ?? 'Activo' }}
                        </span>
                    </div>
                    <div class="p-6 grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-[10px] uppercase font-sans font-bold">Código</p>
                            <p class="font-bold text-brand-green">{{ $curso->codigo_curso }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-sans font-bold">Sede</p>
                            <p class="font-bold">{{ $curso->sede->nombre ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-sans font-bold">Docente</p>
                            <p class="font-bold italic">
                                {{ $curso->docente ? $curso->docente->nombre . ' ' . $curso->docente->apellido_p : 'Sin asignar' }}
                            </p>
                        </div>
                        <div class="border-t pt-2">
                            <p class="text-[10px] uppercase font-bold">Inscritos</p>
                            <p class="text-2xl font-black text-brand-green">{{ $curso->inscritos }} <span
                                    class="text-xs font-normal">({{ $curso->pre_inscritos }} Pre
                                    Inscritos)</span>
                            </p>
                        </div>
                        <div class="border-t pt-2 text-center">
                            <p class="text-[10px] uppercase font-bold">Código QR</p>
                            <div class="flex justify-center mt-1">
                                @if($curso->codigo_qr)
                                    <div class="w-20 h-20 border-2 border-brand-gold p-1 bg-white shadow-sm">
                                        <img src="{{ asset('storage/' . $curso->codigo_qr) }}" alt="Código QR del Curso"
                                            class="w-full h-full object-contain">
                                    </div>
                                @else

                                    <div
                                        class="w-16 h-16 bg-gray-50 border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-400">
                                        <i class="fas fa-qrcode text-xs mb-1"></i>
                                        <span class="text-[7px] uppercase font-bold text-center leading-none">Sin
                                            QR<br>Asignado</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-sm border-2 border-gray-200 shadow-md p-6 flex flex-col items-center justify-center text-center">
                    <p class="text-brand-green font-sans font-bold text-xs uppercase mb-4 tracking-tighter">Institución
                        Perteneciente</p>
                    @if($curso->institucion && $curso->institucion->imagen)
                        <img src="{{$curso->institucion->imagen }}" class="w-32 h-32 object-contain mb-4">
                    @else
                        <div
                            class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4 border-2 border-dashed border-gray-300">
                            <span class="text-gray-400 text-[10px]">SIN LOGO</span>
                        </div>
                    @endif
                    <h3 class="text-brand-green font-bold uppercase leading-tight">
                        {{ $curso->institucion->nombre ?? 'Sin Institución' }}
                    </h3>
                    <p class="text-[10px] mt-2"><i class="fas fa-map-marker-alt"></i>
                        {{ $curso->institucion->direccion ?? 'Dirección no registrada' }}</p>
                </div>
            </div>

            <div class="bg-white rounded-sm shadow-md">

                <div class="bg-white rounded-sm shadow-md">
                    <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3
                            class="text-brand-green font-sans font-bold uppercase tracking-tighter flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Módulos del Programa
                        </h3>
                        <a href="{{ route('modulo.create', ['id_curso' => $curso->id_curso]) }}" class="btn-gold">
                            + Añadir Módulo
                        </a>
                    </div>

                    <div class="overflow-x-auto p-4">
                        <div class="bg-white rounded-sm border-gray-800 shadow-md">
                            <table class="w-full text-left border-1 border-brand-green">
                                <thead class="bg-brand-green font-sans font-bold text-white">
                                    <tr>
                                        <th class="px-6 py-3 text-left">Nombre del Módulo</th>
                                        <th class="px-6 py-3 text-left">Fecha de Inicio</th>
                                        <th class="px-6 py-3 text-left">Fecha de Fin</th>
                                        <th class="px-6 py-3 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @forelse($curso->modulos as $modulo)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 text-left">
                                                <p class="font-sans font-bold text-brand-green">{{ $modulo->nombre }}</p>
                                            </td>
                                            <td class="px-6 py-4 text-left font-sans">
                                                {{ $modulo->fecha_inicio ? \Carbon\Carbon::parse($modulo->fecha_inicio)->format('d/m/Y') : 'Por definir' }}
                                            </td>
                                            <td class="px-6 py-4 text-left font-sans">
                                                {{ $modulo->fecha_fin ? \Carbon\Carbon::parse($modulo->fecha_fin)->format('d/m/Y') : 'Por definir' }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex justify-center gap-3 items-center">

                                                    <a href="{{ route('programs.edit', $curso->id_curso) }}"
                                                        class="text-brand-green hover:text-brand-gold transition">

                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>

                                                        <span
                                                            class="absolute -top-8 left-1/2 -translate-x-1/2 scale-0 transition-all rounded bg-gray-800 px-2 py-1 text-[10px] text-white group-hover:scale-100 whitespace-nowrap z-30 shadow-lg">
                                                            Editar Módulo
                                                        </span>
                                                    </a>

                                                    @if($usuario->rol === 'super_admin')

                                                        <div x-data="{ openDelete: false }">

                                                            <button @click="openDelete = true"
                                                                class="text-red-600 hover:text-red-800 transition cursor-pointer">

                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M19 7l-1 12a2 2 0 01-2 2H8a2 2 0 01-2-2L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4">
                                                                    </path>
                                                                </svg>

                                                                <span
                                                                    class="absolute -top-8 left-1/2 -translate-x-1/2 scale-0 transition-all rounded bg-red-600 px-2 py-1 text-[10px] text-white group-hover:scale-100 whitespace-nowrap z-30 shadow-lg">
                                                                    Eliminar Módulo
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
                                                                        Vas a eliminar el módulo:<br>

                                                                        <span class="text-black font-bold uppercase">
                                                                            {{ $modulo->nombre }}
                                                                        </span>
                                                                    </p>

                                                                    <form
                                                                        action="{{ route('modules.destroy', $modulo->id_modulo) }}"
                                                                        method="POST">

                                                                        @csrf
                                                                        @method('DELETE')

                                                                        <input type="password" name="password_confirm" required
                                                                            class="w-full border border-gray-200 p-2 text-xs mb-4 focus:outline-none focus:border-red-500 bg-gray-50"
                                                                            placeholder="Tu contraseña de administrador">

                                                                        <div class="flex justify-end gap-3">

                                                                            <button type="button" @click="openDelete = false"
                                                                                class="text-[9px] uppercase hover:text-red-600 transition-colors cursor-pointer">

                                                                                Cancelar
                                                                            </button>

                                                                            <button type="submit"
                                                                                class="bg-red-600 text-white px-4 py-2 rounded-sm text-[9px] uppercase hover:bg-red-700 transition-colors cursor-pointer">

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
                                            <td colspan="4"
                                                class="px-6 py-10 text-center text-gray-400 italic uppercase text-xs tracking-widest">
                                                No hay módulos registrados para este curso.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-layout-dashboard>
</body>

</html>
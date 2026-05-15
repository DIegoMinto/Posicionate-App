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
                                    {{-- Mostramos la imagen real desde el storage --}}
                                    <div class="w-20 h-20 border-2 border-brand-gold p-1 bg-white shadow-sm">
                                        <img src="{{ asset('storage/' . $curso->codigo_qr) }}" alt="Código QR del Curso"
                                            class="w-full h-full object-contain">
                                    </div>
                                @else
                                    {{-- Placeholder elegante si no hay imagen --}}
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
                        <img src="{{ asset('storage/' . $curso->institucion->imagen) }}"
                            class="w-32 h-32 object-contain mb-4">
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
                        <div class="inline-block min-w-full overflow-hidden rounded-lg border border-gray-200">
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
                                                <div class="flex justify-center gap-2">
                                                    <button class="text-gray-400 hover:text-brand-gold"
                                                        title="Editar Módulo">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="text-gray-400 hover:text-red-500"
                                                        title="Eliminar Módulo">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
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
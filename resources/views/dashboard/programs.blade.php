<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <x-page-header titulo="Programas">

            <div class="flex flex-col gap-2">

                <form action="{{ route('programs.index') }}" method="GET"
                    class="flex items-center gap-3">

                    <select name="id_sede" onchange="this.form.submit()"
                        class="bg-white text-black text-[10px] font-bold px-3 py-1.5 rounded-md uppercase">
                        <option value="">SEDE: TODAS</option>
                        @foreach($sedes as $sede)
                            <option value="{{ $sede->id_sede }}" {{ request('id_sede') == $sede->id_sede ? 'selected' : '' }}>
                                {{ strtoupper($sede->nombre) }}
                            </option>
                        @endforeach
                    </select>

                    <select name="id_institucion" onchange="this.form.submit()"
                        class="bg-white text-black text-[10px] font-bold px-3 py-1.5 rounded-md uppercase">
                        <option value="">INSTITUCIÓN: TODAS</option>
                        @foreach($instituciones as $inst)
                            <option value="{{ $inst->id_institucion }}" {{ request('id_institucion') == $inst->id_institucion ? 'selected' : '' }}>
                                {{ strtoupper($inst->nombre) }}
                            </option>
                        @endforeach
                    </select>

                    <select name="estado" onchange="this.form.submit()"
                        class="bg-white text-black text-[10px] font-bold px-3 py-1.5 rounded-md uppercase">
                        <option value="">ESTADO: TODOS</option>
                        <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>ACTIVO</option>
                        <option value="en proceso" {{ request('estado') == 'en proceso' ? 'selected' : '' }}>EN PROCESO</option>
                        <option value="finalizado" {{ request('estado') == 'finalizado' ? 'selected' : '' }}>FINALIZADO</option>
                    </select>

                </form>

                <form action="{{ route('programs.index') }}" method="GET"
                    class="flex items-center gap-3">
                    <div>
                        <div class="font-sans text-[12px]">Fecha inicio</div>
                        <input type="date" name="fecha_inicio"
                            value="{{ request('fecha_inicio') }}"
                            onchange="this.form.submit()"
                            class="bg-white text-[10px] px-2 py-1.5 rounded-md uppercase">
                    </div>
                    <div>
                        <div class="font-sans text-[12px]">Fecha Fin</div>
                        <input type="date" name="fecha_fin"
                            value="{{ request('fecha_fin') }}"
                            onchange="this.form.submit()"
                            class="bg-white text-[10px] px-2 py-1.5 rounded-md uppercase">
                    </div>

                </form>

            </div>

            <x-slot name="search">
                <form action="{{ route('programs.index') }}" method="GET"
                    class="relative bg-white rounded-full">

                    <input type="text" name="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar programa..."
                        class="pl-10 pr-4 py-1.5 text-xs w-64 outline-none">

                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-black">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                </form>
            </x-slot>

        </x-page-header>


        <div class="p-6">
            <div class="bg-white p-6 rounded-sm border-2 border-brand-gold shadow-md">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-brand-green uppercase tracking-tighter">Lista de Programas</h1>
                    <a href="{{ route('programs.create') }}">
                        <button class="btn-gold">
                            + AÑADIR PROGRAMA
                        </button>
                    </a>
                </div>
                <div class="grid grid-cols-1 gap-4">
                    @forelse($cursos as $curso)
                                <div class="border-2 border-brand-green rounded-sm shadow-sm">
                                    <div class="bg-white pl-4 pr-4 pt-4 flex items-center gap-4 hover:shadow-md transition-shadow">

                                        <div class="flex-shrink-0">
                                            @if($curso->institucion && $curso->institucion->imagen)
                                                <img src="{{ asset('storage/' . $curso->institucion->imagen) }}"
                                                    class="object-contain w-20 h-20 rounded-md border border-gray-100 bg-gray-50">
                                            @else
                                                <div
                                                    class="w-14 h-14 bg-gray-100 flex items-center justify-center rounded-md border border-dashed border-gray-300">
                                                    <span class="text-[8px] text-gray-400 uppercase font-black text-center">Sin
                                                        Foto</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <div class="font-sans text-brand-green font-bold truncate leading-tight">
                                                {{ $curso->nombre }}
                                            </div>
                                            <div class="font-sans flex items-center gap-1 mt-0.5 italic">
                                                @if($curso->docente)
                                                    {{ $curso->docente->nombre }} {{ $curso->docente->apellido_p }} {{ $curso->docente->apellido_m }}
                                                @else
                                                    <span class="text-red-600">Sin docente asignado</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex flex-col items-center gap-3 border-l border-gray-200 pl-4">

                                            <div class="flex items-center">
                                                <div class="text-center">
                                                    <p class="text-brand-green font-bold leading-none">Inicio</p>
                                                    <p class="font-sans text-black whitespace-nowrap">
                                                        {{ $curso->fecha_inicio ? \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') : '--/--/--' }}
                                                    </p>
                                                </div>
                                                <div class="text-center ml-5">
                                                    <p class="text-brand-green font-bold leading-none">Fin</p>
                                                    <p class="font-sans text-black whitespace-nowrap">
                                                        {{ $curso->fecha_fin ? \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') : '--/--/--' }}
                                                    </p>
                                                </div>
                                            </div>
@if(
                                                            in_array($usuario->cargo, [
                                                                'supervisor_academico',
                                                                
                                                            ]) 
                                                            || $usuario->rol === 'super_admin'
                                                        )
                                            <div class="w-full flex justify-center mt-2">
                                                <form action="{{ route('programs.updateStatus', $curso->id_curso) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="estado" onchange="this.form.submit()" class="w-24 text-[9px] font-bold uppercase py-1 px-2 rounded-sm border-2 transition-all cursor-pointer focus:ring-0 text-center
                            {{ $curso->estado == 'activo' || is_null($curso->estado) ? 'border-brand-green text-white bg-brand-green' : '' }}
                            {{ $curso->estado == 'en proceso' ? 'border-brand-gold text-black bg-brand-gold' : '' }}
                            {{ $curso->estado == 'finalizado' ? 'border-gray-500 text-gray-500 bg-gray-50' : '' }}">

                                                        <option value="activo" {{ $curso->estado == 'activo' || is_null($curso->estado) ? 'selected' : '' }}>
                                                            Activo
                                                        </option>
                                                        <option value="en proceso" {{ $curso->estado == 'en proceso' ? 'selected' : '' }}>
                                                            En Proceso
                                                        </option>
                                                        <option value="finalizado" {{ $curso->estado == 'finalizado' ? 'selected' : '' }}>
                                                            Finalizado
                                                        </option>
                                                    </select>
                                                </form>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-3 border-l border-gray-200 pl-4">
                                            <div class="text-center">
                                                <p class="text-brand-green font-bold leading-none text-[11px] uppercase tracking-tighter mb-2">
                                                    Adicionales
                                                </p>
                                                <div class="flex justify-center items-center gap-2">
                                                    <a href="{{ route('programs.show', ['id' => $curso->id_curso]) }}"
                                                        class="group relative flex items-center justify-center"
                                                        title="Ver Detalles">
                                                            <div class="pb-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                </svg>
                                                            </div>
                                                            
                                                            <span class="absolute -top-8 scale-0 transition-all rounded bg-gray-800 px-2 py-1 text-[10px] text-white group-hover:scale-100 whitespace-nowrap z-30 shadow-lg">
                                                                Ver Información Completa
                                                            </span>
                                                        </a>
                                                        @if(
                                                            in_array($usuario->cargo, [
                                                                'coordinador_academico',
                                                                'supervisor_academico',
                                                                'asistente_academico'
                                                            ]) 
                                                            || $usuario->rol === 'super_admin'
                                                        )
                                                    <a href="{{ route('modulo.create', ['id_curso' => $curso->id_curso]) }}" 
                                                    class="group relative flex items-center justify-center"
                                                    title="Añadir Clase">
                                                        <div class="">
                                                            <img src="/img/add_class.png" class="w-12 h-12 object-contain" alt="Añadir Clase">
                                                        </div>
                                                        
                                                        <span class="absolute -top-8 scale-0 transition-all rounded bg-gray-800 p-1 text-[10px] text-white group-hover:scale-100">
                                                            Añadir Módulo
                                                        </span>
                                                    </a>
                                                    
                                                    <a href="{{ route('programs.edit', ['id' => $curso->id_curso]) }}"
                                                    class="group relative flex items-center justify-center"
                                                    title="Editar Curso">
                                                        <div class="duration-200 pb-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </div>
                                                        
                                                        <span class="absolute -top-8 scale-0 transition-all rounded bg-gray-800 px-2 py-1 text-[10px] text-white group-hover:scale-100 whitespace-nowrap z-30">
                                                            Editar Información
                                                        </span>
                                                    </a>
                                                    @endif
                                                    
                                                    <div x-data="{ openModal: false }">

    <button @click="openModal = true"
        class="group relative flex items-center justify-center cursor-pointer"
        title="Añadir Estudiante">

        <img src="/img/add_student.png" class="w-12 h-12 object-contain" alt="Añadir Estudiante">

        <span class="absolute -top-8 scale-0 transition-all rounded bg-gray-800 p-1 text-[10px] text-white group-hover:scale-100">
            Añadir Estudiante
        </span>
    </button>

    <div x-show="openModal"
        class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 backdrop-blur-sm"
        x-cloak>

        <div class="bg-white w-[600px] max-h-[80vh] overflow-y-auto rounded-sm shadow-2xl"
            @click.away="openModal = false">

            <h2 class="font-sans font-bold mb-4 uppercase text-brand-green bg-brand-green text-white p-6">
                Seleccionar Estudiante
            </h2>

            <div 
    x-data="{
        search: '',
        estudiantes: @js($allEstudiantes)
    }"
    class="p-6"
>

    <input type="text"
        x-model="search"
        placeholder="Buscar por nombre o CI..."
        class="w-full border p-2 text-sm mb-4 rounded-sm focus:outline-none">

    <div class="space-y-2 max-h-[400px] overflow-y-auto">

        <template x-for="est in estudiantes.filter(e => 
            (e.nombre + ' ' + e.apellido_p).toLowerCase().includes(search.toLowerCase()) ||
            ((e.ci ?? '').toLowerCase().includes(search.toLowerCase()))
        )" :key="est.id_estudiante">

            <div class="flex justify-between items-center border p-2 rounded-sm">

                <div>
                    <div class="font-sans font-bold text-left"
                        x-text="est.nombre + ' ' + est.apellido_p">
                    </div>

                    <div class="font-sans text-brand-green text-left">
                        CI: <span x-text="est.ci"></span>
                    </div>
                </div>

                <form method="POST" action="{{ route('curso.agregar.estudiante') }}">
                    @csrf

                    <input type="hidden" name="id_estudiante" :value="est.id_estudiante">
                    <input type="hidden" name="id_curso" value="{{ $curso->id_curso }}">
                    <input type="hidden" name="id_personal" value="{{ $usuario->id_personal }}">

                    <button type="submit"
                        class="btn-gold px-3 py-2 text-[10px]">
                        Añadir
                    </button>
                </form>

                
            </div>

        </template>
<div class="text-right mt-4 p-2">
                <button @click="openModal = false"
                    class="text-xs text-red-600 font-bold font-sans uppercase cursor-pointer">
                    Cerrar
                </button>
            </div>
        <div x-show="estudiantes.filter(e => 
            (e.nombre + ' ' + e.apellido_p).toLowerCase().includes(search.toLowerCase()) ||
            ((e.ci ?? '').toLowerCase().includes(search.toLowerCase()))
        ).length === 0"
            class="text-center text-gray-400 text-xs py-4 italic">

            No se encontraron estudiantes

        </div>

    </div>

</div>

        </div>
    </div>

</div>
                                                    
                                                    <a href="{{ route('curso.estudiantes', $curso->id_curso) }}" 
                                                    class="group relative flex items-center justify-center"
                                                    title="Añadir Clase">
                                                        <div class="">
                                                            <img src="/img/watch_list.png" class="w-10 h-10 object-contain" alt="Añadir Clase">
                                                        </div>
                                                        
                                                        <span class="absolute -top-8 scale-0 transition-all rounded bg-gray-800 p-1 text-[10px] text-white group-hover:scale-100">
                                                            Ver Lista
                                                        </span>
                                                    </a>
                                                     @if(
                                                            in_array($usuario->cargo, [
                                                                'supervisor_academico',
                                                                
                                                            ]) 
                                                            || $usuario->rol === 'super_admin'
                                                        )
                                                    <a href="{{ route('programs.payments.setup', $curso->id_curso) }}" 
                                                    class="group relative flex items-center justify-center"
                                                    title="Añadir Clase">
                                                        <div class="">
                                                            <img src="/img/plans_icon.png" class="w-10 h-10 object-contain" alt="Añadir Clase">
                                                        </div>
                                                        
                                                        <span class="absolute -top-8 scale-0 transition-all rounded bg-gray-800 p-1 text-[10px] text-white group-hover:scale-100">
                                                            Planes de Pago
                                                        </span>
                                                    </a>
                                                    @endif
                                                    <div x-data="{ openEstudiantes: false }">
                                                        <button @click="openEstudiantes = true" 
                                                                class="group relative flex items-center justify-center cursor-pointer focus:outline-none p-2">
                                                            
                                                            <svg xmlns="http://www.w3.org/2000/svg" 
                                                                class="w-6 h-6 text-black" 
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.828a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                            </svg>
                                                            
                                                            <span class="absolute -top-8 scale-0 transition-all rounded bg-gray-800 px-2 py-1 text-[10px] text-white group-hover:scale-100 whitespace-nowrap z-50 shadow-lg font-sans">
                                                                Formulario de Registro
                                                            </span>
                                                        </button>

                                                        <div x-show="openEstudiantes" 
                                                            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm"
                                                            x-cloak>
                                                            
                                                            <div class="bg-white p-6 rounded-sm shadow-2xl w-[85%] max-w-5xl text-left border-t-4 border-brand-green font-sans max-h-[90vh] overflow-y-auto"
                                                                @click.away="openEstudiantes = false">
                                                                
                                                                <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-3">
                                                                    <div class="flex items-center gap-2">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-brand-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                        </svg>
                                                                        <h3 class="text-brand-green uppercase font-sans font-bold tracking-tighter">
                                                                            Formulario de Registro de: 
                                                                            <span>
                                                                                {{ auth()->user()->persona?->nombre }} 
                                                                                {{ auth()->user()->persona?->apellido_p }} 
                                                                            </span>
                                                                            <span>
                                                                                - {{ auth()->user()->cargo }}
                                                                            </span>
                                                                        </h3>
                                                                    </div>
                                                                    <button @click="openEstudiantes = false" class="text-black hover:text-red-600 transition-colors cursor-pointer">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                        </svg>
                                                                    </button>
                                                                </div>

                                                                <div class="py-6" x-data="{ 
                                                                    url: '{{ url('/inscripcion/' . $curso->id_curso . '/' . auth()->user()->id_personal) }}',
                                                                    copied: false,
                                                                    copyToClipboard() {
                                                                        navigator.clipboard.writeText(this.url);
                                                                        this.copied = true;
                                                                        setTimeout(() => this.copied = false, 2000);
                                                                        }
                                                                    }">
                                                                    <div class="text-center mb-8">
                                                                        <p class="text-[11px] font-sans font-bold tracking-widest mb-2 uppercase">Formulario del curso</p>
                                                                        <h4 class="text-lg font-sans font-bold text-black uppercase">{{ $curso->nombre }}</h4>
                                                                    </div>

                                                                    <div class="bg-gray-50 p-6 rounded-sm border border-gray-100">
                                                                        <label class="block uppercase font-sans font-bold text-brand-green mb-2">Copia y envía el formulario al estudiante:</label>
                                                                        
                                                                        <div class="flex gap-2">
                                                                            <input type="text" readonly :value="url" 
                                                                                class="flex-1 bg-white border border-gray-200 p-3 text-xs font-mono focus:outline-none rounded-sm shadow-inner">
                                                                            
                                                                            <button @click="copyToClipboard()" class="btn-gold flex items-center gap-2">
                                                                                <svg x-show="!copied" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                                                </svg>

                                                                                <svg x-show="copied" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                                </svg>
                                                                                
                                                                                <span x-text="copied ? 'COPIADO' : ''"></span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                     @if(
                                                            in_array($usuario->cargo, [
                                                                'supervisor_academico',
                                                            ]) 
                                                            || $usuario->rol === 'super_admin'
                                                        )
                                                    <div x-data="{ openDelete: false }">
                                                        <button @click="openDelete = true" class="group relative flex items-center justify-center pb-1 cursor-pointer">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 group-hover:text-red-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            <span class="absolute -top-8 scale-0 transition-all rounded bg-red-600 px-2 py-1 text-[10px] text-white group-hover:scale-100 whitespace-nowrap z-30 shadow-lg font-sans">
                                                                Eliminar Programa
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
                                                                        {{ $curso->nombre }} {{ $curso->apellido_p}} {{ $curso->apellido_m}}
                                                                    </span>
                                                                </p>

                                                                <form action="{{ route('programs.destroy', $curso->id_curso) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')

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
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center mt-2 px-4 mb-2">
                                        <div class="text-brand-green font-bold tracking-wider flex items-center gap-1">
                                            {{ $curso->sede->nombre }}
                                        </div>

                                        <div
                                            class="bg-brand-green text-white rounded-sm px-3 py-1 flex items-baseline gap-1.5 shadow-sm ml-20">
                                            <div class="flex flex-col items-center leading-none">
                                                <span class="text-lg font-black leading-none">
                                                    {{ $curso->inscritos }}
                                                </span>
                                            </div>

                                            <div class="flex items-baseline text-white font-bold">
                                                <span class="text-[10px] ml-1">
                                                    ({{ $curso->pre_inscritos }})
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                <div class="col-span-full py-10 text-center text-gray-400 italic text-xs uppercase tracking-widest">
                    No hay cursos registrados actualmente.
                </div>
            @endforelse
                            </div>
                        
        </div>
        </div>
    </x-layout-dashboard>

</body>

</html>
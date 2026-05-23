<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creación de Módulo - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <x-page-header titulo="Gestión de Módulos">
            <a href="{{ route('programs.index') }}" class="btn-back">
                ← Volver
            </a>
        </x-page-header>

        <div class="p-6">
            <div class="max-w-3xl bg-white p-8 rounded-sm border-2 border-brand-gold shadow-md">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-brand-green uppercase tracking-tighter">
                        Registrar Nuevo Módulo
                    </h1>
                </div>

                <form action="{{ route('modulo.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <input type="hidden" name="id_curso" value="{{ $id_curso }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="md:col-span-2">
                            <label class="form-label-bold text-black">Nombre del Módulo</label>
                            <input type="text" name="nombre" placeholder="Ej: Módulo 1: Introducción al Marketing"
                                required class="form-input-pill border-2 border-brand-gold">
                        </div>

                        <div>
                            <label class="form-label-bold text-black">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-input-pill border-2 border-brand-gold">
                        </div>

                        <div>
                            <label class="form-label-bold text-black">Fecha de Finalización</label>
                            <input type="date" name="fecha_fin" class="form-input-pill border-2 border-brand-gold">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ route('programs.index') }}"
                            class="text-[10px] font-bold text-gray-400 uppercase py-3 px-6 hover:text-red-500 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-gold">
                            GUARDAR MÓDULO
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </x-layout-dashboard>
</body>

</html>
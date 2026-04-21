<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creación de Clase - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <x-page-header titulo="Gestión de Clases">
        </x-page-header>

        <div class="p-6">
            <div class="max-w-3xl bg-white p-8 rounded-sm border-2 border-brand-gold shadow-md">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-brand-green uppercase tracking-tighter">
                        Programar Nueva Clase
                    </h1>
                </div>

                <form action="{{ route('class.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <input type="hidden" name="id_curso" value="{{ $id_curso }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="form-label-bold text-black">Nombre de la Sesión</label>
                                <input type="text" name="nombre" required
                                    class="form-input-pill border-2 border-brand-gold">
                            </div>

                            <div>
                                <label class="form-label-bold text-black">Fecha de la Clase</label>
                                <input type="date" name="fecha" required
                                    class="form-input-pill border-2 border-brand-gold">
                            </div>

                            <div>
                                <label class="form-label-bold text-black">Tipo de Sesión</label>
                                <select name="tipo" required
                                    class="form-input-pill border-2 border-brand-gold bg-white">
                                    <option value="Clase Regular">Clase Regular</option>
                                    <option value="Webinar">Webinar</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <div>
                                <label class="form-label-bold text-black">Hora de Inicio</label>
                                <input type="time" name="hora_inicio" required
                                    class="form-input-pill border-2 border-brand-gold">
                            </div>

                            <div>
                                <label class="form-label-bold text-black">Hora de Finalización</label>
                                <input type="time" name="hora_fin" required
                                    class="form-input-pill border-2 border-brand-gold">
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ route('programs.index') }}"
                            class="text-[10px] font-bold text-gray-400 uppercase py-3 px-6 hover:text-red-500 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-gold">
                            GUARDAR CLASE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </x-layout-dashboard>
</body>

</html>
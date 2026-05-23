<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sede - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <x-page-header titulo="Sedes">
            <a href="{{ route('sedes.index') }}" class="btn-back">
                ← Volver
            </a>
        </x-page-header>

        <div class="p-6 flex justify-center">
            <div class="w-full max-w-xl bg-white rounded-sm border-2 border-brand-green shadow-md">
                <div class="bg-brand-green text-white p-8">
                    <h1 class="text-2xl font-bold text-white uppercase tracking-tighter">Editar Sede</h1>
                </div>
                <div class="p-8">
                    <form action="{{ route('sedes.update', $sede->id_sede) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4 max-w-md">
                            <div>
                                <label class="form-label-bold text-black uppercase text-xs">Nombre de la Sede</label>
                                <input type="text" name="nombre" value="{{ $sede->nombre }}" required
                                    class="form-input-pill border-2 border-brand-gold">
                            </div>

                            <div>
                                <label class="form-label-bold text-black uppercase text-xs">Dirección</label>
                                <input type="text" name="direccion" value="{{ $sede->direccion }}"
                                    class="form-input-pill border-2 border-brand-gold">
                            </div>

                            <div>
                                <label class="form-label-bold text-black uppercase text-xs">Teléfono</label>
                                <input type="text" name="telefono" value="{{ $sede->telefono }}"
                                    class="form-input-pill border-2 border-brand-gold font-sans">
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                            <a href="{{ route('sedes.index') }}"
                                class="text-[10px] font-bold text-gray-400 uppercase py-3 px-6 hover:text-red-500">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-gold">
                                ACTUALIZAR DATOS
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-layout-dashboard>
</body>

</html>
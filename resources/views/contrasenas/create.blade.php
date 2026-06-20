<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Contraseña - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <x-page-header titulo="Contraseñas">
            <a href="{{ route('contrasenas.index') }}" class="btn-back">
                ← Volver
            </a>
        </x-page-header>

        <div class="p-6 flex justify-center">
            <div class="w-full max-w-3xl bg-white rounded-sm border-2 border-brand-green shadow-md">
                <div class="p-8 bg-brand-green">
                    <h1 class="text-2xl font-bold text-white uppercase tracking-tighter">
                        Asignar Contraseña por Área
                    </h1>
                </div>
                <div class="p-8">
                    <form action="{{ route('contrasenas.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="form-label-bold text-black">Área Destinataria</label>
                                    <select name="area_id" required
                                        class="form-input-pill border-2 border-brand-gold outline-none w-full bg-white px-3 py-1.5 text-xs uppercase">
                                        <option value="">Seleccionar Área</option>
                                        @foreach($areas as $area)
                                            <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                                {{ strtoupper($area->nombre) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('area_id')
                                        <span
                                            class="text-red-500 text-[10px] mt-1 block font-medium uppercase">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div
                                class="flex flex-col justify-center bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg p-6">
                                <div class="w-full">
                                    <label class="form-label-bold text-black mb-2 block">Contraseña del Servicio</label>
                                    <input type="password" name="contrasena" required placeholder="••••••••"
                                        class="form-input-pill border-2 border-brand-gold bg-white">
                                    <p class="text-[8px] text-gray-400 mt-2 uppercase">Esta clave se guardará cifrada
                                        para proteger el área.</p>
                                    @error('contrasena')
                                        <span
                                            class="text-red-500 text-[10px] mt-1 block font-medium uppercase">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                            <a href="{{ route('contrasenas.index') }}"
                                class="text-[10px] font-bold text-gray-400 uppercase py-3 px-6 hover:text-red-500 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-gold">
                                GUARDAR CONTRASEÑA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-layout-dashboard>
</body>

</html>
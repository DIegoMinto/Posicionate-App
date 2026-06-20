<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Área - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <x-page-header titulo="Áreas Organizacionales">
            <a href="{{ route('areas.index') }}" class="btn-back">
                ← Volver
            </a>
        </x-page-header>

        <div class="p-6 flex justify-center">
            <div class="w-full max-w-3xl bg-white rounded-sm border-2 border-brand-green shadow-md">
                <div class="p-8 bg-brand-green">
                    <h1 class="text-2xl font-bold text-white uppercase tracking-tighter">
                        Registrar Nueva Área
                    </h1>
                </div>
                <div class="p-8">
                    <form action="{{ route('areas.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="form-label-bold text-black">Nombre del Área</label>
                                    <input type="text" name="nombre" value="{{ old('nombre') }}" required
                                        placeholder="EJ: RECURSOS HUMANOS, DIRECCIÓN"
                                        class="form-input-pill border-2 border-brand-gold uppercase">
                                    @error('nombre')
                                        <span
                                            class="text-red-500 text-[10px] mt-1 block font-medium uppercase">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div
                                class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-lg p-4 bg-gray-50">
                                <label class="form-label-bold text-black mb-2 uppercase tracking-tighter">Estructura
                                    Organizacional</label>

                                <div class="mb-3">
                                    <div
                                        class="bg-white p-4 rounded-md border-2 border-brand-gold shadow-sm flex items-center justify-center w-24 h-24">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="w-12 h-12 text-brand-gold">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                        </svg>
                                    </div>
                                </div>

                                <p class="text-[8px] text-gray-400 text-center uppercase mt-1">Esta variable agrupará
                                    contraseñas y plataformas globales.</p>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                            <a href="{{ route('areas.index') }}"
                                class="text-[10px] font-bold text-gray-400 uppercase py-3 px-6 hover:text-red-500 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-gold">
                                GUARDAR ÁREA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-layout-dashboard>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <x-page-header titulo="Instituciones">
        </x-page-header>

        <div class="p-6 flex justify-center">
            <div class="w-full max-w-3xl bg-white rounded-sm border-2 border-brand-green shadow-md">
                <div class="mb-6 text-left bg-brand-green">
                    <h1 class="text-2xl font-bold text-white p-8 uppercase tracking-tighter">Editar Institución</h1>
                </div>
                <div class="p-8">
                    <form action="{{ route('institutions.update', $institution->id_institucion) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="form-label-bold text-black">Nombre de la Institución</label>
                                    <input type="text" name="nombre" value="{{ $institution->nombre }}" required
                                        class="form-input-pill border-2 border-brand-gold uppercase">
                                </div>

                                <div>
                                    <label class="form-label-bold text-black">Dirección</label>
                                    <input type="text" name="direccion" value="{{ $institution->direccion }}"
                                        class="form-input-pill border-2 border-brand-gold">
                                </div>

                                <div>
                                    <label class="form-label-bold text-black">Teléfono Móvil</label>
                                    <input type="text" name="telefono" value="{{ $institution->telefono }}"
                                        class="form-input-pill border-2 border-brand-gold font-mono">
                                </div>
                            </div>

                            <div
                                class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-lg p-4 bg-gray-50">
                                <label class="form-label-bold text-black">Actualizar Logo</label>

                                <div class="mb-3">
                                    @if($institution->logo)
                                        <img id="preview" src="{{ asset('storage/' . $institution->logo) }}"
                                            class="w-32 h-32 object-contain border-2 border-brand-gold bg-white p-2 shadow-sm">
                                    @else
                                        <img id="preview" src="https://via.placeholder.com/150?text=SIN+LOGO"
                                            class="w-32 h-32 object-contain border-2 border-gray-300 bg-white p-2 shadow-sm">
                                    @endif
                                </div>

                                <input type="file" name="imagen" id="imagen" accept="image/*" class="hidden"
                                    onchange="previewImage(event)">

                                <button type="button" onclick="document.getElementById('imagen').click()"
                                    class="bg-brand-green text-white text-[9px] font-bold py-2 px-4 rounded-full hover:bg-opacity-90 transition cursor-pointer">
                                    CAMBIAR IMAGEN
                                </button>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                            <a href="{{ route('institutions.index') }}"
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

        <script>
            function previewImage(event) {
                const reader = new FileReader();
                reader.onload = function () {
                    const output = document.getElementById('preview');
                    output.src = reader.result;
                }
                reader.readAsDataURL(event.target.files[0]);
            }
        </script>
    </x-layout-dashboard>

</body>

</html>
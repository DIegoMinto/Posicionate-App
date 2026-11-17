
<x-layout-dashboard :usuario="$usuario">

    <div class="mx-auto">

        <x-page-header titulo="Programación académica">
        </x-page-header>

        <div class="p-6">

            <a href="{{ route('programacion.masterclass.index', $curso->id_curso) }}"
                class="text-sm text-white bg-brand-green hover:text-brand-gold p-2 rounded-sm">
                ← Volver a Masterclass
            </a>

            @php
                $esEdicion = isset($masterclass);
            @endphp

            <div class="mt-5">

                <h2 class="text-2xl font-black text-brand-green">
                    {{ $esEdicion ? 'Editar Masterclass' : 'Registrar Masterclass' }}
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $curso->nombre }}
                </p>

            </div>

            @if($errors->any())
                <div class="mt-5 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                    <p class="font-semibold mb-2">
                        No se pudo guardar la información:
                    </p>

                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-7 max-w-3xl bg-white border border-gray-200 rounded-xl shadow-sm p-6">

                <form
                    action="{{ $esEdicion
                        ? route('programacion.masterclass.update', [$curso->id_curso, $masterclass->id_masterclass])
                        : route('programacion.masterclass.store', $curso->id_curso) }}"
                    method="POST"
                    class="space-y-6">

                    @csrf

                    @if($esEdicion)
                        @method('PUT')
                    @endif

                    {{-- TÍTULO --}}
                    <div>

                        <label for="titulo" class="block text-sm font-bold text-gray-700 mb-2">
                            Título de la Masterclass
                        </label>

                        <input
                            type="text"
                            id="titulo"
                            name="titulo"
                            value="{{ old('titulo', $masterclass->titulo ?? '') }}"
                            placeholder="Ej. MASTERCLASS 1"
                            maxlength="255"
                            required
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-brand-green focus:ring-2 focus:ring-brand-green/10 outline-none">

                        @error('titulo')
                            <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                        @enderror

                    </div>

                    {{-- FECHA --}}
                    <div>

                        <label for="fecha_inicio" class="block text-sm font-bold text-gray-700 mb-2">
                            Fecha de inicio
                        </label>

                        <input
                            type="date"
                            id="fecha_inicio"
                            name="fecha_inicio"
                            value="{{ old('fecha_inicio', isset($masterclass) ? $masterclass->fecha_inicio->format('Y-m-d') : '') }}"
                            required
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-brand-green focus:ring-2 focus:ring-brand-green/10 outline-none">

                        @error('fecha_inicio')
                            <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                        @enderror

                    </div>

                    {{-- DOCENTE --}}
                    <div>

                        <label for="id_docente" class="block text-sm font-bold text-gray-700 mb-2">
                            Docente asignado
                        </label>

                        <select
                            id="id_docente"
                            name="id_docente"
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-brand-green focus:ring-2 focus:ring-brand-green/10 outline-none">

                            <option value="">
                                Sin asignar
                            </option>

                            @foreach($docentes as $docente)

                                <option
                                    value="{{ $docente->id_docente }}"
                                    @selected(old('id_docente', $masterclass->id_docente ?? '') == $docente->id_docente)>

                                    {{ $docente->nombre }}
                                    {{ $docente->apellido_p }}
                                    {{ $docente->apellido_m }}

                                </option>

                            @endforeach

                        </select>

                        @error('id_docente')
                            <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                        @enderror

                    </div>

                    {{-- BOTONES --}}
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-5 border-t border-gray-100">

                        <a href="{{ route('programacion.masterclass.index', $curso->id_curso) }}"
                            class="px-5 py-3 rounded-lg border border-gray-300 text-gray-600 text-sm font-semibold text-center hover:bg-gray-50 transition">
                            Cancelar
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-brand-green text-white text-sm font-semibold hover:opacity-90 transition">

                            <i class="fas {{ $esEdicion ? 'fa-save' : 'fa-plus' }}"></i>

                            {{ $esEdicion ? 'Guardar cambios' : 'Registrar Masterclass' }}

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-layout-dashboard>

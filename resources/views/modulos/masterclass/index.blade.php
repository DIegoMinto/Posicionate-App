<x-layout-dashboard :usuario="$usuario">

    <div class="mx-auto">
        <x-page-header titulo="Gestión de Masterclass">
        </x-page-header>

        <div class="p-6">

            <a href="{{ route('programacion.show', $curso->id_curso) }}"
                class="text-sm text-white bg-brand-green hover:text-brand-gold p-2 rounded-sm">
                ← Volver a programación
            </a>

            <div class="mt-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div>
                    <h2 class="text-2xl font-black text-brand-green">
                        Masterclass
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $curso->nombre }}
                    </p>
                </div>

                <a href="{{ route('programacion.masterclass.create', $curso->id_curso) }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-brand-green text-white hover:opacity-90 font-semibold text-sm">
                    <i class="fas fa-plus"></i>
                    Registrar Masterclass
                </a>

            </div>

            @if(session('success'))
                <div class="mt-5 p-4 rounded-lg bg-green-50 text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mt-7 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

                @if($masterclasses->isNotEmpty())

                    <div class="overflow-x-auto">

                        <table class="w-full text-sm text-left">

                            <thead class="bg-brand-green text-white text-xs uppercase">
                                <tr>
                                    <th class="px-5 py-4">N.º</th>
                                    <th class="px-5 py-4 min-w-56">Título Masterclass</th>
                                    <th class="px-5 py-4 whitespace-nowrap">Fecha de inicio</th>
                                    <th class="px-5 py-4">Día</th>
                                    <th class="px-5 py-4 min-w-48">Docente asignado</th>
                                    <th class="px-5 py-4 text-center">Acciones</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">

                                @foreach($masterclasses as $masterclass)

                                    @php
                                        $fecha = \Carbon\Carbon::parse($masterclass->fecha_inicio)->locale('es');
                                    @endphp

                                    <tr class="hover:bg-gray-50 transition">

                                        <td class="px-5 py-4 font-black text-brand-green">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td class="px-5 py-4 font-semibold text-gray-800">
                                            {{ $masterclass->titulo }}
                                        </td>

                                        <td class="px-5 py-4 text-gray-600 whitespace-nowrap">
                                            {{ $fecha->format('d/m/Y') }}
                                        </td>

                                        <td class="px-5 py-4 text-gray-600">
                                            {{ ucfirst($fecha->translatedFormat('l')) }}
                                        </td>

                                        <td class="px-5 py-4 text-gray-600">
                                            @if($masterclass->docente)
                                                {{ $masterclass->docente->nombre }}
                                                {{ $masterclass->docente->apellido_p }}
                                                {{ $masterclass->docente->apellido_m }}
                                            @else
                                                <span class="text-gray-400 italic">
                                                    Sin asignar
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-5 py-4">
                                            <div class="flex items-center justify-center gap-2">

                                                {{-- EDITAR --}}
                                                <a href="{{ route('programacion.masterclass.edit', [$curso->id_curso, $masterclass->id_masterclass]) }}"
                                                    class="inline-flex items-center gap-1 px-3 py-2 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 text-xs font-semibold transition">
                                                    <i class="fas fa-edit"></i>
                                                    Editar
                                                </a>

                                                {{-- ELIMINAR --}}
                                                <form
                                                    action="{{ route('programacion.masterclass.destroy', [$curso->id_curso, $masterclass->id_masterclass]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('¿Estás seguro de eliminar esta Masterclass? Esta acción no se puede deshacer.')">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 px-3 py-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 text-xs font-semibold transition">
                                                        <i class="fas fa-trash"></i>
                                                        Eliminar
                                                    </button>

                                                </form>

                                            </div>
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="p-10 text-center">

                        <div class="text-gray-300 text-4xl mb-3">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>

                        <p class="font-semibold text-gray-700">
                            Todavía no hay Masterclass registradas.
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            Registra una Masterclass para comenzar a organizar el cronograma.
                        </p>

                    </div>

                @endif

            </div>

        </div>
    </div>

</x-layout-dashboard>
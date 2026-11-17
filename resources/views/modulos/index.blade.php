<x-layout-dashboard :usuario="$usuario">
    <div class="max-w-7xl mx-auto space-y-6">

        <x-page-header titulo="Gestión de módulos" />

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-sm text-gray-500">Programa académico</p>
                    <h2 class="text-2xl font-bold text-brand-green">
                        {{ $curso->nombre }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Administra los módulos, docentes y sesiones del diplomado.
                    </p>
                </div>

                <a href="{{ route('programacion.modulos.create', $curso->id_curso) }}"
                    class="inline-flex items-center justify-center px-5 py-3 rounded-lg bg-brand-green text-white font-semibold hover:opacity-90 transition">
                    + Crear módulo
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-lg bg-green-50 text-green-700 border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-lg bg-red-50 text-red-700 border border-red-200">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-brand-green text-lg">Módulos programados</h3>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $modulos->count() }} módulo(s) registrados
                </p>
            </div>

            @forelse($modulos as $modulo)
                    <div class="p-6 border-b border-gray-100 last:border-0">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="flex gap-4">
                                <div
                                    class="w-12 h-12 rounded-xl bg-brand-green/10 text-brand-green flex items-center justify-center font-bold shrink-0">
                                    {{ $modulo->orden ?? $loop->iteration }}
                                </div>

                                <div>
                                    <h4 class="font-semibold text-gray-800 text-lg">
                                        {{ $modulo->nombre }}
                                    </h4>

                                    <p class="text-sm text-gray-500 mt-1">
                                        Docente:
                                        {{ $modulo->docente
                ? $modulo->docente->nombre . ' ' . $modulo->docente->apellido_p
                : 'Sin asignar' }}
                                    </p>

                                    <div class="flex flex-wrap gap-3 mt-2 text-sm text-gray-500">
                                        <span>
                                            {{ $modulo->cantidad_sesiones }} sesiones
                                        </span>

                                        <span>
                                            Inicio:
                                            {{ $modulo->fecha_inicio?->format('d/m/Y') ?? 'Pendiente' }}
                                        </span>

                                        <span>
                                            Finalización:
                                            {{ $modulo->fecha_fin?->format('d/m/Y') ?? 'Pendiente' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('programacion.modulos.edit', [$curso->id_curso, $modulo->id_modulo]) }}"
                                    class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-medium">
                                    Editar
                                </a>

                                @if(auth()->user()->rol === 'super_admin')
                                    <form method="POST"
                                        action="{{ route('programacion.modulos.destroy', [$curso->id_curso, $modulo->id_modulo]) }}"
                                        onsubmit="return confirm('¿Eliminar este módulo y todas sus sesiones?')">
                                        @csrf
                                        @method('DELETE')

                                        <input type="password" name="password_confirm" placeholder="Contraseña" required
                                            class="w-32 px-3 py-2 rounded-lg border border-gray-200 text-sm">

                                        <button type="submit"
                                            class="px-3 py-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 text-sm font-medium">
                                            Eliminar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        @if($modulo->sesiones->isNotEmpty())
                            <div class="mt-5 ml-0 lg:ml-16">
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-3">
                                    Sesiones programadas
                                </p>

                                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                                    @foreach($modulo->sesiones->sortBy('numero_sesion') as $sesion)
                                        <div class="rounded-lg bg-gray-50 border border-gray-100 p-3">
                                            <p class="font-medium text-gray-700 text-sm">
                                                Sesión {{ $sesion->numero_sesion }}
                                                @if($sesion->titulo)
                                                    · {{ $sesion->titulo }}
                                                @endif
                                            </p>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $sesion->fecha->format('d/m/Y') }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                {{ substr($sesion->hora_inicio, 0, 5) }}
                                                –
                                                {{ substr($sesion->hora_fin, 0, 5) }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
            @empty
                <div class="p-12 text-center">
                    <div class="text-4xl mb-3">📚</div>
                    <h3 class="font-semibold text-gray-700">Todavía no hay módulos</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Comienza creando el primer módulo de este diplomado.
                    </p>
                </div>
            @endforelse
        </div>

        <div>
            <a href="{{ route('programacion.show', $curso->id_curso) }}"
                class="text-sm text-brand-green hover:underline">
                ← Volver a la programación académica
            </a>
        </div>

    </div>
</x-layout-dashboard>
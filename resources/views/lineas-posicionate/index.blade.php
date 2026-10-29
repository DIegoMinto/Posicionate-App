@php
    $usuario = $usuario ?? auth()->user();
@endphp

<x-layout-dashboard :usuario="$usuario">
    <div class="mx-auto max-w-[1600px] space-y-6 p-4 sm:p-6">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-brand-green">Líneas y correos Posicionate</h1>
                <p class="mt-1 text-sm text-gray-500">Administración de números, cuentas y recargas.</p>
            </div>

            @if($usuario?->hasRole('super_admin'))
                <a href="{{ route('lineas-posicionate.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-green px-5 py-3 text-sm font-semibold text-white transition hover:opacity-90">
                    <span class="text-xl">+</span> Registrar línea
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->has('password_confirm'))
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                {{ $errors->first('password_confirm') }}
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total de líneas</p>
                <p class="mt-2 text-3xl font-bold text-brand-green">{{ $totalLineas }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Recargas pendientes o vencidas</p>
                <p class="mt-2 text-3xl font-bold text-brand-green">{{ $porRecargar }}</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 p-4">
                <form method="GET" action="{{ route('lineas-posicionate.index') }}"
                    class="flex flex-col gap-3 sm:flex-row">
                    <input type="search" name="buscar" value="{{ $busqueda }}"
                        placeholder="Buscar celular, correo, persona o área..." class="form-input-pill-2 flex-1">

                    <button type="submit"
                        class="rounded-lg bg-brand-green px-5 py-2.5 text-sm font-semibold text-white">
                        Buscar
                    </button>

                    @if($busqueda)
                        <a href="{{ route('lineas-posicionate.index') }}"
                            class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-semibold text-gray-600">
                            Limpiar
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1150px] text-left text-sm">
                    <thead class="bg-brand-green text-white">
                        <tr>
                            <th class="px-4 py-4">Nro.</th>
                            <th class="px-4 py-4">Celular</th>
                            <th class="px-4 py-4">Perteneciente</th>
                            <th class="px-4 py-4">Correo Posicionate</th>
                            <th class="px-4 py-4">Contraseña</th>
                            <th class="px-4 py-4">Última recarga</th>
                            <th class="px-4 py-4">Siguiente recarga</th>
                            <th class="px-4 py-4">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($lineas as $linea)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-4 py-4 font-semibold text-gray-500">
                                    {{ $lineas->firstItem() + $loop->index }}
                                </td>

                                <td class="px-4 py-4 font-bold text-brand-green">
                                    {{ $linea->celular }}
                                </td>

                                <td class="px-4 py-4">{{ $linea->perteneciente }}</td>

                                <td class="px-4 py-4">{{ $linea->correo ?: '—' }}</td>

                                <td class="px-4 py-4">
                                    <span class="text-gray-400">••••••••</span>
                                </td>

                                <td class="px-4 py-4">
                                    {{ $linea->fecha_ultima_recarga?->format('d/m/Y') ?? '—' }}
                                </td>

                                <td class="px-4 py-4">
                                    @if($linea->fecha_siguiente_recarga)
                                        <span
                                            class="{{ $linea->fecha_siguiente_recarga->toDateString() <= now()->toDateString() ? 'font-semibold text-red-600' : 'text-gray-600' }}">
                                            {{ $linea->fecha_siguiente_recarga->format('d/m/Y') }}
                                        </span>
                                    @else
                                        —
                                    @endif
                                </td>

                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-center gap-4">
                                        <a href="{{ route('lineas-posicionate.edit', $linea) }}" title="Editar línea"
                                            class="group relative flex cursor-pointer items-center justify-center pb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-black transition-colors group-hover:text-[#ccb463]"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 7.125L16.875 4.5M19.5 13.5v5.25A2.25 2.25 0 0117.25 21H5.25A2.25 2.25 0 013 18.75V6.75A2.25 2.25 0 015.25 4.5h7.5" />
                                            </svg>
                                            <span
                                                class="absolute -top-8 z-30 scale-0 whitespace-nowrap rounded bg-[#072e2c] px-2 py-1 text-[10px] text-white shadow-lg transition-all group-hover:scale-100">
                                                Editar línea
                                            </span>
                                        </a>

                                        @if($usuario?->hasRole('super_admin'))
                                            <div x-data="{ openDelete: false }">
                                                <button type="button" @click="openDelete = true"
                                                    class="group relative flex cursor-pointer items-center justify-center pb-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 text-black transition-colors group-hover:text-red-600"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>

                                                    <span
                                                        class="absolute -top-8 z-30 scale-0 whitespace-nowrap rounded bg-red-600 px-2 py-1 text-[10px] text-white shadow-lg transition-all group-hover:scale-100">
                                                        Eliminar línea
                                                    </span>
                                                </button>

                                                <div x-show="openDelete" x-cloak @keydown.escape.window="openDelete = false"
                                                    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
                                                    <div class="w-80 rounded-sm border-t-4 border-red-600 bg-white p-6 text-left font-sans shadow-2xl"
                                                        @click.away="openDelete = false">
                                                        <h3 class="mb-2 font-bold uppercase text-red-600">
                                                            Confirmar eliminación
                                                        </h3>

                                                        <p class="mb-4 text-[10px] text-gray-600">
                                                            Vas a eliminar la línea:<br>
                                                            <span class="font-bold uppercase text-black">
                                                                {{ $linea->celular }}
                                                            </span>
                                                            <br>
                                                            Perteneciente a:
                                                            <span class="font-bold uppercase text-black">
                                                                {{ $linea->perteneciente }}
                                                            </span>
                                                        </p>

                                                        <form action="{{ route('lineas-posicionate.destroy', $linea) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')

                                                            <label for="password_confirm_{{ $linea->id }}"
                                                                class="mb-1 block text-[10px] font-bold uppercase text-gray-600">
                                                                Contraseña de administrador
                                                            </label>

                                                            <input id="password_confirm_{{ $linea->id }}" type="password"
                                                                name="password_confirm" required autocomplete="current-password"
                                                                class="mb-4 w-full border border-gray-200 bg-gray-50 p-2 text-xs focus:border-red-500 focus:outline-none"
                                                                placeholder="Ingresa tu contraseña">

                                                            <div class="flex justify-end gap-3">
                                                                <button type="button" @click="openDelete = false"
                                                                    class="cursor-pointer text-[9px] uppercase text-gray-500 transition-colors hover:text-red-600">
                                                                    Cancelar
                                                                </button>

                                                                <button type="submit"
                                                                    class="cursor-pointer rounded-sm bg-red-600 px-4 py-2 text-[9px] uppercase text-white transition-colors hover:bg-red-700">
                                                                    Eliminar
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    No hay líneas registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-100 p-4">
                {{ $lineas->links() }}
            </div>
        </div>
    </div>
</x-layout-dashboard>
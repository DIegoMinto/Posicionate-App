<x-layout-dashboard :usuario="$usuario ?? auth()->user()">
    <x-page-header titulo="Editar línea Posicionate" />

    <div class="mx-auto max-w-4xl px-4 py-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-[#072e2c]">Editar línea telefónica</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Modifica la información de la línea seleccionada.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <p class="mb-2 font-semibold">Revisa los siguientes campos:</p>
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('lineas-posicionate.update', $linea) }}" method="POST">
                @csrf
                @method('PUT')

                @include('lineas-posicionate._form')

                <div class="mt-8 flex flex-wrap justify-end gap-3 border-t border-gray-100 pt-5">
                    <a href="{{ route('lineas-posicionate.index') }}"
                        class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="rounded-xl bg-[#072e2c] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#104642]">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout-dashboard>
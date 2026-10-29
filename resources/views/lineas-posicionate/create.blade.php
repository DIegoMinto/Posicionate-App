<x-layout-dashboard :usuario="$usuario ?? auth()->user()">
    <x-page-header titulo="Registrar línea Posicionate" />

    <div class="mx-auto max-w-4xl px-4 py-6">
        <div class="border-t-4 border-[#ccb463] rounded-sm bg-white p-6 shadow-sm">
            <div class="mb-6 border-b border-gray-100 pb-4">
                <h2 class="text-lg font-bold uppercase tracking-wide text-[#072e2c]">
                    Nueva línea telefónica
                </h2>
                <p class="mt-1 text-xs text-gray-500">
                    Registra el número, el correo institucional y la persona o área responsable.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-5 border-l-4 border-red-600 bg-red-50 p-4 text-sm text-red-700">
                    <p class="mb-2 font-bold">No se pudo registrar la línea.</p>
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('lineas-posicionate.store') }}" method="POST">
                @csrf

                @include('lineas-posicionate._form')

                <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <a href="{{ route('lineas-posicionate.index') }}"
                        class="px-4 py-2 text-xs font-bold uppercase text-gray-500 transition hover:text-red-600">
                        Cancelar
                    </a>

                    <button type="submit"
                        class="rounded-sm bg-[#072e2c] px-5 py-2 text-xs font-bold uppercase text-white transition hover:bg-[#104642]">
                        Registrar línea
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout-dashboard>
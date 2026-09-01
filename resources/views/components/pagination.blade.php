@props(['paginator'])

@if ($paginator->hasPages())
    <div class="mt-6 flex justify-center">
        <nav class="flex items-center gap-1 font-sans text-[11px]">

            {{-- Botón Anterior --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1.5 rounded-md text-gray-300 border border-gray-200 cursor-not-allowed">
                    Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="px-3 py-1.5 rounded-md border border-brand-green text-brand-green font-bold hover:bg-brand-green hover:text-white transition-colors">
                    Anterior
                </a>
            @endif

            {{-- Cálculo de Rangos --}}
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $window = 1;
            @endphp

            {{-- Primera Página --}}
            @if ($currentPage > $window + 1)
                <a href="{{ $paginator->url(1) }}"
                    class="px-3 py-1.5 rounded-md border border-brand-green text-brand-green font-bold hover:bg-brand-green hover:text-white transition-colors">
                    1
                </a>
                @if ($currentPage > $window + 2)
                    <span class="px-2 py-1.5 text-gray-400 font-bold">...</span>
                @endif
            @endif

            {{-- Páginas intermedias --}}
            @for ($i = max(1, $currentPage - $window); $i <= min($lastPage, $currentPage + $window); $i++)
                @if ($i == $currentPage)
                    <span class="px-3 py-1.5 rounded-md bg-brand-green text-white font-bold">
                        {{ $i }}
                    </span>
                @else
                    <a href="{{ $paginator->url($i) }}"
                        class="px-3 py-1.5 rounded-md border border-brand-green text-brand-green font-bold hover:bg-brand-green hover:text-white transition-colors">
                        {{ $i }}
                    </a>
                @endif
            @endfor

            {{-- Última Página --}}
            @if ($currentPage < $lastPage - $window)
                @if ($currentPage < $lastPage - $window - 1)
                    <span class="px-2 py-1.5 text-gray-400 font-bold">...</span>
                @endif
                <a href="{{ $paginator->url($lastPage) }}"
                    class="px-3 py-1.5 rounded-md border border-brand-green text-brand-green font-bold hover:bg-brand-green hover:text-white transition-colors">
                    {{ $lastPage }}
                </a>
            @endif

            {{-- Botón Siguiente --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="px-3 py-1.5 rounded-md border border-brand-green text-brand-green font-bold hover:bg-brand-green hover:text-white transition-colors">
                    Siguiente
                </a>
            @else
                <span class="px-3 py-1.5 rounded-md text-gray-300 border border-gray-200 cursor-not-allowed">
                    Siguiente
                </span>
            @endif

        </nav>
    </div>
@endif
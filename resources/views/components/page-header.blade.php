@props(['titulo'])

<div class="bg-brand-gold w-full py-1.5 px-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-2">

    <div class="flex flex-wrap items-center gap-2">
        {{ $slot }}
    </div>

    <div class="flex flex-wrap items-center gap-3">

        <div class="flex items-center gap-2 uppercase">
            <span class="text-brand-green font-black text-lg tracking-wider">
                {{ $titulo }}
            </span>
        </div>

        @if(isset($search))
            {{ $search }}
        @endif
    </div>
</div>
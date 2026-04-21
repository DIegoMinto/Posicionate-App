@props(['titulo'])

<div class="bg-brand-gold w-full py-3 px-6 shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4">

    <div class="flex flex-wrap items-center gap-4">
        {{ $slot }}
    </div>

    <div class="flex flex-wrap items-center gap-4">

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
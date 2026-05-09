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

        <x-page-header titulo="Bienvenido a Posicionate App">
        </x-page-header>
        <div class="flex flex-col items-center p-6">

            <img src="{{ asset('img/ranking_general.PNG') }}" alt="Ranking General" class="w-200 object-contain">

            <div class="flex flex-col md:flex-row items-end justify-center gap-8">

                @if(isset($rankingGeneral[1]))
                    @php $segundo = $rankingGeneral[1]; @endphp

                    <div class="flex flex-col items-center">

                        <div class="relative w-[220px]">

                            <div
                                class="w-[220px] h-[220px] rounded-full border-[6px] border-[#BFC0C2] overflow-hidden bg-white relative">

                                <img src="{{ asset('storage/' . $segundo->persona->fotografia) }}"
                                    class="w-full h-full object-cover" alt="Foto">



                            </div>

                            <img src="{{ asset('img/segundo_lugar.png') }}" class="absolute top-0 right-0 w-20"
                                alt="2do Lugar">

                        </div>
                        <div class="mt-2 text-center">

                            <p class="text-4xl font-black text-brand-green leading-none">
                                {{ $segundo->curso_estudiantes_count }}
                            </p>

                            <p class="text-xs uppercase tracking-[4px] text-[#072E2C] font-bold mt-1">
                                Cierres
                            </p>

                        </div>

                    </div>
                @endif
                @if(isset($rankingGeneral[0]))
                    @php $primero = $rankingGeneral[0]; @endphp

                    <div class="flex flex-col items-center">

                        <div class="relative w-[250px] mb-6">

                            <div
                                class="w-[250px] h-[250px] rounded-full border-[6px] border-[#CCB463] overflow-hidden bg-white">

                                <img src="{{ asset('storage/' . $primero->persona->fotografia) }}"
                                    class="w-full h-full object-cover">

                            </div>

                            <img src="{{ asset('img/primer_lugar.png') }}" class="absolute top-0 right-0 w-24">

                        </div>
                        <div class="mt-2 text-center">

                            <p class="text-4xl font-black text-brand-green leading-none">
                                {{ $primero->curso_estudiantes_count }}
                            </p>

                            <p class="text-xs uppercase tracking-[4px] text-[#072E2C] font-bold mt-1">
                                Cierres
                            </p>

                        </div>

                    </div>
                @endif

                @if(isset($rankingGeneral[2]))
                    @php $tercero = $rankingGeneral[2]; @endphp

                    <div class="flex flex-col items-center">

                        <div class="relative w-[220px]">

                            <div
                                class="w-[220px] h-[220px] rounded-full border-[6px] border-[#CD7F32] overflow-hidden bg-white relative">

                                <img src="{{ asset('storage/' . $tercero->persona->fotografia) }}"
                                    class="w-full h-full object-cover" alt="Foto">

                            </div>

                            <img src="{{ asset('img/tercer_lugar.png') }}" class="absolute top-0 right-0 w-20"
                                alt="3er Lugar">

                        </div>

                        {{-- CIERRES --}}
                        <div class="mt-2 text-center">

                            <p class="text-4xl font-black text-brand-green leading-none">
                                {{ $tercero->curso_estudiantes_count }}
                            </p>

                            <p class="text-xs uppercase tracking-[4px] text-[#072E2C] font-bold mt-1">
                                Cierres
                            </p>

                        </div>

                    </div>
                @endif

            </div>
            @if(in_array($usuario->rol, ['super_admin', 'admin']))
                <div class="mt-10">
                    <a href="{{ route('statitics.index') }}" class="">
                        <button class="btn-gold">VER RANKING</button>

                    </a>
                </div>
            @endif

        </div>

    </x-layout-dashboard>
</body>

</html>
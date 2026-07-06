<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Posicionate</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800 antialiased">
    <x-layout-dashboard :usuario="$usuario">

        <div
            class="absolute inset-0 opacity-10 bg-[radial-gradient(#CCB463_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none">
        </div>
        <div class="relative z-10">
            <x-page-header titulo="Bienvenido a Posicionate App" class="text-white">
            </x-page-header>
        </div>

        <div class="space-y-12">

            <section class="bg-white rounded-2xl p-6 md:p-10 shadow-sm border border-gray-100">
                <div class="flex flex-col items-center mb-8">
                    <h2 class="text-2xl md:text-3xl font-black text-brand-green text-center uppercase">Ranking General
                    </h2>
                    <div class="h-1 w-24 bg-[#CCB463] mt-3 rounded-full"></div>
                </div>

                <div
                    class="flex flex-col md:flex-row items-center md:items-end justify-center gap-8 md:gap-4 lg:gap-8 min-h-[380px]">

                    @if(isset($rankingGeneral[1]))
                        @php $segundo = $rankingGeneral[1]; @endphp
                        <div
                            class="flex flex-col items-center order-2 md:order-1 transition-all duration-300 hover:-translate-y-2">
                            <div class="relative w-[190px] lg:w-[210px]">
                                <div
                                    class="w-[190px] h-[190px] lg:w-[210px] lg:h-[210px] rounded-full border-[6px] border-[#BFC0C2] overflow-hidden bg-white shadow-lg relative">
                                    <img src="{{ $segundo->persona->fotografia }}" class="w-full h-full object-cover"
                                        alt="Foto Segundo Lugar">
                                </div>
                                <img src="{{ asset('img/segundo_lugar.PNG') }}"
                                    class="absolute -top-3 -right-3 w-16 filter drop-shadow-md" alt="2do Lugar">
                            </div>
                            <div class="mt-4 text-center">
                                <h4 class="text-lg font-bold text-gray-700 leading-tight">
                                    {{ $segundo->persona->nombre ?? 'Usuario' }}
                                </h4>
                                <p class="text-3xl font-black text-brand-green mt-1">
                                    {{ $segundo->total_puntaje }}@if($segundo->exponente_cursos > 0)<sup
                                    class="text-sm text-brand-green font-bold">{{ $segundo->exponente_cursos }}</sup>@endif
                                    <span class="text-xs font-semibold text-gray-400">pts</span>
                                </p>
                            </div>
                        </div>
                    @endif

                    @if(isset($rankingGeneral[0]))
                        @php $primero = $rankingGeneral[0]; @endphp
                        <div
                            class="flex flex-col items-center order-1 md:order-2 transform scale-105 md:scale-110 z-10 transition-all duration-300 hover:-translate-y-2">
                            <div class="relative w-[220px] lg:w-[240px] mb-4 md:mb-6">
                                <div
                                    class="w-[220px] h-[220px] lg:w-[240px] lg:h-[240px] rounded-full border-[6px] border-[#CCB463] overflow-hidden bg-white shadow-2xl relative">
                                    <img src="{{ $primero->persona->fotografia }}" class="w-full h-full object-cover"
                                        alt="Foto Primer Lugar">
                                </div>
                                <img src="{{ asset('img/primer_lugar.png') }}"
                                    class="absolute -top-5 -right-5 w-20 filter drop-shadow-xl" alt="1er Lugar">
                            </div>
                            <div class="mt-2 text-center">
                                <h3 class="text-xl font-black text-brand-green leading-tight">
                                    {{ $primero->persona->nombre ?? 'Líder Actual' }}
                                </h3>
                                <p class="text-4xl font-black text-[#CCB463] mt-1">
                                    {{ $primero->total_puntaje }}@if($primero->exponente_cursos > 0)<sup
                                    class="text-sm text-brand-gold font-bold">{{ $primero->exponente_cursos }}</sup>@endif
                                    <span class="text-sm font-semibold text-gray-400">pts</span>
                                </p>
                            </div>
                        </div>
                    @endif

                    @if(isset($rankingGeneral[2]))
                        @php $tercero = $rankingGeneral[2]; @endphp
                        <div class="flex flex-col items-center order-3 transition-all duration-300 hover:-translate-y-2">
                            <div class="relative w-[190px] lg:w-[210px]">
                                <div
                                    class="w-[190px] h-[190px] lg:w-[210px] lg:h-[210px] rounded-full border-[6px] border-[#CD7F32] overflow-hidden bg-white shadow-lg relative">
                                    <img src="{{ $tercero->persona->fotografia }}" class="w-full h-full object-cover"
                                        alt="Foto Tercer Lugar">
                                </div>
                                <img src="{{ asset('img/tercer_lugar.png') }}"
                                    class="absolute -top-3 -right-3 w-16 filter drop-shadow-md" alt="3er Lugar">
                            </div>
                            <div class="mt-4 text-center">
                                <h4 class="text-lg font-bold text-gray-700 leading-tight">
                                    {{ $tercero->persona->nombre ?? 'Usuario' }}
                                </h4>
                                <p class="text-3xl font-black text-brand-green mt-1">
                                    {{ $tercero->total_puntaje }}@if($tercero->exponente_cursos > 0)<sup
                                    class="text-sm text-brand-green font-bold">{{ $tercero->exponente_cursos }}</sup>@endif
                                    <span class="text-xs font-semibold text-gray-400">pts</span>
                                </p>
                            </div>
                        </div>
                    @endif

                </div>

                @if(in_array($usuario->rol, ['super_admin', 'admin']))
                    <div class="mt-12 flex justify-center">
                        <a href="{{ route('statitics.index') }}"
                            class="inline-block transform hover:scale-105 transition-transform duration-200">
                            <button class="btn-gold">
                                VER RANKING COMPLETO
                            </button>
                        </a>
                    </div>
                @endif
            </section>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">

                <div
                    class="lg:col-span-2 bg-gradient-to-br from-brand-green to-emerald-900 text-white rounded-2xl p-6 md:p-8 shadow-md relative overflow-hidden flex flex-col justify-between">
                    <div
                        class="absolute right-0 bottom-0 translate-x-10 translate-y-10 w-64 h-64 bg-emerald-800/20 rounded-full blur-2xl">
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2 w-12">
                                <img src="img/menu_icon.png" alt="">
                            </div>
                            <h3 class="text-xl font-bold font-sans tracking-tight uppercase">Sobre Posicionate</h3>
                        </div>
                        <p class="text-white text-sm md:text-base leading-relaxed mb-6" font-sans>
                            POSICIONATE LA PLATA | Centro de Formación Continua
                            En POSICIONATE creemos en el aprendizaje constante como motor de crecimiento personal y
                            profesional.
                            Somos un centro de formación continua enfocado a desarrollar habilidades prácticas.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2 bg-amber-50 rounded-lg text-[#CCB463]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-brand-green">Ubicación Central</h3>
                        </div>

                        <div class="space-y-4 text-sm text-gray-600">
                            <div class="text-black">
                                <p class="font-semibold ">Dirección Física:</p>
                                <p class="mt-0.5 ">Calle René Calvo Arana #87</p>
                                <p class="text-xs ">Sucre, Bolivia</p>
                            </div>
                            <hr class="border-gray-100">
                            <div class="text-black">
                                <p class="font-semibold 0">Canales de Atención:</p>
                                <p class="mt-0.5">academicoposicionate@gmail.com</p>
                                <p>+591 60300960</p>
                            </div>
                        </div>
                    </div>
                </div>

            </section>

        </div>

    </x-layout-dashboard>
</body>

</html>
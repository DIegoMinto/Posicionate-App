<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>{{ $title ?? 'Posicionate' }}</title>

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @props(['usuario'])

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>


<div class="flex h-screen bg-white font-sans overflow-hidden">
    <aside class="w-20 lg:w-64 bg-brand-green flex flex-col border-r-4 border-brand-gold h-full flex-shrink-0 z-20">
        <div class="p-8 flex-shrink-0">
            <img src="/img/logoblancomenu.png" class="w-50" alt="Logo">
        </div>

        <div class="flex-1 overflow-y-auto no-scrollbar">
            <div class="border-2 border-brand-gold m-4 rounded-sm">
                <nav class="mt-4 flex flex-col gap-2">
                    <a href="{{ route('dashboard') }}"
                        class="btn-sidebar group {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                        <div class="btn-sidebar-icon">
                            <img src="/img/homeicon.jpg" class="w-20 object-contain" alt="">
                        </div>
                        <span class="btn-sidebar-text uppercase">PRINCIPAL</span>
                    </a>

                    <div x-data="{ open: {{ request()->routeIs('people.*', 'teachers.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="btn-sidebar group w-full text-left cursor-pointer {{ request()->routeIs('people.*') ? 'is-active' : '' }}">
                            <div class="btn-sidebar-icon">
                                <img src="/img/peopleicon.jpg" class="w-20 object-contain" alt="">
                            </div>
                            <span class="btn-sidebar-text uppercase flex justify-between items-center w-full">
                                PERSONAS
                                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </span>
                        </button>

                        <div x-show="open" x-collapse.duration.300ms class="mt-1 ml-8 flex flex-col gap-1">
                            @if(in_array($usuario->rol, ['super_admin', 'admin']))
                                <a href="{{ route('people.staff') }}"
                                    class="btn-sidebar-sub group {{ request()->routeIs('people.staff') ? 'is-active' : '' }}">
                                    <span class="btn-sidebar-text uppercase text-sm">PERSONAL</span>
                                </a>
                            @endif
                            <a href="{{ route('people.index') }}"
                                class="btn-sidebar-sub group {{ request()->routeIs('people.index') ? 'is-active' : '' }}">
                                <span class="btn-sidebar-text uppercase text-sm">ESTUDIANTES</span>
                            </a>
                            @if(
                                    in_array($usuario->cargo, [
                                        'asistente_academico',
                                        'supervisor_academico',
                                        'coordinador_academico',
                                    ])
                                    ||
                                    in_array($usuario->rol, ['admin', 'super_admin'])
                                )
                                <a href="{{ route('teachers.index') }}"
                                    class="btn-sidebar-sub group {{ request()->routeIs('teachers.index') ? 'is-active' : '' }}">
                                    <span class="btn-sidebar-text uppercase text-sm">DOCENTES</span>
                                </a>
                            @endif

                        </div>
                    </div>

                    <a href="{{ route('programs.index') }}"
                        class="btn-sidebar group {{ request()->routeIs('programs.index') ? 'is-active' : '' }}">
                        <div class="btn-sidebar-icon">
                            <img src="/img/degreeicon.jpg" class="w-20 object-contain" alt="">
                        </div>
                        <span class="btn-sidebar-text leading-tight uppercase">PROGRAMAS</span>
                    </a>

                    <a href="{{ route('wpsender.index') }}"
                        class="btn-sidebar group {{ request()->routeIs('wpsender.index') ? 'is-active' : '' }}">
                        <div class="btn-sidebar-icon">
                            <img src="/img/automatizationicon.png" class="w-20 object-contain" alt="">
                        </div>
                        <span class="btn-sidebar-text leading-tight uppercase">WP SENDER</span>
                    </a>

                    @if(in_array($usuario->rol, ['super_admin']))
                        <a href="{{ route('creations.index') }}"
                            class="btn-sidebar group {{ request()->routeIs('creations.index') ? 'is-active' : '' }}">
                            <div class="btn-sidebar-icon">
                                <img src="/img/add_icon.png" class="w-20 object-contain" alt="">
                            </div>
                            <span class="btn-sidebar-text leading-tight uppercase">GESTIÓN DE DATOS</span>
                        </a>
                    @endif


                    @if(in_array($usuario->rol, ['super_admin', 'admin']))
                        <a href="{{ route('statitics.index') }}"
                            class="btn-sidebar group {{ request()->routeIs('statitics.index') ? 'is-active' : '' }}">
                            <div class="btn-sidebar-icon">
                                <img src="/img/statitics_icon.png" class="w-20 object-contain" alt="">
                            </div>
                            <span class="btn-sidebar-text leading-tight uppercase">CONTEO</span>
                        </a>
                    @endif

                </nav>
            </div>
        </div>

        <div class="p-8 flex-shrink-0">
            <form action="" method="POST">
                @csrf
                <button type="submit"
                    class="w-full border-2 border-white text-white font-bold py-2 px-4 rounded-xl hover:bg-red-700 hover:border-red-700 transition-all text-xs uppercase tracking-tighter">
                    CERRAR SESIÓN
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden bg-gray-50">
        <div class="bg-brand-green flex-shrink-0 z-10">
            <header
                class="bg-brand-green p-4 flex justify-between items-center shadow-lg border-2 border-brand-gold m-4 rounded-sm">
                <div class="flex items-center gap-4">
                    <div
                        class="w-16 h-16 rounded-full border-2 border-brand-gold overflow-hidden bg-gray-200 shadow-md">
                        @if($usuario->persona && $usuario->persona->fotografia)
                            <img src="{{ $usuario->persona->fotografia }}" class="w-full h-full object-cover" alt="Perfil">
                        @else
                            <div class="flex items-center justify-center h-full text-brand-green font-bold bg-gray-300">
                                {{ substr($usuario->persona->nombre, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <h2
                            class="text-base lg:text-xl font-sans font-light truncate max-w-[150px] lg:max-w-none text-white">
                            {{ $usuario->persona->nombre }} {{ $usuario->persona->apellido_p }}
                            {{ $usuario->persona->apellido_m }}
                        </h2>
                        <p class="text-brand-gold font-black text-2xl leading-none mt-1 tracking-wider">
                            {{ $usuario->cargo_nombre }}

                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-white text-s font-sans font-light">{{$usuario->codigo_personal}}</div>
                    <button class="btn-gold">Ver mi perfil</button>
                </div>
            </header>
        </div>

        <div class="flex-1 overflow-auto">
            {{ $slot }}
        </div>
    </main>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
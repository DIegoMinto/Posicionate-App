@props(['usuario'])

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>{{ $title ?? 'Posicionate' }}</title>

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

<div x-data="{
        sidebarOpen: localStorage.getItem('sidebar') !== 'false',
        personasOpen: {{ request()->routeIs('people.*', 'teachers.*') ? 'true' : 'false' }},
        toggle() { this.sidebarOpen = !this.sidebarOpen; localStorage.setItem('sidebar', this.sidebarOpen); }
    }" class="flex h-screen bg-white font-sans overflow-hidden">

    <aside :class="sidebarOpen ? 'w-64' : 'w-16'"
        class="bg-brand-green flex flex-col border-r-4 border-brand-gold h-full flex-shrink-0 z-20 transition-all duration-300 overflow-hidden">

        <div @click="toggle()" class="p-4 flex-shrink-0 flex items-center justify-center cursor-pointer select-none"
            title="Colapsar / expandir menú">
            <img src="/img/menu_icon.png" class="w-16 object-contain transition-all duration-300" alt="Logo">
        </div>
        <div class="flex-1 overflow-y-auto overflow-x-hidden no-scrollbar">
            <div class="border-2 border-brand-gold m-2 rounded-sm">
                <nav class="mt-4 flex flex-col gap-1 pb-2">

                    {{-- ============================================================
                         PRINCIPAL — casa
                    ============================================================ --}}
                    @php $activeDashboard = request()->routeIs('dashboard'); @endphp
                    <a href="{{ route('dashboard') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                        class="btn-sidebar group {{ $activeDashboard ? 'is-active' : '' }}"
                        :title="!sidebarOpen ? 'Principal' : ''">
                        <div class="btn-sidebar-icon flex-shrink-0">
                            <svg class="w-6 h-6 transition-colors duration-200 {{ $activeDashboard ? 'text-brand-gold' : 'text-white group-hover:text-brand-gold' }}"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 11.5L12 4l9 7.5" />
                                <path d="M5.5 9.5V19a1 1 0 001 1H9v-5.5h6V20h2.5a1 1 0 001-1V9.5" />
                            </svg>
                        </div>
                        <span x-show="sidebarOpen" x-collapse.duration.200ms
                            class="btn-sidebar-text uppercase whitespace-nowrap">PRINCIPAL</span>
                    </a>

                    {{-- ============================================================
                         PERSONAS — dos personas
                    ============================================================ --}}
                    @php $activePersonas = request()->routeIs('people.*', 'teachers.*'); @endphp
                    <div>
                        <button @click="sidebarOpen ? personasOpen = !personasOpen : (toggle(), personasOpen = true)"
                            :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                            class="btn-sidebar group w-full text-left cursor-pointer {{ $activePersonas ? 'is-active' : '' }}"
                            :title="!sidebarOpen ? 'Personas' : ''">
                            <div class="btn-sidebar-icon flex-shrink-0">
                                <svg class="w-6 h-6 transition-colors duration-200 {{ $activePersonas ? 'text-brand-gold' : 'text-white group-hover:text-brand-gold' }}"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="9" cy="8" r="3" />
                                    <path d="M3.5 20c0-3.3 2.5-6 5.5-6s5.5 2.7 5.5 6" />
                                    <circle cx="17" cy="9.5" r="2.2" />
                                    <path d="M15.2 14.5c2.3.4 4.3 2.4 4.3 5.5" />
                                </svg>
                            </div>
                            <span x-show="sidebarOpen" x-collapse.duration.200ms
                                class="btn-sidebar-text uppercase flex justify-between items-center w-full whitespace-nowrap">
                                PERSONAS
                                <svg class="w-4 h-4 transition-transform flex-shrink-0 ml-1"
                                    :class="{ 'rotate-180': personasOpen }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </button>

                        <div x-show="sidebarOpen && personasOpen" x-collapse.duration.250ms
                            class="ml-6 flex flex-col gap-1 mt-1">
                            @if(in_array($usuario->rol, ['super_admin', 'admin']))
                                <a href="{{ route('people.staff') }}"
                                    class="btn-sidebar-sub group {{ request()->routeIs('people.staff') ? 'is-active' : '' }}">
                                    <span class="btn-sidebar-text uppercase text-sm whitespace-nowrap">PERSONAL</span>
                                </a>
                            @endif

                            <a href="{{ route('people.index') }}"
                                class="btn-sidebar-sub group {{ request()->routeIs('people.index') ? 'is-active' : '' }}">
                                <span class="btn-sidebar-text uppercase text-sm whitespace-nowrap">ESTUDIANTES</span>
                            </a>

                            @if(
                                    in_array($usuario->cargo, ['asistente_academico', 'supervisor_academico', 'coordinador_academico'])
                                    || in_array($usuario->rol, ['admin', 'super_admin'])
                                )
                                <a href="{{ route('teachers.index') }}"
                                    class="btn-sidebar-sub group {{ request()->routeIs('teachers.index') ? 'is-active' : '' }}">
                                    <span class="btn-sidebar-text uppercase text-sm whitespace-nowrap">DOCENTES</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- ============================================================
                         PROGRAMAS — birrete de graduación
                    ============================================================ --}}
                    @php $activeProgramas = request()->routeIs('programs.index'); @endphp
                    <a href="{{ route('programs.index') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                        class="btn-sidebar group {{ $activeProgramas ? 'is-active' : '' }}"
                        :title="!sidebarOpen ? 'Programas' : ''">
                        <div class="btn-sidebar-icon flex-shrink-0">
                            <svg class="w-6 h-6 transition-colors duration-200 {{ $activeProgramas ? 'text-brand-gold' : 'text-white group-hover:text-brand-gold' }}"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 3L2 8l10 5 10-5-10-5z" />
                                <path d="M6 10.5V16c0 1.5 2.7 3 6 3s6-1.5 6-3v-5.5" />
                                <path d="M22 8v6" />
                            </svg>
                        </div>
                        <span x-show="sidebarOpen" x-collapse.duration.200ms
                            class="btn-sidebar-text leading-tight uppercase whitespace-nowrap">PROGRAMAS</span>
                    </a>

                    {{-- ============================================================
                         WP SENDER — robotcito de automatización
                         (antena + carita + "brazos" a los costados)
                    ============================================================ --}}
                    @php $activeWpSender = request()->routeIs('wpsender.index'); @endphp
                    <a href="{{ route('wpsender.index') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                        class="btn-sidebar group {{ $activeWpSender ? 'is-active' : '' }}"
                        :title="!sidebarOpen ? 'WP Sender' : ''">
                        <div class="btn-sidebar-icon flex-shrink-0">
                            <svg class="w-6 h-6 transition-colors duration-200 {{ $activeWpSender ? 'text-brand-gold' : 'text-white group-hover:text-brand-gold' }}"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2.5v3" />
                                <circle cx="12" cy="2" r="1" fill="currentColor" stroke="none" />
                                <rect x="4" y="7.5" width="16" height="11" rx="3" />
                                <circle cx="9" cy="12.5" r="1.1" fill="currentColor" stroke="none" />
                                <circle cx="15" cy="12.5" r="1.1" fill="currentColor" stroke="none" />
                                <path d="M9 16h6" />
                                <path d="M2 11.5v4M22 11.5v4" />
                            </svg>
                        </div>
                        <span x-show="sidebarOpen" x-collapse.duration.200ms
                            class="btn-sidebar-text leading-tight uppercase whitespace-nowrap">WP SENDER</span>
                    </a>

                    {{-- ============================================================
                         GESTIÓN DE DATOS — base de datos
                    ============================================================ --}}
                    @if(in_array($usuario->rol, ['super_admin']))
                        @php $activeCreations = request()->routeIs('creations.index'); @endphp
                        <a href="{{ route('creations.index') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                            class="btn-sidebar group {{ $activeCreations ? 'is-active' : '' }}"
                            :title="!sidebarOpen ? 'Gestión de datos' : ''">
                            <div class="btn-sidebar-icon flex-shrink-0">
                                <svg class="w-6 h-6 transition-colors duration-200 {{ $activeCreations ? 'text-brand-gold' : 'text-white group-hover:text-brand-gold' }}"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <ellipse cx="12" cy="6" rx="8" ry="3" />
                                    <path d="M4 6v6c0 1.7 3.6 3 8 3s8-1.3 8-3V6" />
                                    <path d="M4 12v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6" />
                                </svg>
                            </div>
                            <span x-show="sidebarOpen" x-collapse.duration.200ms
                                class="btn-sidebar-text leading-tight uppercase whitespace-nowrap">GESTIÓN DE <br>
                                DATOS</span>
                        </a>
                    @endif

                    {{-- ============================================================
                         CONTEO / ESTADÍSTICAS — barras
                    ============================================================ --}}
                    @if(in_array($usuario->rol, ['super_admin', 'admin']))
                        @php $activeStats = request()->routeIs('statitics.index'); @endphp
                        <a href="{{ route('statitics.index') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                            class="btn-sidebar group {{ $activeStats ? 'is-active' : '' }}"
                            :title="!sidebarOpen ? 'Conteo' : ''">
                            <div class="btn-sidebar-icon flex-shrink-0">
                                <svg class="w-6 h-6 transition-colors duration-200 {{ $activeStats ? 'text-brand-gold' : 'text-white group-hover:text-brand-gold' }}"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 20V13M10 20V7M16 20v-4" />
                                    <path d="M2 20h20" />
                                </svg>
                            </div>
                            <span x-show="sidebarOpen" x-collapse.duration.200ms
                                class="btn-sidebar-text leading-tight uppercase whitespace-nowrap">CONTEO</span>
                        </a>
                    @endif
                </nav>
            </div>
        </div>

        <div class="p-4 flex justify-center flex-shrink-0">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" :title="!sidebarOpen ? 'Cerrar sesión' : ''"
                    class="btn-gold flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span x-show="sidebarOpen" x-collapse.duration.200ms class="whitespace-nowrap">
                        CERRAR SESIÓN
                    </span>
                </button>
            </form>
        </div>

    </aside>
    <main class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden bg-gray-50">

        {{-- HEADER --}}
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
                    <div class="text-white text-s font-sans font-light">{{ $usuario->codigo_personal }}</div>
                    <a href="{{ route('users.show', $usuario->id_personal) }}"
                        class="btn-gold inline-flex items-center justify-center">
                        Ver mi perfil
                    </a>
                </div>
            </header>
        </div>
        <div class="flex-1 overflow-auto">
            {{ $slot }}
        </div>

    </main>
</div>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inicio - Posicionate</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core/locales/es.global.min.js"></script>
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

                    <h2 class="text-2xl md:text-3xl font-black text-brand-green text-center uppercase">
                        Ranking Mensual - {{ strtoupper(\Carbon\Carbon::now()->locale('es')->monthName) }}
                    </h2>

                    <div class="h-1 w-24 bg-brand-gold mt-3 rounded-full"></div>

                </div>

                <div
                    class="flex flex-col md:flex-row items-center md:items-end justify-center gap-8 md:gap-4 lg:gap-8 min-h-[380px]">

                    @if(isset($rankingMensual[1]))

                        @php
                            $segundo = $rankingMensual[1];
                        @endphp

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

                                <h4 class="text-lg font-bold text-brand-green leading-tight">
                                    {{ $segundo->persona->nombre ?? 'Usuario' }}
                                </h4>

                                <p class="text-3xl font-black text-brand-green mt-1">

                                    {{ $segundo->total_puntaje }}

                                    @if($segundo->exponente_cursos > 0)

                                        <sup class="text-sm text-brand-green font-bold">
                                            {{ $segundo->exponente_cursos }}
                                        </sup>

                                    @endif

                                    <span class="text-xs font-semibold text-brand-green">
                                        pts
                                    </span>

                                </p>

                            </div>

                        </div>

                    @endif

                    @if(isset($rankingMensual[0]))

                        @php
                            $primero = $rankingMensual[0];
                        @endphp

                        <div
                            class="flex flex-col items-center order-1 md:order-2 transform scale-105 md:scale-110 z-10 transition-all duration-300 hover:-translate-y-2">

                            <div class="relative w-[220px] lg:w-[240px] mb-4 md:mb-6">

                                <div
                                    class="w-[220px] h-[220px] lg:w-[240px] lg:h-[240px] rounded-full border-[6px] border-brand-gold overflow-hidden bg-white shadow-2xl relative">

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

                                <p class="text-4xl font-black text-brand-green mt-1">

                                    {{ $primero->total_puntaje }}

                                    @if($primero->exponente_cursos > 0)

                                        <sup class="text-sm text-brand-green font-bold">
                                            {{ $primero->exponente_cursos }}
                                        </sup>

                                    @endif

                                    <span class="text-sm font-semibold text-brand-green">
                                        pts
                                    </span>

                                </p>

                            </div>

                        </div>

                    @endif

                    @if(isset($rankingMensual[2]))

                        @php
                            $tercero = $rankingMensual[2];
                        @endphp

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

                                <h4 class="text-lg font-bold text-brand-green leading-tight">
                                    {{ $tercero->persona->nombre ?? 'Usuario' }}
                                </h4>

                                <p class="text-3xl font-black text-brand-green mt-1">

                                    {{ $tercero->total_puntaje }}

                                    @if($tercero->exponente_cursos > 0)

                                        <sup class="text-sm text-brand-green font-bold">
                                            {{ $tercero->exponente_cursos }}
                                        </sup>

                                    @endif

                                    <span class="text-xs font-semibold text-brand-green">
                                        pts
                                    </span>

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


            <section class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100">

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">

                    <div>

                        <h3 class="text-2xl font-black text-brand-green uppercase">
                            Calendario de Eventos
                        </h3>

                        <p class="text-sm text-gray-500">
                            Cronograma general de eventos y actividades
                        </p>

                    </div>

                    <div class="flex flex-wrap items-center gap-4">

                        <div class="flex items-center gap-3 text-xs font-semibold">

                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-sm bg-brand-green inline-block"></span>
                                <span>Clases</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-sm bg-[#9B843F] inline-block"></span>
                                <span>Eventos</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-sm bg-[#343434] inline-block"></span>
                                <span>Webinars</span>
                            </div>

                        </div>
                        @if ($usuario->hasAnyCargo(['supervisor_academico', 'asistente_academico', 'coordinador_academico']) || $usuario->hasAnyRole(['super_admin', 'admin']))
                            <button id="btnNuevoEvento" type="button"
                                class="btn-gold !py-2 !px-4 text-xs whitespace-nowrap">

                                + Agregar Evento

                            </button>
                        @endif
                    </div>

                </div>

                <div id="calendar" class="fc-posicionate max-w-5xl mx-auto">
                </div>

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

                                <img src="{{ asset('img/menu_icon.png') }}" alt="">

                            </div>

                            <h3 class="text-xl font-bold font-sans tracking-tight uppercase">
                                Sobre Posicionate
                            </h3>

                        </div>

                        <p class="text-white text-sm md:text-base leading-relaxed mb-6">

                            POSICIONATE LA PLATA | Centro de Formación Continua.

                            En POSICIONATE creemos en el aprendizaje constante como motor de crecimiento personal y
                            profesional.

                            Somos un centro de formación continua enfocado a desarrollar habilidades prácticas.

                        </p>

                    </div>

                </div>


                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">

                    <div>

                        <div class="flex items-center gap-3 mb-4">

                            <div class="p-2 bg-amber-50 rounded-lg text-brand-gold">

                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                    </path>

                                </svg>

                            </div>

                            <h3 class="text-xl font-bold text-brand-green">
                                Ubicación Central
                            </h3>

                        </div>

                        <div class="space-y-4 text-sm text-gray-600">

                            <div class="text-black">

                                <p class="font-semibold">
                                    Dirección Física:
                                </p>

                                <p class="mt-0.5">
                                    Calle René Calvo Arana #87
                                </p>

                                <p class="text-xs">
                                    Sucre, Bolivia
                                </p>

                            </div>

                            <hr class="border-gray-100">

                            <div class="text-black">

                                <p class="font-semibold">
                                    Canales de Atención:
                                </p>

                                <p class="mt-0.5">
                                    academicoposicionate@gmail.com
                                </p>

                                <p>
                                    +591 60300960
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </section>

        </div>

    </x-layout-dashboard>


    <div id="modalEvento"
        class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">

        <div
            class="bg-white p-6 rounded-sm shadow-2xl w-full max-w-md text-left border-t-4 border-brand-green font-sans relative">

            <button id="cerrarModalEvento" type="button"
                class="absolute top-3 right-4 text-gray-400 hover:text-gray-700 text-sm cursor-pointer">

                ✕

            </button>

            <h3 class="text-brand-green uppercase text-sm font-bold mb-4">
                Nuevo Evento
            </h3>

            <form id="formEvento" class="space-y-4">

                <div>

                    <label class="text-[9px] uppercase font-bold text-gray-400 mb-1 block">
                        Título
                    </label>

                    <input type="text" name="titulo" required
                        class="w-full border border-gray-200 p-2 text-xs focus:outline-none focus:border-brand-green bg-gray-50">

                </div>


                <div>

                    <label class="text-[9px] uppercase font-bold text-gray-400 mb-1 block">
                        Tipo
                    </label>

                    <select name="tipo" required
                        class="w-full border border-gray-200 p-2 text-xs focus:outline-none focus:border-brand-green bg-gray-50">

                        <option value="clase">
                            Clase
                        </option>

                        <option value="evento">
                            Evento
                        </option>

                        <option value="webinar">
                            Webinar
                        </option>

                    </select>

                </div>


                <div class="grid grid-cols-2 gap-3">

                    <div>

                        <label class="text-[9px] uppercase font-bold text-gray-400 mb-1 block">
                            Inicio
                        </label>

                        <input type="datetime-local" name="fecha_inicio" required
                            class="w-full border border-gray-200 p-2 text-xs focus:outline-none focus:border-brand-green bg-gray-50">

                    </div>


                    <div>

                        <label class="text-[9px] uppercase font-bold text-gray-400 mb-1 block">
                            Fin
                        </label>

                        <input type="datetime-local" name="fecha_fin"
                            class="w-full border border-gray-200 p-2 text-xs focus:outline-none focus:border-brand-green bg-gray-50">

                    </div>

                </div>


                <div>

                    <label class="text-[9px] uppercase font-bold text-gray-400 mb-1 block">
                        Descripción
                    </label>

                    <textarea name="descripcion" rows="3"
                        class="w-full border border-gray-200 p-2 text-xs focus:outline-none focus:border-brand-green bg-gray-50"></textarea>

                </div>


                <div class="flex justify-end gap-3 pt-2">

                    <button type="button" id="cancelarModalEvento"
                        class="text-[9px] font-sans cursor-pointer uppercase">

                        Cancelar

                    </button>

                    <button type="submit"
                        class="bg-brand-green text-white px-4 py-2 rounded-sm text-[9px] font-sans uppercase cursor-pointer hover:opacity-90 transition">

                        Guardar

                    </button>

                </div>

            </form>

        </div>

    </div>


    <div id="modalDetalleEvento"
        class="hidden fixed inset-0 z-[10000] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">

        <div
            class="bg-white p-6 rounded-sm shadow-2xl w-full max-w-sm text-left border-t-4 border-brand-green font-sans relative">

            <button id="cerrarDetalleEvento" type="button"
                class="absolute top-3 right-4 text-gray-400 hover:text-gray-700 text-sm cursor-pointer">

                ✕

            </button>


            <h3 id="detalleTipo" class="uppercase text-sm font-bold mb-1">
            </h3>


            <h2 id="detalleTitulo" class="text-base font-black text-black uppercase leading-tight pr-6 mb-4">
            </h2>


            <div class="border-t border-gray-100 pt-3 space-y-3">

                <div>

                    <p class="text-[9px] uppercase font-bold text-gray-400 mb-1">
                        Fecha
                    </p>

                    <p id="detalleFecha" class="text-xs text-gray-700">
                    </p>

                </div>


                <div id="detalleDescripcionContainer">

                    <p class="text-[9px] uppercase font-bold text-gray-400 mb-1">
                        Descripción
                    </p>

                    <p id="detalleDescripcion" class="text-xs text-gray-600 leading-relaxed">
                    </p>

                </div>

            </div>


            <div class="flex justify-end mt-5">

                <button id="cerrarDetalleEventoBtn" type="button"
                    class="bg-brand-green text-white px-4 py-2 rounded-sm text-[9px] font-sans uppercase cursor-pointer hover:opacity-90 transition">

                    Cerrar

                </button>

            </div>

        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const calendarEl = document.getElementById('calendar');

            const csrfToken =
                document.querySelector('meta[name="csrf-token"]').content;

            const modal =
                document.getElementById('modalEvento');

            const modalDetalle =
                document.getElementById('modalDetalleEvento');

            const detalleTitulo =
                document.getElementById('detalleTitulo');

            const detalleTipo =
                document.getElementById('detalleTipo');

            const detalleFecha =
                document.getElementById('detalleFecha');

            const detalleDescripcion =
                document.getElementById('detalleDescripcion');

            const detalleDescripcionContainer =
                document.getElementById('detalleDescripcionContainer');


            function estiloTipo(tipo) {

                switch ((tipo || '').toLowerCase()) {

                    case 'clase':
                        return {
                            bg: '#072E2C',
                            text: '#FFFFFF',
                            label: 'Clase'
                        };

                    case 'evento':
                        return {
                            bg: '#9B843F',
                            text: '#FFFFFF',
                            label: 'Evento'
                        };

                    case 'webinar':
                        return {
                            bg: '#343434',
                            text: '#FFFFFF',
                            label: 'Webinar'
                        };

                    default:
                        return {
                            bg: '#072E2C',
                            text: '#FFFFFF',
                            label: 'Clase'
                        };
                }
            }


            function iconoTipo(tipo) {

                switch ((tipo || '').toLowerCase()) {

                    case 'clase':

                        return `
                        <svg class="calendar-event-icon"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                        </svg>
                    `;

                    case 'evento':

                        return `
                        <svg class="calendar-event-icon"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    `;

                    case 'webinar':

                        return `
                        <svg class="calendar-event-icon"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true">
                            <rect x="2" y="5" width="20" height="14" rx="2"/>
                            <polygon points="10,9 16,12 10,15"/>
                        </svg>
                    `;

                    default:

                        return `
                        <svg class="calendar-event-icon"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                        </svg>
                    `;
                }
            }


            function formatearFecha(fechaInicio, fechaFin) {

                if (!fechaInicio) {
                    return '';
                }

                const inicio = new Date(fechaInicio);

                if (isNaN(inicio.getTime())) {
                    return '';
                }

                const opcionesFecha = {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                };

                const opcionesHora = {
                    hour: '2-digit',
                    minute: '2-digit'
                };

                let resultado =
                    inicio.toLocaleDateString(
                        'es-ES',
                        opcionesFecha
                    ) +
                    ' · ' +
                    inicio.toLocaleTimeString(
                        'es-ES',
                        opcionesHora
                    );

                if (fechaFin) {

                    const fin = new Date(fechaFin);

                    if (!isNaN(fin.getTime())) {

                        resultado +=
                            ' – ' +
                            fin.toLocaleDateString(
                                'es-ES',
                                opcionesFecha
                            ) +
                            ' · ' +
                            fin.toLocaleTimeString(
                                'es-ES',
                                opcionesHora
                            );
                    }
                }

                return resultado;
            }


            const calendar = new FullCalendar.Calendar(calendarEl, {

                initialView: 'dayGridMonth',

                locale: 'es',

                height: 520,

                dayMaxEventRows: 2,

                moreLinkClick: 'popover',

                fixedWeekCount: false,

                eventDisplay: 'block',


                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false
                },


                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listMonth'
                },


                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    list: 'Agenda'
                },


                events: '{{ route("calendario.eventos") }}',


                eventDidMount: function (info) {

                    const tipo =
                        info.event.extendedProps?.tipo || 'clase';

                    const estilo =
                        estiloTipo(tipo);


                    info.el.style.setProperty(
                        'background-color',
                        estilo.bg,
                        'important'
                    );

                    info.el.style.setProperty(
                        'border-color',
                        estilo.bg,
                        'important'
                    );

                    info.el.style.setProperty(
                        'color',
                        estilo.text,
                        'important'
                    );


                    info.el.setAttribute(
                        'data-event-type',
                        tipo
                    );


                    info.el.title =
                        info.event.title;
                },


                eventContent: function (info) {

                    const tipo =
                        info.event.extendedProps?.tipo || 'clase';

                    const estilo =
                        estiloTipo(tipo);

                    const icono =
                        iconoTipo(tipo);


                    return {
                        html: `
                        <div
                            class="calendar-event-content"
                            style="
                                color: ${estilo.text};
                                display: flex;
                                align-items: center;
                                gap: 6px;
                                width: 100%;
                                overflow: hidden;
                            "
                        >
                            ${icono}

                            <span
                                class="calendar-event-title"
                                style="
                                    color: ${estilo.text};
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                    white-space: nowrap;
                                    min-width: 0;
                                "
                            >
                                ${info.event.title}
                            </span>
                        </div>
                    `
                    };
                },


                eventClick: function (info) {

                    const evento =
                        info.event;

                    const props =
                        evento.extendedProps || {};

                    const estilo =
                        estiloTipo(props.tipo);


                    detalleTitulo.textContent =
                        evento.title;


                    detalleTipo.textContent =
                        estilo.label;

                    detalleTipo.style.color =
                        estilo.bg;


                    detalleFecha.textContent =
                        formatearFecha(
                            props.fecha_inicio,
                            props.fecha_fin
                        );


                    if (props.descripcion) {

                        detalleDescripcion.textContent =
                            props.descripcion;

                        detalleDescripcionContainer.classList.remove(
                            'hidden'
                        );

                    } else {

                        detalleDescripcion.textContent =
                            'Sin descripción';

                        detalleDescripcionContainer.classList.remove(
                            'hidden'
                        );
                    }


                    modalDetalle.classList.remove(
                        'hidden'
                    );
                }

            });


            calendar.render();


            const btnNuevoEvento =
                document.getElementById(
                    'btnNuevoEvento'
                );


            if (btnNuevoEvento) {

                btnNuevoEvento.addEventListener(
                    'click',
                    function () {

                        modal.classList.remove(
                            'hidden'
                        );

                    }
                );
            }


            const cerrarModalEvento =
                document.getElementById(
                    'cerrarModalEvento'
                );


            if (cerrarModalEvento) {

                cerrarModalEvento.addEventListener(
                    'click',
                    function () {

                        modal.classList.add(
                            'hidden'
                        );

                    }
                );
            }


            const cancelarModalEvento =
                document.getElementById(
                    'cancelarModalEvento'
                );


            if (cancelarModalEvento) {

                cancelarModalEvento.addEventListener(
                    'click',
                    function () {

                        modal.classList.add(
                            'hidden'
                        );

                    }
                );
            }


            function cerrarDetalle() {

                modalDetalle.classList.add(
                    'hidden'
                );
            }


            const cerrarDetalleEvento =
                document.getElementById(
                    'cerrarDetalleEvento'
                );


            if (cerrarDetalleEvento) {

                cerrarDetalleEvento.addEventListener(
                    'click',
                    cerrarDetalle
                );
            }


            const cerrarDetalleEventoBtn =
                document.getElementById(
                    'cerrarDetalleEventoBtn'
                );


            if (cerrarDetalleEventoBtn) {

                cerrarDetalleEventoBtn.addEventListener(
                    'click',
                    cerrarDetalle
                );
            }


            if (modalDetalle) {

                modalDetalle.addEventListener(
                    'click',
                    function (e) {

                        if (
                            e.target ===
                            modalDetalle
                        ) {

                            cerrarDetalle();

                        }

                    }
                );
            }


            if (modal) {

                modal.addEventListener(
                    'click',
                    function (e) {

                        if (
                            e.target === modal
                        ) {

                            modal.classList.add(
                                'hidden'
                            );

                        }

                    }
                );
            }


            const formEvento =
                document.getElementById(
                    'formEvento'
                );


            if (formEvento) {

                formEvento.addEventListener(
                    'submit',
                    function (e) {

                        e.preventDefault();


                        const form =
                            this;


                        const formData =
                            new FormData(form);


                        const payload =
                            Object.fromEntries(
                                formData.entries()
                            );


                        fetch(
                            '{{ route("calendario.guardarEvento") }}',
                            {
                                method: 'POST',

                                headers: {
                                    'Content-Type':
                                        'application/json',

                                    'X-CSRF-TOKEN':
                                        csrfToken,

                                    'Accept':
                                        'application/json'
                                },

                                body:
                                    JSON.stringify(
                                        payload
                                    )
                            }
                        )

                            .then(
                                async res => {

                                    const data =
                                        await res.json();


                                    if (!res.ok) {

                                        throw new Error(
                                            data.message ||
                                            'Error al guardar el evento'
                                        );
                                    }


                                    return data;
                                }
                            )

                            .then(
                                () => {

                                    calendar.refetchEvents();

                                    modal.classList.add(
                                        'hidden'
                                    );

                                    form.reset();

                                }
                            )

                            .catch(
                                err => {

                                    console.error(
                                        err
                                    );

                                    alert(
                                        err.message ||
                                        'No se pudo guardar el evento.'
                                    );

                                }
                            );

                    }
                );
            }

        });
    </script>

</body>

</html>
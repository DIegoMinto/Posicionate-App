<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Programación académica</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 25px 35px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #374151;
            background: #ffffff;
            margin: 0;
        }

        .page-title {
            color: #072e2c;
            font-size: 19px;
            font-weight: bold;
            margin: 0 0 22px;
            padding-bottom: 12px;
            border-bottom: 2px solid #ccb463;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #111827;
            margin: 20px 0 13px;
        }

        /* INFORMACIÓN GENERAL */
        .general-info {
            margin-top: 14px;
            padding-left: 24px;
            color: #374151;
        }

        .general-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .general-info tr {
            display: block;
        }

        .general-info td {
            display: block;
            width: 100%;
            padding: 5px 0;
            font-size: 10px;
            line-height: 1.6;
            vertical-align: top;
        }

        .general-info strong {
            color: #1f2937;
        }

        /* CONTENEDORES DE TABLAS */
        .table-card {
            margin-top: 14px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 9px;
            overflow: hidden;
        }

        table.schedule {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .schedule thead {
            display: table-header-group;
        }

        .schedule th {
            background-color: #072e2c;
            color: #ffffff;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: left;
            padding: 13px 10px;
            border: none;
        }

        .schedule td {
            padding: 12px 10px;
            font-size: 9px;
            color: #4b5563;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
            line-height: 1.6;
        }

        .schedule tr:nth-child(even) td {
            background: #fafafa;
        }

        .schedule tr:last-child td {
            border-bottom: none;
        }

        .module-number {
            color: #072e2c;
            font-weight: bold;
        }

        .main-text {
            color: #1f2937;
            font-weight: bold;
        }

        .muted {
            color: #9ca3af;
            font-style: italic;
            font-size: 9px;
        }

        /* ESTADOS VACÍOS */
        .empty-state {
            padding: 28px;
            text-align: center;
            border: 1px solid #e5e7eb;
            border-radius: 9px;
            margin-top: 14px;
            color: #6b7280;
            background: #ffffff;
        }

        .empty-title {
            color: #374151;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .footer {
            position: fixed;
            bottom: -12px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 6px;
        }

        .keep-together {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    @php
        $modulos = $curso->modulos
            ->whereNotNull('orden')
            ->sortBy('orden')
            ->values();

        $aRomanos = function ($numero) {
            $valores = [
                1000 => 'M',
                900 => 'CM',
                500 => 'D',
                400 => 'CD',
                100 => 'C',
                90 => 'XC',
                50 => 'L',
                40 => 'XL',
                10 => 'X',
                9 => 'IX',
                5 => 'V',
                4 => 'IV',
                1 => 'I',
            ];

            $resultado = '';

            foreach ($valores as $valor => $simbolo) {
                while ($numero >= $valor) {
                    $resultado .= $simbolo;
                    $numero -= $valor;
                }
            }

            return $resultado;
        };

        $dias = $dias ?? [
            0 => 'Domingo',
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            'domingo' => 'Domingo',
            'lunes' => 'Lunes',
            'martes' => 'Martes',
            'miercoles' => 'Miércoles',
            'miércoles' => 'Miércoles',
            'jueves' => 'Jueves',
            'viernes' => 'Viernes',
            'sabado' => 'Sábado',
            'sábado' => 'Sábado',
        ];
    @endphp

    {{-- ENCABEZADO DE LA PÁGINA --}}
    <div class="page-title">
        PROGRAMACIÓN ACADÉMICA
    </div>

    @if($programacion)

        {{-- 1. INFORMACIÓN GENERAL --}}
        <div class="section-title">
            1. INFORMACIÓN GENERAL
        </div>

        <div class="general-info">
            <table>
                <tr>
                    <td>
                        <strong>Nombre del Programa:</strong>
                        {{ $curso->nombre }}
                    </td>

                    <td>
                        <strong>Coordinador del programa:</strong>
                        {{ $programacion->coordinador
            ? $programacion->coordinador->nombre . ' ' .
            $programacion->coordinador->apellido_p . ' ' .
            $programacion->coordinador->apellido_m
            : 'Sin coordinador asignado' }}
                    </td>
                </tr>

                <tr>
                    <td>
                        <strong>Carga Horaria Total:</strong>
                        {{ $programacion->carga_horaria ?? 'No definida' }}
                        @if($programacion->carga_horaria) horas @endif
                    </td>

                    <td>
                        <strong>Horario General:</strong>
                        @if($programacion->hora_inicio && $programacion->hora_fin)
                            {{ substr($programacion->hora_inicio, 0, 5) }}
                            a {{ substr($programacion->hora_fin, 0, 5) }}
                        @else
                            No definido
                        @endif
                    </td>
                </tr>

                <tr>
                    <td>
                        <strong>Duración:</strong>
                        {{ $programacion->duracion_meses }} meses -
                        {{ $programacion->cantidad_modulos }} módulos
                    </td>

                    <td>
                        <strong>Certificado por:</strong>
                        {{ $curso->institucion->nombre ?? 'No definida' }}
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <strong>Días de Clase:</strong>
                        {{ collect($programacion->dias_clase ?? [])
            ->map(fn($dia) => $dias[$dia] ?? ucfirst((string) $dia))
            ->implode(', ') ?: 'No definidos' }}
                    </td>
                </tr>
            </table>
        </div>

        {{-- 2. CRONOGRAMA ACADÉMICO --}}
        <div class="section-title">
            2. CRONOGRAMA ACADÉMICO DEL DIPLOMADO
        </div>

        <div class="table-card">

            @if($modulos->isNotEmpty())

                <table class="schedule">
                    <thead>
                        <tr>
                            <th width="8%">Módulo</th>
                            <th width="23%">Contenido académico</th>
                            <th width="13%">Fecha de inicio</th>
                            <th width="13%">Fecha de fin</th>
                            <th width="19%">Descanso modular</th>
                            <th width="24%">Docente asignado</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($modulos as $modulo)
                            <tr>
                                <td>
                                    <span class="module-number">
                                        {{ $aRomanos($modulo->orden) }}.
                                    </span>
                                </td>

                                <td>
                                    <span class="main-text">
                                        {{ $modulo->nombre ?: 'Pendiente' }}
                                    </span>
                                </td>

                                <td>
                                    {{ $modulo->fecha_inicio
                            ? \Carbon\Carbon::parse($modulo->fecha_inicio)->format('d/m/Y')
                            : 'Pendiente' }}
                                </td>

                                <td>
                                    {{ $modulo->fecha_fin
                            ? \Carbon\Carbon::parse($modulo->fecha_fin)->format('d/m/Y')
                            : 'Pendiente' }}
                                </td>

                                <td>
                                    @if($modulo->fecha_inicio_descanso && $modulo->fecha_fin_descanso)
                                        <span class="main-text">
                                            {{ \Carbon\Carbon::parse($modulo->fecha_inicio_descanso)->format('d/m/Y') }}
                                        </span>
                                        <br>
                                        <span class="muted">hasta</span>
                                        <br>
                                        <span class="main-text">
                                            {{ \Carbon\Carbon::parse($modulo->fecha_fin_descanso)->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="muted">Sin descanso</span>
                                    @endif
                                </td>

                                <td>
                                    @if($modulo->docente)
                                        <span class="main-text">
                                            {{ $modulo->docente->nombre }}
                                            {{ $modulo->docente->apellido_p }}
                                            {{ $modulo->docente->apellido_m }}
                                        </span>
                                    @else
                                        <span class="muted">Sin asignar</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @else

                <div class="empty-state">
                    <div class="empty-title">
                        Todavía no hay módulos registrados.
                    </div>
                    La estructura modular aparecerá aquí cuando se registre.
                </div>

            @endif

        </div>

        {{-- 3. CRONOGRAMA DE MASTERCLASS --}}
        <div class="section-title">
            3. CRONOGRAMA DE LAS MASTERCLASS
        </div>

        <div class="table-card">

            @if($masterclasses->isNotEmpty())

                <table class="schedule">
                    <thead>
                        <tr>
                            <th width="8%">N.º</th>
                            <th width="32%">Título Masterclass</th>
                            <th width="18%">Fecha de inicio</th>
                            <th width="17%">Día</th>
                            <th width="25%">Docente asignado</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($masterclasses as $masterclass)

                            @php
                                $fechaMasterclass = \Carbon\Carbon::parse(
                                    $masterclass->fecha_inicio
                                )->locale('es');
                            @endphp

                            <tr>
                                <td>
                                    <span class="module-number">
                                        {{ $loop->iteration }}
                                    </span>
                                </td>

                                <td>
                                    <span class="main-text">
                                        {{ $masterclass->titulo }}
                                    </span>
                                </td>

                                <td>
                                    {{ $fechaMasterclass->format('d/m/Y') }}
                                </td>

                                <td>
                                    {{ ucfirst($fechaMasterclass->translatedFormat('l')) }}
                                </td>

                                <td>
                                    @if($masterclass->docente)
                                        <span class="main-text">
                                            {{ $masterclass->docente->nombre }}
                                            {{ $masterclass->docente->apellido_p }}
                                            {{ $masterclass->docente->apellido_m }}
                                        </span>
                                    @else
                                        <span class="muted">Sin asignar</span>
                                    @endif
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>

            @else

                <div class="empty-state">
                    <div class="empty-title">
                        Todavía no hay Masterclass registradas.
                    </div>
                    El cronograma de clases magistrales aparecerá aquí cuando se registren.
                </div>

            @endif

        </div>

    @else

        <div class="empty-state">
            <div class="empty-title">
                Programación académica pendiente
            </div>
            Este diplomado todavía no cuenta con una configuración académica.
            Crea su programación para comenzar a organizar los módulos y las clases.
        </div>

    @endif

    <div class="footer">
        POSICIONATE | FORMACIÓN CONTINUA
    </div>

</body>

</html>
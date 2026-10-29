<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 35px 45px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
            line-height: 1.5;
        }

        .encabezado {
            text-align: center;
            border-bottom: 2px solid #072e2c;
            padding-bottom: 14px;
            margin-bottom: 25px;
        }

        .empresa {
            color: #072e2c;
            font-size: 17px;
            font-weight: bold;
        }

        .subtitulo {
            font-size: 11px;
            letter-spacing: 1px;
        }

        h2 {
            text-align: center;
            font-size: 15px;
            margin: 25px 0;
            text-transform: uppercase;
        }

        .tipo {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            border: 1px solid #cbd5d1;
            padding: 9px;
            vertical-align: top;
        }

        .etiqueta {
            width: 34%;
            background: #f1f4f3;
            font-weight: bold;
        }

        .seccion {
            margin-top: 22px;
            margin-bottom: 8px;
            color: #072e2c;
            font-weight: bold;
            font-size: 12px;
        }

        .detalle {
            border: 1px solid #cbd5d1;
            padding: 14px;
            min-height: 75px;
            text-align: justify;
        }

        .firmas {
            margin-top: 80px;
        }

        .firma {
            width: 44%;
            text-align: center;
            border-top: 1px solid #333;
            padding-top: 8px;
        }

        .pie {
            margin-top: 25px;
            text-align: center;
            color: #666;
            font-size: 9px;
        }
    </style>
</head>

<body>
    <div class="encabezado">
        <div class="empresa">POSICIONATE S.R.L.</div>
        <div class="subtitulo">FORMACIÓN CONTINUA — CONTROL ACADÉMICO</div>
    </div>

    <div class="tipo">
        {{ $registro->tipo === 'retiro' ? '☒ RETIRO   ☐ ABANDONO' : '☐ RETIRO   ☒ ABANDONO' }}
    </div>

    <h2>
        {{ $registro->tipo === 'retiro'
    ? 'Registro de retiro de diplomado'
    : 'Registro de abandono de diplomado' }}
    </h2>

    <table>
        <tr>
            <td class="etiqueta">Nombre del estudiante</td>
            <td>{{ $registro->nombre_estudiante }}</td>
        </tr>
        <tr>
            <td class="etiqueta">Cédula de identidad (C.I.)</td>
            <td>{{ $registro->ci_estudiante }}</td>
        </tr>
        <tr>
            <td class="etiqueta">Teléfono / celular</td>
            <td>{{ $registro->telefono_estudiante ?: 'No registrado' }}</td>
        </tr>
        <tr>
            <td class="etiqueta">Programa / diplomado</td>
            <td>{{ $registro->nombre_curso }}</td>
        </tr>
        <tr>
            <td class="etiqueta">
                {{ $registro->tipo === 'retiro'
    ? 'Fecha de solicitud'
    : 'Fecha de registro de abandono' }}
            </td>
            <td>{{ $registro->fecha_registro->locale('es')->translatedFormat('d \d\e F \d\e Y') }}</td>
        </tr>
        <tr>
            <td class="etiqueta">Módulos cursados / avanzados</td>
            <td>{{ $registro->modulos_cursados ?: 'No especificado' }}</td>
        </tr>
    </table>

    <div class="seccion">Estado financiero</div>

    <table>
        <tr>
            <td class="etiqueta">Monto total</td>
            <td>{{ number_format((float) $registro->monto_total, 2, '.', ',') }} Bs</td>
        </tr>
        <tr>
            <td class="etiqueta">Monto pagado</td>
            <td>{{ number_format((float) $registro->monto_pagado, 2, '.', ',') }} Bs</td>
        </tr>
        <tr>
            <td class="etiqueta">Saldo pendiente</td>
            <td>{{ number_format((float) $registro->saldo_pendiente, 2, '.', ',') }} Bs</td>
        </tr>
    </table>

    <div class="seccion">
        {{ $registro->tipo === 'retiro'
    ? 'Motivo del retiro'
    : 'Detalle del abandono' }}
    </div>

    <div class="detalle">
        {{ $registro->detalle }}
    </div>

    <div class="pie">
        POSICIONATE S.R.L. — Formación Continua
        <br>
        Documento generado el {{ now()->format('d/m/Y H:i') }}
        <br><br>
        <strong>Emitido por:</strong>
        {{ $registro->nombre_emisor ?: 'No registrado' }}
    </div>
</body>

</html>
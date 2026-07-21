<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Plan de Pagos - {{ $curso->nombre ?? '' }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Sans', sans-serif;
            color: #072e2c;
            background-repeat: repeat;
            background-size: cover;
        }

        .linea-fondo {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .page {
            position: relative;
            z-index: 2;
            padding: 30px 35px 110px 35px;
        }

        .header-wrap {
            position: relative;
            margin-bottom: 22px;
            min-height: 50px;
        }

        .header-banner-img {
            position: absolute;
            top: 0;
            left: -35px;
            height: 48px;
            width: auto;
            display: block;
        }

        .datos {
            font-size: 11px;
            margin-bottom: 16px;
        }

        .datos strong {
            text-transform: uppercase;
        }

        .pill-wrap {
            text-align: center;
            margin: 14px 0 18px 0;
        }

        .pill {
            display: inline-block;
            background-color: #072e2c;
            color: #ffffff;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 2px 18px;
            border-radius: 10px;
        }

        table.plan {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        table.plan thead th {
            background-color: #072e2c;
            color: #ffffff;
            text-transform: uppercase;
            font-size: 9px;
            font-weight: bold;
            padding: 8px 6px;
            text-align: center;
            border: 1px solid #072e2c;
        }

        table.plan tbody td {
            padding: 7px 6px;
            text-align: center;
            border: 1px solid #d9d9d9;
            background-color: rgba(255, 255, 255, 0.85);
        }

        table.plan tbody tr:nth-child(even) td {
            background-color: rgba(235, 240, 237, 0.85);
        }

        .fecha-pendiente {
            color: #b58a00;
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-completo {
            background-color: #d7f3df;
            color: #1c7a3f;
        }

        .badge-incompleto {
            background-color: #fbdada;
            color: #b03434;
        }

        table.plan tfoot td {
            background-color: #072e2c;
            color: #ffffff;
            font-weight: bold;
            font-size: 10px;
            padding: 9px 6px;
            text-align: center;
            border: 1px solid #072e2c;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 3;
        }

        .footer img {
            width: 100%;
            display: block;
        }
    </style>
</head>

<body>

    <img class="linea-fondo" src="{{ public_path('img/linea.png') }}">

    <div class="page">

        <div class="header-wrap">
            <img class="header-banner-img" src="{{ public_path('img/header_plan.png') }}" alt="Plan de Pagos">
        </div>

        <div class="datos">
            <p style="font-size:20px;"><strong>Diplomado en:</strong> {{ $curso->nombre ?? '-' }}</p>
            <p><strong>Nombre del Participante:</strong>
                {{ $estudiante->nombre }} {{ $estudiante->apellido_p }} {{ $estudiante->apellido_m }}
            </p>
            <p><strong>CI/NIT:</strong> {{ $estudiante->ci }}</p>
            <p><strong>Teléfono:</strong> {{ $estudiante->telefono_movil ?? '-' }}</p>

            @php
                $total = $pagos->sum('monto_pagar');
                $pagado = $pagos->sum('monto_pagado');
                $pendiente = $total - $pagado;
            @endphp

            <p><strong>Saldo Total Adeudado:</strong> {{ number_format($total, 2) }} Bs</p>
            <p><strong>Modalidad de Pago:</strong> {{ $inscripcion->plan->nombre ?? '-' }}</p>
        </div>

        <div class="pill-wrap">
            <span class="pill"
                style="text-transform: uppercase;">{{ $inscripcion->plan->nombre ?? 'Pago en Cuotas' }}</span>
        </div>

        <table class="plan">
            <thead>
                <tr>
                    <th style="width:5%">N°</th>
                    <th style="width:12%">Fecha</th>
                    <th style="width:23%">Concepto</th>
                    <th style="width:15%">Monto Cuota</th>
                    <th style="width:15%">Monto Pagado</th>
                    <th style="width:15%">Saldo</th>
                    <th style="width:15%">Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pagos as $index => $pago)
                    @php
                        $saldoFila = $pago->monto_pagar - $pago->monto_pagado;
                        $estaCompleto = $pago->estado === 'pagado';
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($pago->fecha_pagada)
                                {{ \Carbon\Carbon::parse($pago->fecha_pagada)->format('d/m/Y') }}
                            @elseif($pago->fecha_programada)
                                {{ \Carbon\Carbon::parse($pago->fecha_programada)->format('d/m/Y') }}
                            @else
                                <span class="fecha-pendiente">-</span>
                            @endif
                        </td>
                        <td>{{ $pago->detalle }}</td>
                        <td>{{ number_format($pago->monto_pagar, 2) }}</td>
                        <td>{{ $pago->monto_pagado > 0 ? number_format($pago->monto_pagado, 2) : '-' }}</td>
                        <td>{{ $saldoFila > 0 ? number_format($saldoFila, 2) : '-' }}</td>
                        <td>
                            {{ $estaCompleto ? 'Completo' : 'Incompleto' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No hay pagos registrados para este estudiante.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align:right;">Totales</td>
                    <td>{{ number_format($total, 2) }}</td>
                    <td>{{ number_format($pagado, 2) }}</td>
                    <td>{{ number_format($pendiente, 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

    </div>

    <div class="footer">
        <img class="footer-imagen" src="{{ public_path('img/footer_plan.png') }}" alt="Footer">
    </div>

</body>

</html>
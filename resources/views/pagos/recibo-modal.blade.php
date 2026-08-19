<style>
    .recibo-wrapper {
        font-family: Helvetica, Arial, sans-serif;
        color: #1a1a1a;
        background: #fff;
        padding: 30px 40px;
    }

    .recibo-wrapper .header-table {
        width: 100%;
        margin-bottom: 10px;
    }

    .recibo-wrapper .titulo {
        font-size: 40px;
        font-weight: bold;
        color: #1b3b2f;
        letter-spacing: -1px;
    }

    .recibo-wrapper .linea-gold {
        width: 40px;
        height: 4px;
        background-color: #ccb463;
        margin-top: 6px;
        margin-bottom: 20px;
    }

    .recibo-wrapper .numero-box {
        display: inline-block;
        border-radius: 5px;
        font-size: 13px;
        padding: 6px 10px;
        background-color: #072e2c;
        color: #fff;
        white-space: nowrap;
        font-weight: bold;
    }

    .recibo-wrapper .info-box {
        background-color: #fff;
        border: 1px solid #e0e0e0;
        padding: 12px 20px;
        margin-bottom: 20px;
    }

    .recibo-wrapper .info-box td {
        padding: 0 15px;
        vertical-align: top;
    }

    .recibo-wrapper .label-small {
        font-size: 10px;
        font-weight: bold;
        color: #072e2c;
        text-transform: uppercase;
    }

    .recibo-wrapper .valor-small {
        font-size: 13px;
        color: #072e2c;
    }

    .recibo-wrapper .campo {
        margin-bottom: 14px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 6px;
    }

    .recibo-wrapper .campo-label {
        font-size: 10px;
        font-weight: bold;
        text-transform: uppercase;
        color: #072e2c;
    }

    .recibo-wrapper .campo-valor {
        font-size: 12px;
        color: #072e2c;
    }

    .recibo-wrapper .totales-table {
        width: 100%;
        margin-top: 30px;
    }

    .recibo-wrapper .caja-label {
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 4px;
        color: #072e2c;
    }

    .recibo-wrapper .caja-total {
        border: 1px solid #ccc;
        padding: 10px 20px;
        font-size: 20px;
        font-weight: bold;
        color: #072e2c;
        border-radius: 5px;
    }

    .recibo-wrapper .total-final {
        background-color: #1b3b2f;
        color: #fff;
        padding: 12px 20px;
        font-size: 12px;
        font-weight: bold;
    }

    .recibo-wrapper .footer-imagen {
        width: 100%;
        height: auto;
        margin-top: 20px;
    }

    .recibo-wrapper .campo {
        text-align: left;
    }

    .recibo-wrapper .campo-label,
    .recibo-wrapper .campo-valor {
        text-align: left;
    }
</style>

<div class="recibo-wrapper">

    <table class="header-table" width="100%" style="border-collapse: collapse;">
        <tr>
            <td width="60%" style="vertical-align: middle;">
                <div class="titulo">RECIBO</div>
                <div class="linea-gold" style="margin-bottom: 0;"></div>
            </td>

            <td width="40%" style="text-align: right; vertical-align: middle; padding-right: 0px;">
                <img src="{{ asset('img/header.png') }}" alt="Logo"
                    style="height: 65px; width: auto; display: inline-block;">
            </td>

            <td width="15%" style="text-align: right; vertical-align: middle;">
                <div class="numero-box">
                    N°: &nbsp; {{ $numeroRecibo }}
                </div>
            </td>
        </tr>
    </table>

    <table class="info-box" width="100%">
        <tr>
            <td width="15%" style="border-right: 1px solid #ccb463;">
                <div class="label-small">Día</div>
                <div class="valor-small">{{ $dia }}</div>
            </td>
            <td width="20%" style="border-right: 1px solid #ccb463;">
                <div class="label-small">Mes</div>
                <div class="valor-small">{{ $mes }}</div>
            </td>
            <td width="15%" style="border-right: 1px solid #ccb463;">
                <div class="label-small">Año</div>
                <div class="valor-small">{{ $anio }}</div>
            </td>
            <td width="50%">
                <div class="label-small">Nombre del Asesor</div>
                <div class="valor-small">{{ $asesorNombre }}</div>
            </td>
        </tr>
    </table>

    <div class="campo" style="text-align: left;">
        <span class="campo-label">Nombre:</span>
        <span class="campo-valor">{{ $estudiante->nombre }} {{ $estudiante->apellido_p }}
            {{ $estudiante->apellido_m }}</span>
    </div>

    <table width="100%">
        <tr>
            <td width="70%" style="border-bottom:1px solid #ddd; padding-bottom:6px; text-align: left;">
                <span class="campo-label">Monto:</span>
                <span class="campo-valor">{{ $montoLetras }}</span>
            </td>
            <td width="30%" style="text-align:right;">
                <span
                    style="background:#072e2c;color:#fff;padding:4px 14px;font-size:14px;font-weight:bold;border-radius:5px;">Bs.</span>
            </td>
        </tr>
    </table>

    <div class="campo" style="margin-top:14px; text-align: left;">
        <span class="campo-label">Por Concepto De:</span>
        <span class="campo-valor">{{ $curso->codigo_curso ?? '' }}; {{ $curso->nombre }}</span>
    </div>

    <div class="campo">&nbsp;</div>

    <table class="totales-table" width="100%" style="border-collapse: collapse;">
        <tr>
            <td width="35%" style="vertical-align: middle;">
                <table style="border-collapse: collapse;">
                    <tr>
                        <td style="padding: 0; vertical-align: middle;">
                            <div class="caja-label" style="margin-bottom: 0; margin-right: 8px;">A Cuenta:</div>
                        </td>
                        <td style="padding: 0; vertical-align: middle;">
                            <div class="caja-total" style="font-size: 24px; padding: 6px 12px; border-radius: 5px;">
                                {{ number_format($pago->monto_pagado, 0) }}
                            </div>
                        </td>
                    </tr>
                </table>
            </td>

            <td width="35%" style="vertical-align: middle;">
                <table style="border-collapse: collapse;">
                    <tr>
                        <td style="padding: 0; vertical-align: middle;">
                            <div class="caja-label" style="margin-bottom: 0; margin-right: 8px;">Saldo:</div>
                        </td>
                        <td style="padding: 0; vertical-align: middle;">
                            <div class="caja-total" style="font-size: 24px; padding: 6px 12px; border-radius: 5px;">
                                {{ number_format($saldo, 0) }}
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="30%" style="text-align: right; vertical-align: middle;">
                <div class="total-final"
                    style="display: inline-block; padding: 8px 14px; font-size: 13px; text-align: center;border-radius: 5px">
                    TOTAL: <span
                        style="font-size: 20px; font-weight: bold; margin-left: 4px;">{{ number_format($pago->monto_pagar, 0) }}</span>
                </div>
            </td>
        </tr>
    </table>

    <img class="footer-imagen" src="{{ asset('img/footer.png') }}" alt="Footer">

</div>
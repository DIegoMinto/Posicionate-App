<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }

        h1 {
            font-size: 14px;
            margin-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 4px 6px;
            text-align: left;
        }

        th {
            background-color: #1f4620;
            color: #fff;
            text-transform: uppercase;
            font-size: 9px;
        }

        tr:nth-child(even) {
            background-color: #f7f7f7;
        }
    </style>
</head>

<body>
    <h1>Lista de Estudiantes {{ isset($curso) ? '- ' . $curso->nombre : '' }}</h1>
    <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>

    @php
        $columnas = $columnas ?? [
            'ci',
            'extension_ci',
            'nombre',
            'apellido_p',
            'apellido_m',
            'telefono',
            'correo',
            'asesor',
            'fecha',
            'estado',
            'estadia'
        ];
    @endphp

    <table>
        <thead>
            <tr>
                <th>N°</th>
                @if(!isset($curso))
                    <th>Curso</th>
                @endif
                @if(in_array('ci', $columnas))
                <th>CI</th> @endif
                @if(in_array('extension_ci', $columnas))
                <th>Ext</th> @endif
                @if(in_array('nombre', $columnas))
                <th>Nombre</th> @endif
                @if(in_array('apellido_p', $columnas))
                <th>Ap. Paterno</th> @endif
                @if(in_array('apellido_m', $columnas))
                <th>Ap. Materno</th> @endif
                @if(in_array('telefono', $columnas))
                <th>Teléfono</th> @endif
                @if(in_array('correo', $columnas))
                <th>Correo</th> @endif
                @if(in_array('asesor', $columnas))
                <th>Asesor</th> @endif
                @if(in_array('fecha', $columnas))
                <th>Fecha Registro</th> @endif
                @if(in_array('estado', $columnas))
                <th>Estado</th> @endif
                @if(in_array('estadia', $columnas))
                <th>Estadía</th> @endif
            </tr>
        </thead>
        <tbody>
            @forelse($estudiantes as $index => $e)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    @if(!isset($curso))
                        <td>{{ $e->curso_nombre }}</td>
                    @endif
                    @if(in_array('ci', $columnas))
                    <td>{{ $e->ci }}</td> @endif
                    @if(in_array('extension_ci', $columnas))
                    <td>{{ $e->extension_ci }}</td> @endif
                    @if(in_array('nombre', $columnas))
                    <td>{{ $e->nombre }}</td> @endif
                    @if(in_array('apellido_p', $columnas))
                    <td>{{ $e->apellido_p }}</td> @endif
                    @if(in_array('apellido_m', $columnas))
                    <td>{{ $e->apellido_m }}</td> @endif
                    @if(in_array('telefono', $columnas))
                    <td>{{ $e->telefono_movil ?? '-' }}</td> @endif
                    @if(in_array('correo', $columnas))
                    <td>{{ $e->correo_electronico ?? '-' }}</td> @endif
                    @if(in_array('asesor', $columnas))
                    <td>{{ $e->asesor_nombre }} {{ $e->asesor_apellido }}</td> @endif
                    @if(in_array('fecha', $columnas))
                    <td>{{ \Carbon\Carbon::parse($e->fecha_inscripcion)->format('d/m/Y H:i') }}</td> @endif
                    @if(in_array('estado', $columnas))
                    <td>{{ $e->estado }}</td> @endif
                    @if(in_array('estadia', $columnas))
                        <td>
                            <span style="font-weight: bold; text-transform: uppercase;">
                                {{ $e->estadia }}
                            </span>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="12">No hay estudiantes registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
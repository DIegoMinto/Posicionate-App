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

    <table>
        <thead>
            <tr>
                <th>N°</th>
                @if(!isset($curso))
                <th>Curso</th>@endif
                <th>CI</th>
                <th>Ext</th>
                <th>Nombre</th>
                <th>Ap. Paterno</th>
                <th>Ap. Materno</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Asesor</th>
                <th>Fecha Registro</th>
                <th>Estado</th>
                <th>Estadía</th>
            </tr>
        </thead>
        <tbody>
            @forelse($estudiantes as $index => $e)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    @if(!isset($curso))
                    <td>{{ $e->curso_nombre }}</td>@endif
                    <td>{{ $e->ci }}</td>
                    <td>{{ $e->extension_ci }}</td>
                    <td>{{ $e->nombre }}</td>
                    <td>{{ $e->apellido_p }}</td>
                    <td>{{ $e->apellido_m }}</td>
                    <td>{{ $e->telefono_movil ?? '-' }}</td>
                    <td>{{ $e->correo_electronico ?? '-' }}</td>
                    <td>{{ $e->asesor_nombre }} {{ $e->asesor_apellido }}</td>
                    <td>{{ \Carbon\Carbon::parse($e->fecha_inscripcion)->format('d/m/Y H:i') }}</td>
                    <td>{{ $e->estado }}</td>
                    <td>
                        <span style="font-weight: bold; text-transform: uppercase;">
                            {{ $e->estadia ?? 'activo' }}
                        </span>
                    </td>

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
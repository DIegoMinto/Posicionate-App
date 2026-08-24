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
    <h1>Lista de Personal</h1>
    <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>ID</th>
                <th>CI</th>
                <th>Nombres</th>
                <th>Ap. Paterno</th>
                <th>Ap. Materno</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Cargo</th>
                <th>Sede</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($personales as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->codigo_personal }}</td>
                    <td>{{ $p->persona->ci ?? 'S/CI' }}</td>
                    <td>{{ $p->persona->nombre }}</td>
                    <td>{{ $p->persona->apellido_p }}</td>
                    <td>{{ $p->persona->apellido_m }}</td>
                    <td>{{ $p->persona->telefono_movil ?? '-' }}</td>
                    <td>{{ $p->persona->correo_electronico ?? '-' }}</td>
                    <td>{{ $p->cargos_nombres ?: 'No definido' }}</td>
                    <td>{{ $p->sede->nombre ?? 'N/A' }}</td>
                    <td>{{ $p->es_vigente ? 'Vigente' : 'No vigente' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11">No hay registros de personal.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
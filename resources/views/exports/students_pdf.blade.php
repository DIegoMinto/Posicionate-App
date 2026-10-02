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
    @php
        use App\Support\StudentExportColumns as Cols;

        $labels = Cols::labels();
        $columnas = $columnas ?? array_keys($labels);
    @endphp

    <h1>Lista de Estudiantes {{ isset($curso) ? '- ' . $curso->nombre : '' }}</h1>
    <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>N°</th>
                @if(!isset($curso))
                    <th>Curso</th>
                @endif
                @foreach($columnas as $c)
                    <th>{{ $labels[$c] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($estudiantes as $index => $e)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    @if(!isset($curso))
                        <td>{{ $e->curso_nombre }}</td>
                    @endif
                    @foreach($columnas as $c)
                        <td>
                            @if($c === 'estadia')
                                <span style="font-weight: bold; text-transform: uppercase;">
                                    {{ Cols::value($e, $c, $index) }}
                                </span>
                            @else
                                {{ Cols::value($e, $c, $index) }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columnas) + 1 + (isset($curso) ? 0 : 1) }}">
                        No hay estudiantes registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
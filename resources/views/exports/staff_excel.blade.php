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
        @foreach($personales as $index => $p)
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
        @endforeach
    </tbody>
</table>
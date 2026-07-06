<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">

        <x-page-header titulo="Conteo de la Institución">

            <form method="GET" class="flex flex-col lg:flex-row lg:items-end gap-3 flex-wrap">

                <select name="mes" onchange="this.form.submit()"
                    class="bg-white text-black text-[10px] font-bold px-3 py-1.5 rounded-md">
                    <option value="">MES: TODOS</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>
                            {{ strtoupper(\Carbon\Carbon::create()->month($m)->locale('es')->monthName) }}
                        </option>
                    @endforeach
                </select>

                <select name="anio" onchange="this.form.submit()"
                    class="bg-white text-black text-[10px] font-bold px-3 py-1.5 rounded-md">
                    <option value="">AÑO: TODOS</option>
                    @foreach(range(date('Y') - 3, date('Y')) as $y)
                        <option value="{{ $y }}" {{ request('anio') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endforeach
                </select>

                <select name="id_personal" onchange="this.form.submit()"
                    class="bg-white text-black text-[10px] font-bold px-3 py-1.5 rounded-md">
                    <option value="">ASESOR: TODOS</option>
                    @foreach($personales as $p)
                        <option value="{{ $p->id_personal }}" {{ request('id_personal') == $p->id_personal ? 'selected' : '' }}>
                            {{ strtoupper($p->persona->nombre . ' ' . $p->persona->apellido_p) }}
                        </option>
                    @endforeach
                </select>

                <select name="orden" onchange="this.form.submit()"
                    class="bg-white text-black text-[10px] font-bold px-3 py-1.5 rounded-md">
                    <option value="desc" {{ $orden == 'desc' ? 'selected' : '' }}>MAYOR A MENOR</option>
                    <option value="asc" {{ $orden == 'asc' ? 'selected' : '' }}>MENOR A MAYOR</option>
                </select>

            </form>

        </x-page-header>

        <div class="p-6">
            <div class="bg-white p-6 rounded-sm border-2 border-brand-gold shadow-md">

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-brand-green uppercase tracking-tighter">
                        Conteo de Ventas por Asesor
                    </h1>
                </div>

                <div class="overflow-x-auto rounded-xl border border-brand-green">

                    <table class="min-w-max text-left border-collapse">

                        <thead>
                            <tr
                                class="border-b-2 border-brand-gold uppercase text-[10px] font-black bg-brand-green text-white">
                                <th class="py-3 px-4 text-center whitespace-nowrap min-w-[160px]">N°</th>
                                <th class="py-3 px-4 text-center whitespace-nowrap min-w-[160px]">Código</th>
                                <th class="py-3 px-4 text-center whitespace-nowrap min-w-[160px]">Asesor</th>
                                <th class="py-3 px-4 text-center whitespace-nowrap min-w-[160px]">Total</th>
                                <th
                                    class="py-3 px-4 text-center whitespace-nowrap min-w-[160px] text-white border-l border-brand-gold">
                                    Puntaje</th>

                                @foreach($cursos as $curso)
                                    <th class="py-3 px-4 text-center min-w-[220px] max-w-[220px]">
                                        <div class="line-clamp-2 leading-tight overflow-hidden text-ellipsis">
                                            {{ strtoupper($curso->nombre) }}
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody class="text-gray-700 text-[11px] font-medium font-sans">

                            @forelse($data as $index => $row)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 text-black">

                                    <td class="py-3 px-4 text-center whitespace-nowrap min-w-[160px]">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="py-3 px-4 text-center whitespace-nowrap min-w-[160px]">
                                        {{ $row['personal']->codigo_personal }}
                                    </td>

                                    <td class="py-3 px-4 text-center whitespace-nowrap min-w-[160px]">
                                        {{ $row['personal']->persona->apellido_p }}
                                        {{ $row['personal']->persona->apellido_m }}
                                        {{ $row['personal']->persona->nombre }}
                                    </td>

                                    <td class="py-3 px-4 text-center whitespace-nowrap min-w-[160px]">
                                        <span
                                            class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-[10px] font-black">
                                            {{ $row['total'] }}
                                        </span>
                                    </td>

                                    <td
                                        class="py-3 px-4 text-center whitespace-nowrap min-w-[160px] bg-amber-50 font-black text-amber-800 border-l border-amber-200">
                                        {{ $row['puntaje'] }} pts
                                    </td>

                                    @foreach($cursos as $curso)
                                        <td class="py-3 px-4 text-center whitespace-nowrap min-w-[160px]">
                                            {{ $row['cursos'][$curso->id_curso] ?? 0 }}
                                        </td>
                                    @endforeach

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 5 + count($cursos) }}" class="py-12 text-center text-gray-400 italic">
                                        No hay datos disponibles.
                                    </td>
                                </tr>
                            @endforelse

                            <tr class="bg-brand-green font-sans font-medium text-white">
                                <td colspan="3" class="py-3 px-4 text-right uppercase">
                                    TOTAL GENERAL
                                </td>

                                <td class="py-3 px-4 text-center text-white">
                                    {{ $totalGeneral }}
                                </td>

                                <td class="py-3 px-4 text-center text-white font-black border-l border-brand-gold">
                                    {{ $totalPuntajeGeneral }} pts
                                </td>

                                @foreach($cursos as $curso)
                                    <td class="py-3 px-4 text-center">
                                        {{ $totalesCursos[$curso->id_curso] ?? 0 }}
                                    </td>
                                @endforeach
                            </tr>

                        </tbody>

                    </table>

                </div>
            </div>
        </div>

    </x-layout-dashboard>

</body>

</html>
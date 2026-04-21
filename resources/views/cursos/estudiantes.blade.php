<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes - {{ $curso->nombre }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">

        <x-page-header titulo="Estudiantes de {{ $curso->nombre }}">
        </x-page-header>

        <div class="p-6">
            <div class="bg-white p-6 rounded-sm border-2 border-brand-gold shadow-md">

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-brand-green uppercase tracking-tighter">
                        Lista de Estudiantes
                    </h1>
                </div>

                <div class="overflow-x-auto rounded-xl border-brand-green border-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b-2 border-brand-gold uppercase text-[10px] font-black bg-brand-green text-white">
                                <th class="py-3 px-4 whitespace-nowrap">N°</th>
                                <th class="py-3 px-4 whitespace-nowrap">CI</th>
                                <th class="py-3 px-4 whitespace-nowrap">Ext</th>
                                <th class="py-3 px-4 whitespace-nowrap">Nombre</th>
                                <th class="py-3 px-4 whitespace-nowrap">Apellido P</th>
                                <th class="py-3 px-4 whitespace-nowrap">Apellido M</th>
                                <th class="py-3 px-4 whitespace-nowrap">Teléfono</th>
                                <th class="py-3 px-4 whitespace-nowrap">Correo</th>
                                <th class="py-3 px-4 whitespace-nowrap">Asesor</th>
                                <th class="py-3 px-4 whitespace-nowrap">Fecha de Registro</th>
                                <th class="py-3 px-4 text-center whitespace-nowrap">Estado</th>
                                <th class="py-3 px-4 text-right sticky right-0 bg-brand-green">Operaciones</th>
                            </tr>
                        </thead>

                        <tbody class="text-gray-700 text-[11px] font-medium">
                            @forelse($estudiantes as $index => $e)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors text-black">

                                    <td class="py-3 px-4">{{ $index + 1 }}</td>

                                    <td class="py-3 px-4 whitespace-nowrap">
                                        {{ $e->ci }}
                                    </td>

                                    <td class="py-3 px-4 whitespace-nowrap">
                                        {{ $e->extension_ci }}
                                    </td>

                                    <td class="py-3 px-4 whitespace-nowrap">
                                        {{ $e->nombre }}
                                    </td>

                                    <td class="py-3 px-4">
                                        {{ $e->apellido_p }}
                                    </td>

                                    <td class="py-3 px-4">
                                        {{ $e->apellido_m }}
                                    </td>

                                    <td class="py-3 px-4 font-mono whitespace-nowrap">
                                        {{ $e->telefono_movil ?? '-' }}
                                    </td>

                                    <td class="py-3 px-4 lowercase whitespace-nowrap">
                                        {{ $e->correo_electronico ?? '-' }}
                                    </td>

                                    <td class="py-3 px-4 whitespace-nowrap italic">
                                        {{ $e->asesor_nombre }} {{ $e->asesor_apellido }}
                                    </td>

                                    <td class="py-3 px-4 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($e->fecha_inscripcion)->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="py-3 px-4 text-center">
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase
                                                                {{ $e->estado == 'pre_inscrito' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                                {{ $e->estado == 'inscrito' ? 'bg-green-100 text-green-700' : '' }}">
                                            {{ $e->estado }}
                                        </span>
                                    </td>

                                    <td class="px-4 text-right sticky right-0 bg-white">
                                        <!-- LO DEJAMOS VACÍO COMO PEDISTE -->
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="py-12 text-center text-gray-400 italic">
                                        No hay estudiantes registrados en este curso.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </x-layout-dashboard>
</body>

</html>
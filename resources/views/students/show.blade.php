<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de Estudiante - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">

        <x-page-header titulo="Perfil de Estudiante: {{ $estudiante->nombre }} {{ $estudiante->apellido_p }}" />

        <div class="m-6 border-2 border-brand-green rounded-xl p-7">

            <div class="flex flex-col lg:flex-row gap-4">

                <div class="flex">
                    <div class="p-4 text-xl">
                        <strong>
                            {{ $estudiante->nombre }}
                            {{ $estudiante->apellido_p }}
                            {{ $estudiante->apellido_m }}
                        </strong>

                        <div class="flex items-center">
                            <div class="font-black text-brand-green text-2xl">
                                Estudiante
                            </div>
                            <div class="ml-3">
                                CI: {{ $estudiante->ci }} {{ $estudiante->extension_ci }}
                            </div>
                        </div>

                        <div class="flex flex-wrap p-1 text-[15px] items-center">
                            <img src="/img/phone_icon.png" class="w-6 h-6">
                            <div class="m-2">{{ $estudiante->telefono_movil }}</div>

                            <img src="/img/postcard_icon.png" class="w-6 h-6 ml-4">
                            <div class="m-2">{{ $estudiante->correo_electronico }}</div>

                            <img src="/img/cake_icon.png" class="w-6 h-6 ml-4">
                            <div class="m-2">{{ $estudiante->fecha_nacimiento }}</div>
                        </div>
                    </div>
                </div>

                <div class="ml-auto flex">
                    <div class="m-4">
                        <img src="/img/location_icon.png" class="w-12 h-12">
                    </div>

                    <div class="text-xl ml-3 pt-4">

                        <div>
                            {{ $estudiante->ciudad_residencia ?? 'Sin ciudad' }} -
                            {{ optional($estudiante->departamento)->nombre ?? 'Sin departamento' }}
                        </div>

                        <div>
                            {{ $estudiante->domicilio }}
                        </div>

                    </div>
                </div>

            </div>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">

                <div class="bg-gray-50 p-4 rounded-lg border">
                    <h3 class="font-bold text-brand-green mb-2">Datos Personales</h3>

                    <p><strong>Género:</strong>
                        {{ $estudiante->genero === 'M' ? 'Masculino' : 'Femenino' }}
                    </p>
                    <p><strong>Fecha Nacimiento:</strong> {{ $estudiante->fecha_nacimiento ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border">
                    <h3 class="font-bold text-brand-green mb-2">Formación Académica</h3>

                    <p><strong>Profesión:</strong> {{ $estudiante->profesion->nombre ?? '-' }}</p>
                    <p><strong>Grado:</strong> {{ $estudiante->gradoAcademico->nombre ?? '-' }}</p>
                    <p><strong>Institución:</strong> {{ $estudiante->institucionEgreso->nombre ?? '-' }}</p>
                </div>

            </div>

            <div class="mt-8 bg-white p-6 rounded-xl border border-brand-green/30 shadow-sm">
                <h3 class="text-xl font-bold text-brand-green mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                    Cursos Inscritos ({{ $estudiante->cursos->count() }})
                </h3>

                @if($estudiante->cursos->isEmpty())
                    <div class="p-4 text-center text-gray-500 bg-gray-50 rounded-lg">
                        El estudiante no se encuentra inscrito en ningún curso actualmente.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b-2 border-brand-green/20 text-brand-green bg-gray-50 text-sm">
                                    <th class="p-3">Código</th>
                                    <th class="p-3">Curso</th>
                                    <th class="p-3">Tipo</th>
                                    <th class="p-3">Fechas</th>
                                    <th class="p-3 text-center">Estado del Estudiante</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @foreach($estudiante->cursos as $curso)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="p-3 font-mono font-bold text-gray-700">
                                            {{ $curso->codigo_curso ?? 'S/N' }}
                                        </td>
                                        <td class="p-3 font-medium text-gray-900">
                                            {{ $curso->nombre }}
                                        </td>
                                        <td class="p-3 text-gray-600">
                                            {{ $curso->tipo ?? 'N/A' }}
                                        </td>
                                        <td class="p-3 text-gray-500 text-xs">
                                            {{ $curso->fecha_inicio ? \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') : '-' }}
                                            al
                                            {{ $curso->fecha_fin ? \Carbon\Carbon::parse($curso->fecha_fin)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="p-3 text-center">
                                            @php
                                                $estado = $curso->pivot->estado;
                                                $badgeColor = match (strtolower($estado)) {
                                                    'activo', 'inscrito', 'aprobado' => 'bg-green-100 text-green-800 border-green-300',
                                                    'pendiente', 'pre_inscrito' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                                    'retirado', 'reprobado', 'inactivo' => 'bg-red-100 text-red-800 border-red-300',
                                                    default => 'bg-gray-100 text-gray-800 border-gray-300',
                                                };
                                            @endphp
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $badgeColor }}">
                                                {{ ucfirst($estado ?? 'Sin estado') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>

    </x-layout-dashboard>
</body>

</html>
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

        </div>

    </x-layout-dashboard>
</body>

</html>
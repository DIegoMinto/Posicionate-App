<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de Docente - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">

        <x-page-header titulo="Perfil de Docente: {{ $docente->nombre }} {{ $docente->apellido_p }}">
            <a href="{{ route('teachers.index') }}" class="btn-back">
                ← Volver
            </a>
        </x-page-header>

        <div class="m-6 border-2 border-brand-green rounded-xl p-7">

            <div class="flex flex-col lg:flex-row gap-4">

                <div class="flex">
                    <div class="pt-4">
                        <img src="{{ $docente->fotografia ? $docente->fotografia : asset('img/default-avatar.png') }}"
                            class="w-25 h-25 rounded-xs object-cover border">
                    </div>

                    <div class="p-4 text-xl">
                        <strong>
                            {{ $docente->nombre }}
                            {{ $docente->apellido_p }}
                            {{ $docente->apellido_m }}
                        </strong>

                        <div class="flex items-center">
                            <div class="font-black text-brand-green text-2xl">
                                {{ $docente->profesion->nombre ?? 'Sin Profesión' }}
                            </div>
                            <div class="ml-3">
                                CI: {{ $docente->ci }} {{ $docente->extension_ci }}
                            </div>
                        </div>

                        <div class="flex flex-wrap p-1 text-[15px] items-center">
                            <img src="/img/phone_icon.png" class="w-6 h-6">
                            <div class="m-2">{{ $docente->telefono_movil ?? 'S/N' }}</div>

                            <img src="/img/postcard_icon.png" class="w-6 h-6 ml-4">
                            <div class="m-2">{{ $docente->correo_electronico ?? 'S/C' }}</div>

                            <img src="/img/cake_icon.png" class="w-6 h-6 ml-4">
                            <div class="m-2">{{ $docente->fecha_nacimiento ?? 'S/F' }}</div>
                        </div>
                    </div>
                </div>

                <div class="ml-auto flex">
                    <div class="m-4">
                        <img src="/img/location_icon.png" class="w-12 h-12">
                    </div>
                    <div class="text-xl ml-3 pt-4">
                        <div>
                            {{ $docente->ciudad->nombre ?? 'Sin ciudad' }} -
                            {{ optional($docente->ciudad->departamento)->nombre ?? 'Sin departamento' }}
                        </div>
                        <div>
                            {{ $docente->domicilio ?? 'Sin domicilio registrado' }}
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">

                <div class="bg-gray-50 p-4 rounded-lg border">
                    <h3 class="font-bold text-brand-green mb-2">Datos Personales</h3>
                    <p><strong>Género:</strong> {{ $docente->genero === 'M' ? 'Masculino' : 'Femenino' }}</p>
                    <p><strong>Fecha Nacimiento:</strong> {{ $docente->fecha_nacimiento ?? '-' }}</p>
                    <p><strong>CI:</strong> {{ $docente->ci }} {{ $docente->extension_ci }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border">
                    <h3 class="font-bold text-brand-green mb-2">Formación Académica</h3>
                    <p><strong>Profesión:</strong> {{ $docente->profesion->nombre ?? '-' }}</p>
                    <p><strong>Grado:</strong> {{ $docente->grado->nombre ?? '-' }}</p>
                    <p><strong>Institución Egreso:</strong> {{ $docente->institucion->nombre ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border">
                    <h3 class="font-bold text-brand-green mb-2">Datos Bancarios</h3>
                    <p><strong>Banco:</strong> {{ $docente->institucion_bancaria->nombre ?? '-' }}</p>
                    <p><strong>N° Cuenta:</strong> {{ $docente->numero_cuenta_bancaria ?? '-' }}</p>
                    <p><strong>Emite Factura:</strong> {{ $docente->emite_factura ? 'SÍ' : 'NO' }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border col-span-1 md:col-span-2 lg:col-span-1">
                    <h3 class="font-bold text-brand-green mb-2">Programas, Cursos y Diplomados que le gustaría dar</h3>
                    <p class="whitespace-pre-line">{{ $docente->programas_dar ?? 'No asignados' }}</p>
                </div>

            </div>

            <div class="bg-gray-50 rounded-lg border col-span-1 md:col-span-2 lg:col-span-2 p-4 mt-6">
                <h3 class="font-bold text-brand-green mb-3">Documentación Adjunta</h3>
                <div class="flex flex-wrap gap-4">

                    @if($docente->curriculum)
                        <a href="{{ $docente->curriculum }}" target="_blank"
                            class="bg-brand-green text-white px-4 py-2 rounded text-xs font-bold hover:scale-105 transition uppercase">
                            Ver Curriculum
                        </a>
                    @endif

                    @if($docente->fotocarnet)
                        <a href="{{ $docente->fotocarnet }}" target="_blank"
                            class="bg-brand-gold text-black px-4 py-2 rounded text-xs font-bold hover:scale-105 transition uppercase">
                            Ver Foto Carnet
                        </a>
                    @endif

                    @if($docente->fotografia)
                        <a href="{{ $docente->fotografia }}" target="_blank"
                            class="bg-blue-600 text-white px-4 py-2 rounded text-xs font-bold hover:scale-105 transition uppercase">
                            Ver Fotografía
                        </a>
                    @endif

                </div>
            </div>
        </div>

    </x-layout-dashboard>
</body>

</html>
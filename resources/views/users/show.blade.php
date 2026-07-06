<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de Personal - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">

        <x-page-header
            titulo="Perfil de Usuario: {{ $personal->persona->nombre }} {{ $personal->persona->apellido_p }}" />

        <div class="m-6 border-2 border-brand-green rounded-xl p-7">

            <div class="flex flex-col lg:flex-row gap-4">

                <div class="flex">
                    <div class="pt-4">
                        <img src="{{ $personal->persona->fotografia }}"
                            class="w-25 h-25 rounded-xs border border-brand-green">
                    </div>

                    <div class="p-4 text-xl">
                        <strong>
                            {{ $personal->persona->nombre }}
                            {{ $personal->persona->apellido_p }}
                            {{ $personal->persona->apellido_m }}
                        </strong>

                        <div class="flex items-center">
                            <div class="font-black text-brand-green text-2xl">
                                {{ $personal->cargo_nombre }}
                            </div>
                            <div class="ml-3">
                                CI: {{ $personal->persona->ci }}
                            </div>
                        </div>

                        <div class="flex flex-wrap p-1 text-[15px] items-center">
                            <img src="/img/phone_icon.png" class="w-6 h-6">
                            <div class="m-2">{{ $personal->persona->telefono_movil }}</div>

                            <img src="/img/postcard_icon.png" class="w-6 h-6 ml-4">
                            <div class="m-2">{{ $personal->persona->correo_electronico }}</div>

                            <img src="/img/cake_icon.png" class="w-6 h-6 ml-4">
                            <div class="m-2">{{ $personal->persona->fecha_nacimiento }}</div>
                        </div>
                    </div>
                </div>

                <div class="ml-auto flex">
                    <div class="m-4">
                        <img src="/img/location_icon.png" class="w-12 h-12">
                    </div>
                    <div class="text-xl ml-3 pt-4">
                        <div>
                            {{ optional($personal->persona->ciudad)->nombre ?? 'Sin ciudad' }} -
                            {{ optional(optional($personal->persona->ciudad)->departamento)->nombre ?? 'Sin departamento' }}
                        </div>
                        <div>
                            {{ $personal->persona->domicilio }}
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">

                <div class="bg-gray-50 p-4 rounded-lg border">
                    <h3 class="font-bold text-brand-green mb-2">Datos Personales</h3>

                    <p><strong>Género:</strong>
                        {{ $personal->persona->genero === 'M' ? 'Masculino' : 'Femenino' }}
                    </p>
                    <p><strong>Fecha Nacimiento:</strong> {{ $personal->persona->fecha_nacimiento ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border">
                    <h3 class="font-bold text-brand-green mb-2">Formación Académica</h3>

                    <p><strong>Profesión:</strong> {{ $personal->persona->profesion->nombre ?? '-' }}</p>
                    <p><strong>Grado:</strong> {{ $personal->persona->grado->nombre ?? '-' }}</p>
                    <p><strong>Institución:</strong> {{ $personal->persona->institucion->nombre ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border">
                    <h3 class="font-bold text-brand-green mb-2">Datos Laborales</h3>

                    <p><strong>Cargo:</strong> {{ $personal->cargo }}</p>
                    <p><strong>Sede:</strong> {{ $personal->sede->nombre ?? 'N/A' }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border">
                    <h3 class="font-bold text-brand-green mb-2">Datos Bancarios</h3>

                    <p><strong>N° Cuenta:</strong> {{ $personal->persona->numero_cuenta_bancaria ?? '-' }}</p>
                    <p><strong>Banco:</strong> {{ optional($personal->persona->institucion_bancaria)->nombre ?? '-' }}
                    </p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border">
                    <h3 class="font-bold text-brand-green mb-2">Contactos de Referencia</h3>

                    <p><strong>Ref 1:</strong> {{ $personal->persona->referencia_familiar_1 ?? '-' }}</p>
                    <p><strong>Celular:</strong> {{ $personal->persona->celular_familiar_1 ?? '-' }}</p>

                    <p class="mt-2"><strong>Ref 2:</strong> {{ $personal->persona->referencia_familiar_2 ?? '-' }}</p>
                    <p><strong>Celular:</strong> {{ $personal->persona->celular_familiar_2 ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border">
                    <h3 class="font-bold text-brand-green mb-2">Habilidades</h3>

                    <p><strong>Técnicas:</strong><br>
                        {{ $personal->persona->habilidades_tecnicas ?? '-' }}
                    </p>

                    <p class="mt-2"><strong>Blandas:</strong><br>
                        {{ $personal->persona->habilidades_blandas ?? '-' }}
                    </p>

                </div>
                @if(auth()->user()->id_personal === $personal->id_personal || auth()->user()->rol === 'super_admin')
                    <a href="{{ route('users.edit', $personal->id_personal) }}">
                        <button class="btn-gold">
                            Editar Perfil
                        </button>
                    </a>
                @endif
                <div class="bg-gray-50 p-4 rounded-lg border col-span-1 md:col-span-2 lg:col-span-3">
                    <h3 class="font-bold text-brand-green mb-3">Información Adicional</h3>

                    <div class="flex flex-wrap gap-4">

                        @if($personal->persona->curriculum)
                            <a href="{{ $personal->persona->curriculum }}" target="_blank"
                                class="bg-brand-green text-white px-4 py-2 rounded text-xs font-bold hover:scale-105 transition">
                                Ver Curriculum
                            </a>
                        @endif

                        @if($personal->persona->fotocarnet)
                            <a href="{{ $personal->persona->foto_carnet }}" target="_blank"
                                class="bg-brand-gold text-black px-4 py-2 rounded text-xs font-bold hover:scale-105 transition">
                                Ver Foto Carnet
                            </a>
                        @endif

                        @if($personal->persona->enlace_ubicacion_maps)
                            <a href="{{ $personal->persona->enlace_ubicacion_maps }}" target="_blank"
                                class="bg-blue-600 text-white px-4 py-2 rounded text-xs font-bold hover:scale-105 transition">
                                Ver Ubicación
                            </a>
                        @endif

                    </div>
                </div>

            </div>

        </div>

    </x-layout-dashboard>
</body>

</html>
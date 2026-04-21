<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <x-page-header titulo="Gestión de variables">

        </x-page-header>
        <div class="p-6">
            <div class="bg-white p-6 rounded-sm border-2 border-brand-gold shadow-md">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-brand-green uppercase tracking-tighter">Ingrese al apartado de
                        variables que desee gestionar</h1>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 justify-items-center">
                    <div class="card w-64 p-4 border-2 border-brand-gold relative">
                        <div
                            class="border border-brand-gold rounded-md p-6 flex flex-col items-center text-center h-full">

                            <div class="mb-4">
                                <img src="{{ asset('img/institution_icon.png') }}" alt="Sedes" class="object-contain">
                            </div>

                            <div class="mb-6">
                                <h2 class="text-white text-xl font-bold leading-tight">Gestionar</h2>
                                <p class="text-brand-gold font-black text-2xl">Instituciones</p>
                            </div>

                            <a href="{{route('institutions.index') }}"
                                class="btn-gold uppercase text-xs font-bold py-2 px-6 rounded-md">
                                Acceder
                            </a>
                        </div>
                    </div>
                    <div class="card w-64 p-4 border-2 border-brand-gold relative">
                        <div
                            class="border border-brand-gold rounded-md p-6 flex flex-col items-center text-center h-full">

                            <div class="mb-4">
                                <img src="{{ asset('img/sede_icon.png') }}" alt="Sedes" class="object-contain">
                            </div>

                            <div class="mb-6">
                                <h2 class="text-white text-xl font-bold leading-tight">Gestionar</h2>
                                <p class="text-brand-gold font-black text-2xl">Sedes</p>
                            </div>

                            <a href="{{route('sedes.index') }}"
                                class="btn-gold uppercase text-xs font-bold py-2 px-6 rounded-md">
                                Acceder
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-layout-dashboard>
</body>

</html>
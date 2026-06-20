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

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 justify-items-center">

                    <div class="card w-64 p-4 border-2 border-brand-gold relative">
                        <div
                            class="border border-brand-gold rounded-md p-6 flex flex-col items-center text-center h-full justify-between">
                            <div class="mb-4 h-16 flex items-center justify-center w-full">
                                <img src="{{ asset('img/institution_icon.png') }}" alt="Instituciones"
                                    class="h-12 object-contain">
                            </div>
                            <div class="mb-6">
                                <h2 class="text-white text-xl font-bold leading-tight">Gestionar</h2>
                                <p class="text-brand-gold font-black text-2xl">Instituciones</p>
                            </div>
                            <a href="{{ route('institutions.index') }}"
                                class="btn-gold uppercase text-xs font-bold py-2 px-6 rounded-md w-full">
                                Acceder
                            </a>
                        </div>
                    </div>

                    <div class="card w-64 p-4 border-2 border-brand-gold relative">
                        <div
                            class="border border-brand-gold rounded-md p-6 flex flex-col items-center text-center h-full justify-between">
                            <div class="mb-4 h-16 flex items-center justify-center w-full">
                                <img src="{{ asset('img/sede_icon.png') }}" alt="Sedes" class="h-12 object-contain">
                            </div>
                            <div class="mb-6">
                                <h2 class="text-white text-xl font-bold leading-tight">Gestionar</h2>
                                <p class="text-brand-gold font-black text-2xl">Sedes</p>
                            </div>
                            <a href="{{ route('sedes.index') }}"
                                class="btn-gold uppercase text-xs font-bold py-2 px-6 rounded-md w-full">
                                Acceder
                            </a>
                        </div>
                    </div>

                    <div class="card w-64 p-4 border-2 border-brand-gold relative">
                        <div
                            class="border border-brand-gold rounded-md p-6 flex flex-col items-center text-center h-full justify-between">
                            <div class="mb-4 h-16 flex items-center justify-center w-full">
                                <div
                                    class="bg-brand-green/10 p-2 rounded-full flex items-center justify-center w-14 h-14">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-8 h-8 text-brand-gold">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mb-6">
                                <h2 class="text-white text-xl font-bold leading-tight">Gestionar</h2>
                                <p class="text-brand-gold font-black text-2xl">Contraseñas</p>
                            </div>
                            <a href="{{ route('contrasenas.index') }}"
                                class="btn-gold uppercase text-xs font-bold py-2 px-6 rounded-md w-full">
                                Acceder
                            </a>
                        </div>
                    </div>

                    <div class="card w-64 p-4 border-2 border-brand-gold relative">
                        <div
                            class="border border-brand-gold rounded-md p-6 flex flex-col items-center text-center h-full justify-between">
                            <div class="mb-4 h-16 flex items-center justify-center w-full">
                                <div
                                    class="bg-brand-green/10 p-2 rounded-full flex items-center justify-center w-14 h-14">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-8 h-8 text-brand-gold">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mb-6">
                                <h2 class="text-white text-xl font-bold leading-tight">Gestionar</h2>
                                <p class="text-brand-gold font-black text-2xl">Áreas</p>
                            </div>
                            <a href="{{ route('areas.index') }}"
                                class="btn-gold uppercase text-xs font-bold py-2 px-6 rounded-md w-full">
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
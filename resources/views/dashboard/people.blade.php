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
        <div class="bg-white p-6 rounded-sm border-2 border-brand-gold shadow-md">
            <h1 class="text-2xl font-bold text-brand-green mb-4">Gestión de Estudiantes</h1>
        </div>
    </x-layout-dashboard>
</body>

</html>
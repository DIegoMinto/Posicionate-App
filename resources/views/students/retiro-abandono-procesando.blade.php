<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generando documento</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="bg-white rounded-xl shadow-lg p-8 text-center max-w-md mx-4">
        <div class="animate-spin rounded-full h-12 w-12 border-4 border-gray-200 border-t-brand-green mx-auto mb-5">
        </div>

        <h1 class="text-lg font-bold text-brand-green">
            Generando documento PDF
        </h1>

        <p class="text-sm text-gray-500 mt-2">
            El registro fue guardado. Estamos preparando el documento.
        </p>
    </div>

    <script>
        const urlEstudiantes = @json($urlEstudiantes);
        const urlPdf = @json($urlPdf);

        if (window.opener && !window.opener.closed) {
            window.opener.location.href = urlEstudiantes;
        }

        window.location.replace(urlPdf);
    </script>
</body>

</html>
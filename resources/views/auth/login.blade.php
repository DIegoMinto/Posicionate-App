<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen w-full flex items-center justify-center bg-cover bg-center bg-no-repeat p-4"
    style="background-image: url('/img/loginbackground.jpg');">

    <div class="relative z-10 flex items-stretch max-w-4xl shadow-2xl overflow-hidden rounded-3xl">

        <div class="bg-white flex items-center justify-center border-y border-l border-white/20 hidden md:flex">
            <img src="/img/mascota.png" alt="Mascota" class="w-80 h-auto object-contain">
        </div>

        <div id="authentication-modal"
            class="w-full max-w-md bg-brand-green p-6 md:p-10 text-white border border-white/10">

            <div class="pb-6 border-b border-white/20 mb-6">
                <img src="/img/logoblanco.png" alt="Logo" class="mx-auto h-12 object-contain">
            </div>

            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold tracking-[0.2em] uppercase">
                    Bienvenido
                </h3>
            </div>
            @if ($errors->any())
                <div class="mb-4 text-red-500">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <form action="{{route('login.verify')}}" method="post" class="space-y-5">
                @csrf <div>
                    <label for="user" class="block mb-2 text-l font-semibold tracking-wide">Usuario</label>
                    <input type="text" name="user" id="user" class="form-input-pill" placeholder="Ingresa tu usuario"
                        required />
                </div>

                <div>
                    <label for="password" class="block mb-2 text-l font-semibold tracking-wide">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-input-pill"
                        placeholder="Ingresa tu contraseña" required />
                </div>

                <div class=" pt-2">
                    <div class="btn-container-center">
                        <button type="submit" class="btn-gold w-full md:w-auto">
                            INGRESAR
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</body>

</html>


</html>
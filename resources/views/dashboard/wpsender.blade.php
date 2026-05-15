<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WP Sender - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <x-page-header titulo="WP Sender"></x-page-header>

        <div x-data="wp()" x-init="init()" class="w-full mx-auto p-6">

            <div x-show="status !== 'open'" class="bg-white p-2 rounded-xl shadow text-center">

                <h2 class="font-bold mb-4">Escanea el QR</h2>

                <template x-if="qr">
                    <img :src="qr" class="mx-auto w-64 h-64">
                </template>

                <template x-if="!qr">
                    <p class="text-red-500">Cargando QR...</p>
                </template>

            </div>

            <div x-show="status === 'open'" class="">

                <div class="bg-white p-6 rounded-sm border-2 border-brand-gold shadow-md">

                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-brand-green uppercase tracking-tighter">
                            Herramientas de WhatsApp
                        </h1>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 justify-items-center">

                        <div class="card w-64 p-4 border-2 border-brand-gold relative">
                            <div
                                class="border border-brand-gold rounded-md p-6 flex flex-col items-center text-center h-full">

                                <div class="mb-4">
                                    <img src="{{ asset('img/send_icon.png') }}" class="object-contain">
                                </div>

                                <div class="mb-6">
                                    <h2 class="text-white text-xl font-bold leading-tight">Enviar</h2>
                                    <p class="text-brand-gold font-black text-2xl">Masivos</p>
                                </div>

                                <a href="{{ route('wpsender.send') }}"
                                    class="btn-gold uppercase text-xs font-bold py-2 px-6 rounded-md">
                                    Acceder
                                </a>

                            </div>
                        </div>

                        <div class="card w-64 p-4 border-2 border-brand-gold relative">
                            <div
                                class="border border-brand-gold rounded-md p-6 flex flex-col items-center text-center h-full">

                                <div class="mb-4">
                                    <img src="{{ asset('img/extract_icon.png') }}" class="object-contain">
                                </div>

                                <div class="mb-6">
                                    <h2 class="text-white text-xl font-bold leading-tight">Extraer</h2>
                                    <p class="text-brand-gold font-black text-2xl">Contactos</p>
                                </div>

                                <a href="#" class="btn-gold uppercase text-xs font-bold py-2 px-6 rounded-md">
                                    Acceder
                                </a>

                            </div>
                        </div>

                    </div>
                </div>

            </div>


        </div>

        <script>
            function wp() {
                return {
                    instance: @json($usuario->codigo_personal),
                    qr: null,
                    status: 'loading',

                    async init() {
                        await this.getQR();
                        this.checkLoop();
                    },

                    async getQR() {
                        try {
                            const res = await fetch(`/whatsapp/qr`);
                            const data = await res.json();

                            console.log("RESPUESTA COMPLETA:", data);

                            this.qr = data.data?.base64 || data.data?.qrcode?.base64 || null;

                            console.log("QR FINAL:", this.qr);

                        } catch (e) {
                            console.error(e);
                        }
                    },

                    async checkStatus() {
                        try {
                            const res = await fetch(`/whatsapp/status/${this.instance}`);
                            const data = await res.json();

                            this.status = data.instance?.state || data.state || 'closed';

                            console.log("STATUS:", this.status);

                        } catch (e) { }
                    },

                    checkLoop() {
                        setInterval(() => {
                            this.checkStatus();
                        }, 5000);
                    }
                }
            }

        </script>
    </x-layout-dashboard>

</body>

</html>
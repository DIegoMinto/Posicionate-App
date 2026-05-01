<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WP Sender - Posicionate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layout-dashboard :usuario="$usuario">
        <x-page-header titulo="Envío Masivo"></x-page-header>

        <div x-data="sender()" class="p-6 max-w-3xl mx-auto">

            <div class="bg-white p-6 rounded-sm border-2 border-brand-gold shadow-md">

                <h2 class="text-xl font-bold text-brand-green mb-4 uppercase">
                    Enviar mensajes por WhatsApp
                </h2>
                <label class="form-label-bold text-brand-green">Formato de números:</label>
                <select x-model="formato" class="form-select-pill border border-brand-green w-100">
                    <option value="auto">Auto (recomendado)</option>
                    <option value="con_plus">+591 Número (Ej. +591 60312310)</option>
                    <option value="sin_codigo">Número (Ej. 60312310)</option>
                    <option value="con_codigo">591 Número (Ej. 591 60312310)</option>
                </select>
                <br>
                <br>
                <label for=" " class="form-label-bold text-brand-green">Colocar Números</label>
                <textarea x-model="numeros" placeholder="Ej: Pon los números según el formato seleccionado"
                    class="w-full border border-brand-green rounded-s p-3 mb-4 text-sm resize-none"></textarea>

                <div class="mb-4">
                    <label>Adjuntar archivo (JPG, PNG, PDF)</label>
                    <br>
                    <input type="file" @change="handleFile" class="mt-2 p-2 cursor-pointer btn-gold w-48" />
                </div>
                <label for=" " class="form-label-bold text-brand-green">Contenido del Mensaje</label>
                <textarea x-model="mensaje" placeholder="Escribe tu mensaje..."
                    class="w-full border border-brand-green rounded-sm p-3 mb-4 text-sm h-50 resize-none"></textarea>

                <select x-model="modo" class="form-select-pill border border-brand-green w-100">
                    <option value="junto">Enviar mensaje y archivo juntos</option>
                    <option value="separado">Enviar mensaje y archivo separados</option>
                </select>
                <br>
                <br>
                <label for=" " class="form-label-bold text-brand-green">Intérvalo de envíos (Segundos)</label>
                <div class="flex items-center gap-4 mb-4 mt-2 text-brand-green font-sans">
                    <div>
                        <label class="text-xs block">Mínimo</label>
                        <input type="number" x-model="delayMin" class="border p-2 w-20 rounded" min="1">
                    </div>
                    <div class="font-bold mt-4">-</div>
                    <div>
                        <label class="text-xs block">Máximo</label>
                        <input type="number" x-model="delayMax" class="border p-2 w-20 rounded" min="1">
                    </div>
                </div>

                <button @click="enviar" class="btn-gold px-6 py-2 text-xs uppercase font-bold">
                    Enviar
                </button>

            </div>

            <div
                class="mt-6 bg-white text-brand-green p-4 h-64 overflow-y-auto font-mono text-xs rounded border-2 border-brand-gold">
                <h2 class="text-xl font-sans font-bold text-brand-green mb-4 uppercase">
                    Estado de envío
                </h2>
                <template x-for="log in logs">
                    <div x-html="log"></div>
                </template>
            </div>

        </div>

        <script>
            function sender() {
                return {
                    numeros: '',
                    mensaje: '',
                    formato: 'auto',
                    delayMin: 3,
                    delayMax: 7,
                    modo: 'junto',
                    file: null,
                    logs: [],

                    handleFile(event) {
                        this.file = event.target.files[0];
                        this.logs.push(`📂 Archivo seleccionado: ${this.file.name}`);
                    },

                    normalizarNumero(num) { // ✅ AQUÍ BIEN
                        let limpio = num.replace(/\D/g, '');

                        if (this.formato === 'sin_codigo') {
                            return '591' + limpio;
                        }

                        if (this.formato === 'con_plus' || this.formato === 'con_codigo') {
                            return limpio.startsWith('591') ? limpio : '591' + limpio;
                        }

                        // AUTO
                        if (limpio.length === 8) {
                            return '591' + limpio;
                        }

                        if (limpio.startsWith('591')) {
                            return limpio;
                        }

                        return limpio;
                    },

                    async enviar() {
                        if (!this.numeros || !this.mensaje) {
                            alert("Faltan datos");
                            return;
                        }

                        this.logs = [];

                        const lista = this.numeros
                            .split(/[\n,]+/) // 🔥 mejora: acepta comas y saltos
                            .map(n => n.trim())
                            .filter(n => n !== '');

                        for (let i = 0; i < lista.length; i++) {
                            let num = lista[i];
                            let limpio = this.normalizarNumero(num);

                            this.logs.push(`📤 [${i + 1}/${lista.length}] Enviando a ${limpio}...`);

                            try {
                                const formData = new FormData();
                                formData.append('numeros', limpio);
                                formData.append('mensaje', this.mensaje);
                                formData.append('modo', this.modo);

                                if (this.file) {
                                    formData.append('file', this.file);
                                }

                                const response = await fetch("{{ url('/whatsapp/send') }}", {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: formData
                                });

                                const data = await response.json();

                                if (data.ok) {
                                    this.logs.push(`✅ OK: ${limpio}`);
                                } else {
                                    this.logs.push(`❌ Error en ${limpio}: ${JSON.stringify(data)}`);
                                }

                            } catch (e) {
                                this.logs.push(`⚠️ Error técnico: ${e.message}`);
                            }

                            if (i < lista.length - 1) {
                                const min = parseInt(this.delayMin);
                                const max = parseInt(this.delayMax);

                                const waitTime = Math.floor(Math.random() * (max - min + 1) + min);

                                this.logs.push(`⏳ Esperando ${waitTime} segundos...`);
                                await new Promise(r => setTimeout(r, waitTime * 1000));
                            }
                        }

                        this.logs.push(`🏁 Proceso finalizado`);
                    }
                }
            }


        </script>
    </x-layout-dashboard>

</body>

</html>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

                                <button @click="openExtractionModal()"
                                    class="btn-gold uppercase text-xs font-bold py-2 px-6 rounded-md target-btn">
                                    Acceder
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div x-show="modalOpen"
                class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-black bg-opacity-50 p-4"
                x-transition style="display: none;">

                <div class="bg-white rounded-lg shadow-xl w-full max-w-lg overflow-hidden border border-brand-gold">
                    <div class="bg-gray-100 p-4 border-b border-brand-gold flex justify-between items-center">
                        <h3 class="text-lg font-bold text-brand-green uppercase">Extraer Contactos</h3>
                        <button @click="modalOpen = false"
                            class="text-gray-500 hover:text-red-500 text-xl font-bold">&times;</button>
                    </div>

                    <div class="flex border-b border-gray-200">
                        <button @click="switchTab('groups')"
                            :class="activeTab === 'groups' ? 'border-b-2 border-brand-gold text-brand-green font-bold' : 'text-gray-400'"
                            class="flex-1 py-2 text-sm uppercase">
                            Por Grupo
                        </button>
                        <button @click="switchTab('labels')"
                            :class="activeTab === 'labels' ? 'border-b-2 border-brand-gold text-brand-green font-bold' : 'text-gray-400'"
                            class="flex-1 py-2 text-sm uppercase">
                            Por Etiqueta
                        </button>
                    </div>

                    <div class="p-6">

                        <div x-show="extractionMessage"
                            :class="extractionSuccess ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'"
                            class="mb-4 p-3 rounded border text-sm" x-text="extractionMessage">
                        </div>

                        <div x-show="activeTab === 'groups'">
                            <div x-show="loadingGroups" class="text-center text-gray-500 py-4">
                                Cargando grupos...
                            </div>

                            <div x-show="!loadingGroups && groups.length === 0" class="text-center text-gray-500 py-4">
                                No se encontraron grupos para esta instancia.
                            </div>

                            <div x-show="!loadingGroups && groups.length > 0"
                                class="max-h-64 overflow-y-auto space-y-2">
                                <template x-for="group in groups" :key="group.id">
                                    <label
                                        class="flex items-center gap-2 p-2 border rounded hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" :value="group.id" x-model="selectedGroups">
                                        <span class="text-sm text-gray-800" x-text="group.subject"></span>
                                        <span class="text-xs text-gray-400 ml-auto"
                                            x-text="'(' + group.size + ' miembros)'"></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <div x-show="activeTab === 'labels'">
                            <div x-show="loadingLabels" class="text-center text-gray-500 py-4">
                                Cargando etiquetas...
                            </div>

                            <div x-show="!loadingLabels && labels.length === 0" class="text-center text-gray-500 py-4">
                                No se encontraron etiquetas en WhatsApp Business.
                            </div>

                            <div x-show="!loadingLabels && labels.length > 0"
                                class="max-h-64 overflow-y-auto space-y-2">
                                <template x-for="label in labels" :key="label.id">
                                    <label
                                        class="flex items-center gap-2 p-2 border rounded hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" :value="label.id" x-model="selectedLabels">
                                        <span class="w-3 h-3 rounded-full inline-block"
                                            :style="'background-color:' + (label.color || '#ccc')"></span>
                                        <span class="text-sm text-gray-800" x-text="label.name"></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                    </div>

                    <div class="bg-gray-50 p-4 border-t border-gray-200 flex justify-end space-x-2">
                        <button @click="modalOpen = false"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded text-xs uppercase font-bold hover:bg-gray-400">
                            Cancelar
                        </button>

                        <button x-show="activeTab === 'groups'" @click="processExcelExport()"
                            :disabled="selectedGroups.length === 0 || processingExtraction"
                            :class="selectedGroups.length === 0 || processingExtraction ? 'opacity-50 cursor-not-allowed' : 'hover:bg-opacity-90'"
                            class="px-4 py-2 bg-blue-600 text-white rounded text-xs uppercase font-bold flex items-center">
                            <span x-show="processingExtraction" class="mr-2 animate-spin">⏳</span>
                            <span x-text="processingExtraction ? 'Generando...' : 'Descargar Excel'"></span>
                        </button>

                        <button x-show="activeTab === 'labels'" @click="processLabelExcelExport()"
                            :disabled="selectedLabels.length === 0 || processingExtraction"
                            :class="selectedLabels.length === 0 || processingExtraction ? 'opacity-50 cursor-not-allowed' : 'hover:bg-opacity-90'"
                            class="px-4 py-2 bg-blue-600 text-white rounded text-xs uppercase font-bold flex items-center">
                            <span x-show="processingExtraction" class="mr-2 animate-spin">⏳</span>
                            <span x-text="processingExtraction ? 'Generando...' : 'Descargar Excel'"></span>
                        </button>
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

                    modalOpen: false,
                    loadingGroups: false,
                    processingExtraction: false,
                    groups: [],
                    selectedGroups: [],
                    extractionMessage: '',
                    extractionSuccess: true,
                    activeTab: 'groups',
                    labels: [],
                    selectedLabels: [],
                    loadingLabels: false,

                    async init() {
                        await this.getQR();
                        this.checkLoop();
                    },

                    async getQR() {
                        try {
                            const res = await fetch(`/whatsapp/qr`);
                            const data = await res.json();
                            this.qr = data.data?.base64 || data.data?.qrcode?.base64 || null;
                        } catch (e) {
                            console.error(e);
                        }
                    },

                    async checkStatus() {
                        try {
                            const res = await fetch(`/whatsapp/status/${this.instance}`);
                            const data = await res.json();
                            this.status = data.instance?.state || data.state || 'closed';
                        } catch (e) { }
                    },

                    checkLoop() {
                        setInterval(() => {
                            this.checkStatus();
                        }, 5000);
                    },

                    async openExtractionModal() {
                        this.modalOpen = true;
                        this.loadingGroups = true;
                        this.extractionMessage = '';
                        this.selectedGroups = [];
                        this.groups = [];

                        try {
                            const res = await fetch('/whatsapp/groups');
                            if (!res.ok) throw new Error('Error de comunicación con Laravel');

                            const data = await res.json();
                            console.log("Grupos cargados en Blade:", data);

                            this.groups = Array.isArray(data) ? data : [];

                        } catch (e) {
                            this.extractionSuccess = false;
                            this.extractionMessage = 'Error al renderizar el listado de grupos.';
                            console.error(e);
                        } finally {
                            this.loadingGroups = false;
                        }
                    },

                    async processExtraction() {
                        if (this.selectedGroups.length === 0) return;

                        this.processingExtraction = true;
                        this.extractionMessage = '';
                        let totalSaved = 0;
                        let errors = 0;

                        for (const groupJid of this.selectedGroups) {
                            try {
                                const res = await fetch('/whatsapp/groups/extract', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                    },
                                    body: JSON.stringify({ groupJid: groupJid })
                                });

                                const result = await res.json();
                                if (res.ok && result.ok) {
                                    totalSaved += result.saved;
                                } else {
                                    errors++;
                                }
                            } catch (e) {
                                errors++;
                                console.error("Error al extraer grupo: " + groupJid, e);
                            }
                        }

                        this.processingExtraction = false;
                        this.extractionSuccess = errors === 0;

                        if (errors === 0) {
                            this.extractionMessage = `¡Éxito completo! Se extrajeron ${totalSaved} contactos de los grupos seleccionados.`;
                            this.selectedGroups = [];
                        } else if (totalSaved > 0) {
                            this.extractionMessage = `Extracción parcial: se guardaron ${totalSaved} contactos, pero fallaron ${errors} grupo(s).`;
                        } else {
                            this.extractionMessage = 'No se pudo completar la extracción de ninguno de los grupos seleccionados.';
                        }
                    },

                    async processExcelExport() {
                        if (this.selectedGroups.length === 0) return;

                        this.processingExtraction = true;
                        this.extractionMessage = '';

                        try {
                            const res = await fetch('/whatsapp/groups/export-excel', {
                                method: 'POST',
                                credentials: 'same-origin',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                },
                                body: JSON.stringify({ groupJids: this.selectedGroups })
                            });

                            if (!res.ok) {
                                const err = await res.json();
                                throw new Error(err.error || 'Error al generar el Excel');
                            }

                            const blob = await res.blob();
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = 'contactos.xlsx';
                            document.body.appendChild(a);
                            a.click();
                            a.remove();
                            window.URL.revokeObjectURL(url);

                            this.extractionSuccess = true;
                            this.extractionMessage = '¡Excel descargado con éxito!';

                        } catch (e) {
                            this.extractionSuccess = false;
                            this.extractionMessage = e.message;
                        } finally {
                            this.processingExtraction = false;
                        }
                    },
                    switchTab(tab) {
                        this.activeTab = tab;
                        this.extractionMessage = '';
                        if (tab === 'labels' && this.labels.length === 0) {
                            this.loadLabels();
                        }
                    },

                    async loadLabels() {
                        this.loadingLabels = true;
                        try {
                            const res = await fetch('/whatsapp/labels');
                            if (!res.ok) throw new Error('Error al cargar etiquetas');
                            const data = await res.json();
                            this.labels = Array.isArray(data) ? data : [];
                        } catch (e) {
                            this.extractionSuccess = false;
                            this.extractionMessage = 'Error al cargar etiquetas.';
                            console.error(e);
                        } finally {
                            this.loadingLabels = false;
                        }
                    },

                    async processLabelExcelExport() {
                        if (this.selectedLabels.length === 0) return;

                        this.processingExtraction = true;
                        this.extractionMessage = '';

                        try {
                            const res = await fetch('/whatsapp/labels/export-excel', {
                                method: 'POST',
                                credentials: 'same-origin',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                },
                                body: JSON.stringify({ labelIds: this.selectedLabels })
                            });

                            if (!res.ok) {
                                const err = await res.json();
                                throw new Error(err.error || 'Error al generar el Excel');
                            }

                            const blob = await res.blob();
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = 'contactos_etiquetas.xlsx';
                            document.body.appendChild(a);
                            a.click();
                            a.remove();
                            window.URL.revokeObjectURL(url);

                            this.extractionSuccess = true;
                            this.extractionMessage = '¡Excel descargado con éxito!';

                        } catch (e) {
                            this.extractionSuccess = false;
                            this.extractionMessage = e.message;
                        } finally {
                            this.processingExtraction = false;
                        }
                    }
                }
            }
        </script>
    </x-layout-dashboard>
</body>

</html>
<div class="space-y-4">
    <div>
        <label class="block text-sm font-bold text-gray-700">Números de destino (separados por coma):</label>
        <textarea x-model="numeros" class="w-full p-3 border rounded-lg focus:ring-brand-gold focus:border-brand-gold"
            rows="3" placeholder="59170000000, 59171111111"></textarea>
    </div>

    <div>
        <label class="block text-sm font-bold text-gray-700">Cargar Archivo (Imagen/PDF):</label>
        <input type="file" @change="handleFile"
            class="w-full p-2 border-2 border-dashed rounded-lg bg-gray-50 cursor-pointer">
    </div>

    <div>
        <label class="block text-sm font-bold text-gray-700">Mensaje o Pie de foto:</label>
        <textarea x-model="mensaje" class="w-full p-3 border rounded-lg focus:ring-brand-gold focus:border-brand-gold"
            rows="4" placeholder="Escribe tu mensaje aquí..."></textarea>
    </div>

    <button @click="iniciarEnvio" :disabled="enviando"
        class="w-full py-4 bg-brand-gold text-black font-black rounded-xl hover:bg-yellow-600 disabled:opacity-50 transition-all uppercase">
        <span x-show="!enviando">🚀 Iniciar Envío Masivo</span>
        <span x-show="enviando">⏳ Procesando Envío...</span>
    </button>

    <div class="mt-6 bg-slate-900 text-green-400 p-4 rounded-lg font-mono text-xs h-48 overflow-y-auto custom-scrollbar"
        id="log-container">
        <template x-for="log in logs">
            <div class="mb-1">
                <span class="text-blue-300" x-text="log.time"></span>
                <span x-html="log.text"></span>
            </div>
        </template>
        <div x-show="logs.length === 0" class="text-gray-500 italic">Esperando acción...</div>
    </div>
</div>
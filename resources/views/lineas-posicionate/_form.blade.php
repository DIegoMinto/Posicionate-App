@php
    $editando = isset($linea);
    $pertenenciaTipo = old('pertenencia_tipo', $linea->pertenencia_tipo ?? 'personal');
@endphp

<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <div>
        <label for="celular" class="mb-2 block text-sm font-semibold text-[#072e2c]">
            Número de celular *
        </label>
        <input id="celular" name="celular" type="text" maxlength="20"
            value="{{ old('celular', $linea->celular ?? '') }}" required placeholder="Ej. 70000000"
            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-[#ccb463] focus:ring-2 focus:ring-[#ccb463]/20">
        @error('celular') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label for="pertenencia_tipo" class="mb-2 block text-sm font-semibold text-[#072e2c]">
            Perteneciente a *
        </label>
        <select id="pertenencia_tipo" name="pertenencia_tipo" required
            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:border-[#ccb463] focus:ring-2 focus:ring-[#ccb463]/20">
            <option value="personal" @selected($pertenenciaTipo === 'personal')>Personal</option>
            <option value="area" @selected($pertenenciaTipo === 'area')>Área o departamento</option>
        </select>
    </div>

    <div id="campo-personal" class="md:col-span-2">
        <label for="id_persona" class="mb-2 block text-sm font-semibold text-[#072e2c]">
            Seleccionar personal *
        </label>
        <select id="id_persona" name="id_persona"
            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:border-[#ccb463] focus:ring-2 focus:ring-[#ccb463]/20">
            <option value="">Selecciona una persona</option>
            @foreach ($personas as $persona)
                <option value="{{ $persona->id_persona }}" @selected((string) old('id_persona', $linea->id_persona ?? '') === (string) $persona->id_persona)>
                    {{ trim($persona->nombre . ' ' . $persona->apellido_p . ' ' . $persona->apellido_m) }}
                </option>
            @endforeach
        </select>
        @error('id_persona') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div id="campo-area" class="md:col-span-2">
        <label for="pertenencia_texto" class="mb-2 block text-sm font-semibold text-[#072e2c]">
            Nombre del área o departamento *
        </label>
        <input id="pertenencia_texto" name="pertenencia_texto" type="text" maxlength="255"
            value="{{ old('pertenencia_texto', $linea->pertenencia_texto ?? '') }}"
            placeholder="Ej. OFICINA, ACADÉMICO, ADMINISTRACIÓN"
            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#ccb463] focus:ring-2 focus:ring-[#ccb463]/20">
        @error('pertenencia_texto') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="correo" class="mb-2 block text-sm font-semibold text-[#072e2c]">
            Correo Posicionate
        </label>
        <input id="correo" name="correo" type="email" maxlength="255" value="{{ old('correo', $linea->correo ?? '') }}"
            placeholder="correo@posicionate.org"
            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#ccb463] focus:ring-2 focus:ring-[#ccb463]/20">
        @error('correo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="contrasena" class="mb-2 block text-sm font-semibold text-[#072e2c]">
            Contraseña del correo
        </label>
        <input id="contrasena" name="contrasena" type="password" maxlength="1000" autocomplete="new-password"
            placeholder="{{ $editando ? 'Dejar vacío para conservar la actual' : 'Opcional' }}"
            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#ccb463] focus:ring-2 focus:ring-[#ccb463]/20">
        @error('contrasena') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="fecha_ultima_recarga" class="mb-2 block text-sm font-semibold text-[#072e2c]">
            Fecha de última recarga
        </label>
        <input id="fecha_ultima_recarga" name="fecha_ultima_recarga" type="date"
            value="{{ old('fecha_ultima_recarga', isset($linea) ? $linea->fecha_ultima_recarga?->format('Y-m-d') : '') }}"
            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-[#ccb463] focus:ring-2 focus:ring-[#ccb463]/20">
        @error('fecha_ultima_recarga') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        <p class="mt-1 text-xs text-gray-500">La siguiente recarga se calcula automáticamente a 30 días.</p>
    </div>

    @if ($editando)
        <div>
            <label class="mb-2 block text-sm font-semibold text-[#072e2c]">
                Siguiente recarga calculada
            </label>
            <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-600">
                {{ $linea->fecha_siguiente_recarga?->format('d/m/Y') ?? 'Se calculará al guardar' }}
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selector = document.getElementById('pertenencia_tipo');
        const campoPersonal = document.getElementById('campo-personal');
        const campoArea = document.getElementById('campo-area');
        const persona = document.getElementById('id_persona');
        const area = document.getElementById('pertenencia_texto');

        function actualizarCampos() {
            const esPersonal = selector.value === 'personal';

            campoPersonal.hidden = !esPersonal;
            campoArea.hidden = esPersonal;

            persona.required = esPersonal;
            area.required = !esPersonal;

            if (esPersonal) {
                area.value = '';
            } else {
                persona.value = '';
            }
        }

        selector.addEventListener('change', actualizarCampos);
        actualizarCampos();
    });
</script>
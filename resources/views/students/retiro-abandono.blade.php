<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de retiro o abandono</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans text-gray-800">
    <x-layout-dashboard :usuario="$usuario">
        <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">

            <div class="mb-6 overflow-hidden rounded-2xl bg-brand-green text-white shadow-lg">
                <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl text-brand-green">
                            <img src="{{ asset('img/menu_icon.png') }}" alt="Menú" class="h-12 w-12 object-contain">
                        </div>

                        <div>
                            <p class="mb-1 text-xs font-sans font-bold uppercase tracking-[0.2em] text-brand-gold">
                                POSICIONATE S.R.L.
                            </p>
                            <h1 class="text-xl font-bold sm:text-2xl">
                                Registro de retiro o abandono
                            </h1>
                            <p class="mt-2 text-sm text-white/75">
                                Formación Continua · Gestión de inscripciones
                            </p>
                        </div>
                    </div>

                    <div class="rounded-lg border border-brand-gold px-4 py-3">
                        <p class="text-xs uppercase tracking-wider text-white font-sans">Estudiante</p>
                        <p class="mt-1 font-semibold">
                            {{ trim($estudiante->nombre . ' ' . $estudiante->apellido_p . ' ' . $estudiante->apellido_m) }}
                        </p>
                        <p class="mt-1 text-xs text-white font-sans">
                            CI: {{ $estudiante->ci }}
                        </p>
                    </div>
                </div>

                <div class="h-1.5 bg-brand-gold"></div>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <div class="mb-2 font-bold">No se pudo procesar el formulario:</div>
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('students.retiro-abandono.store', $estudiante->id_estudiante) }}"
                class="space-y-6">
                @csrf

                <input type="hidden" name="id_curso" value="{{ $curso->id_curso }}">

                <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-5 py-4 sm:px-7">
                        <h2 class="font-bold text-brand-green">Información del registro</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Selecciona el tipo de trámite y verifica los datos del participante.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-7">
                        <div class="sm:col-span-2">
                            <label for="tipo" class="mb-2 block text-sm font-semibold text-brand-green">
                                Tipo de registro <span class="text-red-500">*</span>
                            </label>
                            <select id="tipo" name="tipo" required class="form-select-pill-2">
                                <option value="retiro" @selected(old('tipo', 'retiro') === 'retiro')>
                                    Retiro de diplomado (con justificación)
                                </option>
                                <option value="abandono" @selected(old('tipo') === 'abandono')>
                                    Abandono de diplomado (sin justificación)
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-brand-green">
                                Nombre completo
                            </label>
                            <input
                                value="{{ trim($estudiante->nombre . ' ' . $estudiante->apellido_p . ' ' . $estudiante->apellido_m) }}"
                                readonly class="form-input-pill-2 cursor-not-allowed bg-gray-50">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-brand-green">
                                Cédula de identidad
                            </label>
                            <input value="{{ $estudiante->ci }}" readonly
                                class="form-input-pill-2 cursor-not-allowed bg-gray-50">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-brand-green">
                                Teléfono / celular
                            </label>
                            <input value="{{ $estudiante->telefono_movil }}" readonly
                                class="form-input-pill-2 cursor-not-allowed bg-gray-50">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-brand-green">
                                Programa / diplomado
                            </label>
                            <input value="{{ $curso->nombre }}" readonly
                                class="form-input-pill-2 cursor-not-allowed bg-gray-50">
                        </div>

                        <div>
                            <label for="fecha_registro" id="etiqueta-fecha"
                                class="mb-2 block text-sm font-semibold text-brand-green">
                                Fecha de solicitud <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="fecha_registro" name="fecha_registro"
                                value="{{ old('fecha_registro', now()->toDateString()) }}" required
                                class="form-input-pill-2">
                        </div>

                        <div>
                            <label for="modulos_cursados" class="mb-2 block text-sm font-semibold text-brand-green">
                                Módulos cursados / avanzados
                            </label>
                            <textarea id="modulos_cursados" name="modulos_cursados" rows="4" maxlength="3000"
                                placeholder="Indica los módulos cursados o el avance académico del participante..."
                                class="form-input-pill-2 resize-y">{{ old('modulos_cursados') }}</textarea>
                            <p class="mt-1 text-xs text-gray-400">
                                Campo opcional. Máximo 3000 caracteres.
                            </p>
                        </div>
                    </div>
                </section>

                <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-5 py-4 sm:px-7">
                        <h2 class="font-bold text-brand-green">Estado financiero</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Resumen económico correspondiente a esta inscripción.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-3 sm:p-7">
                        <div class="rounded-xl border border-gray-100 bg-gray-50 p-5">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Monto total
                            </p>
                            <p class="mt-3 text-2xl font-bold text-brand-green">
                                {{ number_format($montoTotal, 2) }}
                                <span class="text-sm font-semibold">Bs</span>
                            </p>
                        </div>

                        <div class="rounded-xl border border-green-100 bg-green-50 p-5">
                            <p class="text-xs font-semibold uppercase tracking-wider text-green-800">
                                Monto pagado
                            </p>
                            <p class="mt-3 text-2xl font-bold text-green-800">
                                {{ number_format($montoPagado, 2) }}
                                <span class="text-sm font-semibold">Bs</span>
                            </p>
                        </div>

                        <div class="rounded-xl border border-amber-200 bg-amber-50 p-5">
                            <p class="text-xs font-semibold uppercase tracking-wider text-amber-800">
                                Saldo pendiente
                            </p>
                            <p class="mt-3 text-2xl font-bold text-amber-900">
                                {{ number_format($saldoPendiente, 2) }}
                                <span class="text-sm font-semibold">Bs</span>
                            </p>
                        </div>
                    </div>

                    <div class="mx-5 mb-5 rounded-lg bg-gray-50 px-4 py-3 text-xs text-gray-500 sm:mx-7 sm:mb-7">
                        Los importes corresponden al plan de pagos de esta inscripción.
                    </div>
                </section>

                <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-5 py-4 sm:px-7">
                        <h2 class="font-bold text-brand-green">Motivo y observaciones</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Registra los antecedentes que respaldan el trámite.
                        </p>
                    </div>

                    <div class="p-5 sm:p-7">
                        <label for="detalle" id="etiqueta-detalle"
                            class="mb-2 block text-sm font-semibold text-brand-green">
                            Motivo del retiro <span class="text-red-500">*</span>
                        </label>
                        <textarea id="detalle" name="detalle" rows="5" maxlength="5000" required
                            class="form-input-pill-2 resize-y"
                            placeholder="Describe el motivo o las circunstancias del retiro o abandono...">{{ old('detalle', 'El participante solicitó formalmente el retiro del programa académico por motivos personales.') }}</textarea>
                    </div>
                </section>

                <div class="flex flex-col-reverse gap-3 pb-6 sm:flex-row sm:justify-end">
                    <a href="{{ route('students.facturacion', [
    'id' => $estudiante->id_estudiante,
    'id_curso' => $curso->id_curso
]) }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                        Cancelar
                    </a>

                    <button type="submit" class="btn-gold">

                        Generar documento PDF
                    </button>
                </div>
            </form>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const tipo = document.getElementById('tipo');
                const fecha = document.getElementById('etiqueta-fecha');
                const detalle = document.getElementById('etiqueta-detalle');
                const texto = document.getElementById('detalle');

                const motivoRetiro = 'El participante solicitó formalmente el retiro del programa académico por motivos personales.';
                const motivoAbandono = 'Abandono del programa académico sin justificación. El participante dejó de asistir sin presentar solicitud escrita ni documentación de respaldo.';

                const actualizar = () => {
                    if (tipo.value === 'retiro') {
                        fecha.innerHTML = 'Fecha de solicitud <span class="text-red-500">*</span>';
                        detalle.innerHTML = 'Motivo del retiro <span class="text-red-500">*</span>';

                        if (!texto.value.trim() || texto.value === motivoAbandono) {
                            texto.value = motivoRetiro;
                        }
                    } else {
                        fecha.innerHTML = 'Fecha de registro de abandono <span class="text-red-500">*</span>';
                        detalle.innerHTML = 'Detalle del abandono <span class="text-red-500">*</span>';

                        if (!texto.value.trim() || texto.value === motivoRetiro) {
                            texto.value = motivoAbandono;
                        }
                    }
                };

                tipo.addEventListener('change', actualizar);
                actualizar();
            });
        </script>
    </x-layout-dashboard>
</body>

</html>
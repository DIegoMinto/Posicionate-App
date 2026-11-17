<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('modulo', function (Blueprint $table) {

            $table->unsignedSmallInteger('orden')
                ->nullable()
                ->after('nombre');

            $table->text('contenido_academico')
                ->nullable()
                ->after('orden');

            $table->unsignedSmallInteger('cantidad_sesiones')
                ->default(1)
                ->after('contenido_academico');

            $table->json('dias_clase')
                ->nullable()
                ->after('cantidad_sesiones');

            $table->time('hora_inicio')
                ->nullable()
                ->after('dias_clase');

            $table->time('hora_fin')
                ->nullable()
                ->after('hora_inicio');

            $table->unsignedSmallInteger('dias_descanso')
                ->default(0)
                ->after('hora_fin');

            $table->unsignedBigInteger('id_docente')
                ->nullable()
                ->after('id_curso');

            $table->foreign('id_docente')
                ->references('id_docente')
                ->on('docente')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('modulo', function (Blueprint $table) {

            $table->dropForeign(['id_docente']);

            $table->dropColumn([
                'orden',
                'contenido_academico',
                'cantidad_sesiones',
                'dias_clase',
                'hora_inicio',
                'hora_fin',
                'dias_descanso',
                'id_docente',
            ]);
        });
    }
};

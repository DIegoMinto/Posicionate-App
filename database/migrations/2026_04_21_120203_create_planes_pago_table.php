<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('planes_pago', function (Blueprint $table) {
            $table->id('id_planes_pago');

            // Solo declaramos la columna UNA VEZ
            $table->unsignedBigInteger('id_curso');

            $table->string('nombre');
            $table->boolean('incluye_matricula')->default(false);
            $table->decimal('precio_base', 10, 2);
            $table->timestamps();

            // Luego creamos la relación
            $table->foreign('id_curso')
                ->references('id_curso') // La PK de tu tabla curso
                ->on('curso')           // Tu tabla curso
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planes_pago');
    }
};

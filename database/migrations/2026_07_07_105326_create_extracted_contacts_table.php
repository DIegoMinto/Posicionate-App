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
        Schema::create('extracted_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('instance');
            $table->string('wa_id');
            $table->string('phone')->nullable();
            $table->boolean('is_lid')->default(false);
            $table->string('name')->nullable();
            $table->string('source_type');
            $table->string('source_ref');
            $table->timestamps();

            $table->unique(['user_id', 'instance', 'wa_id', 'source_type', 'source_ref']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extracted_contacts');
    }
};

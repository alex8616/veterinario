<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('desparasitaciones', function (Blueprint $table) {
            $table->id();

            // Mascota a la que se realizó la desparasitación
            $table->foreignId('mascota_id')
                ->constrained('mascotas')
                ->cascadeOnDelete();

            // Veterinario que realizó la desparasitación
            $table->foreignId('veterinario_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('producto');

            $table->date('fecha');

            $table->date('proxima_fecha')->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desparasitaciones');
    }
};
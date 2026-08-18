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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();

            // Mascota que tiene la cita
            $table->foreignId('mascota_id')
                ->constrained('mascotas')
                ->cascadeOnDelete();

            // Veterinario encargado de la cita
            $table->foreignId('veterinario_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->date('fecha');

            $table->time('hora');

            $table->string('motivo');

            $table->string('estado')
                ->default('pendiente');

            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
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
        Schema::create('vacunas', function (Blueprint $table) {
            $table->id();

            // Mascota a la que se aplicó la vacuna
            $table->foreignId('mascota_id')
                ->constrained('mascotas')
                ->cascadeOnDelete();

            // Veterinario que aplicó la vacuna
            $table->foreignId('veterinario_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('nombre');

            $table->date('fecha_aplicacion');

            $table->date('proxima_dosis')->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacunas');
    }
};
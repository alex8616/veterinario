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
        Schema::create('tratamientos', function (Blueprint $table) {
            $table->id();

            // Mascota que recibe el tratamiento
            $table->foreignId('mascota_id')
                ->constrained('mascotas')
                ->cascadeOnDelete();

            // Veterinario que indicó el tratamiento
            $table->foreignId('veterinario_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Consulta en la que se indicó el tratamiento
            $table->foreignId('consulta_id')
                ->nullable()
                ->constrained('consultas')
                ->nullOnDelete();

            $table->string('nombre');

            $table->text('descripcion')->nullable();

            $table->date('fecha_inicio');

            $table->date('fecha_fin')->nullable();

            $table->string('estado')
                ->default('activo');

            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tratamientos');
    }
};
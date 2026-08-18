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
        Schema::create('consultas', function (Blueprint $table) {
            $table->id();

            // Mascota atendida
            $table->foreignId('mascota_id')
                ->constrained('mascotas')
                ->cascadeOnDelete();

            // Veterinario que realizó la consulta
            $table->foreignId('veterinario_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Cita relacionada, si la consulta proviene de una cita
            $table->foreignId('cita_id')
                ->nullable()
                ->constrained('citas')
                ->nullOnDelete();

            $table->date('fecha');

            $table->string('motivo');

            $table->text('diagnostico')->nullable();

            $table->text('observaciones')->nullable();

            $table->decimal('peso', 5, 2)->nullable();

            $table->decimal('temperatura', 4, 1)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};
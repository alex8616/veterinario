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
        Schema::create('comentarios', function (Blueprint $table) {
            $table->id();

            // Usuario que realizó el comentario
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Noticia comentada
            $table->foreignId('noticia_id')
                ->constrained('noticias')
                ->cascadeOnDelete();

            $table->text('contenido');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};
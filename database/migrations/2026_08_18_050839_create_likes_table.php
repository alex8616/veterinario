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
        Schema::create('likes', function (Blueprint $table) {
            $table->id();

            // Usuario que dio el like
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Noticia a la que se dio like
            $table->foreignId('noticia_id')
                ->constrained('noticias')
                ->cascadeOnDelete();

            $table->timestamps();

            // Un usuario solo puede dar un like
            // a la misma noticia una vez.
            $table->unique(['user_id', 'noticia_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
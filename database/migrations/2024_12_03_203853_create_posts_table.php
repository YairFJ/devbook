<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable(); // Agregamos el título como opcional
                $table->text('content')->nullable(); // Hacemos opcional el contenido
                $table->string('image_path')->nullable(); // Campo para la imagen
                $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con el usuario
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
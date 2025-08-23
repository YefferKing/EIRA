<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('opciones_articulos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('articulo_id')->constrained('articulos')->onDelete('cascade');
            $table->string('tipo_opcion'); // 'tono', 'tamaño', 'color', etc.
            $table->string('valor_opcion'); // 'claro', 'medio', 'oscuro', etc.
            $table->string('codigo_color')->nullable(); // Para tonos o colores
            $table->decimal('precio_adicional', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('opciones_articulos');
    }
};
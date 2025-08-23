<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carrito', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->foreignId('articulo_id')->constrained('articulos')->onDelete('cascade');
            $table->integer('cantidad')->default(1);
            $table->json('opciones_seleccionadas')->nullable(); // {tono: "claro", tamaño: "M"}
            $table->decimal('precio_unitario', 10, 2);
            $table->timestamps();
            
            $table->index(['session_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('carrito');
    }
};
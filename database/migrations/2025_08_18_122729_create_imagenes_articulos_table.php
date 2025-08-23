<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('imagenes_articulos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('articulo_id')->constrained('articulos')->onDelete('cascade');
            $table->string('url');
            $table->integer('orden')->default(0);
            $table->boolean('es_principal')->default(false);
            $table->timestamps();
            
            $table->index(['articulo_id', 'orden']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('imagenes_articulos');
    }
};
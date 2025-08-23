<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->text('descripcion')->nullable();
            $table->string('icono')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
        
        // Agregar categoria_id a articulos
        Schema::table('articulos', function (Blueprint $table) {
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('articulos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropColumn('categoria_id');
        });
        
        Schema::dropIfExists('categorias');
    }
};
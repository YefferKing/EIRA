<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenArticulo extends Model
{
    use HasFactory;

    protected $table = 'imagenes_articulos';

    protected $fillable = [
        'articulo_id',
        'url',
        'orden',
        'es_principal'
    ];

    protected $casts = [
        'es_principal' => 'boolean'
    ];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }
}
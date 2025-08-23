<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpcionArticulo extends Model
{
    use HasFactory;

    protected $table = 'opciones_articulos';

    protected $fillable = [
        'articulo_id',
        'tipo_opcion',
        'valor_opcion',
        'codigo_color',
        'precio_adicional'
    ];

    protected $casts = [
        'precio_adicional' => 'decimal:2'
    ];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }
}
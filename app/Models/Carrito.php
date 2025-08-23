<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;

    protected $table = 'carrito';

    protected $fillable = [
        'session_id',
        'articulo_id', 
        'cantidad',
        'opciones_seleccionadas',
        'precio_unitario'
    ];

    protected $casts = [
        'opciones_seleccionadas' => 'array',
        'precio_unitario' => 'decimal:2'
    ];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->cantidad * $this->precio_unitario;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'valor',
        'valor_especial',
        'tiene_descuento',
        'imagen',
        'activo',
        'categoria_id'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'valor_especial' => 'decimal:2',
        'tiene_descuento' => 'boolean',
        'activo' => 'boolean'
    ];

    public function imagenes()
    {
        return $this->hasMany(ImagenArticulo::class)->orderBy('orden');
    }

    public function imagenPrincipal()
    {
        return $this->hasOne(ImagenArticulo::class)->where('es_principal', true);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function opciones()
    {
        return $this->hasMany(OpcionArticulo::class);
    }

    public function getPrecioFinalAttribute()
    {
        return $this->tiene_descuento && $this->valor_especial 
            ? $this->valor_especial 
            : $this->valor;
    }

    public function getDescuentoPorcentajeAttribute()
    {
        if (!$this->tiene_descuento || !$this->valor_especial) {
            return 0;
        }
        
        return round((($this->valor - $this->valor_especial) / $this->valor) * 100);
    }
}
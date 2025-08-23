<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'slug', 'descripcion', 'icono', 'activa'];

    protected $casts = ['activa' => 'boolean'];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($categoria) {
            $categoria->slug = Str::slug($categoria->nombre);
        });
    }

    public function articulos()
    {
        return $this->hasMany(Articulo::class);
    }
}
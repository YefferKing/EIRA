<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        $query = Articulo::with(['opciones', 'categoria', 'imagenPrincipal'])->where('activo', true);
        
        // Búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->get('buscar');
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('descripcion', 'like', "%{$buscar}%")
                  ->orWhere('codigo', 'like', "%{$buscar}%");
            });
        }
        
        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }
        
        // Filtro por descuentos
        if ($request->filled('solo_descuentos')) {
            $query->where('tiene_descuento', true);
        }
        
        $articulos = $query->orderBy('nombre')->paginate(12);
        $categorias = Categoria::where('activa', true)->get();
        
        return view('catalogo.index', compact('articulos', 'categorias'));
    }
    
    public function categoria(Categoria $categoria)
    {
        $articulos = $categoria->articulos()
                              ->with('opciones')
                              ->where('activo', true)
                              ->paginate(12);
        $categorias = Categoria::where('activa', true)->get();
        
        return view('catalogo.categoria', compact('categoria', 'articulos', 'categorias'));
    }
    
    public function show(Articulo $articulo)
    {
        if (!$articulo->activo) {
            abort(404);
        }
        
        $articulo->load(['opciones', 'imagenes']);
        
        return view('catalogo.show', compact('articulo'));
    }
}
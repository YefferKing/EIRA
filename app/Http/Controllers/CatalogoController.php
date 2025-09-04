<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        // Construir la consulta base
        $query = Articulo::with(['imagenPrincipal', 'categoria']);
        
        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->get('buscar');
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', '%' . $buscar . '%')
                  ->orWhere('descripcion', 'like', '%' . $buscar . '%')
                  ->orWhere('codigo', 'like', '%' . $buscar . '%');
            });
        }
        
        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->get('categoria'));
        }
        
        // Filtro por solo descuentos
        if ($request->filled('solo_descuentos') && $request->get('solo_descuentos') == '1') {
            $query->where('tiene_descuento', true);
        }
        
        // Ordenar y paginar
        $articulos = $query->latest()->paginate(12);
        
        // ✅ LÍNEA CLAVE: Conservar parámetros en paginación
        $articulos->appends($request->query());
        
        // Obtener categorías para los filtros
        $categorias = Categoria::orderBy('nombre')->get();
        
        return view('catalogo.index', compact('articulos', 'categorias'));
    }
    
    public function categoria(Categoria $categoria, Request $request)
    {
        // Método para la ruta /categoria/{categoria:slug}
        $query = $categoria->articulos()->with(['imagenPrincipal']);
        
        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->get('buscar');
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', '%' . $buscar . '%')
                  ->orWhere('descripcion', 'like', '%' . $buscar . '%')
                  ->orWhere('codigo', 'like', '%' . $buscar . '%');
            });
        }
        
        // Filtro por solo descuentos
        if ($request->filled('solo_descuentos') && $request->get('solo_descuentos') == '1') {
            $query->where('tiene_descuento', true);
        }
        
        $articulos = $query->latest()->paginate(12);
        
        // ✅ También conservar parámetros aquí
        $articulos->appends($request->query());
        
        $categorias = Categoria::orderBy('nombre')->get();
        
        return view('catalogo.categoria', compact('articulos', 'categoria', 'categorias'));
    }
    
    public function show(Articulo $articulo)
    {
        $articulo->load(['imagenes' => function($query) {
            $query->orderBy('orden');
        }, 'categoria', 'opciones']);
        
        return view('catalogo.show', compact('articulo'));
    }
}
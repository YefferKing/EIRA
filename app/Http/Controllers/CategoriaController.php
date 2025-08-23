<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::latest()->paginate(10);
        return view('admin.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('admin.categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255|unique:categorias',
            'descripcion' => 'nullable',
            'icono' => 'nullable|max:50',
        ]);

        Categoria::create($request->all());

        return redirect()->route('admin.categorias.index')
                        ->with('success', 'Categoría creada exitosamente.');
    }

    public function edit(Categoria $categoria)
    {
        return view('admin.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|max:255|unique:categorias,nombre,' . $categoria->id,
            'descripcion' => 'nullable',
            'icono' => 'nullable|max:50',
        ]);

        $categoria->update($request->all());

        return redirect()->route('admin.categorias.index')
                        ->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()->route('admin.categorias.index')
                        ->with('success', 'Categoría eliminada exitosamente.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Articulo;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function index()
    {
        $items = Carrito::with('articulo')
                       ->where('session_id', session()->getId())
                       ->get();
        
        $total = $items->sum('subtotal');
        
        return view('carrito.index', compact('items', 'total'));
    }

    public function agregar(Request $request, Articulo $articulo)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1',
            'opciones' => 'nullable|array'
        ]);

        $sessionId = session()->getId();
        $precio = $articulo->precio_final;

        // Verificar si ya existe en carrito
        $itemExistente = Carrito::where('session_id', $sessionId)
                              ->where('articulo_id', $articulo->id)
                              ->where('opciones_seleccionadas', json_encode($request->opciones ?? []))
                              ->first();

        if ($itemExistente) {
            $itemExistente->cantidad += $request->cantidad;
            $itemExistente->save();
        } else {
            Carrito::create([
                'session_id' => $sessionId,
                'articulo_id' => $articulo->id,
                'cantidad' => $request->cantidad,
                'opciones_seleccionadas' => $request->opciones ?? [],
                'precio_unitario' => $precio
            ]);
        }

        return redirect()->back()->with('success', 'Producto agregado al carrito');
    }

    public function actualizar(Request $request, Carrito $item)
    {
        $request->validate(['cantidad' => 'required|integer|min:1']);
        
        $item->update(['cantidad' => $request->cantidad]);
        
        return redirect()->back()->with('success', 'Carrito actualizado');
    }

    public function eliminar(Carrito $item)
    {
        $item->delete();
        return redirect()->back()->with('success', 'Producto eliminado del carrito');
    }

    public function vaciar()
    {
        Carrito::where('session_id', session()->getId())->delete();
        return redirect()->back()->with('success', 'Carrito vaciado');
    }

    public function contador()
    {
        $count = Carrito::where('session_id', session()->getId())->sum('cantidad');
        return response()->json(['count' => $count]);
    }
}
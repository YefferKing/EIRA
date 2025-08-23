@extends('layouts.app')

@section('title', 'Carrito de Compras')

@section('content')
<div class="container py-4">
    <h2 class="text-brand-primary mb-4">🛒 Mi Carrito de Compras</h2>

    @if($items->count() > 0)
    <div class="row">
        <div class="col-lg-8">
            @foreach($items as $item)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            @if($item->articulo->imagenPrincipal)
                            <img src="{{ $item->articulo->imagenPrincipal->url }}" class="img-fluid rounded" alt="{{ $item->articulo->nombre }}">
                            @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                📦
                            </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-1">{{ $item->articulo->nombre }}</h6>
                            <small class="text-muted">{{ $item->articulo->codigo }}</small>
                            @if($item->opciones_seleccionadas)
                            <div class="mt-1">
                                @foreach($item->opciones_seleccionadas as $tipo => $valor)
                                <span class="badge bg-secondary small fw-normal">{{ ucfirst($tipo) }}: {{ $valor }}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <strong>${{ number_format($item->precio_unitario, 0, ',', '.') }}</strong>
                        </div>
                        <div class="col-md-3">
                            <form action="{{ route('carrito.actualizar', $item) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <div class="input-group input-group-sm">
                                    <input type="number" class="form-control" name="cantidad" 
                                           value="{{ $item->cantidad }}" min="1" max="10">
                                    <button type="submit" class="btn btn-outline-primary">✓</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-1">
                            <form action="{{ route('carrito.eliminar', $item) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">🗑️</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-brand-primary text-white">
                    <h5 class="mb-0 text-brand-primary">💰 Resumen del Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal:</span>
                        <strong>${{ number_format($total, 0, ',', '.') }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <h5>Total:</h5>
                        <h5 class="text-brand-primary">${{ number_format($total, 0, ',', '.') }}</h5>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button class="btn btn-brand-primary btn-lg" onclick="procederPago()">
                            💳 Proceder al Pago
                        </button>
                        <a href="{{ route('catalogo.index') }}" class="btn btn-brand-secondary">
                            🛍️ Seguir Comprando
                        </a>
                        <form action="{{ route('carrito.vaciar') }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100" 
                                    onclick="return confirm('¿Vaciar carrito?')">
                                🗑️ Vaciar Carrito
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <div class="card">
            <div class="card-body">
                <h3 class="text-muted">🛒 Tu carrito está vacío</h3>
                <p class="text-muted">¡Agrega algunos productos y comienza a comprar!</p>
                <a href="{{ route('catalogo.index') }}" class="btn btn-brand-primary">
                    🛍️ Ir de Compras
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function procederPago() {
    const productosData = {!! json_encode($items->map(function($item) {
        $nombre = $item->articulo->nombre . ' (x' . $item->cantidad . ')';
        $precio = '$' . number_format($item->precio_unitario, 0, ',', '.');
        
        $opciones = '';
        if ($item->opciones_seleccionadas) {
            $opcionesArray = [];
            foreach ($item->opciones_seleccionadas as $tipo => $valor) {
                $opcionesArray[] = ucfirst($tipo) . ': ' . $valor;
            }
            if (!empty($opcionesArray)) {
                $opciones = ' • ' . implode(', ', $opcionesArray);
            }
        }
        
        return $nombre . $opciones . ' - ' . $precio;
    })) !!};
    
    const total = '{{ number_format($total, 0, ",", ".") }}';
    
    // Usar caracteres ASCII seguros en lugar de emojis
    let mensaje = 'Hola! Quiero realizar este pedido:\n\n';
    mensaje += '>> PRODUCTOS:\n';
    mensaje += productosData.map((producto, index) => `${index + 1}. ${producto}`).join('\n');
    mensaje += '\n\n>> TOTAL: $' + total + '\n\n';
    mensaje += 'Pueden confirmar disponibilidad y tiempo de entrega?\n';
    mensaje += 'Gracias!';
    
    const whatsappUrl = `https://wa.me/573053265944?text=${encodeURIComponent(mensaje)}`;
    window.open(whatsappUrl, '_blank');
}
</script>
@endpush
@endsection
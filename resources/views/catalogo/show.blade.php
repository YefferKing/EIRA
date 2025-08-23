@extends('layouts.app')

@section('title', $articulo->nombre)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('catalogo.index') }}" class="text-brand-primary">🛍️ Catálogo</a>
            </li>
            <li class="breadcrumb-item active">{{ $articulo->nombre }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Reemplaza la sección de imagen del producto en tu vista show.blade.php -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <!-- Imagen principal -->
                <div class="position-relative">
                    @if($articulo->imagenes->count() > 0)
                    <img src="{{ $articulo->imagenes->first()->url }}" 
                        class="card-img-top imagen-principal" 
                        style="height: 400px; object-fit: cover;" 
                        alt="{{ $articulo->nombre }}"
                        id="imagen-principal">
                    @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                        style="height: 400px;">
                        <span class="text-muted" style="font-size: 6rem;">📦</span>
                    </div>
                    @endif
                    
                    <!-- Indicador de imagen actual (si hay múltiples imágenes) -->
                    @if($articulo->imagenes->count() > 1)
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-dark bg-opacity-75" id="contador-imagen">
                            1 / {{ $articulo->imagenes->count() }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Galería de miniaturas (TODAS las imágenes) -->
            @if($articulo->imagenes->count() > 1)
            <div class="mt-3">
                <div class="row g-2">
                    @foreach($articulo->imagenes as $index => $imagen)
                    <div class="col-3">
                        <img src="{{ $imagen->url }}" 
                            class="img-thumbnail miniatura-imagen {{ $index === 0 ? 'miniatura-activa' : '' }}" 
                            style="height: 80px; width: 100%; object-fit: cover; cursor: pointer; transition: all 0.3s;"
                            onclick="cambiarImagenPrincipal('{{ $imagen->url }}', {{ $index }})"
                            data-index="{{ $index }}"
                            alt="Imagen {{ $index + 1 }}">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Información del producto -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h1 class="h2 text-brand-primary">{{ $articulo->nombre }}</h1>
                        @if($articulo->tiene_descuento)
                        <span class="badge-descuento">🔥 -{{ $articulo->descuento_porcentaje }}%</span>
                        @endif
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="text-brand-primary">📝 Descripción</h5>
                        <p class="text-muted">{{ $articulo->descripcion }}</p>
                    </div>
                    
                    <!-- Opciones del producto -->
                    @if($articulo->opciones->count() > 0)
                    <div class="mb-4">
                        <h5 class="text-brand-primary">⚙️ Opciones Disponibles</h5>
                        
                        @php
                            $opcionesAgrupadas = $articulo->opciones->groupBy('tipo_opcion');
                        @endphp
                        
                        @foreach($opcionesAgrupadas as $tipo => $opciones)
                        <div class="mb-3">
                            <h6 class="text-uppercase text-muted small">{{ $tipo }}</h6>
                            <div class="row g-2">
                                @foreach($opciones as $opcion)
                                <div class="col-auto">
                                    <div class="d-flex align-items-center gap-2 p-2 border rounded">
                                        @if($opcion->codigo_color)
                                        <span class="opcion-color" 
                                              style="background-color: {{ $opcion->codigo_color }}"></span>
                                        @endif
                                        <span class="small">{{ $opcion->valor_opcion }}</span>
                                        @if($opcion->precio_adicional > 0)
                                        <span class="badge bg-secondary small fw-normal">
                                            +${{ number_format($opcion->precio_adicional, 0, ',', '.') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    
                    <!-- Precio -->
                    <div class="mb-4 p-3 bg-light rounded">
                        @if($articulo->tiene_descuento)
                        <div class="d-flex align-items-center gap-3">
                            <span class="precio-original h5">
                                ${{ number_format($articulo->valor, 0, ',', '.') }}
                            </span>
                            <span class="precio-especial h3 mb-0">
                                ${{ number_format($articulo->precio_final, 0, ',', '.') }}
                            </span>
                        </div>
                        <small class="text-muted">
                            ¡Ahorras ${{ number_format($articulo->valor - $articulo->precio_final, 0, ',', '.') }}!
                        </small>
                        @else
                        <div class="h3 text-brand-primary mb-0">
                            ${{ number_format($articulo->precio_final, 0, ',', '.') }}
                        </div>
                        @endif
                    </div>
                    
                    <!-- Formulario de compra -->
                    <form action="{{ route('carrito.agregar', $articulo) }}" method="POST" id="form-compra">
                        @csrf
                        
                        <!-- Selector de cantidad -->
                        <div class="mb-4">
                            <label class="form-label text-brand-primary">📦 Cantidad</label>
                            <div class="input-group" style="max-width: 150px;">
                                <button type="button" class="btn btn-outline-secondary" onclick="cambiarCantidad(-1)">-</button>
                                <input type="number" class="form-control text-center" name="cantidad" id="cantidad" 
                                       value="1" min="1" max="10">
                                <button type="button" class="btn btn-outline-secondary" onclick="cambiarCantidad(1)">+</button>
                            </div>
                        </div>
                        
                        <!-- Selección de opciones -->
                        @if($articulo->opciones->count() > 0)
                        <div class="mb-4">
                            <h6 class="text-brand-primary">⚙️ Selecciona opciones:</h6>
                            
                            @php
                                $opcionesAgrupadas = $articulo->opciones->groupBy('tipo_opcion');
                            @endphp
                            
                            @foreach($opcionesAgrupadas as $tipo => $opciones)
                            <div class="mb-3">
                                <label class="form-label">{{ ucfirst($tipo) }}:</label>
                                <select class="form-select" name="opciones[{{ $tipo }}]" required>
                                    <option value="">Seleccionar {{ $tipo }}...</option>
                                    @foreach($opciones as $opcion)
                                    <option value="{{ $opcion->valor_opcion }}" 
                                            data-precio="{{ $opcion->precio_adicional }}">
                                        {{ $opcion->valor_opcion }}
                                        @if($opcion->precio_adicional > 0)
                                        (+${{ number_format($opcion->precio_adicional, 0, ',', '.') }})
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @endforeach
                        </div>
                        @endif
                        
                        <!-- Botones de acción -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-brand-primary btn-lg">
                                🛒 Agregar al Carrito
                            </button>
                            <button type="button" class="btn btn-success btn-lg" onclick="comprarAhora()">
                                💳 Comprar Ahora
                            </button>
                            <a href="{{ route('catalogo.index') }}" class="btn btn-brand-secondary">
                                ← Volver al Catálogo
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Productos relacionados o sugeridos -->
    <div class="mt-5">
        <h3 class="text-brand-primary mb-4">🌟 Otros productos que podrían interesarte</h3>
        <div class="row">
            @php
                $productosRelacionados = App\Models\Articulo::where('activo', true)
                    ->where('id', '!=', $articulo->id)
                    ->inRandomOrder()
                    ->take(4)
                    ->get();
            @endphp
            
            @foreach($productosRelacionados as $relacionado)
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card h-100">
                    @if($relacionado->imagenPrincipal)
                    <img src="{{ $relacionado->imagenPrincipal->url }}" 
                        class="card-img-top" style="height: 150px; object-fit: cover;" 
                        alt="{{ $relacionado->nombre }}">
                    @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                         style="height: 150px;">
                        <span class="text-muted" style="font-size: 2rem;">📦</span>
                    </div>
                    @endif
                    
                    <div class="card-body">
                        <h6 class="card-title">{{ $relacionado->nombre }}</h6>
                        <p class="card-text small text-muted">
                            {{ Str::limit($relacionado->descripcion, 50) }}
                        </p>
                        <div class="text-brand-primary">
                            ${{ number_format($relacionado->precio_final, 0, ',', '.') }}
                        </div>
                        <a href="{{ route('catalogo.show', $relacionado) }}" 
                           class="btn btn-brand-secondary btn-sm mt-2">
                            Ver Detalles
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<style>
/* Estilos para la galería */
.miniatura-imagen {
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.miniatura-imagen:hover {
    border-color: #007bff;
    opacity: 0.8;
    transform: scale(1.05);
}

.miniatura-activa {
    border-color: #6f42c1 !important;
    opacity: 1;
    transform: scale(1.05);
}

.imagen-principal {
    transition: opacity 0.3s ease;
}
</style>

@push('scripts')
<script>

let imagenActualIndex = 0;
const totalImagenes = {{ $articulo->imagenes->count() }};

function cambiarCantidad(cambio) {
    const input = document.getElementById('cantidad');
    let valor = parseInt(input.value) + cambio;
    if (valor < 1) valor = 1;
    if (valor > 10) valor = 10;
    input.value = valor;
}

function comprarAhora() {
    // Agregar al carrito y redirigir
    document.getElementById('form-compra').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (response.ok) {
                window.location.href = '{{ route("carrito.index") }}';
            }
        });
    });
    
    document.getElementById('form-compra').submit();
}

function contactarVendedor() {
    const mensaje = `¡Hola! Estoy interesado/a en el producto: ${@json($articulo->nombre)} (Código: ${@json($articulo->codigo)})`;
    const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(mensaje)}`;
    window.open(whatsappUrl, '_blank');
}

// Actualizar contador del carrito
function actualizarContadorCarrito() {
    fetch('{{ route("carrito.contador") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('carrito-count').textContent = data.count;
        });
}

document.getElementById('form-compra').addEventListener('submit', function() {
    setTimeout(actualizarContadorCarrito, 1000);
});

function cambiarImagenPrincipal(url, index) {
    // Cambiar la imagen principal
    const imagenPrincipal = document.getElementById('imagen-principal');
    
    // Efecto de transición suave
    imagenPrincipal.style.opacity = '0.5';
    
    setTimeout(() => {
        imagenPrincipal.src = url;
        imagenPrincipal.style.opacity = '1';
    }, 150);
    
    // Actualizar el índice actual
    imagenActualIndex = index;
    
    // Actualizar el contador
    const contador = document.getElementById('contador-imagen');
    if (contador) {
        contador.textContent = `${index + 1} / ${totalImagenes}`;
    }
    
    // Actualizar las miniaturas activas
    document.querySelectorAll('.miniatura-imagen').forEach((img, i) => {
        if (i === index) {
            img.classList.add('miniatura-activa');
        } else {
            img.classList.remove('miniatura-activa');
        }
    });
}

document.addEventListener('keydown', function(e) {
    if (totalImagenes <= 1) return;
    
    if (e.key === 'ArrowLeft') {
        const nuevoIndex = (imagenActualIndex - 1 + totalImagenes) % totalImagenes;
        const imagenes = {!! $articulo->imagenes->toJson() !!};
        cambiarImagenPrincipal(imagenes[nuevoIndex].url, nuevoIndex);
    } else if (e.key === 'ArrowRight') {
        const nuevoIndex = (imagenActualIndex + 1) % totalImagenes;
        const imagenes = {!! $articulo->imagenes->toJson() !!};
        cambiarImagenPrincipal(imagenes[nuevoIndex].url, nuevoIndex);
    }
});
</script>
@endpush
@endsection
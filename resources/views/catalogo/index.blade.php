@extends('layouts.app')

@section('title', 'Catálogo de Productos')

@section('content')
<div class="header-elegante">
    <div class="container text-center">
        <h1 class="display-4">Catálogo</h1>
        <p class="lead">Descubre nuestros productos exclusivos</p>
    </div>
</div>

<div class="container">
    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('catalogo.index') }}" class="row g-3">
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="buscar" 
                                   placeholder="Buscar por nombre, descripción o código..." 
                                   value="{{ request('buscar') }}">
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="categoria">
                                <option value="">Todas las categorías</option>
                                @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->icono }} {{ $categoria->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="solo_descuentos" 
                                       value="1" {{ request('solo_descuentos') ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    🔥 Solo ofertas
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-brand-primary w-100">
                                🔍 Buscar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Categorías rápidas -->
    <div class="row mb-4">
    <div class="col-12">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('catalogo.index') }}" 
               class="category-btn color {{ !request('categoria') ? 'active' : '' }}">
                🏪 Todos
            </a>
            @foreach($categorias as $categoria)
            <a href="{{ route('catalogo.index', ['categoria' => $categoria->id]) }}" 
               class="category-btn color {{ request('categoria') == $categoria->id ? 'active' : '' }}">
                {{ $categoria->icono }} {{ $categoria->nombre }}
            </a>
            @endforeach
        </div>
    </div>
</div>


    <!-- Productos -->
    @if($articulos->count() > 0)
    <div class="row">
        @foreach($articulos as $articulo)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100">
                @if($articulo->imagenPrincipal)
                <img src="{{ $articulo->imagenPrincipal->url }}" 
                     class="card-img-top" style="height: 200px; object-fit: cover;" 
                     alt="{{ $articulo->nombre }}">
                @else
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                     style="height: 200px;">
                    <span class="text-muted" style="font-size: 3rem;">📦</span>
                </div>
                @endif

                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title text-brand-primary">{{ $articulo->nombre }}</h6>
                        @if($articulo->tiene_descuento)
                        <span class="badge bg-danger">-{{ $articulo->descuento_porcentaje }}%</span>
                        @endif
                    </div>
                    
                    <p class="card-text flex-grow-1">
                        {{ Str::limit($articulo->descripcion, 80) }}
                    </p>
                    
                    <!-- Precios -->
                    <div class="mt-auto">
                        @if($articulo->tiene_descuento)
                        <div class="d-flex align-items-center gap-2">
                            <span style="text-decoration: line-through; color: #6c757d; font-size: 0.9em;">
                                ${{ number_format($articulo->valor, 0, ',', '.') }}
                            </span>
                            <span style="color: #dc3545; font-weight: normal; font-size: 1.2em;">
                                ${{ number_format($articulo->precio_final, 0, ',', '.') }}
                            </span>
                        </div>
                        @else
                        <div class="h5 text-brand-primary mb-0">
                            ${{ number_format($articulo->precio_final, 0, ',', '.') }}
                        </div>
                        @endif
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('catalogo.show', $articulo) }}" 
                           class="btn btn-brand-primary w-100">
                            🛒 Ver artículo
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-4">
        {{ $articulos->appends(request()->query())->links() }}
    </div>
    
    @else
    <div class="text-center py-5">
        <div class="card">
            <div class="card-body">
                <h3 class="text-muted">😔 No se encontraron productos</h3>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

<style>
.category-btn {
    background: transparent;
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: normal;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    position: relative;
}

.category-btn:hover {
    color: var(--primary-color);
    text-decoration: none;
    background: rgba(64, 24, 116, 0.05);
}

.category-btn.active {
    background: transparent;
    color: var(--primary-color);
    border-bottom: 4px solid #401874;
    border-radius: 0;
}

.color{
    color: #401874;
}
</style>
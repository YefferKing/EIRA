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

<!-- Paginación responsive -->
@if($articulos->hasPages())
<div class="d-flex justify-content-center mt-4">
    <div class="pagination-container">
        <div class="pagination-info">
            <span class="text-muted">
                Mostrando {{ $articulos->firstItem() ?? 0 }} - {{ $articulos->lastItem() ?? 0 }} 
                de {{ $articulos->total() }} resultados
            </span>
        </div>
        
        <nav>
            <!-- Botón Anterior -->
            @if ($articulos->onFirstPage())
                <span class="page-btn disabled">
                    <i class="fas fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $articulos->previousPageUrl() }}" class="page-btn">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            <!-- Botón Siguiente -->
            @if ($articulos->hasMorePages())
                <a href="{{ $articulos->nextPageUrl() }}" class="page-btn">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="page-btn disabled">
                    <i class="fas fa-chevron-right"></i>
                </span>
            @endif
        </nav>
    </div>
</div>
@endif
    
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

.pagination-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
    background: #fff;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(64, 24, 116, 0.1);
    margin: 0 auto;
    max-width: 100%;
}

.pagination-info {
    font-size: 14px;
    color: #6c757d;
    font-weight: 500;
    text-align: center;
}

.custom-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
    width: 100%;
}

.page-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 16px;
    background: linear-gradient(135deg, #401874, #6B46C1);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(64, 24, 116, 0.3);
    white-space: nowrap;
    min-height: 40px;
}

.page-btn:hover:not(.disabled) {
    background: linear-gradient(135deg, #2d0f4f, #553C9A);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(64, 24, 116, 0.4);
    color: white;
    text-decoration: none;
}

.page-btn.disabled {
    background: #e9ecef;
    color: #6c757d;
    cursor: not-allowed;
    box-shadow: none;
}

.page-numbers {
    display: flex;
    gap: 4px;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    margin: 0 8px;
}

.page-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    text-decoration: none;
    color: #401874;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    background: rgba(64, 24, 116, 0.05);
    border: 2px solid transparent;
}

.page-num:hover:not(.active) {
    background: rgba(64, 24, 116, 0.15);
    color: #401874;
    text-decoration: none;
    transform: scale(1.05);
}

.page-num.active {
    background: linear-gradient(135deg, #401874, #6B46C1);
    color: white;
    border-color: rgba(255, 255, 255, 0.3);
    box-shadow: 0 4px 15px rgba(64, 24, 116, 0.3);
}

/* Responsive - Tablets */
@media (max-width: 768px) {
    .pagination-container {
        padding: 16px;
        margin: 0 16px;
        gap: 12px;
    }
    
    .custom-pagination {
        gap: 6px;
        flex-direction: column;
    }
    
    .page-btn {
        padding: 10px 14px;
        font-size: 12px;
        min-width: 80px;
    }
    
    .page-numbers {
        margin: 8px 0;
        gap: 3px;
        order: -1; /* Números primero en tablet */
    }
    
    .page-num {
        width: 36px;
        height: 36px;
        font-size: 13px;
    }
    
    .pagination-info {
        font-size: 12px;
        order: 1; /* Info al final en tablet */
    }
}

/* Responsive - Móviles */
@media (max-width: 480px) {
    .pagination-container {
        padding: 12px;
        margin: 0 8px;
        gap: 10px;
    }
    
    .custom-pagination {
        flex-direction: column;
        gap: 10px;
    }
    
    .page-btn {
        padding: 8px 12px;
        font-size: 12px;
        min-width: 70px;
    }
    
    .page-btn i {
        display: none; /* Ocultar iconos en móvil */
    }
    
    .page-numbers {
        margin: 0;
        gap: 2px;
        max-width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding: 4px 0;
        justify-content: flex-start;
    }
    
    .page-num {
        width: 32px;
        height: 32px;
        font-size: 12px;
        flex-shrink: 0; /* Evitar que se compriman */
        border-radius: 6px;
    }
    
    .pagination-info {
        font-size: 11px;
        line-height: 1.3;
    }
}

/* Móviles muy pequeños */
@media (max-width: 320px) {
    .pagination-container {
        padding: 10px;
        margin: 0 4px;
    }
    
    .page-numbers {
        gap: 1px;
    }
    
    .page-num {
        width: 28px;
        height: 28px;
        font-size: 11px;
    }
    
    .page-btn {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }
}

/* Animaciones */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.pagination-container {
    animation: fadeIn 0.5s ease-out;
}

/* Estados de foco para accesibilidad */
.page-btn:focus,
.page-num:focus {
    outline: 2px solid #401874;
    outline-offset: 2px;
}

/* Estilo para scroll horizontal en números de página (móvil) */
.page-numbers::-webkit-scrollbar {
    height: 4px;
}

.page-numbers::-webkit-scrollbar-track {
    background: rgba(64, 24, 116, 0.1);
    border-radius: 2px;
}

.page-numbers::-webkit-scrollbar-thumb {
    background: rgba(64, 24, 116, 0.3);
    border-radius: 2px;
}

.page-numbers::-webkit-scrollbar-thumb:hover {
    background: rgba(64, 24, 116, 0.5);
}
</style>
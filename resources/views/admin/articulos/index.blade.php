@extends('layouts.app')

@section('title', 'Administración de Artículos')

@section('content')
<div class="header-elegante">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-5">⚙️ Panel de Administración</h1>
                <p class="lead mb-0">Gestiona tus productos de manera elegante</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('admin.articulos.create') }}" class="btn btn-light btn-lg">
                    ➕ Nuevo Artículo
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        @php
            $totalArticulos = App\Models\Articulo::count();
            $articulosActivos = App\Models\Articulo::where('activo', true)->count();
            $articulosConDescuento = App\Models\Articulo::where('tiene_descuento', true)->count();
        @endphp
        
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-brand-primary">{{ $totalArticulos }}</h3>
                    <p class="text-muted mb-0">📦 Total Artículos</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success">{{ $articulosActivos }}</h3>
                    <p class="text-muted mb-0">✅ Activos</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-danger">{{ $articulosConDescuento }}</h3>
                    <p class="text-muted mb-0">🔥 En Oferta</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de artículos -->
    <div class="admin-panel">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-brand-primary mb-0">📋 Lista de Artículos</h3>
            <a href="{{ route('admin.articulos.create') }}" class="btn btn-brand-primary">
                ➕ Nuevo Artículo
            </a>
        </div>

        @if($articulos->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Imagen</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($articulos as $articulo)
                    <tr>
                        <td>
                            @if($articulo->imagenPrincipal)
                            <img src="{{ $articulo->imagenPrincipal->url }}" 
                                 class="rounded" style="width: 50px; height: 50px; object-fit: cover;" 
                                 alt="{{ $articulo->nombre }}">
                            @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                📦
                            </div>
                            @endif
                        </td>
                        <td>
                            <code class="text-brand-primary">{{ $articulo->codigo }}</code>
                        </td>
                        <td>
                            <strong>{{ $articulo->nombre }}</strong>
                            <br>
                            <small class="text-muted">
                                {{ Str::limit($articulo->descripcion, 50) }}
                            </small>
                        </td>
                        <td>
                            @if($articulo->tiene_descuento)
                            <div>
                                <span class="precio-original small">
                                    ${{ number_format($articulo->valor, 0, ',', '.') }}
                                </span>
                                <br>
                                <span class="precio-especial">
                                    ${{ number_format($articulo->precio_final, 0, ',', '.') }}
                                </span>
                                <span class="badge-descuento small">
                                    -{{ $articulo->descuento_porcentaje }}%
                                </span>
                            </div>
                            @else
                            <span class="text-brand-primary">
                                ${{ number_format($articulo->precio_final, 0, ',', '.') }}
                            </span>
                            @endif
                        </td>
                        <td>
                            @if($articulo->activo)
                            <span class="badge bg-success">✅ Activo</span>
                            @else
                            <span class="badge bg-secondary">❌ Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">
                                {{ $articulo->opciones->count() }} opción(es)
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('catalogo.show', $articulo) }}" 
                                   class="btn btn-sm btn-outline-primary" title="Ver">
                                    👀
                                </a>
                                <a href="{{ route('admin.articulos.edit', $articulo) }}" 
                                   class="btn btn-sm btn-outline-warning" title="Editar">
                                    ✏️
                                </a>
                                <form action="{{ route('admin.articulos.destroy', $articulo) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Estás seguro de eliminar este artículo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $articulos->links() }}
        </div>
        
        @else
        <div class="text-center py-5">
            <h4 class="text-muted">📭 No hay artículos registrados</h4>
            <p class="text-muted">Comienza creando tu primer artículo</p>
            <a href="{{ route('admin.articulos.create') }}" class="btn btn-brand-primary">
                ➕ Crear Primer Artículo
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
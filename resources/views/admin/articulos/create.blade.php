@extends('layouts.app')

@section('title', 'Crear Nuevo Artículo')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.articulos.index') }}" class="text-brand-primary">⚙️ Administración</a>
            </li>
            <li class="breadcrumb-item active">Crear Artículo</li>
        </ol>
    </nav>

    <div class="admin-panel">
        <div class="text-center mb-4">
            <h2 class="text-brand-primary">➕ Crear Nuevo Artículo</h2>
            <p class="text-muted">Completa la información del producto</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger">
            <h6>❌ Por favor corrige los siguientes errores:</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.articulos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <!-- Información básica -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header bg-brand-primary text-white">
                            <h5 class="mb-0 text-brand-primary">📝 Información Básica</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="codigo" class="form-label">Código *</label>
                                    <input type="text" class="form-control @error('codigo') is-invalid @enderror" 
                                           id="codigo" name="codigo" value="{{ old('codigo') }}" 
                                           placeholder="Ej: CORR001" required>
                                    @error('codigo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="categoria_id" class="form-label">Categoría *</label>
                                    <select class="form-control @error('categoria_id') is-invalid @enderror" 
                                            id="categoria_id" name="categoria_id" required>
                                        <option value="">Seleccionar categoría...</option>
                                        @foreach(App\Models\Categoria::where('activa', true)->get() as $categoria)
                                        <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->icono }} {{ $categoria->nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('categoria_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="nombre" class="form-label">Nombre *</label>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                           id="nombre" name="nombre" value="{{ old('nombre') }}" 
                                           placeholder="Ej: Corrector de Maquillaje" required>
                                    @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                              id="descripcion" name="descripcion" rows="4" 
                                              placeholder="Describe las características y beneficios del producto...">{{ old('descripcion') }}</textarea>
                                    @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Precios -->
                    <div class="card mb-4">
                        <div class="card-header bg-brand-secondary">
                            <h5 class="mb-0 text-brand-primary">💰 Precios</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="valor" class="form-label">Precio Regular *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control @error('valor') is-invalid @enderror" 
                                               id="valor" name="valor" value="{{ old('valor') }}" 
                                               step="0.01" min="0" placeholder="0.00" required>
                                    </div>
                                    @error('valor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="valor_especial" class="form-label">Precio Especial (Descuento)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control @error('valor_especial') is-invalid @enderror" 
                                               id="valor_especial" name="valor_especial" value="{{ old('valor_especial') }}" 
                                               step="0.01" min="0" placeholder="0.00">
                                    </div>
                                    <small class="text-muted">Si aplica descuento, debe ser menor al precio regular</small>
                                    @error('valor_especial')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Opciones del producto -->
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">⚙️ Opciones del Producto</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Agrega opciones como tonos, tamaños, colores, etc.</p>
                            
                            <div id="opciones-container">
                                <div class="opcion-item border rounded p-3 mb-3">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label">Tipo</label>
                                            <select class="form-control" name="opciones[0][tipo_opcion]">
                                                <option value="">Seleccionar...</option>
                                                <option value="tono">Tono</option>
                                                <option value="color">Color</option>
                                                <option value="tamaño">Tamaño</option>
                                                <option value="material">Material</option>
                                                <option value="acabado">Acabado</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Valor</label>
                                            <input type="text" class="form-control" 
                                                   name="opciones[0][valor_opcion]" 
                                                   placeholder="Ej: Claro, Medio, Oscuro">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Código Color (opcional)</label>
                                            <input type="color" class="form-control form-control-color" 
                                                   name="opciones[0][codigo_color]">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Precio Extra</label>
                                            <input type="number" class="form-control" 
                                                   name="opciones[0][precio_adicional]" 
                                                   step="0.01" min="0" placeholder="0.00" value="0">
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-danger w-100 remove-opcion">🗑️</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-brand-secondary" id="add-opcion">
                                ➕ Agregar Opción
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Panel lateral -->
                <div class="col-lg-4">
                    <!-- Imágenes -->
                    <div class="card mb-4">
                        <div class="card-header bg-warning">
                            <h5 class="mb-0">🖼️ Imágenes del Producto</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <div id="preview-container" class="row g-2">
                                    <div class="col-12" id="placeholder-imagen" style="min-height: 200px;">
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center h-100">
                                            <span class="text-muted" style="font-size: 3rem;">📸</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="file" class="form-control @error('imagenes') is-invalid @enderror" 
                                   id="imagenes" name="imagenes[]" accept="image/*" multiple>
                            <small class="text-muted">JPG, PNG, GIF (máx. 2MB cada una) - Puedes seleccionar múltiples imágenes</small>
                            @error('imagenes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('imagenes.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">📊 Estado</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       id="activo" name="activo" value="1" 
                                       {{ old('activo', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">
                                    ✅ Producto Activo
                                </label>
                                <small class="text-muted d-block">
                                    Los productos activos aparecen en el catálogo público
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="card">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-brand-primary btn-lg">
                                    💾 Guardar Artículo
                                </button>
                                <a href="{{ route('admin.articulos.index') }}" 
                                   class="btn btn-brand-secondary">
                                    ← Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let opcionIndex = 1;
    
    // Agregar nueva opción
    document.getElementById('add-opcion').addEventListener('click', function() {
        const container = document.getElementById('opciones-container');
        const newOpcion = document.querySelector('.opcion-item').cloneNode(true);
        
        // Actualizar índices
        newOpcion.querySelectorAll('input, select').forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace('[0]', `[${opcionIndex}]`));
                input.value = '';
            }
        });
        
        container.appendChild(newOpcion);
        opcionIndex++;
        
        // Agregar event listener al botón de eliminar
        newOpcion.querySelector('.remove-opcion').addEventListener('click', function() {
            newOpcion.remove();
        });
    });
    
    // Eliminar opción
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-opcion')) {
            const opciones = document.querySelectorAll('.opcion-item');
            if (opciones.length > 1) {
                e.target.closest('.opcion-item').remove();
            } else {
                alert('Debe mantener al menos una opción o eliminar todas las opciones.');
            }
        }
    });
    
    // Preview de imágenes múltiples con gestión avanzada
    let imagenesSeleccionadas = [];
    let imagenPrincipal = 0;
    
    document.getElementById('imagenes').addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        imagenesSeleccionadas = [];
        
        files.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                imagenesSeleccionadas.push({
                    file: file,
                    index: index,
                    esPrincipal: index === 0
                });
            }
        });
        
        mostrarPreviewImagenes();
    });
    
    function mostrarPreviewImagenes() {
        const container = document.getElementById('preview-container');
        const placeholder = document.getElementById('placeholder-imagen');
        
        if (!container || !placeholder) return;
        
        container.innerHTML = '';
        
        if (imagenesSeleccionadas.length > 0) {
            placeholder.style.display = 'none';
            
            imagenesSeleccionadas.forEach((imagen, index) => {
                if (!imagen || !imagen.file) return;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (!e.target || !e.target.result) return;
                    
                    const colDiv = document.createElement('div');
                    colDiv.className = 'col-6 mb-2';
                    colDiv.style.position = 'relative';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-fluid rounded imagen-preview';
                    img.style.cssText = 'height: 120px; object-fit: cover; width: 100%; cursor: pointer;';
                    
                    // Badge principal
                    if (imagen.esPrincipal) {
                        const badgePrincipal = document.createElement('span');
                        badgePrincipal.className = 'badge bg-success position-absolute';
                        badgePrincipal.style.cssText = 'top: 5px; left: 5px; z-index: 1;';
                        badgePrincipal.textContent = '⭐ Principal';
                        colDiv.appendChild(badgePrincipal);
                    }
                    
                    // Botón eliminar
                    const btnEliminar = document.createElement('button');
                    btnEliminar.type = 'button';
                    btnEliminar.className = 'btn btn-danger btn-sm btn-imagen position-absolute';
                    btnEliminar.style.cssText = 'top: 5px; right: 5px; z-index: 1; padding: 4px 8px;';
                    btnEliminar.innerHTML = '🗑️';
                    btnEliminar.onclick = () => eliminarImagen(index);
                    
                    // Botón marcar como principal
                    if (!imagen.esPrincipal && imagenesSeleccionadas.length > 1) {
                        const btnPrincipal = document.createElement('button');
                        btnPrincipal.type = 'button';
                        btnPrincipal.className = 'btn btn-warning btn-sm btn-imagen position-absolute';
                        btnPrincipal.style.cssText = 'bottom: 5px; left: 5px; z-index: 1; padding: 2px 6px; font-size: 10px;';
                        btnPrincipal.innerHTML = '⭐';
                        btnPrincipal.title = 'Marcar como principal';
                        btnPrincipal.onclick = () => marcarComoPrincipal(index);
                        colDiv.appendChild(btnPrincipal);
                    }
                    
                    // Badge número
                    const badgeNumero = document.createElement('span');
                    badgeNumero.className = 'badge bg-primary position-absolute';
                    badgeNumero.style.cssText = 'bottom: 5px; right: 5px; z-index: 1;';
                    badgeNumero.textContent = index + 1;
                    
                    colDiv.appendChild(img);
                    colDiv.appendChild(btnEliminar);
                    colDiv.appendChild(badgeNumero);
                    
                    if (container) {
                        container.appendChild(colDiv);
                    }
                };
                reader.readAsDataURL(imagen.file);
            });
        } else {
            if (placeholder) {
                placeholder.style.display = 'block';
                container.appendChild(placeholder);
            }
        }
    }
    
    function eliminarImagen(index) {
        if (imagenesSeleccionadas.length <= 1) {
            alert('Debe mantener al menos una imagen');
            return;
        }
        
        // Si eliminas la principal, la primera restante será la nueva principal
        const eraLaPrincipal = imagenesSeleccionadas[index].esPrincipal;
        imagenesSeleccionadas.splice(index, 1);
        
        if (eraLaPrincipal && imagenesSeleccionadas.length > 0) {
            imagenesSeleccionadas[0].esPrincipal = true;
        }
        
        actualizarInputFile();
        mostrarPreviewImagenes();
    }
    
    function marcarComoPrincipal(index) {
        // Desmarcar la anterior principal
        imagenesSeleccionadas.forEach(img => img.esPrincipal = false);
        
        // Marcar la nueva principal
        imagenesSeleccionadas[index].esPrincipal = true;
        
        // Mover la imagen principal al inicio del array
        const imagenPrincipal = imagenesSeleccionadas.splice(index, 1)[0];
        imagenesSeleccionadas.unshift(imagenPrincipal);
        
        actualizarInputFile();
        mostrarPreviewImagenes();
    }
    
    function actualizarInputFile() {
        const inputFile = document.getElementById('imagenes');
        if (!inputFile) return;
        
        const dt = new DataTransfer();
        imagenesSeleccionadas.forEach(imagen => {
            if (imagen && imagen.file) {
                dt.items.add(imagen.file);
            }
        });
        inputFile.files = dt.files;
    }
    
    // Validación de precio especial
    document.getElementById('valor_especial').addEventListener('input', function() {
        const valorRegular = parseFloat(document.getElementById('valor').value) || 0;
        const valorEspecial = parseFloat(this.value) || 0;
        
        if (valorEspecial > 0 && valorEspecial >= valorRegular) {
            this.setCustomValidity('El precio especial debe ser menor al precio regular');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>
@endpush
@endsection
@extends('layouts.app')

@section('title', 'Editar Artículo')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.articulos.index') }}" class="text-brand-primary">⚙️ Administración</a>
            </li>
            <li class="breadcrumb-item active">Editar: {{ $articulo->nombre }}</li>
        </ol>
    </nav>

    <div class="admin-panel">
        <div class="text-center mb-4">
            <h2 class="text-brand-primary">✏️ Editar Artículo</h2>
            <p class="text-muted">Modifica la información del producto</p>
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

        <!-- FORMULARIO PRINCIPAL -->
        <form id="form-actualizar-articulo" enctype="multipart/form-data">
            @csrf
            
            <!-- Campos ocultos para identificación -->
            <input type="hidden" name="articulo_id" value="{{ $articulo->id }}">
            <input type="hidden" name="_method" value="PUT">
            
            <div class="row">
                <!-- COLUMNA IZQUIERDA - INFORMACIÓN PRINCIPAL -->
                <div class="col-lg-8">
                    <!-- Información básica -->
                    <div class="card mb-4">
                        <div class="card-header bg-brand-primary text-black">
                            <h5 class="mb-0 text-brand-primary">📝 Información Básica</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="codigo" class="form-label">Código *</label>
                                    <input type="text" 
                                           class="form-control @error('codigo') is-invalid @enderror" 
                                           id="codigo" 
                                           name="codigo" 
                                           value="{{ old('codigo', $articulo->codigo) }}" 
                                           placeholder="Ej: CORR001" 
                                           required>
                                    @error('codigo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre *</label>
                                    <input type="text" 
                                           class="form-control @error('nombre') is-invalid @enderror" 
                                           id="nombre" 
                                           name="nombre" 
                                           value="{{ old('nombre', $articulo->nombre) }}" 
                                           placeholder="Ej: Corrector de Maquillaje" 
                                           required>
                                    @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="categoria_id" class="form-label">Categoría *</label>
                                    <select class="form-control @error('categoria_id') is-invalid @enderror" 
                                            id="categoria_id" 
                                            name="categoria_id" 
                                            required>
                                        <option value="">Selecciona una categoría...</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}" 
                                                    {{ old('categoria_id', $articulo->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                                {{ $categoria->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('categoria_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="descripcion" class="form-label">Descripción *</label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                              id="descripcion" 
                                              name="descripcion" 
                                              rows="4" 
                                              placeholder="Describe las características y beneficios del producto..."
                                              required>{{ old('descripcion', $articulo->descripcion) }}</textarea>
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
                                        <input type="number" 
                                               class="form-control @error('valor') is-invalid @enderror" 
                                               id="valor" 
                                               name="valor" 
                                               value="{{ old('valor', $articulo->valor) }}" 
                                               step="0.01" 
                                               min="0" 
                                               placeholder="0.00" 
                                               required>
                                    </div>
                                    @error('valor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="valor_especial" class="form-label">Precio Especial (Descuento)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" 
                                               class="form-control @error('valor_especial') is-invalid @enderror" 
                                               id="valor_especial" 
                                               name="valor_especial" 
                                               value="{{ old('valor_especial', $articulo->valor_especial) }}" 
                                               step="0.01" 
                                               min="0" 
                                               placeholder="0.00">
                                    </div>
                                    <small class="text-muted">Si aplica descuento, debe ser menor al precio regular</small>
                                    @error('valor_especial')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Opciones existentes -->
                    @if($articulo->opciones->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">⚙️ Opciones Existentes</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach($articulo->opciones as $opcion)
                                <div class="col-md-6">
                                    <div class="border rounded p-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ ucfirst($opcion->tipo_opcion) }}:</strong> {{ $opcion->valor_opcion }}
                                            @if($opcion->codigo_color)
                                            <span class="opcion-color ms-2" style="background-color: {{ $opcion->codigo_color }}; width: 20px; height: 20px; display: inline-block; border: 1px solid #ccc; border-radius: 3px;"></span>
                                            @endif
                                            @if($opcion->precio_adicional > 0)
                                            <span class="badge bg-secondary small">+${{ number_format($opcion->precio_adicional, 0, ',', '.') }}</span>
                                            @endif
                                        </div>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                onclick="eliminarOpcion({{ $opcion->id }})"
                                                title="Eliminar opción">
                                            🗑️
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Agregar nuevas opciones -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">➕ Agregar Nueva Opción</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Tipo</label>
                                    <select class="form-control" id="nueva_tipo_opcion">
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
                                    <input type="text" 
                                           class="form-control" 
                                           id="nueva_valor_opcion" 
                                           placeholder="Ej: Claro, Medio, Oscuro">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Color</label>
                                    <input type="color" 
                                           class="form-control form-control-color" 
                                           id="nueva_codigo_color">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Precio Extra</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="nueva_precio_adicional" 
                                           step="0.01" 
                                           min="0" 
                                           placeholder="0.00">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" 
                                            class="btn btn-success w-100" 
                                            onclick="agregarOpcion()">
                                        ➕
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Mensaje de estado -->
                            <div id="opcion-message" class="mt-3" style="display: none;"></div>
                        </div>
                    </div>
                </div>

                <!-- COLUMNA DERECHA - PANEL LATERAL -->
                <div class="col-lg-4">
                    <!-- Imágenes actuales -->
                    @if($articulo->imagenes->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header bg-warning">
                            <h5 class="mb-0">🖼️ Imágenes Actuales</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                @foreach($articulo->imagenes as $imagen)
                                <div class="col-6 position-relative">
                                    <img src="{{ $imagen->url }}" 
                                        alt="Imagen {{ $loop->iteration }}" 
                                        class="img-thumbnail w-100" 
                                        style="height: 120px; object-fit: cover;">
                                    
                                    <!-- Badge de imagen principal -->
                                    @if($imagen->es_principal)
                                    <span class="position-absolute top-0 start-0 badge bg-success m-1">
                                        ⭐ Principal
                                    </span>
                                    @endif
                                    
                                    <!-- Botones de acción -->
                                    <div class="position-absolute top-0 end-0 m-1">
                                        <div class="btn-group-vertical btn-group-sm">
                                            @if(!$imagen->es_principal)
                                            <form action="{{ route('admin.imagenes.principal', $imagen) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" 
                                                        class="btn btn-warning btn-sm" 
                                                        title="Marcar como principal">
                                                    ⭐
                                                </button>
                                            </form>
                                            @endif
                                            
                                            <form action="{{ route('admin.imagenes.destroy', $imagen) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('¿Eliminar esta imagen?')"
                                                        title="Eliminar imagen">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <!-- Orden de la imagen -->
                                    <small class="position-absolute bottom-0 start-0 badge bg-dark m-1">
                                        #{{ $imagen->orden + 1 }}
                                    </small>
                                </div>
                                @endforeach
                            </div>
                            
                            <hr>
                            
                            <!-- Reordenar imágenes -->
                            <div class="text-center">
                                <small class="text-muted">
                                    💡 Puedes marcar una imagen como principal haciendo clic en ⭐
                                </small>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Agregar nuevas imágenes -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">📷 Agregar Nuevas Imágenes</h5>
                        </div>
                        <div class="card-body">
                            <!-- Preview de nuevas imágenes -->
                            <div id="preview-container" class="mb-3" style="display: none;">
                                <h6>Vista previa:</h6>
                                <div id="preview-images" class="row g-2"></div>
                            </div>
                            
                            <input type="file" 
                                   class="form-control @error('imagenes.*') is-invalid @enderror" 
                                   id="imagenes" 
                                   name="imagenes[]" 
                                   accept="image/*" 
                                   multiple>
                            <small class="text-muted">
                                JPG, PNG, GIF (máx. 2MB cada una)<br>
                                Puedes seleccionar múltiples imágenes
                            </small>
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
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="activo" 
                                       name="activo" 
                                       value="1" 
                                       {{ old('activo', $articulo->activo) ? 'checked' : '' }}>
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
                                <!-- BOTÓN DE ACTUALIZAR CON AJAX -->
                                <button type="button" 
                                        class="btn btn-brand-primary btn-lg" 
                                        id="btn-actualizar">
                                    💾 Actualizar Artículo
                                </button>
                                
                                <a href="{{ route('admin.articulos.index') }}" 
                                   class="btn btn-brand-secondary">
                                    ← Volver al Listado
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- META TAG PARA CSRF TOKEN -->
<meta name="csrf-token" content="{{ csrf_token() }}">

@push('scripts')
<script>

// Pasar rutas de Laravel a JavaScript
window.routes = {
    updateArticulo: "{{ route('admin.articulos.update', $articulo->id) }}",
    storeOpcion: "{{ route('admin.articulos.opciones.store', $articulo) }}",
    destroyOpcion: "{{ url('/admin/articulos/opciones') }}/"
};


document.addEventListener('DOMContentLoaded', function() {
    
    // Preview de múltiples imágenes
    const inputImagenes = document.getElementById('imagenes');
    if (inputImagenes) {
        inputImagenes.addEventListener('change', function(e) {
            const files = e.target.files;
            const previewContainer = document.getElementById('preview-container');
            const previewImages = document.getElementById('preview-images');
            
            // Limpiar previews anteriores
            previewImages.innerHTML = '';
            
            if (files.length > 0) {
                previewContainer.style.display = 'block';
                
                Array.from(files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const col = document.createElement('div');
                            col.className = 'col-6';
                            
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'img-thumbnail w-100';
                            img.style.height = '80px';
                            img.style.objectFit = 'cover';
                            
                            const small = document.createElement('small');
                            small.className = 'text-muted d-block text-center';
                            small.textContent = `Nueva ${index + 1}`;
                            
                            col.appendChild(img);
                            col.appendChild(small);
                            previewImages.appendChild(col);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                previewContainer.style.display = 'none';
            }
        });
    }
    
    // Validación de precio especial
    const valorEspecialInput = document.getElementById('valor_especial');
    if (valorEspecialInput) {
        valorEspecialInput.addEventListener('input', function() {
            const valorRegular = parseFloat(document.getElementById('valor').value) || 0;
            const valorEspecial = parseFloat(this.value) || 0;
            
            if (valorEspecial > 0 && valorEspecial >= valorRegular) {
                this.setCustomValidity('El precio especial debe ser menor al precio regular');
            } else {
                this.setCustomValidity('');
            }
        });
    }
    
    // Event listener para el botón de actualizar con AJAX
    const btnActualizar = document.getElementById('btn-actualizar');
    if (btnActualizar) {
        btnActualizar.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirmarActualizacion()) {
                enviarFormularioAjax();
            } else {
            }
        });
    }
});

// Función para confirmar actualización
function confirmarActualizacion() {
    
    // Verificar campos requeridos básicos
    const codigo = document.getElementById('codigo').value.trim();
    const nombre = document.getElementById('nombre').value.trim();
    const categoria = document.getElementById('categoria_id').value;
    const descripcion = document.getElementById('descripcion').value.trim();
    const valor = document.getElementById('valor').value;
    
    if (!codigo || !nombre || !categoria || !descripcion || !valor) {
        alert('Por favor completa todos los campos obligatorios marcados con *');
        return false;
    }
    
    // Validar precio especial si está presente
    const valorEspecial = document.getElementById('valor_especial').value;
    if (valorEspecial && parseFloat(valorEspecial) >= parseFloat(valor)) {
        alert('El precio especial debe ser menor al precio regular');
        return false;
    }
    
    return confirm('¿Estás seguro de actualizar este artículo?');
}

// FUNCIÓN PRINCIPAL: ENVÍO VIA AJAX
function enviarFormularioAjax() {
    
    const form = document.getElementById('form-actualizar-articulo');
    const formData = new FormData(form);
    
    // FORZAR MÉTODO PUT
    formData.set('_method', 'PUT');
    formData.set('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // URL ESPECÍFICA PARA UPDATE
    const url = window.routes.updateArticulo;
    
    // Deshabilitar botón
    const btnActualizar = document.getElementById('btn-actualizar');
    const textoOriginal = btnActualizar.innerHTML;
    btnActualizar.disabled = true;
    btnActualizar.innerHTML = '⏳ Actualizando...';
    
    fetch(url, {
        method: 'POST', // Laravel necesita POST con _method=PUT
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Verificar el tipo de contenido de la respuesta
        const contentType = response.headers.get('content-type');
        
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            // Si es una redirección o HTML (respuesta exitosa de Laravel)
            return { success: true, redirect: true };
        }
    })
    .then(data => {
        
        if (data.success || data.redirect) {
            
            // Mostrar mensaje de éxito directamente en la aplicación
            mostrarMensajeExito('✅ Artículo actualizado exitosamente');
            
            // Redirigir después de mostrar el mensaje
            setTimeout(() => {
                window.location.href = '{{ route("admin.articulos.index") }}';
            }, 1500);
        } else {
            throw new Error(data.message || 'Error desconocido en la actualización');
        }
    })
    .catch(error => {
        console.error('=== ERROR ===');
        console.error('Error completo:', error);
        console.error('Stack:', error.stack);
        
        // Mostrar error al usuario directamente en la aplicación
        mostrarMensajeError('❌ Error al actualizar: ' + error.message);
        
        // Rehabilitar botón
        btnActualizar.disabled = false;
        btnActualizar.innerHTML = textoOriginal;
    });
}

// Función para agregar opción via AJAX (sin cambios)
function agregarOpcion() {
    
    const tipoOpcion = document.getElementById('nueva_tipo_opcion').value;
    const valorOpcion = document.getElementById('nueva_valor_opcion').value;
    const codigoColor = document.getElementById('nueva_codigo_color').value;
    const precioAdicional = document.getElementById('nueva_precio_adicional').value || 0;
    
    // Validar campos requeridos
    if (!tipoOpcion || !valorOpcion) {
        mostrarMensaje('❌ Tipo y valor son campos obligatorios', 'danger');
        return;
    }
    
    // Crear FormData
    const formData = new FormData();
    formData.append('tipo_opcion', tipoOpcion);
    formData.append('valor_opcion', valorOpcion);
    formData.append('codigo_color', codigoColor);
    formData.append('precio_adicional', precioAdicional);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Deshabilitar botón mientras se procesa
    const boton = event.target;
    const textoOriginal = boton.innerHTML;
    boton.disabled = true;
    boton.innerHTML = '⏳ Agregando...';
    
    // Realizar petición AJAX
    fetch(window.routes.storeOpcion, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            mostrarMensaje('✅ ' + data.message, 'success');
            
            // Limpiar formulario
            document.getElementById('nueva_tipo_opcion').value = '';
            document.getElementById('nueva_valor_opcion').value = '';
            document.getElementById('nueva_codigo_color').value = '#000000';
            document.getElementById('nueva_precio_adicional').value = '';
            
            // Recargar la página después de 1 segundo para mostrar la nueva opción
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            mostrarMensaje('❌ ' + (data.message || 'Error al agregar la opción'), 'danger');
        }
    })
    .catch(error => {
        console.error('Error completo:', error);
        mostrarMensaje('❌ Error de conexión. Detalles: ' + error.message, 'danger');
    })
    .finally(() => {
        // Rehabilitar botón
        boton.disabled = false;
        boton.innerHTML = textoOriginal;
    });
}

// Función para eliminar opción via AJAX (sin cambios)
function eliminarOpcion(opcionId) {
    
    if (!confirm('¿Estás seguro de eliminar esta opción?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('_method', 'DELETE');
    
    const url = window.routes.destroyOpcion + opcionId;
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            mostrarMensaje('✅ ' + data.message, 'success');
            
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            mostrarMensaje('❌ ' + (data.message || 'Error al eliminar la opción'), 'danger');
        }
    })
    .catch(error => {
        console.error('Error completo:', error);
        mostrarMensaje('❌ Error de conexión. Detalles: ' + error.message, 'danger');
    });
}

// Función para mostrar mensajes
function mostrarMensaje(mensaje, tipo) {
    const messageDiv = document.getElementById('opcion-message');
    if (messageDiv) {
        messageDiv.className = `alert alert-${tipo}`;
        messageDiv.innerHTML = mensaje;
        messageDiv.style.display = 'block';
        
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 3000);
    } else {
        // Fallback: usar alert si no existe el contenedor
        alert(mensaje.replace(/[❌✅]/g, '').trim());
    }
}

// Función para mostrar mensaje de éxito global
function mostrarMensajeExito(mensaje) {
    // Crear o obtener el contenedor de mensajes globales
    let alertContainer = document.getElementById('alert-container');
    
    if (!alertContainer) {
        alertContainer = document.createElement('div');
        alertContainer.id = 'alert-container';
        alertContainer.style.position = 'fixed';
        alertContainer.style.top = '20px';
        alertContainer.style.right = '20px';
        alertContainer.style.zIndex = '9999';
        alertContainer.style.maxWidth = '400px';
        document.body.appendChild(alertContainer);
    }
    
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show';
    alertDiv.style.marginBottom = '10px';
    alertDiv.innerHTML = `
        <strong>${mensaje}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    alertContainer.appendChild(alertDiv);
    
    // Auto-remover después de 3 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 3000);
}

// Función para mostrar mensaje de error global
function mostrarMensajeError(mensaje) {
    // Crear o obtener el contenedor de mensajes globales
    let alertContainer = document.getElementById('alert-container');
    
    if (!alertContainer) {
        alertContainer = document.createElement('div');
        alertContainer.id = 'alert-container';
        alertContainer.style.position = 'fixed';
        alertContainer.style.top = '20px';
        alertContainer.style.right = '20px';
        alertContainer.style.zIndex = '9999';
        alertContainer.style.maxWidth = '400px';
        document.body.appendChild(alertContainer);
    }
    
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
    alertDiv.style.marginBottom = '10px';
    alertDiv.innerHTML = `
        <strong>${mensaje}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    alertContainer.appendChild(alertDiv);
    
    // Auto-remover después de 5 segundos (más tiempo para errores)
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
@endpush
@endsection
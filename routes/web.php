<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CarritoController;

// RUTA TEMPORAL PARA EJECUTAR COMANDOS (ELIMINAR DESPUÉS)
Route::get('/fix-commands', function() {
    try {
        Artisan::call('storage:link', ['--force' => true]);
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        
        return '<h2>✅ Commands executed successfully!</h2>' .
               '<ul>' .
               '<li>✅ storage:link --force</li>' .
               '<li>✅ config:clear</li>' .
               '<li>✅ route:clear</li>' .
               '<li>✅ view:clear</li>' .
               '<li>✅ cache:clear</li>' .
               '</ul>' .
               '<p><a href="' . url('/') . '">← Volver al inicio</a></p>' .
               '<p><strong>Nota:</strong> Eliminar esta ruta después del uso.</p>';
    } catch (Exception $e) {
        return '<h2>❌ Error:</h2><p>' . $e->getMessage() . '</p>';
    }
});

// Página principal - Catálogo
Route::get('/', [CatalogoController::class, 'index'])->name('catalogo.index');
Route::get('/articulo/{articulo}', [CatalogoController::class, 'show'])->name('catalogo.show');
Route::get('/categoria/{categoria:slug}', [CatalogoController::class, 'categoria'])->name('catalogo.categoria');

// Carrito de compras
Route::prefix('carrito')->name('carrito.')->group(function () {
    Route::get('/', [CarritoController::class, 'index'])->name('index');
    Route::post('/agregar/{articulo}', [CarritoController::class, 'agregar'])->name('agregar');
    Route::patch('/actualizar/{item}', [CarritoController::class, 'actualizar'])->name('actualizar');
    Route::delete('/eliminar/{item}', [CarritoController::class, 'eliminar'])->name('eliminar');
    Route::delete('/vaciar', [CarritoController::class, 'vaciar'])->name('vaciar');
    Route::get('/contador', [CarritoController::class, 'contador'])->name('contador');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        return redirect()->route('admin.articulos.index');
    })->name('profile.edit');
});

// Dashboard (redirige al catálogo)
Route::get('/dashboard', function () {
    return redirect()->route('catalogo.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Panel de administración (solo usuarios autenticados)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.articulos.index');
    });
    
    // Categorías
    Route::resource('categorias', CategoriaController::class);
    
    // Artículos
    Route::resource('articulos', ArticuloController::class);
    
    // Opciones de artículos
    Route::post('articulos/{articulo}/opciones', [ArticuloController::class, 'storeOpcion'])->name('articulos.opciones.store');
    Route::delete('articulos/opciones/{opcion}', [ArticuloController::class, 'destroyOpcion'])->name('articulos.opciones.destroy');
    
    // Rutas para manejo de imágenes (MOVIDAS AQUÍ DENTRO)
    Route::put('imagenes/{imagen}/principal', [ArticuloController::class, 'marcarPrincipal'])
        ->name('imagenes.principal');
    Route::delete('imagenes/{imagen}', [ArticuloController::class, 'eliminarImagen'])
        ->name('imagenes.destroy');
});

require __DIR__.'/auth.php';
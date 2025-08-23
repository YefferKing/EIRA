<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Categoria;
use App\Models\OpcionArticulo;
use App\Models\ImagenArticulo;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize;

class ArticuloController extends Controller

{
    public function index()
    {
        $articulos = Articulo::with(['opciones', 'imagenPrincipal', 'categoria'])->latest()->paginate(15);
        return view('admin.articulos.index', compact('articulos'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        return view('admin.articulos.create', compact('categorias'));
    }

    public function store(Request $request)
{
    $request->validate([
        'codigo' => 'required|unique:articulos|max:50',
        'nombre' => 'required|max:255',
        'categoria_id' => 'required|exists:categorias,id',
        'descripcion' => 'required',
        'valor' => 'required|numeric|min:0',
        'valor_especial' => 'nullable|numeric|min:0|lt:valor',
        'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ], [
        'categoria_id.required' => 'Debe seleccionar una categoría.',
        'categoria_id.exists' => 'La categoría seleccionada no es válida.',
        'valor_especial.lt' => 'El precio especial debe ser menor al precio regular.',
    ]);

    $data = $request->except(['imagenes']);
    
    // Determinar si tiene descuento
    $data['tiene_descuento'] = $request->filled('valor_especial') && $request->valor_especial < $request->valor;
    
    $articulo = Articulo::create($data);
    
    // Manejar múltiples imágenes
    if ($request->hasFile('imagenes')) {
        // Inicializar Cloudinary
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ]
        ]);
        
        foreach ($request->file('imagenes') as $index => $imagen) {
            try {
                // Usar la sintaxis correcta de Cloudinary
                $result = $cloudinary->uploadApi()->upload($imagen->getRealPath(), [
                    'folder' => 'eira'
                ]);
                
                $uploadedFileUrl = $result['secure_url'];
                
                ImagenArticulo::create([
                    'articulo_id' => $articulo->id,
                    'url' => $uploadedFileUrl,
                    'orden' => $index,
                    'es_principal' => $index === 0 // Primera imagen es principal
                ]);
            } catch (\Exception $e) {
                \Log::error('Error subiendo imagen: ' . $e->getMessage());
            }
        }
    }
    
    // Crear opciones si se enviaron
    if ($request->filled('opciones')) {
        foreach ($request->opciones as $opcion) {
            if (!empty($opcion['tipo_opcion']) && !empty($opcion['valor_opcion'])) {
                // Asegurar que precio_adicional tenga un valor
                $opcionData = [
                    'tipo_opcion' => $opcion['tipo_opcion'],
                    'valor_opcion' => $opcion['valor_opcion'],
                    'codigo_color' => $opcion['codigo_color'] ?? null,
                    'precio_adicional' => $opcion['precio_adicional'] ?? 0,
                ];
                
                $articulo->opciones()->create($opcionData);
            }
        }
    }

    return redirect()->route('admin.articulos.index')
                    ->with('success', 'Artículo creado exitosamente.');
}

    public function marcarPrincipal(ImagenArticulo $imagen)
    {
        // Quitar principal de todas las imágenes del artículo
        ImagenArticulo::where('articulo_id', $imagen->articulo_id)
            ->update(['es_principal' => false]);
        
        // Marcar esta como principal
        $imagen->update(['es_principal' => true]);
        
        return redirect()->back()->with('success', 'Imagen marcada como principal.');
    }

    public function eliminarImagen(ImagenArticulo $imagen)
    {
        try {
            // Si es una imagen de Cloudinary, eliminarla del servidor
            if (str_contains($imagen->url, 'cloudinary')) {
                $cloudinary = new Cloudinary([
                    'cloud' => [
                        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                        'api_key' => env('CLOUDINARY_API_KEY'),
                        'api_secret' => env('CLOUDINARY_API_SECRET'),
                    ]
                ]);
                
                // Extraer el public_id de la URL
                $parts = explode('/', parse_url($imagen->url, PHP_URL_PATH));
                $filename = pathinfo(end($parts), PATHINFO_FILENAME);
                $publicId = 'eira/' . $filename;
                
                $cloudinary->uploadApi()->destroy($publicId);
            }
            
            $articuloId = $imagen->articulo_id;
            $esPrincipal = $imagen->es_principal;
            
            // Eliminar la imagen de la base de datos
            $imagen->delete();
            
            // Si era la imagen principal y quedan más imágenes, marcar la primera como principal
            if ($esPrincipal) {
                $primeraImagen = ImagenArticulo::where('articulo_id', $articuloId)
                    ->orderBy('orden')
                    ->first();
                
                if ($primeraImagen) {
                    $primeraImagen->update(['es_principal' => true]);
                }
            }
            
            return redirect()->back()->with('success', 'Imagen eliminada correctamente.');
            
        } catch (\Exception $e) {
            \Log::error('Error eliminando imagen: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar la imagen.');
        }
    }

    public function show(Articulo $articulo)
    {
        $articulo->load(['opciones', 'imagenes', 'categoria']);
        return view('admin.articulos.show', compact('articulo'));
    }

    public function edit(Articulo $articulo)
    {
        $articulo->load(['opciones', 'imagenes' => function($query) {
            $query->orderBy('orden');
        }, 'categoria']);
        
        $categorias = Categoria::orderBy('nombre')->get();
        
        return view('admin.articulos.edit', compact('articulo', 'categorias'));
    }

    public function update(Request $request, Articulo $articulo)
    {
        $request->validate([
            'codigo' => 'required|max:50|unique:articulos,codigo,' . $articulo->id,
            'nombre' => 'required|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion' => 'required',
            'valor' => 'required|numeric|min:0',
            'valor_especial' => 'nullable|numeric|min:0|lt:valor',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'categoria_id.required' => 'Debe seleccionar una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
            'valor_especial.lt' => 'El precio especial debe ser menor al precio regular.',
        ]);

        $data = $request->except(['imagenes']);
        
        // Determinar si tiene descuento
        $data['tiene_descuento'] = $request->filled('valor_especial') && $request->valor_especial < $request->valor;
        
        $articulo->update($data);

        // Manejar nuevas imágenes (si se enviaron)
        if ($request->hasFile('imagenes')) {
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ]
            ]);
            
            $ultimoOrden = $articulo->imagenes()->max('orden') ?? -1;
            
            foreach ($request->file('imagenes') as $index => $imagen) {
                try {
                    // Usar Cloudinary en lugar de almacenamiento local
                    $result = $cloudinary->uploadApi()->upload($imagen->getRealPath(), [
                        'folder' => 'eira'
                    ]);
                    
                    $uploadedFileUrl = $result['secure_url'];
                    
                    ImagenArticulo::create([
                        'articulo_id' => $articulo->id,
                        'url' => $uploadedFileUrl,
                        'orden' => $ultimoOrden + $index + 1,
                        'es_principal' => $articulo->imagenes()->count() === 0 && $index === 0
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Error subiendo imagen: ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('admin.articulos.index')
                        ->with('success', 'Artículo actualizado exitosamente.');
    }

    public function destroy(Articulo $articulo)
{
    // Eliminar imágenes de Cloudinary
    foreach ($articulo->imagenes as $imagen) {
        if (str_contains($imagen->url, 'cloudinary')) {
            try {
                $cloudinary = new Cloudinary([
                    'cloud' => [
                        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                        'api_key' => env('CLOUDINARY_API_KEY'),
                        'api_secret' => env('CLOUDINARY_API_SECRET'),
                    ]
                ]);
                
                $publicId = pathinfo(parse_url($imagen->url, PHP_URL_PATH), PATHINFO_FILENAME);
                $cloudinary->uploadApi()->destroy('eira/' . $publicId);
            } catch (\Exception $e) {
                \Log::error('Error eliminando imagen de Cloudinary: ' . $e->getMessage());
            }
        }
    }
    
    $articulo->delete();

    return redirect()->route('admin.articulos.index')
                    ->with('success', 'Artículo eliminado exitosamente.');
}
    
    public function storeOpcion(Request $request, Articulo $articulo)
    {
        $request->merge([
            'precio_adicional' => $request->precio_adicional ?? 0
        ]);
        
        $request->validate([
            'tipo_opcion' => 'required|string|max:50',
            'valor_opcion' => 'required|string|max:100',
            'codigo_color' => 'nullable|string|max:7',
            'precio_adicional' => 'numeric|min:0',
        ]);
        
        $opcion = $articulo->opciones()->create($request->all());
        
        // Si es petición AJAX, devolver JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Opción agregada exitosamente.',
                'opcion' => $opcion
            ]);
        }
        
        return redirect()->back()->with('success', 'Opción agregada exitosamente.');
    }
    
    public function destroyOpcion(OpcionArticulo $opcion)
    {
        try {
            $opcion->delete();
            
            // Si es petición AJAX, devolver JSON
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Opción eliminada exitosamente.'
                ]);
            }
            
            return redirect()->back()->with('success', 'Opción eliminada exitosamente.');
            
        } catch (\Exception $e) {
            \Log::error('Error eliminando opción: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la opción.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error al eliminar la opción.');
        }
    }
}
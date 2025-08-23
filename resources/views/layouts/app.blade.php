<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('eira.ico') }}">

    <title>@yield('title', 'Eira')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cherry+Bomb+One&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cherry+Bomb+One&display=swap');

        :root {
            --primary-color: #401874;
            --secondary-color: #E6D6FA;
            --primary-font: 'Cherry Bomb One', cursive;
        }

        body {
            font-family: 'Cherry Bomb One', cursive;
            background: linear-gradient(135deg, var(--secondary-color) 0%, #f8f9fa 100%);
            min-height: 100vh;
        }

        .brand-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .brand-secondary {
            background-color: var(--secondary-color) !important;
            border-color: var(--secondary-color) !important;
            color: var(--primary-color) !important;
        }

        .text-brand-primary {
            color: var(--primary-color) !important;
        }

        .text-brand-secondary {
            color: var(--secondary-color) !important;
        }

        .navbar-brand {
            font-family: var(--primary-font);
            font-size: 1.8rem;
            color: var(--primary-color) !important;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(64, 24, 116, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(64, 24, 116, 0.2);
        }

        .btn-brand-primary {
            background: linear-gradient(45deg, var(--primary-color), #5c2a94);
            border: none;
            color: white;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: normal;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-brand-primary:hover {
            background: linear-gradient(45deg, #5c2a94, var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(64, 24, 116, 0.3);
            color: white;
        }

        .btn-brand-secondary {
            background: var(--secondary-color);
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: normal;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-brand-secondary:hover {
            background: var(--primary-color);
            color: var(--secondary-color);
        }

        .form-control {
            border: 2px solid var(--secondary-color);
            border-radius: 10px;
            font-family: var(--primary-font);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(64, 24, 116, 0.25);
        }

        .precio-original {
            text-decoration: line-through;
            color: #6c757d;
            font-size: 0.9em;
        }

        .precio-especial {
            color: #dc3545;
            font-weight: normal;
            font-size: 1.2em;
        }

        .badge-descuento {
            background: linear-gradient(45deg, #dc3545, #ff6b6b);
            color: white;
            border-radius: 50px;
            padding: 5px 15px;
            font-size: 0.8em;
            font-weight: normal;
        }

        .opcion-color {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            cursor: pointer;
            transition: transform 0.2s ease;
            display: inline-block;
            margin: 2px;
        }

        .opcion-color:hover {
            transform: scale(1.1);
        }

        .opcion-color.selected {
            border-color: var(--primary-color);
            transform: scale(1.2);
        }

        .header-elegante {
            background: linear-gradient(135deg, var(--primary-color) 0%, #5c2a94 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .admin-panel {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            margin: 1rem 0;
            box-shadow: 0 10px 30px rgba(64, 24, 116, 0.1);
        }

        /* Estilos para carrusel de producto */
        .carousel-indicators button {
            border: 3px solid white;
            border-radius: 8px;
            opacity: 0.7;
            transition: all 0.3s ease;
        }

        .carousel-indicators button.active {
            opacity: 1;
            border-color: var(--primary-color);
            transform: scale(1.1);
        }

        .carousel-indicators button:hover {
            opacity: 0.9;
            transform: scale(1.05);
        }

        .carousel-control-prev, .carousel-control-next {
            background: rgba(64, 24, 116, 0.8);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            top: 50%;
            transform: translateY(-50%);
        }

        .carousel-control-prev {
            left: 10px;
        }

        .carousel-control-next {
            right: 10px;
        }

        /* Estilos para gestión de imágenes */
        .imagen-preview {
            transition: all 0.3s ease;
        }

        .imagen-preview:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .btn-imagen {
            font-size: 12px;
            border-radius: 15px;
            backdrop-filter: blur(5px);
            background: rgba(0,0,0,0.7);
            border: none;
            color: white;
        }

        .btn-imagen:hover {
            background: rgba(0,0,0,0.9);
            transform: scale(1.1);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-nav {
            gap: 20px;
        }

        .nav-link {
            padding: 8px 16px !important;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: rgba(64, 24, 116, 0.1);
        }

        .d-flex.align-items-center.gap-3 {
            gap: 15px !important;
        }

        .form-control.me-2 {
            min-width: 200px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 120px;
            text-align: center;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            white-space: nowrap;
        }

        .navbar-nav .nav-item {
            margin: 0 5px;
        }

        @media (max-width: 992px) {
            .navbar-brand img {
                height: 50px !important;
            }
            
            .d-flex.align-items-center.gap-3 {
                flex-direction: column;
                gap: 10px !important;
                width: 100%;
            }
            
            .d-flex form {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand-simple" href="{{ route('catalogo.index') }}">
            <img src="{{ asset('logo.png') }}" alt="Eira" class="logo-img">
            <span class="brand-text">Eira</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav-simple mx-auto">
                <li class="nav-item">
                    <a class="nav-link-simple {{ request()->routeIs('catalogo.*') ? 'active' : '' }}" 
                       href="{{ route('catalogo.index') }}">
                        🛍️ Catálogo
                    </a>
                </li>
                @auth
                <li class="nav-item">
                    <a class="nav-link-simple {{ request()->routeIs('admin.*') ? 'active' : '' }}" 
                       href="{{ route('admin.articulos.index') }}">
                        ⚙️ Administración
                    </a>
                </li>
                @endauth
            </ul>
            
            <div class="navbar-actions">
                @if(request()->routeIs('catalogo.*'))
                <div class="search-container">
                    <form method="GET" action="{{ route('catalogo.index') }}">
                        <input type="search" name="buscar" placeholder="Buscar..." value="{{ request('buscar') }}">
                        <button type="submit">🔍</button>
                    </form>
                </div>
                @endif
                
                <a href="{{ route('carrito.index') }}" class="cart-btn">
                    🛒
                    <span class="cart-count">{{ App\Models\Carrito::where('session_id', session()->getId())->sum('cantidad') }}</span>
                </a>
                
                @auth
                <div class="dropdown">
                    <button class="admin-btn dropdown-toggle" data-bs-toggle="dropdown">
                        {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item">🚪 Cerrar Sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @else
                <a href="{{ route('login') }}" class="admin-btn">Admin</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

    <!-- Content -->
    <main>
        @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @yield('content')
    </main>

<footer class="bg-white mt-5 py-4 border-top">
    <div class="container">
        <div class="row align-items-center">
            <!-- Información de la empresa -->
            <div class="col-md-6 text-center text-md-start">
                <p class="text-brand-primary mb-1">
                    ✨ <strong>Eira accesorios</strong>
                </p>
                <small class="text-muted d-block">
                    <i class="fas fa-map-marker-alt"></i> Cúcuta, Norte de Satander, Colombia.
                </small>
            </div>
            
            <!-- Redes sociales -->
            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                <p class="mb-2 text-brand-primary"><strong>Síguenos en:</strong></p>
                <div class="d-flex justify-content-center justify-content-md-end gap-3">
                    <!-- Instagram -->
                    <a href="https://www.instagram.com/eira._accesorios/?igsh=OWdmZWcxc3c3dngz#" target="_blank" 
                       class="btn btn-outline-primary btn-sm rounded-circle d-flex align-items-center justify-content-center"
                       style="width: 40px; height: 40px; background: linear-gradient(45deg, #E4405F, #833AB4, #C13584); border: none; color: white;"
                       title="Síguenos en Instagram">
                        <i class="fab fa-instagram" style="font-size: 18px;"></i>
                        <span class="visually-hidden">Instagram</span>
                    </a>
                    
                    <!-- WhatsApp -->
                    <a href="https://wa.me/573053265944" target="_blank" 
                       class="btn btn-outline-success btn-sm rounded-circle d-flex align-items-center justify-content-center"
                       style="width: 40px; height: 40px; background-color: #25D366; border: none; color: white;"
                       title="Contáctanos por WhatsApp">
                        <i class="fab fa-whatsapp" style="font-size: 18px;"></i>
                        <span class="visually-hidden">WhatsApp</span>
                    </a>
                    
                    <!-- TikTok -->
                    <a href="https://www.tiktok.com/@eira._accesorios?_t=ZS-8yxv3L2kgXI&_r=1" target="_blank" 
                       class="btn btn-outline-dark btn-sm rounded-circle d-flex align-items-center justify-content-center"
                       style="width: 40px; height: 40px; background-color: #000; border: none; color: white;"
                       title="Síguenos en TikTok">
                        <i class="fab fa-tiktok" style="font-size: 18px;"></i>
                        <span class="visually-hidden">TikTok</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Línea divisoria y copyright -->
        <hr class="my-3" style="border-color: var(--secondary-color);">
        <div class="text-center">
            <small class="text-muted">
                © {{ date('Y') }} Eira. Todos los derechos reservados.
            </small>
        </div>
    </div>
</footer>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>

<style>
/* Efectos hover para redes sociales */
.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}

/* Animación de pulso para WhatsApp */
.btn[href*="wa.me"]:hover {
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0% { transform: scale(1) translateY(-2px); }
    50% { transform: scale(1.05) translateY(-2px); }
    100% { transform: scale(1) translateY(-2px); }
}

/* Hover específico para Instagram */
.btn[href*="instagram"]:hover {
    background: linear-gradient(45deg, #405DE6, #5851DB, #833AB4, #C13584, #E1306C, #FD1D1D, #F56040, #F77737, #FCAF45, #FFDC80) !important;
}

/* Estructura de página para footer pegado al final */
html, body {
    height: 100%;
}

body {
    display: flex;
    flex-direction: column;
}

main {
    flex: 1;
}

footer {
    margin-top: auto;
}

.navbar-brand-simple {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: var(--primary-color) !important;
}

.logo-img {
    height: 50px;
    width: auto;
}

.brand-text {
    font-size: 1.5rem;
    font-weight: bold;
}

.navbar-nav-simple {
    display: flex;
    gap: 30px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.nav-link-simple {
    text-decoration: none;
    color: #666;
    font-weight: 500;
    padding: 8px 0;
    border-bottom: 2px solid transparent;
    transition: all 0.3s ease;
}

.nav-link-simple:hover,
.nav-link-simple.active {
    color: var(--primary-color);
    border-bottom-color: var(--primary-color);
}

.navbar-actions {
    display: flex;
    align-items: center;
    gap: 15px;
}

.search-container form {
    display: flex;
    align-items: center;
    background: #f8f9fa;
    border-radius: 25px;
    padding: 8px 15px;
    border: 1px solid #e0e0e0;
    margin:0;
}

.search-container input {
    border: none;
    background: transparent;
    outline: none;
    width: 180px;
    font-family: inherit;
}

.search-container button {
    border: none;
    background: transparent;
    cursor: pointer;
    margin-left: 10px;
}

.cart-btn {
    position: relative;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 50%;
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
}

.cart-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(64, 24, 116, 0.3);
    color: white;
}

.cart-count {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #dc3545;
    color: white;
    font-size: 0.7rem;
    min-width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.admin-btn {
    background: var(--secondary-color);
    color: var(--primary-color);
    border: 1px solid var(--primary-color);
    border-radius: 20px;
    padding: 8px 20px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.admin-btn:hover {
    background: var(--primary-color);
    color: white;
}

/* Responsive */
@media (max-width: 991px) {
    .navbar-nav-simple {
        flex-direction: column;
        gap: 10px;
        margin: 20px 0;
    }
    
    .navbar-actions {
        flex-direction: column;
        width: 100%;
        gap: 15px;
    }
    
    .search-container form {
        width: 100%;
    }
    
    .search-container input {
        width: 100%;
    }
}
</style>
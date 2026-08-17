<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>🐾 La Madriguera - @yield('titulo', 'Inicio')</title>
    <script src="{{ asset('js/funciones.js') }}"></script>
    <style>
        .imagen-anuncio {
            max-width: 200px;
            max-height: 150px;
            object-fit: cover;
        }
        .dashboard-container {
            min-height: 80vh;
            padding: 20px 0;
        }
        .alert-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 1050;
            min-width: 300px;
        }
        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .user-welcome {
            color: #333;
            font-weight: 500;
        }
        .logout-btn {
            background: none;
            border: none;
            color: #007bff;
            cursor: pointer;
            padding: 5px 10px;
            text-decoration: none;
        }
        .logout-btn:hover {
            color: #0056b3;
            text-decoration: underline;
        }
    </style>
</head>
<body>
  <nav class="navbar">
    <div class="logo">🐾 La Madriguera</div>

    <!-- Botón hamburguesa -->
    <div class="hamburger" id="hamburger" aria-label="Menú" aria-expanded="false" role="button" tabindex="0">
      <div></div>
      <div></div>
      <div></div>
    </div>

    <ul class="nav-links" id="nav-links">
      <li class="dropdown">
        <a href="{{route('welcome')}}">Inicio</a>
      </li>
      
      <li class="dropdown">
        <a href="javascript:void(0)">Servicios</a>
        <ul class="submenu">
          <li><a href="{{route('vacunacion')}}">Vacunación</a></li>
          <li><a href="{{route('desparasitacion')}}">Desparasitación</a></li>
          <li><a href="{{route('cirugias')}}">Cirugías</a></li>
          <li><a href="{{route('peluqueria')}}">Peluquería</a></li>
        </ul>
      </li>
      
      <li class="dropdown">
        <a href="javascript:void(0)">Productos</a>
        <ul class="submenu">
          <li><a href="{{route('alimentos')}}">Alimentos</a></li>
          <li><a href="{{route('medicamentos')}}">Medicamentos</a></li>
          <li><a href="{{route('juguetesyaccesorios')}}">Juguetes y accesorios</a></li>
        </ul>
      </li>
      
      <li class="dropdown">
        <a href="javascript:void(0)">Contenido educativo</a>
        <ul class="submenu">
          <li><a href="{{route('cuidados')}}">Cuidados</a></li>
          <li><a href="{{route('prevencion')}}">Prevención</a></li>
          <li><a href="{{route('noticias.index')}}">Noticias</a></li>
        </ul>
      </li>
      
      <li><a href="{{route('reservasycitas')}}">Reservas / Citas</a></li>
      
      <li class="dropdown">
        <a href="javascript:void(0)">Contacto</a>
        <ul class="submenu">
          <li><a href="{{route('telefono')}}">Teléfono</a></li>
          <li><a href="{{route('mapa')}}">Mapa</a></li>
        </ul>
      </li>

    <!-- Menú de usuario -->
    <div class="user-menu">
      @auth
        <span class="user-welcome">¡Hola, {{ Auth::user()->name }}!</span>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
          @csrf
          <button type="submit" class="logout-btn">Cerrar Sesión</button>
        </form>
      @else
        <div class="user-icon">
          <a href="/login">
            <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="Usuario" />
          </a>
        </div>
      @endauth
    </div>
  </nav>
   
  <!-- Contenido Principal -->
  <main class="dashboard-container">
    <!-- Alertas de éxito/error -->
    <div class="alert-container">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          ✅ {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          ❌ {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>❌ Errores encontrados:</strong>
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
    </div>

    @yield('contenido')
  </main>

  <footer class="footer">
    <div class="footer-container">
      <!-- Información de contacto -->
      <div class="footer-section">
        <h4>📍 Contacto</h4>
        <p>Dirección: Calle Principal #123, Potosí</p>
        <p>Teléfono: +591 123-45678</p>
        <p>Email: contacto@lamadriguera.com</p>
      </div>

      <!-- Enlaces rápidos -->
      <div class="footer-section">
        <h4>🔗 Enlaces</h4>
        <ul>
          <li><a href="{{route('welcome')}}">Inicio</a></li>
          <li><a href="#servicios">Servicios</a></li>
          <li><a href="#productos">Productos</a></li>
          <li><a href="{{route('reservasycitas')}}">Reservas</a></li>
          <li><a href="#contacto">Contacto</a></li>
        </ul>
      </div>

      <!-- Redes sociales -->
      <div class="footer-section">
        <h4>🐾 Síguenos</h4>
        <div class="social-icons">
          <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" alt="WhatsApp" /></a>
          <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook" /></a>
          <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733579.png" alt="Instagram" /></a>
        </div>
      </div>
    </div>

    <!-- Derechos reservados -->
    <div class="footer-bottom">
      <p>&copy; 2025 Veterinaria La Madriguera. Todos los derechos reservados.</p>
    </div>
  </footer>

  <script>
    // Cerrar automáticamente las alertas después de 5 segundos
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });

    // Script para el menú hamburguesa (si no lo tienes en funciones.js)
    document.addEventListener('DOMContentLoaded', function() {
        const hamburger = document.getElementById('hamburger');
        const navLinks = document.getElementById('nav-links');
        
        if (hamburger && navLinks) {
            hamburger.addEventListener('click', function() {
                navLinks.classList.toggle('active');
                hamburger.classList.toggle('active');
            });
        }
    });
  </script>
</body>
</html>
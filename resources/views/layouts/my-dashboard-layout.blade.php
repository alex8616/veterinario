<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon" />
    <title>PANEL DE CONTROL VETERINARIO</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <!-- Archivos CSS existentes -->
    <link rel="stylesheet" href="{{ asset('plainadmin/assets/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('plainadmin/assets/css/lineicons.css')}}" />
    <link rel="stylesheet" href="{{ asset('plainadmin/assets/css/materialdesignicons.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('plainadmin/assets/css/fullcalendar.css')}}" />
    <link rel="stylesheet" href="{{ asset('plainadmin/assets/css/main.css')}}" />
    
    <!-- Estilos personalizados - Versión clara y elegante -->
    <style>
        /* ===== BASE ===== */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }
        /* ===== SIDEBAR - CLARO ===== */
        .sidebar-nav-wrapper {
            background: #ffffff;
            border-right: 1px solid #e9edf4;
            box-shadow: 2px 0 12px rgba(0,0,0,0.02);
        }
        .navbar-logo a {
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
            color: #0f172a !important;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 0;
        }
        .navbar-logo a::before {
            content: "🐾";
            font-size: 1.8rem;
        }
        .navbar-logo a span {
            background: #3b82f6;
            padding: 2px 12px;
            border-radius: 30px;
            font-size: 0.65rem;
            font-weight: 600;
            color: white;
            margin-left: 6px;
            letter-spacing: 0.3px;
        }
        .sidebar-nav .nav-item > a {
            color: #334155;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 12px;
            margin: 2px 10px;
            transition: all 0.2s ease;
        }
        .sidebar-nav .nav-item > a:hover,
        .sidebar-nav .nav-item > a.active {
            background: #f1f5f9;
            color: #0f172a;
        }
        .sidebar-nav .nav-item > a .icon {
            font-size: 1.3rem;
            margin-right: 12px;
            opacity: 0.7;
            color: #475569;
        }
        .sidebar-nav .nav-item > a:hover .icon {
            opacity: 1;
            color: #3b82f6;
        }
        .sidebar-nav .nav-item > a .text {
            font-size: 0.95rem;
        }
        .dropdown-nav li a {
            color: #475569;
            padding: 8px 20px 8px 54px;
            font-size: 0.9rem;
            border-radius: 8px;
            transition: all 0.15s;
        }
        .dropdown-nav li a:hover {
            background: #f1f5f9;
            color: #0f172a;
        }
        .sidebar-nav .divider hr {
            border-color: #e9edf4;
            margin: 10px 20px;
        }
        /* Botón cerrar sesión en sidebar */
        .sidebar-nav .nav-item form button {
            color: #334155;
            padding: 10px 20px;
            border-radius: 12px;
            margin: 2px 10px;
            transition: all 0.2s;
            font-weight: 500;
        }
        .sidebar-nav .nav-item form button:hover {
            background: #fef2f2;
            color: #dc2626;
        }
        .sidebar-nav .nav-item form button:hover .icon i {
            color: #dc2626;
        }

        /* ===== HEADER ===== */
        .header {
            background: #ffffff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            padding: 12px 0;
            border-bottom: 1px solid #eef2f6;
        }
        .header-search form {
            background: #f1f5f9;
            border-radius: 40px;
            padding: 4px 8px 4px 18px;
            transition: all 0.2s;
            min-width: 220px;
        }
        .header-search form:focus-within {
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
            border: 1px solid #dbeafe;
        }
        .header-search input {
            background: transparent;
            border: none;
            padding: 8px 0;
            font-size: 0.9rem;
            color: #1e293b;
            width: 100%;
        }
        .header-search input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }
        .header-search button {
            background: transparent;
            border: none;
            color: #64748b;
            padding: 6px 12px;
            border-radius: 40px;
        }
        .header-search button:hover {
            color: #3b82f6;
        }
        /* Notificaciones y mensajes */
        .notification-box button,
        .header-message-box button {
            background: transparent;
            border: none;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.2s;
            position: relative;
            color: #64748b;
        }
        .notification-box button:hover,
        .header-message-box button:hover {
            background: #f1f5f9;
            color: #0f172a;
        }
        .notification-box button span,
        .header-message-box button span {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid white;
        }
        /* Perfil en header */
        .profile-box .profile-info .info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .profile-box .profile-info .image {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px rgba(59,130,246,0.15);
        }
        .profile-box .profile-info .info h6 {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0;
            color: #0f172a;
        }
        .profile-box .profile-info .info p {
            font-size: 0.75rem;
            color: #64748b;
            margin-bottom: 0;
            text-transform: capitalize;
            letter-spacing: 0.3px;
        }
        /* Dropdown perfil */
        .profile-box .dropdown-menu {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.06);
            padding: 8px 0;
            margin-top: 12px;
            min-width: 220px;
            border: 1px solid #eef2f6;
        }
        .profile-box .dropdown-menu .author-info {
            padding: 12px 20px;
        }
        .profile-box .dropdown-menu .author-info .image {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .profile-box .dropdown-menu .author-info .content h4 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 2px;
            color: #0f172a;
        }
        .profile-box .dropdown-menu .author-info .content a {
            color: #64748b;
            font-size: 0.8rem;
            text-decoration: none;
        }
        .profile-box .dropdown-menu .dropdown-item {
            padding: 10px 20px;
            color: #334155;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.15s;
        }
        .profile-box .dropdown-menu .dropdown-item i {
            font-size: 1.2rem;
            color: #64748b;
        }
        .profile-box .dropdown-menu .dropdown-item:hover {
            background: #f1f5f9;
            color: #0f172a;
        }
        .profile-box .dropdown-menu .dropdown-item:hover i {
            color: #0f172a;
        }
        .profile-box .dropdown-menu .divider {
            border-top: 1px solid #eef2f6;
            margin: 4px 0;
        }

        /* ===== MAIN CONTENT ===== */
        .main-wrapper {
            background: #f8fafc;
        }
        .main-wrapper section > div {
            background: #ffffff;
            border-radius: 24px;
            padding: 28px 32px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.02);
            border: 1px solid #eef2f6;
        }

        /* ===== FOOTER ===== */
        .footer {
            background: transparent;
            padding: 20px 0 10px;
            border-top: 1px solid #eef2f6;
            margin-top: 20px;
        }
        .footer .copyright p {
            color: #64748b;
            font-weight: 400;
            font-size: 0.85rem;
        }
        .footer .terms a {
            color: #64748b;
            font-size: 0.8rem;
            text-decoration: none;
            transition: color 0.2s;
        }
        .footer .terms a:hover {
            color: #0f172a;
        }
        .footer .terms a:not(:last-child)::after {
            content: "·";
            margin: 0 12px;
            color: #cbd5e1;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .navbar-logo a {
                font-size: 1.2rem;
            }
            .header-search form {
                min-width: auto;
                width: 100%;
            }
            .main-wrapper section > div {
                padding: 16px 18px;
                border-radius: 16px;
            }
        }
    </style>
</head>
<body>
    <div id="preloader">
        <div class="spinner"></div>
    </div>

    <!-- ===== SIDEBAR CLARO ===== -->
    <aside class="sidebar-nav-wrapper">
        <div class="navbar-logo">
            <a href="#">
                VETERINARIA
            </a>
        </div>
        <nav class="sidebar-nav">
            <ul>
                @if(auth()->user()->role === 'admin')
                    <li class="nav-item nav-item-has-children">
                        <a href="#0" data-bs-toggle="collapse" data-bs-target="#menuGestion" aria-expanded="true">
                            <span class="icon">
                                <i class="lni lni-users"></i>
                            </span>
                            <span class="text">Gestión</span>
                        </a>
                        <ul id="menuGestion" class="collapse dropdown-nav show">
                            <li>
                                <a href="{{ route('admin.clientes') }}">Clientes</a>
                            </li>
                            <li>
                                <a href="#">Veterinarios</a>
                            </li>
                            <li>
                                <a href="#">Usuarios</a>
                            </li>
                            <li>
                                <a href="#">Mascotas</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if(auth()->user()->role === 'veterinario')
                    <li class="nav-item nav-item-has-children">
                        <a href="#0" 
                           data-bs-toggle="collapse" 
                           data-bs-target="#menuVeterinaria" 
                           aria-expanded="true">
                            <span class="icon"><i class="lni lni-hospital"></i></span>
                            <span class="text">Veterinaria</span>
                        </a>
                        <ul id="menuVeterinaria" class="collapse dropdown-nav show">
                            <li><a href="{{ route('veterinario.agenda') }}">Citas</a></li>
                            <li><a href="{{ route('veterinario.citas') }}">Consultas</a></li>
                            <li><a href="{{ route('veterinario.historia') }}">Historia clínica</a></li>
                        </ul>
                    </li>
                @endif

                @if(auth()->user()->role === 'cliente')
                    <li class="nav-item nav-item-has-children">
                        <a href="#0" 
                           data-bs-toggle="collapse" 
                           data-bs-target="#menuCliente" 
                           aria-expanded="true">
                            <span class="icon"><i class="lni lni-user"></i></span>
                            <span class="text">Mi cuenta</span>
                        </a>
                        <ul id="menuCliente" class="collapse dropdown-nav show">
                            <li><a href="{{ route('perfil') }}">Mi perfil</a></li>
                            <li><a href="{{ route('mascotas.index') }}">Mis mascotas</a></li>
                            <li><a href="{{ route('citas.index') }}">Mis citas</a></li>
                            <li><a href="{{ route('historia-clinica') }}">Historia clínica</a></li>
                        </ul>
                    </li>
                @endif

                <span class="divider"><hr></span>

                <li class="nav-item">
                    <a href="{{ route('noticias.index') }}">
                        <span class="icon"><i class="lni lni-book"></i></span>
                        <span class="text">Noticias</span>
                    </a>
                </li>

                <span class="divider"><hr></span>

                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" style="background:none; border:none; width:100%; text-align:left; padding:0;">
                            <span class="icon"><i class="lni lni-exit"></i></span>
                            <span class="text">Cerrar sesión</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </aside>

    <div class="overlay"></div>

    <!-- ===== MAIN WRAPPER ===== -->
    <main class="main-wrapper">
        <!-- HEADER -->
        <header class="header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-lg-5 col-md-5 col-6">
                        <div class="header-left d-flex align-items-center">
                            <div class="header-search d-none d-md-flex w-100">
                                <form action="#" class="d-flex align-items-center w-100">
                                    <input type="text" placeholder="Buscar..." />
                                    <button><i class="lni lni-search-alt"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-7 col-6">
                        <div class="header-right d-flex align-items-center justify-content-end gap-2">
                            <!-- Notificaciones -->
                            <div class="notification-box d-none d-md-flex">
                                <button class="dropdown-toggle" type="button" id="notification" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11 20.1667C9.88317 20.1667 8.88718 19.63 8.23901 18.7917H13.761C13.113 19.63 12.1169 20.1667 11 20.1667Z" fill="currentColor"/>
                                        <path d="M10.1157 2.74999C10.1157 2.24374 10.5117 1.83333 11 1.83333C11.4883 1.83333 11.8842 2.24374 11.8842 2.74999V2.82604C14.3932 3.26245 16.3051 5.52474 16.3051 8.24999V14.287C16.3051 14.5301 16.3982 14.7633 16.564 14.9352L18.2029 16.6342C18.4814 16.9229 18.2842 17.4167 17.8903 17.4167H4.10961C3.71574 17.4167 3.5185 16.9229 3.797 16.6342L5.43589 14.9352C5.6017 14.7633 5.69485 14.5301 5.69485 14.287V8.24999C5.69485 5.52474 7.60672 3.26245 10.1157 2.82604V2.74999Z" fill="currentColor"/>
                                    </svg>
                                    <span></span>
                                </button>
                            </div>
                            <!-- Mensajes -->
                            <div class="header-message-box d-none d-md-flex">
                                <button class="dropdown-toggle" type="button" id="message" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.74866 5.97421C7.91444 5.96367 8.08162 5.95833 8.25005 5.95833C12.5532 5.95833 16.0417 9.4468 16.0417 13.75C16.0417 13.9184 16.0364 14.0856 16.0259 14.2514C16.3246 14.138 16.6127 14.003 16.8883 13.8482L19.2306 14.629C19.7858 14.8141 20.3141 14.2858 20.129 13.7306L19.3482 11.3882C19.8694 10.4604 20.1667 9.38996 20.1667 8.25C20.1667 4.70617 17.2939 1.83333 13.75 1.83333C11.0077 1.83333 8.66702 3.55376 7.74866 5.97421Z" fill="currentColor"/>
                                        <path d="M14.6667 13.75C14.6667 17.2938 11.7939 20.1667 8.25004 20.1667C7.11011 20.1667 6.03962 19.8694 5.11182 19.3482L2.76946 20.129C2.21421 20.3141 1.68597 19.7858 1.87105 19.2306L2.65184 16.8882C2.13062 15.9604 1.83338 14.89 1.83338 13.75C1.83338 10.2062 4.70622 7.33333 8.25004 7.33333C11.7939 7.33333 14.6667 10.2062 14.6667 13.75ZM5.95838 13.75C5.95838 13.2437 5.54797 12.8333 5.04171 12.8333C4.53545 12.8333 4.12504 13.2437 4.12504 13.75C4.12504 14.2563 4.53545 14.6667 5.04171 14.6667C5.54797 14.6667 5.95838 14.2563 5.95838 13.75ZM9.16671 13.75C9.16671 13.2437 8.7563 12.8333 8.25004 12.8333C7.74379 12.8333 7.33338 13.2437 7.33338 13.75C7.33338 14.2563 7.74379 14.6667 8.25004 14.6667C8.7563 14.6667 9.16671 14.2563 9.16671 13.75ZM11.4584 14.6667C11.9647 14.6667 12.375 14.2563 12.375 13.75C12.375 13.2437 11.9647 12.8333 11.4584 12.8333C10.9521 12.8333 10.5417 13.2437 10.5417 13.75C10.5417 14.2563 10.9521 14.6667 11.4584 14.6667Z" fill="currentColor"/>
                                    </svg>
                                    <span></span>
                                </button>
                            </div>
                            <!-- Perfil -->
                            <div class="profile-box">
                                <button class="dropdown-toggle bg-transparent border-0" type="button" id="profile" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="profile-info">
                                        <div class="info">
                                            <div class="image">
                                                @php
                                                    $name = auth()->user()->name;
                                                    $initials = collect(explode(' ', $name))->map(fn($word) => strtoupper(substr($word, 0, 1)))->take(2)->implode('');
                                                @endphp
                                                {{ $initials }}
                                            </div>
                                            <div class="d-none d-sm-block">
                                                <h6>{{ auth()->user()->name }}</h6>
                                                <p>{{ ucfirst(auth()->user()->role) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profile">
                                    <li>
                                        <div class="author-info d-flex align-items-center gap-3">
                                            <div class="image">{{ $initials }}</div>
                                            <div class="content">
                                                <h4>{{ auth()->user()->name }}</h4>
                                                <a href="#">{{ auth()->user()->email }}</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="divider"></li>
                                    <li><a class="dropdown-item" href="#0"><i class="lni lni-user"></i> Ver perfil</a></li>
                                    <li><a class="dropdown-item" href="#0"><i class="lni lni-alarm"></i> Notificaciones</a></li>
                                    <li><a class="dropdown-item" href="#0"><i class="lni lni-inbox"></i> Mensajes</a></li>
                                    <li><a class="dropdown-item" href="#0"><i class="lni lni-cog"></i> Configuración</a></li>
                                    <li class="divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="lni lni-exit"></i> Cerrar sesión
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- CONTENIDO PRINCIPAL -->
        <section style="margin: 10px;">
            <div>
                @yield('content')    
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-6 order-last order-md-first">
                        <div class="copyright text-center text-md-start">
                            <p class="text-sm">
                                &copy; {{ date('Y') }} Veterinaria. Todos los derechos reservados.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="terms d-flex justify-content-center justify-content-md-end">
                            <a href="#0" class="text-sm">Términos y condiciones</a>
                            <a href="#0" class="text-sm">Política de privacidad</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </main>

    <!-- SCRIPTS -->
    <script src="{{ asset('plainadmin/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plainadmin/assets/js/Chart.min.js') }}"></script>
    <script src="{{ asset('plainadmin/assets/js/dynamic-pie-chart.js') }}"></script>
    <script src="{{ asset('plainadmin/assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('plainadmin/assets/js/fullcalendar.js') }}"></script>
    <script src="{{ asset('plainadmin/assets/js/jvectormap.min.js') }}"></script>
    <script src="{{ asset('plainadmin/assets/js/world-merc.js') }}"></script>
    <script src="{{ asset('plainadmin/assets/js/polyfill.js') }}"></script>
    <script src="{{ asset('plainadmin/assets/js/main.js') }}"></script>    
</body>
</html>
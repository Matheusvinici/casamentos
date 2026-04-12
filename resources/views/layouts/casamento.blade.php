<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Nosso Casamento')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Lato:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --rose: #c9a87c;
            --rose-light: #e8d5b7;
            --rose-dark: #a07d50;
            --champagne: #f5f0e8;
            --cream: #faf8f5;
            --gold: #d4a853;
            --gold-light: #f0e0b8;
            --text-dark: #3d3427;
            --text-medium: #6b5e50;
            --text-light: #9a8e80;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Lato', sans-serif;
            background-color: var(--cream);
            color: var(--text-dark);
            -webkit-font-smoothing: antialiased;
        }

        /* Navbar */
        .casamento-navbar {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(201,168,124,0.15);
            padding: 0.8rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .casamento-navbar .nav-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            color: var(--rose-dark);
            text-decoration: none;
            font-weight: 500;
            letter-spacing: 1px;
            transition: color 0.3s ease;
        }

        .casamento-navbar .nav-brand:hover {
            color: var(--gold);
        }

        .casamento-navbar .nav-link-custom {
            color: var(--text-medium);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: all 0.3s ease;
            padding: 0.4rem 1rem;
            border-radius: 20px;
        }

        .casamento-navbar .nav-link-custom:hover {
            color: var(--rose-dark);
            background: rgba(201,168,124,0.08);
        }

        .casamento-navbar .btn-logout {
            background: transparent;
            border: 1.5px solid var(--rose);
            color: var(--rose-dark);
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            padding: 0.35rem 1.2rem;
            border-radius: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .casamento-navbar .btn-logout:hover {
            background: var(--rose);
            color: var(--white);
        }

        .user-greeting {
            font-size: 0.85rem;
            color: var(--text-light);
            font-weight: 400;
        }

        .user-greeting strong {
            color: var(--text-dark);
            font-weight: 600;
        }

        /* Footer */
        .casamento-footer {
            background: var(--text-dark);
            color: rgba(255,255,255,0.6);
            padding: 2.5rem 0;
            text-align: center;
            font-size: 0.85rem;
        }

        .casamento-footer .footer-names {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: var(--rose-light);
            margin-bottom: 0.5rem;
            letter-spacing: 2px;
        }

        .casamento-footer .footer-date {
            color: rgba(255,255,255,0.4);
            font-size: 0.8rem;
            letter-spacing: 1px;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: var(--cream);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--rose-light);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--rose);
        }

        /* Utilities */
        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        @yield('extra-styles')
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="casamento-navbar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="/" class="nav-brand">Mary & Matheus</a>
                <div class="d-flex align-items-center gap-3">
                    @auth
                        <span class="user-greeting d-none d-md-inline">
                            Olá, <strong>{{ Auth::user()->name }}</strong>
                        </span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-logout">
                                <i class="fas fa-sign-out-alt me-1"></i> Sair
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="nav-link-custom">
                            <i class="fas fa-user me-1"></i> Entrar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="casamento-footer">
        <div class="container">
            <div class="footer-names">Mari & Matheus</div>
            <div class="footer-date">06 de Junho de 2026</div>
            <p class="mt-2 mb-0" style="font-size: 0.75rem;">Feito com <i class="fas fa-heart" style="color: var(--rose);"></i> para o nosso grande dia</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>

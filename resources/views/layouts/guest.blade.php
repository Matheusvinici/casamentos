<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Login - Nosso Casamento')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Lato:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
            background: linear-gradient(160deg, var(--cream) 0%, var(--champagne) 50%, var(--rose-light) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            -webkit-font-smoothing: antialiased;
        }

        .auth-container {
            width: 100%;
            max-width: 440px;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-header a {
            text-decoration: none;
        }

        .auth-names {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: var(--text-dark);
            font-weight: 400;
            margin-bottom: 0.3rem;
        }

        .auth-names .amp {
            font-style: italic;
            color: var(--rose);
            font-size: 0.8em;
        }

        .auth-date {
            font-size: 0.75rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--text-light);
        }

        .auth-card {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(201,168,124,0.15);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            box-shadow: 0 20px 60px rgba(201,168,124,0.1);
        }

        .auth-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            color: var(--text-dark);
            text-align: center;
            margin-bottom: 1.8rem;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-label-custom {
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--text-medium);
            margin-bottom: 0.4rem;
            display: block;
        }

        .form-control-custom {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1.5px solid rgba(201,168,124,0.25);
            border-radius: 10px;
            font-family: 'Lato', sans-serif;
            font-size: 0.9rem;
            color: var(--text-dark);
            background: var(--white);
            transition: all 0.3s ease;
            outline: none;
        }

        .form-control-custom::placeholder {
            color: var(--text-light);
        }

        .form-control-custom:focus {
            border-color: var(--rose);
            box-shadow: 0 0 0 3px rgba(201,168,124,0.1);
        }

        .form-control-custom.is-invalid {
            border-color: #ef4444;
        }

        .invalid-feedback {
            font-size: 0.78rem;
            color: #ef4444;
            margin-top: 0.3rem;
        }

        .btn-auth {
            width: 100%;
            padding: 0.8rem;
            background: linear-gradient(135deg, var(--rose), var(--rose-dark));
            color: var(--white);
            border: none;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(201,168,124,0.35);
        }

        .auth-links {
            text-align: center;
            margin-top: 1.5rem;
        }

        .auth-links a {
            color: var(--rose-dark);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .auth-links a:hover {
            color: var(--gold);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.2rem 0;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(201,168,124,0.2);
        }

        .divider span {
            padding: 0 1rem;
            color: var(--text-light);
            font-size: 0.75rem;
            letter-spacing: 1px;
        }

        .remember-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .remember-check input[type="checkbox"] {
            accent-color: var(--rose);
            width: 16px;
            height: 16px;
        }

        .remember-check label {
            font-size: 0.82rem;
            color: var(--text-medium);
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <a href="/">
                <h1 class="auth-names">Mary <span class="amp">&</span> Matheus</h1>
                <p class="auth-date">06 de Junho de 2026</p>
            </a>
        </div>

        <div class="auth-card">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
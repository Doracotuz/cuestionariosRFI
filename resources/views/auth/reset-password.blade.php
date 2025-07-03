<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuestionarios RFI - Restablecer Contraseña</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@800&family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background:rgb(44, 56, 86);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* Estrellas animadas */
        .stars {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: transparent;
            overflow: hidden;
        }

        .star {
            position: absolute;
            width: 2px;
            height: 2px;
            background: #ffffff;
            border-radius: 50%;
            animation: twinkle 5s infinite ease-in-out, move 10s infinite linear;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }

        @keyframes move {
            0% { transform: translateY(100vh); }
            100% { transform: translateY(-10vh); }
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 12px;
            width: 420px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            z-index: 1;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .logo {
            margin-bottom: 25px;
            display: inline-block;
        }

        .logo img {
            width: 150px;
            height: auto;
            animation: logoMovement 5s infinite ease-in-out;
        }

        @keyframes logoMovement {
            0% { transform: translateY(0px) scale(1); }
            25% { transform: translateY(-7px) scale(1.03); }
            50% { transform: translateY(0px) scale(1); }
            75% { transform: translateY(7px) scale(0.97); }
            100% { transform: translateY(0px) scale(1); }
        }

        .login-container h1 {
            font-size: 38px;
            color: #2c3856;
            margin-bottom: 10px;
            font-family: 'Raleway', sans-serif;
            font-weight: 800;
        }

        .tagline {
            font-size: 15px;
            color: #666666;
            margin-bottom: 35px;
            letter-spacing: 1px;
        }

        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            color: #34495e;
            margin-bottom: 8px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #dfe6e9;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            color: #34495e;
            background-color: #fcfcfc;
            transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .form-group input:focus {
            outline: none;
            border-color: #ff9c00;
            box-shadow: 0 0 8px rgba(255, 156, 0, 0.2);
        }

        .error-message {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        /* Mensajes de éxito y error */
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }


        .btn-login {
            background: #ff9c00;
            color: #fff;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 17px;
            font-family: 'Raleway', sans-serif;
            font-weight: 800;
            letter-spacing: 0.5px;
            transition: background 0.3s ease-in-out, transform 0.2s ease-in-out;
        }

        .btn-login:hover {
            background: #e08c00;
            transform: translateY(-2px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .footer-text {
            margin-top: 30px;
            font-size: 11px;
            color: #95a5a6;
        }

        .footer-text a {
            color: #95a5a6;
            text-decoration: none;
            margin: 0 5px;
            transition: color 0.3s ease-in-out;
        }

        .footer-text a:hover {
            color: #7f8c8d;
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                align-items: flex-start;
                height: auto;
                overflow: auto;
                padding: 20px;
            }

            .login-container {
                width: 100%;
                max-width: 400px;
                margin-top: 40px;
            }

            .logo img {
                width: 120px;
            }

            .login-container h1 {
                font-size: 28px;
            }

            .tagline {
                font-size: 13px;
            }

            .btn-login {
                font-size: 16px;
                padding: 12px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="stars">
        <script>
            for (let i = 0; i < 100; i++) {
                const star = document.createElement('div');
                star.className = 'star';
                star.style.left = Math.random() * 100 + 'vw';
                star.style.top = Math.random() * 100 + 'vh';
                star.style.animationDelay = Math.random() * 10 + 's';
                document.querySelector('.stars').appendChild(star);
            }
        </script>
    </div>
    <div class="login-container">
        <div class="logo">
            <img src="{{ asset('images/LogoAzul.png') }}" alt="Minmer Global Logo" id="minmer-logo">
        </div>
        <h1>Restablecer Contraseña</h1>
        <p class="tagline">Establezca su nueva contraseña.</p>

        {{-- Mostrar mensaje de éxito si existe --}}
        @if (session('status'))
            <div class="success-message">
                {{ session('status') }}
            </div>
        @endif

        {{-- Mostrar mensajes de error de validación --}}
        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Nueva Contraseña</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirmar Contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn-login">Restablecer Contraseña</button>
        </form>
        <div class="footer-text">
            © 2025 Minmer Global. Todos los derechos reservados<br>
            <a href="/terms-conditions">Términos y condiciones</a> | <a href="/privacy-policy">Política de privacidad</a>
        </div>
    </div>
</body>
</html>

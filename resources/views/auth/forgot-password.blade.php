<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuestionarios RFI - Recuperar Contraseña</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
        <style>
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background-image:
                linear-gradient(to bottom right, rgba(230, 230, 230, 0.9), rgba(200, 200, 200, 0.9)),
                url('/images/background.jpg'); /* Esta es la imagen del body que quieres mantener */
            background-repeat: no-repeat;
            background-position: center center;
            background-attachment: fixed;
            background-size: cover;
            background-blend-mode: overlay;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
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
            width: 150px; /* Logo 50% más grande */
            height: auto;
            /* Animación continua de movimiento y destellos para el logo */
            animation: logoMovement 5s infinite ease-in-out, logoSparkle 1.5s infinite alternate;
        }

        /* Animación de movimiento elegante y continuo para el logo */
        @keyframes logoMovement {
            0% { transform: translateY(0px) scale(1); }
            25% { transform: translateY(-7px) scale(1.03); }
            50% { transform: translateY(0px) scale(1); }
            75% { transform: translateY(7px) scale(0.97); }
            100% { transform: translateY(0px) scale(1); }
        }

        /* Animación de destellos brillantes de color blanco para el logo (como chispas) */
        @keyframes logoSparkle {
            0% {
                filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.3));
                box-shadow: 0 0 5px rgba(255, 255, 255, 0.2), 0 0 10px rgba(255, 255, 255, 0.1);
                opacity: 0.9;
            }
            50% {
                filter: drop-shadow(0 0 25px rgba(255, 255, 255, 1));
                box-shadow: 0 0 20px rgba(255, 255, 255, 0.8), 0 0 40px rgba(255, 255, 255, 0.6), 0 0 60px rgba(255, 255, 255, 0.4);
                opacity: 1;
            }
            100% {
                filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.3));
                box-shadow: 0 0 5px rgba(255, 255, 255, 0.2), 0 0 10px rgba(255, 255, 255, 0.1);
                opacity: 0.9;
            }
        }


        .login-container h1 {
            font-size: 38px;
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .tagline {
            font-size: 15px;
            color: #7f8c8d;
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
            border-color: #3498db;
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.2);
        }

        .error-message {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .form-check {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .remember-me {
            font-size: 13px;
            color: #555;
            display: flex;
            align-items: center;
        }

        .remember-me input[type="checkbox"] {
            margin-right: 8px;
            accent-color: #3498db;
        }

        .forgot-password {
            color: #3498db;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.3s ease-in-out;
        }

        .forgot-password:hover {
            color: #2980b9;
            text-decoration: underline;
        }

        .btn-login {
            background: #3498db;
            color: #fff;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 17px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: background 0.3s ease-in-out, transform 0.2s ease-in-out;
        }

        .btn-login:hover {
            background: #2980b9;
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

        /* Contenedor principal de las capas de fondo */
        .image-right {
            position: absolute;
            top: 0;
            right: 0;
            width: 60%;
            height: 100%;
            overflow: hidden; /* Asegura que las capas no se salgan */
            z-index: 0; /* Asegúrate de que esté debajo del login-container */
            animation: slideInRight 1s ease-out forwards; /* Animación inicial al cargar */
        }

        /* Estilos para cada capa de fondo */
        .background-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-repeat: no-repeat;
            background-position: center right;
            background-size: cover;
            background-blend-mode: overlay;
            opacity: 0; /* Por defecto, todas ocultas */
            transition: opacity 2s ease-in-out; /* Duración de la transición de difuminado (2s) */
        }

        .background-layer.active {
            opacity: 1; /* La capa activa es visible */
        }

        /* Animación inicial para que la sección de la derecha aparezca al cargar */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0; /* Asegura que comienza totalmente invisible */
            }
            to {
                transform: translateX(0);
                opacity: 1; /* Termina totalmente visible */
            }
        }

        /* RESPONSIVE DESIGN */
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

            .image-right {
                display: none; /* Oculta la imagen de la derecha en dispositivos pequeños */
            }

            .logo img {
                width: 120px; /* Ajusta el tamaño del logo para móviles */
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
    <div class="login-container">
        <h1>Recuperar Contraseña</h1>
        <p class="tagline">Ingrese su correo electrónico para restablecerla.</p>
        @if (session('status'))
            <div class="success-message">
                {{ session('status') }}
            </div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn-login">Enviar enlace para restablecimiento</button>
        </form>
        <div class="footer-text">
            © 2025 Minmer Global. Todos los derechos reservados<br>
            <a href="#">Términos y condiciones</a> | <a href="#">Política de privacidad</a>
        </div>
    </div>
    <div class="image-right">
        <!-- Puedes añadir una imagen estática o un carrusel si lo deseas, similar al login principal -->
        <div class="background-layer active" style="background-image: url('{{ asset('images/password-reset-bg.jpg') }}');"></div>
    </div>
</body>
</html>

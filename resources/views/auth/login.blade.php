<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuestionarios RFI - Minmer Global</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@800&family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background: rgb(44, 56, 86);
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
            accent-color: #ff9c00;
        }

        .forgot-password {
            color: #ff9c00;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.3s ease-in-out;
        }

        .forgot-password:hover {
            color: #e08c00;
            text-decoration: underline;
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
        <!-- Generar estrellas dinámicamente con JavaScript -->
        <script>
            const starsContainer = document.querySelector('.stars');
            for (let i = 0; i < 100; i++) {
                const star = document.createElement('div');
                star.className = 'star';
                star.style.left = Math.random() * 100 + 'vw';
                star.style.top = Math.random() * 100 + 'vh';
                star.style.animationDelay = Math.random() * 10 + 's';
                starsContainer.appendChild(star);

                // Evento para mover la estrella al tocarla
                star.addEventListener('mouseover', (e) => {
                    const rect = star.getBoundingClientRect();
                    const mouseX = e.clientX - rect.left;
                    const mouseY = e.clientY - rect.top;
                    const pushStrength = 50; // Fuerza del empujón
                    const dx = (mouseX - rect.width / 2) / (rect.width / 2) * pushStrength;
                    const dy = (mouseY - rect.height / 2) / (rect.height / 2) * pushStrength;

                    star.style.transition = 'transform 0.3s ease-out';
                    star.style.transform = `translate(${dx}px, ${dy}px)`;

                    // Regresar a la posición original después de un tiempo
                    setTimeout(() => {
                        star.style.transition = 'transform 1s ease-out';
                        star.style.transform = 'translate(0, 0)';
                    }, 300);
                });
            }
        </script>
    </div>
    <div class="login-container">
        <div class="logo">
            <img src="{{ asset('images/LogoAzul.png') }}" alt="Minmer Global Logo" id="minmer-logo">
        </div>
        <h1>Cuestionarios RFI</h1>
        <p class="tagline">Soluciones Integrales Minmer Global</p>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="email" id="usuario" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="password" required>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-check">
                <label class="remember-me">
                    <input type="checkbox" name="remember"> Permanecer Conectado
                </label>
                <a href="{{ route('password.request') }}" class="forgot-password">Recuperar Contraseña</a>
            </div>
            <button type="submit" class="btn-login">INICIAR SESIÓN</button>
        </form>
        <div class="footer-text">
            © 2025 Minmer Global. Todos los derechos reservados<br>
            <a href="/terms-conditions">Términos y condiciones</a> | <a href="/privacy-policy">Política de privacidad</a>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuestionarios RFI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <header>
        <div class="header-container">
            <img src="{{ asset('images/logoBlanco.png') }}" alt="Logo" class="header-logo">
            {{-- Se cambió 'home' a 'dashboard' ya que 'home' no está definida por defecto en Breeze --}}
            <a href="{{ route('dashboard') }}" class="header-title">Cuestionarios RFI</a>
            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
            <nav class="header-nav">
                <!-- Elementos de navegación principales -->
                <a href="{{ route('dashboard') }}" class="{{ Request::routeIs('dashboard') ? 'active-link' : '' }}">Dashboard</a>
                <a href="{{ route('admin.users.index') }}">Usuarios</a>
                <a href="{{ route('admin.questionnaires.index') }}">Cuestionarios</a>
                


                <!-- Sección de Perfil y Cerrar Sesión -->
                @auth
                <div class="dropdown">
                    <button class="dropbtn">
                        {{ Auth::user()->name }} <i class="fas fa-caret-down"></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="{{ route('profile.edit') }}">Perfil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">Salir</button>
                        </form>
                    </div>
                </div>
                @endauth
            </nav>
        </div>
    </header>


</body>
</html>

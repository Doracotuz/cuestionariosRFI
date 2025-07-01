<header>
    <div class="header-container">
        <img src="{{ asset('images/logoBlanco.png') }}" alt="Logo" class="header-logo">
        <a href="{{ route('dashboard') }}" class="header-title">Cuestionarios RFI</a>
        <div class="menu-toggle">
            <i class="fas fa-bars"></i>
        </div>
        <nav class="header-nav">
            <!-- Elementos de navegación principales -->
            @if(Auth::check() && Auth::user()->role === 'admin')
            <a href="{{ route('dashboard') }}" class="{{ Request::routeIs('dashboard') ? 'active-link' : '' }}">Dashboard</a>
            @endif

            <!-- Dropdown de Operaciones (para Admin) -->
            @if(Auth::check() && Auth::user()->role === 'admin')
            <div class="dropdown">
                <button class="dropbtn">
                    Administración <i class="fas fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href="{{ route('admin.users.index') }}">Usuarios</a>
                    <a href="{{ route('admin.questionnaires.index') }}">Cuestionarios</a>
                    <a href="{{ route('admin.assignments.index') }}">Asignaciones</a>
                    <a href="{{ route('admin.responses.index') }}">Respuestas</a>
                </div>
            </div>
            @endif

            <!-- Enlace a Cuestionarios para todos los usuarios autenticados -->
            <a href="{{ route('user.questionnaires.index') }}" class="{{ Request::routeIs('user.questionnaires.index') ? 'active-link' : '' }}">Cuestionarios</a>

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
                        <button type="submit">Cerrar Sesión</button>
                    </form>
                </div>
            </div>
            @endauth
        </nav>
    </div>
</header>


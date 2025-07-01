<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Cuestionarios RFI') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Incluye Font Awesome para los iconos del header -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <!-- Incluye Montserrat para las vistas de autenticación si lo deseas -->
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">


        <!-- Scripts de Vite (para app.css y app.js de Breeze) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Estilos de tu encabezado personalizado (header.css) -->
        <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <!-- Incluye tu nuevo componente de encabezado aquí -->
            <x-app-header></x-app-header>

            {{--
                La sección "Page Heading" se ha eliminado de aquí
                para evitar conflictos de z-index con el dropdown del header.
                Los títulos de página ahora se manejarán directamente
                dentro del contenido de cada vista.
            --}}
            {{-- @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset --}}

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Scripts de tu encabezado personalizado -->
        <script src="{{ asset('js/header.js') }}"></script>

        <!-- Aquí se renderizarán los scripts "pusheados" desde las vistas hijas -->
        @stack('scripts')
    </body>
</html>
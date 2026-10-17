<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Forcha Sport · Ropa y equipo deportivo')</title>
    <meta name="description"
        content="@yield('description', 'Ropa deportiva, zapatillas y accesorios de calidad premium. Envíos a todo el Perú.')">

    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpeg') }}">
    <meta property="og:title" content="@yield('title', 'Forcha Sport · Ropa y equipo deportivo')">
    <meta property="og:image" content="{{ asset('images/logo.jpeg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen flex-col">
    @include('partials.announcement')
    @include('partials.header')

    <main class="flex-1">
        @include('partials.flash')
        @yield('content')
    </main>

    @include('partials.footer')
</body>

</html>

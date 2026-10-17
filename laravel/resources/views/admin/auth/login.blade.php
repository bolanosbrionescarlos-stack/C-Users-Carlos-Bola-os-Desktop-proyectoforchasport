<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Iniciar sesión · Forcha Sport Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen items-center justify-center bg-dark">
    <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-[0_16px_64px_rgba(0,0,0,0.45)] sm:p-8">
        <div class="text-center">
            <div class="font-display text-3xl font-black uppercase tracking-tight text-dark">
                FORCHA<span class="text-primary">—</span>SPORT
            </div>
            <p class="mt-1 text-sm uppercase tracking-widest text-gray-400">Panel de administración</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-4">
            @csrf

            <div>
                <label for="email" class="mb-1 block text-sm font-semibold">Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="input" required autofocus>
            </div>

            <div>
                <label for="password" class="mb-1 block text-sm font-semibold">Contraseña</label>
                <input id="password" type="password" name="password" class="input" required>
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember" value="1" class="rounded border-gray-300">
                Recordarme
            </label>

            @if ($errors->any())
                <div class="rounded-md border border-danger/30 bg-danger/10 px-4 py-3 text-sm text-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <button type="submit" class="btn-primary w-full">Iniciar sesión</button>
        </form>
    </div>
</body>

</html>

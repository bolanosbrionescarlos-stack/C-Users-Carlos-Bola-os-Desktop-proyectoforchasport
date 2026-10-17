<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Panel') · Forcha Sport Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-surface">
    <div class="flex min-h-screen">
        {{-- Sidebar (fijo en escritorio, off-canvas en móvil) --}}
        <div data-admin-backdrop class="fixed inset-0 z-40 hidden bg-black/50 lg:hidden"></div>
        <aside data-admin-sidebar id="admin-sidebar"
            class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col bg-dark text-gray-300 transition-transform duration-200 lg:static lg:z-auto lg:translate-x-0">
            <div class="flex items-center justify-between border-b border-dark-3 px-6 py-5">
                <div>
                    <a href="{{ route('admin.dashboard') }}"
                        class="font-display text-xl font-black uppercase tracking-tight text-white">
                        FORCHA<span class="text-primary">—</span>SPORT
                    </a>
                    <p class="mt-1 text-xs uppercase tracking-widest text-gray-500">Panel de administración</p>
                </div>
                <button type="button" data-admin-close
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md text-2xl leading-none text-gray-400 hover:bg-dark-3 hover:text-white"
                    aria-label="Cerrar menú">×</button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4 text-sm font-display font-semibold uppercase tracking-wide">
                @foreach ([
                    'admin.dashboard' => ['label' => 'Dashboard', 'icon' => '📊'],
                    'admin.orders.index' => ['label' => 'Pedidos', 'icon' => '🧾'],
                    'admin.productos.index' => ['label' => 'Productos', 'icon' => '👕'],
                    'admin.categorias.index' => ['label' => 'Categorías', 'icon' => '🏷️'],
                    'admin.cupones.index' => ['label' => 'Cupones', 'icon' => '🎟️'],
                    'admin.config.edit' => ['label' => 'Configuración', 'icon' => '⚙️'],
                ] as $routeName => $item)
                    <a href="{{ route($routeName) }}"
                        @class([
                            'flex items-center gap-3 rounded-md px-3 py-3 transition min-h-[44px]',
                            'bg-primary text-white' => request()->routeIs($routeName),
                            'hover:bg-dark-3 hover:text-white' => ! request()->routeIs($routeName),
                        ])>
                        <span>{{ $item['icon'] }}</span>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="border-t border-dark-3 px-3 py-4">
                <a href="{{ route('home') }}" target="_blank"
                    class="flex min-h-[44px] items-center gap-3 rounded-md px-3 py-3 text-sm font-display font-semibold uppercase transition hover:bg-dark-3 hover:text-white">
                    <span>🛒</span> Ver tienda
                </a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex min-h-[44px] w-full items-center gap-3 rounded-md px-3 py-3 text-left text-sm font-display font-semibold uppercase transition hover:bg-dark-3 hover:text-white">
                        <span>🚪</span> Cerrar sesión
                    </button>
                </form>
            </div>
        </aside>

        {{-- Contenido --}}
        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex items-center justify-between gap-3 border-b border-gray-200 bg-white px-4 py-3 lg:px-8 lg:py-4">
                <div class="flex min-w-0 items-center gap-3">
                    <button type="button" data-admin-open data-admin-toggle
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md border border-gray-200 text-xl leading-none text-dark hover:bg-surface lg:hidden"
                        aria-label="Abrir menú" aria-controls="admin-sidebar" aria-expanded="false">☰</button>
                    <h1 class="truncate font-display text-xl font-bold uppercase text-dark sm:text-2xl">@yield('title', 'Panel')</h1>
                </div>
                <span class="hidden shrink-0 text-sm text-gray-500 sm:block">{{ auth()->user()->name }}</span>
            </header>

            <main class="p-4 lg:p-8">
                @if (session('status'))
                    <div class="mb-6 rounded-md border border-success/30 bg-success/10 px-4 py-3 text-sm font-medium text-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-md border border-danger/30 bg-danger/10 px-4 py-3 text-sm text-danger">
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>

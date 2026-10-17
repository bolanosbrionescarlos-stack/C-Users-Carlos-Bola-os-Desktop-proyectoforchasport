@inject('cart', 'App\Services\CartService')

<header class="sticky top-0 z-40 border-b border-gray-100 bg-white/95 backdrop-blur">
    <div class="shop-container flex h-[72px] items-center justify-between gap-6">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex shrink-0 items-center">
            <img src="{{ asset('images/logo.jpeg') }}" alt="Forcha Sport"
                class="h-11 w-11 object-contain sm:h-12 sm:w-12">
        </a>

        {{-- Navegación escritorio --}}
        <nav class="hidden items-center gap-6 font-display text-sm font-semibold uppercase tracking-wide lg:flex">
            <a href="{{ route('products.index') }}" class="text-dark transition hover:text-primary">Todos los Productos</a>
            <a href="{{ route('products.index', ['categoria' => 'ropa-deportiva']) }}"
                class="text-dark transition hover:text-primary">Ropa Deportiva</a>
            <a href="{{ route('products.index', ['categoria' => 'zapatillas']) }}"
                class="text-dark transition hover:text-primary">Zapatillas</a>
            <a href="{{ route('products.index', ['categoria' => 'accesorios']) }}"
                class="text-dark transition hover:text-primary">Accesorios</a>
            <a href="{{ route('products.index', ['onSale' => 1]) }}"
                class="text-primary transition hover:text-primary-dark">Ofertas</a>
        </nav>

        {{-- Acciones --}}
        <div class="flex items-center gap-3">
            <button type="button" data-search-toggle aria-label="Abrir búsqueda"
                class="flex h-10 w-10 items-center justify-center rounded-full text-dark transition hover:bg-surface">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.35-4.35" />
                </svg>
            </button>

            <a href="{{ route('cart.index') }}" aria-label="Abrir carrito"
                class="relative flex h-10 w-10 items-center justify-center rounded-full text-dark transition hover:bg-surface">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <path d="M16 10a4 4 0 0 1-8 0" />
                </svg>
                @if ($cart->totalItems() > 0)
                    <span
                        class="absolute -right-0.5 -top-0.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-primary px-1 text-[11px] font-bold text-white">
                        {{ $cart->totalItems() }}
                    </span>
                @endif
            </a>

            <button type="button" data-mobile-menu-button aria-label="Menú"
                class="flex h-10 w-10 items-center justify-center rounded-full text-dark transition hover:bg-surface lg:hidden">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Buscador --}}
    <div data-search-form class="hidden border-t border-gray-100 bg-white">
        <form method="GET" action="{{ route('products.index') }}" class="shop-container flex items-center gap-3 py-3">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Buscar productos..."
                class="input" autofocus>
            <button type="submit" class="btn-primary btn-sm">Buscar</button>
        </form>
    </div>

    {{-- Navegación móvil --}}
    <nav data-mobile-menu class="hidden border-t border-gray-100 bg-white lg:hidden">
        <div class="shop-container flex flex-col py-2">
            <a href="{{ route('products.index') }}" class="border-b border-gray-50 py-3 font-display font-semibold uppercase">Todos
                los Productos</a>
            <a href="{{ route('products.index', ['categoria' => 'ropa-deportiva']) }}"
                class="border-b border-gray-50 py-3 font-display font-semibold uppercase">Ropa Deportiva</a>
            <a href="{{ route('products.index', ['categoria' => 'zapatillas']) }}"
                class="border-b border-gray-50 py-3 font-display font-semibold uppercase">Zapatillas</a>
            <a href="{{ route('products.index', ['categoria' => 'accesorios']) }}"
                class="border-b border-gray-50 py-3 font-display font-semibold uppercase">Accesorios</a>
            <a href="{{ route('products.index', ['onSale' => 1]) }}"
                class="py-3 font-display font-semibold uppercase text-primary">Ofertas</a>
        </div>
    </nav>
</header>

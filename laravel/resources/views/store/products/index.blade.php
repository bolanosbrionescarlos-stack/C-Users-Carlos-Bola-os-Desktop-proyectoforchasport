@extends('layouts.app')

@section('title', $currentCategory ? $currentCategory->name.' · Productos' : 'Catálogo de Productos · Forcha Sport')

@section('description', 'Explora toda la colección de ropa deportiva, zapatillas y accesorios FORCHA - SPORT. Envíos a todo el Perú.')

@section('content')
    <div class="shop-container py-10">
        <div class="mb-8">
            <h1 class="text-4xl font-black uppercase text-dark">
                @if (request('q'))
                    Resultados para “{{ request('q') }}”
                @elseif ($currentCategory)
                    {{ $currentCategory->name }}
                @else
                    Todos los productos
                @endif
            </h1>
            <p class="mt-2 text-gray-500">{{ $products->total() }} productos encontrados</p>
        </div>

        <div class="grid gap-10 lg:grid-cols-[240px_1fr]">
            {{-- Filtros --}}
            <aside class="space-y-8">
                <div>
                    <h3 class="font-display text-base font-bold uppercase tracking-wide text-dark">Categorías</h3>
                    <ul class="mt-3 space-y-1.5 text-sm">
                        <li>
                            <a href="{{ route('products.index') }}"
                                @class(['font-semibold text-primary' => ! request('categoria'), 'text-gray-500 hover:text-dark' => request('categoria')])>
                                Todos
                            </a>
                        </li>
                        @foreach ($categories as $category)
                            <li>
                                <a href="{{ route('products.index', ['categoria' => $category->slug]) }}"
                                    @class(['font-semibold text-primary' => request('categoria') === $category->slug, 'text-gray-500 hover:text-dark' => request('categoria') !== $category->slug])>
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h3 class="font-display text-base font-bold uppercase tracking-wide text-dark">Filtros</h3>
                    <ul class="mt-3 space-y-1.5 text-sm">
                        <li>
                            <a href="{{ request()->fullUrlWithQuery(['onSale' => 1]) }}"
                                @class(['font-semibold text-primary' => request()->boolean('onSale')])>
                                🏷️ Solo ofertas
                            </a>
                        </li>
                        <li>
                            <a href="{{ request()->fullUrlWithQuery(['isNew' => 1]) }}"
                                @class(['font-semibold text-primary' => request()->boolean('isNew')])>
                                ✨ Nuevos
                            </a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-display text-base font-bold uppercase tracking-wide text-dark">Ordenar por</h3>
                    <ul class="mt-3 space-y-1.5 text-sm">
                        @foreach (['' => 'Más recientes', 'price-asc' => 'Precio: menor a mayor', 'price-desc' => 'Precio: mayor a menor', 'name' => 'Nombre A-Z'] as $value => $label)
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['orderBy' => $value, 'page' => null]) }}"
                                    @class(['font-semibold text-primary' => request('orderBy', '') === $value, 'text-gray-500 hover:text-dark' => request('orderBy', '') !== $value])>
                                    {{ $label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <form method="GET" action="{{ route('products.index') }}" class="space-y-3">
                    @foreach (request()->query() as $key => $value)
                        @if (in_array($key, ['q', 'categoria', 'onSale', 'isNew', 'orderBy'], true))
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <h3 class="font-display text-base font-bold uppercase tracking-wide text-dark">Precio (S/)</h3>
                    <div class="flex items-center gap-2">
                        <input type="number" name="minPrice" min="0" step="1" placeholder="Mín"
                            value="{{ request('minPrice') }}" class="input !px-3 !py-2 text-sm">
                        <input type="number" name="maxPrice" min="0" step="1" placeholder="Máx"
                            value="{{ request('maxPrice') }}" class="input !px-3 !py-2 text-sm">
                    </div>
                    <button type="submit" class="btn-outline btn-sm w-full">Aplicar</button>
                </form>
            </aside>

            {{-- Grid de productos --}}
            <div>
                @if ($products->isEmpty())
                    <div class="rounded-lg border border-gray-200 py-20 text-center">
                        <span class="text-5xl">🔍</span>
                        <h3 class="mt-4 font-display text-2xl font-bold uppercase text-dark">No encontramos productos</h3>
                        <p class="mt-2 text-gray-500">Intenta con otros filtros o términos de búsqueda.</p>
                        <a href="{{ route('products.index') }}" class="btn-primary mt-6">Ver todos los productos</a>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-5 md:grid-cols-3">
                        @foreach ($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    <div class="mt-10">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

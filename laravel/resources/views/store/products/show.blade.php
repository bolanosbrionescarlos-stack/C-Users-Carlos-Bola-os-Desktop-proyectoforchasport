@extends('layouts.app')

@section('title', $product->name.' · Forcha Sport')

@section('description', \Illuminate\Support\Str::limit(strip_tags($product->description ?? $product->name), 155))

@section('content')
    @php($variants = $product->activeVariants)
    @php($discount = $product->discountPercent())

    <div class="shop-container py-10">
        <nav class="mb-6 text-sm text-gray-400">
            <a href="{{ route('home') }}" class="hover:text-primary">Inicio</a> /
            <a href="{{ route('products.index') }}" class="hover:text-primary">Productos</a> /
            @if ($product->category)
                <a href="{{ route('products.index', ['categoria' => $product->category->slug]) }}"
                    class="hover:text-primary">{{ $product->category->name }}</a> /
            @endif
            <span class="text-dark">{{ $product->name }}</span>
        </nav>

        <div class="grid gap-12 lg:grid-cols-2">
            {{-- Imagen --}}
            <div class="min-w-0">
                <div class="relative aspect-square overflow-hidden rounded-lg border border-gray-100 bg-surface">
                    @if ($product->mainImage())
                        <img src="{{ $product->mainImage() }}" alt="{{ $product->name }}"
                            class="h-full w-full object-cover"
                            onerror="this.classList.add('hidden'); this.parentElement.querySelector('[data-fallback]').classList.remove('hidden');">
                    @endif

                    <div @class([
                        'absolute inset-0 flex flex-col items-center justify-center gap-3 bg-gradient-to-br from-dark to-dark-3 text-white',
                        'hidden' => $product->mainImage(),
                    ]) data-fallback>
                        <span class="text-6xl">📦</span>
                        <span class="font-display text-2xl font-bold uppercase">{{ $product->name }}</span>
                    </div>

                    <div class="absolute left-4 top-4 flex flex-col gap-1.5">
                        @if ($product->is_new)
                            <span class="badge-new">Nuevo</span>
                        @endif
                        @if ($discount > 0)
                            <span class="badge-sale">-{{ $discount }}%</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Detalles --}}
            <div class="min-w-0">
                @if ($product->category)
                    <span class="text-sm font-semibold uppercase tracking-wide text-primary">{{ $product->category->name }}</span>
                @endif

                <h1 class="mt-2 break-words text-3xl font-black uppercase text-dark sm:text-4xl md:text-5xl">{{ $product->name }}</h1>

                <div class="mt-4 flex flex-wrap items-baseline gap-x-3 gap-y-1">
                    <span class="font-display text-4xl font-black text-dark">@money($product->price)</span>
                    @if ($product->compare_price && $product->compare_price > $product->price)
                        <span class="text-xl text-gray-400 line-through">@money($product->compare_price)</span>
                        <span class="badge-sale">Ahorras @money($product->compare_price - $product->price)</span>
                    @endif
                </div>

                @if ($product->description)
                    <p class="mt-6 leading-relaxed text-gray-600">{{ $product->description }}</p>
                @endif

                @if ($variants->isEmpty())
                    <div class="mt-8 rounded-md border border-warning/30 bg-warning/10 px-4 py-3 text-sm font-medium">
                        Este producto está temporalmente agotado.
                    </div>
                @else
                    <form method="POST" action="{{ route('cart.add') }}" class="mt-8">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        @php($hasOptions = $variants->contains(fn ($variant) => $variant->size || $variant->color))
                        @php($firstAvailableId = $variants->first(fn ($variant) => $variant->stock > 0)?->id)

                        @if ($hasOptions)
                            <fieldset>
                                <legend class="font-display text-base font-bold uppercase tracking-wide text-dark">
                                    Elige tu variante
                                </legend>

                                <div class="mt-3 space-y-3">
                                    @foreach ($variants->groupBy(fn ($variant) => $variant->size ?: 'Única') as $size => $sizeVariants)
                                        <div>
                                            <span class="text-sm font-semibold uppercase text-gray-500">Talla:
                                                {{ $size }}</span>
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                @foreach ($sizeVariants as $index => $variant)
                                                    <label
                                                        class="cursor-pointer">
                                                        <input type="radio" name="variant_id" value="{{ $variant->id }}"
                                                            class="peer sr-only" {{ $variant->id === $firstAvailableId ? 'checked' : '' }}
                                                            {{ $variant->stock === 0 ? 'disabled' : '' }}>
                                                        <span
                                                            class="inline-flex min-w-14 flex-col items-center rounded-md border-2 border-gray-200 px-3 py-2 text-center transition peer-checked:border-primary peer-checked:bg-primary/5 peer-disabled:cursor-not-allowed peer-disabled:opacity-40">
                                                            <span class="font-display text-sm font-bold uppercase">
                                                                {{ $variant->color ?: ($variant->size ?: 'Único') }}
                                                            </span>
                                                            <span class="text-[11px] text-gray-400">
                                                                {{ $variant->stock > 0 ? ($variant->stock <= 5 ? '¡Quedan '.$variant->stock.'!' : 'En stock') : 'Agotado' }}
                                                            </span>
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </fieldset>
                        @else
                            <input type="hidden" name="variant_id" value="{{ $variants->first()->id }}">
                        @endif

                        <div class="mt-8 flex items-center gap-3 sm:gap-4">
                            <div class="flex shrink-0 items-center rounded-md border border-gray-200">
                                <button type="button" data-qty-minus class="px-4 py-3 text-lg text-gray-500 hover:text-primary"
                                    aria-label="Quitar uno">−</button>
                                <input type="number" name="quantity" value="1" min="1"
                                    max="{{ min(20, $variants->max('stock')) }}" class="w-16 border-x border-gray-200 py-3 text-center outline-none">
                                <button type="button" data-qty-plus class="px-4 py-3 text-lg text-gray-500 hover:text-primary"
                                    aria-label="Agregar uno">＋</button>
                            </div>

                            <button type="submit" class="btn-primary min-w-0 flex-1"
                                {{ $product->hasStock() ? '' : 'disabled' }}>
                                {{ $product->hasStock() ? 'Agregar al carrito' : 'Agotado' }}
                            </button>
                        </div>
                    </form>
                @endif

                @if ($product->details)
                    <div class="mt-10 border-t border-gray-100 pt-6">
                        <h2 class="font-display text-lg font-bold uppercase text-dark">Detalles del producto</h2>
                        <div class="mt-3 whitespace-pre-line text-sm leading-relaxed text-gray-600">{{ $product->details }}</div>
                    </div>
                @endif

                <div class="mt-8 grid gap-3 rounded-lg bg-surface p-5 text-sm text-gray-600 sm:grid-cols-2">
                    <span>🚚 Envío gratis desde S/{{ number_format((float) config('store.free_shipping_over'), 0) }}</span>
                    <span>🔒 Pago seguro: Yape, Plin, transferencia o contra entrega</span>
                    <span>↩️ Cambios en 7 días</span>
                    <span>💬 Asesoría por WhatsApp</span>
                </div>
            </div>
        </div>

        {{-- Relacionados --}}
        @if ($related->isNotEmpty())
            <section class="mt-20">
                <h2 class="text-3xl font-black uppercase text-dark">También te puede gustar</h2>
                <div class="divider !mx-0"></div>

                <div class="mt-6 grid grid-cols-2 gap-5 md:grid-cols-4">
                    @foreach ($related as $relatedProduct)
                        <x-product-card :product="$relatedProduct" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection

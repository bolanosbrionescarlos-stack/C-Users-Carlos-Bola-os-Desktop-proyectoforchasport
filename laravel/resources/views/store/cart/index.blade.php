@extends('layouts.app')

@section('title', 'Tu carrito · Forcha Sport')

@section('content')
    @inject('cart', 'App\Services\CartService')

    <div class="shop-container py-10">
        <h1 class="text-4xl font-black uppercase text-dark">Tu carrito</h1>
        <div class="divider !mx-0"></div>

        @if ($lines->isEmpty())
            <div class="rounded-lg border border-gray-200 py-20 text-center">
                <span class="text-5xl">🛒</span>
                <h2 class="mt-4 font-display text-2xl font-bold uppercase text-dark">Tu carrito está vacío</h2>
                <p class="mt-2 text-gray-500">Explora el catálogo y agrega tus productos favoritos.</p>
                <a href="{{ route('products.index') }}" class="btn-primary mt-6">Ver productos</a>
            </div>
        @else
            <div class="grid gap-10 lg:grid-cols-[1fr_360px]">
                {{-- Líneas del carrito --}}
                <div class="space-y-4">
                    @foreach ($lines as $line)
                        <div class="grid grid-cols-[96px_1fr] gap-4 rounded-lg border border-gray-100 p-4 sm:grid-cols-[96px_1fr_auto]">
                            <a href="{{ route('products.show', $line['product']) }}"
                                class="aspect-square overflow-hidden rounded-md bg-surface">
                                @if ($line['product']->mainImage())
                                    <img src="{{ $line['product']->mainImage() }}" alt="{{ $line['product']->name }}"
                                        class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full items-center justify-center text-3xl">📦</div>
                                @endif
                            </a>

                            <div>
                                <a href="{{ route('products.show', $line['product']) }}"
                                    class="font-display text-lg font-bold uppercase text-dark hover:text-primary">
                                    {{ $line['product']->name }}
                                </a>
                                @if ($line['variant'])
                                    <p class="mt-0.5 text-sm text-gray-500">{{ $line['variant']->label() }}</p>
                                @endif
                                <p class="mt-1 text-sm text-gray-500">Precio unitario: @money($line['unit_price'])</p>

                                @if ($line['quantity'] < $line['available_stock'])
                                    <p class="mt-1 text-xs text-warning">Solo quedan {{ $line['available_stock'] }} unidades
                                    </p>
                                @elseif ($line['quantity'] >= $line['available_stock'])
                                    <p class="mt-1 text-xs text-danger">Stock máximo disponible: {{ $line['available_stock'] }}
                                    </p>
                                @endif

                                <div class="mt-3 flex items-center gap-3">
                                    <form method="POST" action="{{ route('cart.update', $line['key']) }}" class="flex items-center">
                                        @csrf
                                        @method('PATCH')
                                        <div class="flex items-center rounded-md border border-gray-200">
                                            <button type="button" data-qty-minus class="px-3 py-1.5 text-gray-500">−</button>
                                            <input type="number" name="quantity" value="{{ $line['quantity'] }}" min="0"
                                                max="{{ min(20, $line['available_stock']) }}"
                                                class="w-12 border-x border-gray-200 py-1.5 text-center text-sm outline-none">
                                            <button type="button" data-qty-plus class="px-3 py-1.5 text-gray-500">＋</button>
                                        </div>
                                        <button type="submit" class="btn-outline btn-sm ml-2">Actualizar</button>
                                    </form>

                                    <form method="POST" action="{{ route('cart.remove', $line['key']) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-gray-400 underline transition hover:text-danger">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="text-right font-display text-xl font-bold text-dark">
                                @money($line['subtotal'])
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Resumen --}}
                <aside class="h-fit rounded-lg border border-gray-100 p-6">
                    <h2 class="font-display text-xl font-bold uppercase text-dark">Resumen del pedido</h2>

                    <dl class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Subtotal</dt>
                            <dd class="font-semibold">@money($subtotal)</dd>
                        </div>

                        @if ($discount > 0)
                            <div class="flex justify-between text-success">
                                <dt>Descuento ({{ $coupon->code }})</dt>
                                <dd class="font-semibold">-@money($discount)</dd>
                            </div>
                        @endif

                        <div class="flex justify-between border-t border-gray-100 pt-2 text-base">
                            <dt class="font-display font-bold uppercase">Total</dt>
                            <dd class="font-display font-black">@money($subtotal - $discount)
                                <span class="text-xs font-normal text-gray-400">+ envío</span>
                            </dd>
                        </div>
                    </dl>

                    {{-- Cupón --}}
                    <div class="mt-6 border-t border-gray-100 pt-4">
                        @if ($coupon)
                            <div class="flex items-center justify-between rounded-md border border-success/30 bg-success/10 px-3 py-2 text-sm">
                                <span class="font-semibold text-success">{{ $coupon->code }} · {{ $coupon->description() }}</span>
                                <form method="POST" action="{{ route('cart.coupon.remove') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-danger" aria-label="Quitar cupón">✕</button>
                                </form>
                            </div>
                        @else
                            <form method="POST" action="{{ route('cart.coupon.apply') }}" class="flex gap-2">
                                @csrf
                                <input type="text" name="coupon" placeholder="Código de descuento" class="input !py-2 text-sm"
                                    value="{{ old('coupon') }}">
                                <button type="submit" class="btn-dark btn-sm">Aplicar</button>
                            </form>
                            @error('coupon')
                                <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    <a href="{{ route('checkout.create') }}" class="btn-primary mt-6 w-full">Proceder al pago</a>
                    <a href="{{ route('products.index') }}" class="btn-outline mt-3 w-full">Seguir comprando</a>

                    <p class="mt-4 text-center text-xs text-gray-400">
                        🚚 Envío gratis en pedidos desde S/{{ number_format((float) config('store.free_shipping_over'), 0) }}
                    </p>
                </aside>
            </div>
        @endif
    </div>
@endsection

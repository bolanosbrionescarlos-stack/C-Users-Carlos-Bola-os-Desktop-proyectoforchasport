@extends('layouts.app')

@section('title', 'Pedido '.$order->code.' · Forcha Sport')

@section('content')
    @php($whatsapp = \App\Models\StoreConfig::get('whatsapp', config('store.whatsapp')))

    <div class="shop-container max-w-4xl py-12">
        <div class="rounded-lg border border-success/30 bg-success/10 p-6 text-center">
            <span class="text-4xl">🎉</span>
            <h1 class="mt-3 text-3xl font-black uppercase text-dark">¡Gracias por tu pedido!</h1>
            <p class="mt-2 text-gray-600">
                Tu pedido <strong class="font-display text-lg">{{ $order->code }}</strong> fue registrado correctamente.
                Te contactaremos por WhatsApp al <strong>{{ $order->customer_phone }}</strong> para confirmar los datos
                de pago y la entrega.
            </p>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2">
            <div class="rounded-lg border border-gray-100 p-6">
                <h2 class="font-display text-lg font-bold uppercase text-dark">Estado del pedido</h2>
                <div class="mt-3">
                    <span class="inline-block rounded-full px-3 py-1 text-sm font-bold text-white"
                        style="background: {{ $order->order_status->color() }}">
                        {{ $order->statusLabel() }}
                    </span>
                </div>

                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Método de envío</dt>
                        <dd class="font-semibold">
                            {{ config("store.shipping_methods.{$order->shipping_method}.label", $order->shipping_method) }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Método de pago</dt>
                        <dd class="font-semibold">
                            {{ config("store.payment_methods.{$order->payment_method}.label", $order->payment_method) }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Estado de pago</dt>
                        <dd class="font-semibold" style="color: {{ $order->payment_status->color() }}">
                            {{ $order->paymentStatusLabel() }}
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-lg border border-gray-100 p-6">
                <h2 class="font-display text-lg font-bold uppercase text-dark">Entrega</h2>
                <p class="mt-3 text-sm leading-relaxed text-gray-600">
                    <strong>{{ $order->customer_name }}</strong><br>
                    {{ $order->fullAddress() }}<br>
                    @if ($order->reference)
                        Referencia: {{ $order->reference }}<br>
                    @endif
                    📱 {{ $order->customer_phone }}
                </p>
                <a href="https://wa.me/{{ $whatsapp }}" class="btn-dark btn-sm mt-4">Escribir por WhatsApp</a>
            </div>
        </div>

        <div class="mt-6 rounded-lg border border-gray-100 p-6">
            <h2 class="font-display text-lg font-bold uppercase text-dark">Productos</h2>

            <ul class="mt-4 divide-y divide-gray-100">
                @foreach ($order->items as $item)
                    <li class="flex items-center justify-between gap-4 py-3">
                        <div class="flex items-center gap-3">
                            @if ($item->image)
                                <img src="{{ $item->image }}" alt="{{ $item->product_name }}" class="h-14 w-14 rounded-md object-cover">
                            @else
                                <span class="flex h-14 w-14 items-center justify-center rounded-md bg-surface text-2xl">📦</span>
                            @endif
                            <div>
                                <p class="font-display font-bold uppercase text-dark">{{ $item->product_name }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $item->quantity }} × @money($item->price)
                                    @if ($item->size || $item->color)
                                        · {{ collect([$item->size, $item->color])->filter()->implode(' / ') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <span class="font-display font-bold">@money($item->subtotal())</span>
                    </li>
                @endforeach
            </ul>

            <dl class="mt-4 space-y-2 border-t border-gray-100 pt-4 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Subtotal</dt>
                    <dd class="font-semibold">@money($order->subtotal)</dd>
                </div>
                @if ((float) $order->discount > 0)
                    <div class="flex justify-between text-success">
                        <dt>Descuento ({{ $order->coupon_code }})</dt>
                        <dd class="font-semibold">-@money($order->discount)</dd>
                    </div>
                @endif
                <div class="flex justify-between">
                    <dt class="text-gray-500">Envío</dt>
                    <dd class="font-semibold">
                        @if ((float) $order->shipping_cost > 0)
                            @money($order->shipping_cost)
                        @else
                            Gratis
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between border-t border-gray-100 pt-2 text-lg">
                    <dt class="font-display font-bold uppercase">Total</dt>
                    <dd class="font-display font-black">@money($order->total)</dd>
                </div>
            </dl>
        </div>

        <div class="mt-8 flex flex-wrap justify-center gap-4">
            <a href="{{ route('products.index') }}" class="btn-outline">Seguir comprando</a>
            <a href="{{ route('home') }}" class="btn-dark">Volver al inicio</a>
        </div>
    </div>
@endsection

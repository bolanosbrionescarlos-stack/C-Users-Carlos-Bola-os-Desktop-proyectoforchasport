@extends('admin.layouts.app')

@section('title', 'Pedido '.$order->code)

@section('content')
    <div class="grid gap-6 lg:grid-cols-[1fr_340px]">
        <div class="space-y-6">
            {{-- Productos --}}
            <section class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
                <div class="border-b border-gray-100 px-3 py-4 md:px-6">
                    <h2 class="font-display text-lg font-bold uppercase text-dark">Productos</h2>
                </div>

                <table class="min-w-full text-sm">
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="px-3 py-3 md:px-6">
                                    <p class="font-semibold text-dark">{{ $item->product_name }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $item->quantity }} × @money($item->price)
                                        @if ($item->size || $item->color)
                                            · {{ collect([$item->size, $item->color])->filter()->implode(' / ') }}
                                        @endif
                                    </p>
                                </td>
                                <td class="px-3 py-3 text-right font-semibold md:px-6">@money($item->subtotal())</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t border-gray-100 text-sm">
                        <tr>
                            <td class="px-3 py-2 text-gray-500 md:px-6">Subtotal</td>
                            <td class="px-3 py-2 text-right md:px-6">@money($order->subtotal)</td>
                        </tr>
                        @if ((float) $order->discount > 0)
                            <tr class="text-success">
                                <td class="px-3 py-2 md:px-6">Descuento ({{ $order->coupon_code }})</td>
                                <td class="px-3 py-2 text-right md:px-6">-@money($order->discount)</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="px-3 py-2 text-gray-500 md:px-6">Envío ({{ $shippingMethods[$order->shipping_method]['label'] ?? $order->shipping_method }})</td>
                            <td class="px-3 py-2 text-right md:px-6">
                                @if ((float) $order->shipping_cost > 0)
                                    @money($order->shipping_cost)
                                @else
                                    Gratis
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-3 font-display font-bold uppercase md:px-6">Total</td>
                            <td class="px-3 py-3 text-right font-display text-lg font-black md:px-6">@money($order->total)</td>
                        </tr>
                    </tfoot>
                </table>
            </section>

            {{-- Cliente --}}
            <section class="rounded-lg border border-gray-200 bg-white p-4 md:p-6">
                <h2 class="font-display text-lg font-bold uppercase text-dark">Cliente y entrega</h2>

                <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-gray-500">Nombre</dt>
                        <dd class="font-semibold">{{ $order->customer_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Celular</dt>
                        <dd class="font-semibold">{{ $order->customer_phone }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Correo</dt>
                        <dd class="font-semibold">{{ $order->customer_email ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Método de pago</dt>
                        <dd class="font-semibold">
                            {{ $paymentMethods[$order->payment_method]['label'] ?? $order->payment_method }}
                            @if ($order->payment_ref)
                                · Ref: {{ $order->payment_ref }}
                            @endif
                        </dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-gray-500">Dirección</dt>
                        <dd class="font-semibold">{{ $order->fullAddress() }}</dd>
                    </div>
                    @if ($order->reference)
                        <div>
                            <dt class="text-gray-500">Referencia de ubicación</dt>
                            <dd class="font-semibold">{{ $order->reference }}</dd>
                        </div>
                    @endif
                    @if ($order->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-gray-500">Notas</dt>
                            <dd class="font-semibold">{{ $order->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </section>
        </div>

        {{-- Acciones --}}
        <div class="space-y-6">
            <section class="rounded-lg border border-gray-200 bg-white p-4 md:p-6">
                <h2 class="font-display text-lg font-bold uppercase text-dark">Estado del pedido</h2>

                <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="mt-4 space-y-3">
                    @csrf
                    @method('PATCH')

                    <select name="order_status" class="input">
                        @foreach (\App\Enums\OrderStatus::options() as $value => $label)
                            <option value="{{ $value }}" @selected($order->order_status->value === $value)>
                                {{ $label }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn-primary btn-sm w-full">Actualizar estado</button>
                </form>
            </section>

            <section class="rounded-lg border border-gray-200 bg-white p-4 md:p-6">
                <h2 class="font-display text-lg font-bold uppercase text-dark">Estado de pago</h2>

                <form method="POST" action="{{ route('admin.orders.payment', $order) }}" class="mt-4 space-y-3">
                    @csrf
                    @method('PATCH')

                    <select name="payment_status" class="input">
                        @foreach (\App\Enums\PaymentStatus::options() as $value => $label)
                            <option value="{{ $value }}" @selected($order->payment_status->value === $value)>
                                {{ $label }}</option>
                        @endforeach
                    </select>

                    <input type="text" name="payment_ref" value="{{ old('payment_ref', $order->payment_ref) }}"
                        placeholder="Referencia de pago" class="input">

                    <button type="submit" class="btn-dark btn-sm w-full">Actualizar pago</button>
                </form>
            </section>

            <section class="rounded-lg border border-gray-200 bg-surface p-4 text-sm text-gray-600 md:p-6">
                <p><strong>Creado:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p class="mt-1"><strong>Actualizado:</strong> {{ $order->updated_at->format('d/m/Y H:i') }}</p>
                <a href="https://wa.me/{{ ltrim($order->customer_phone, '+') }}" target="_blank"
                    class="btn-dark btn-sm mt-4 w-full">WhatsApp al cliente</a>
            </section>
        </div>
    </div>
@endsection

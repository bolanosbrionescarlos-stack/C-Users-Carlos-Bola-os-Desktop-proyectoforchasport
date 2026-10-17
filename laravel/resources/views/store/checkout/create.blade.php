@extends('layouts.app')

@section('title', 'Finalizar compra · Forcha Sport')

@section('content')
    <div class="shop-container py-10">
        <h1 class="text-4xl font-black uppercase text-dark">Finalizar compra</h1>
        <div class="divider !mx-0"></div>

        <div class="grid gap-10 lg:grid-cols-[1fr_380px]">
            <form method="POST" action="{{ route('checkout.store') }}" class="space-y-8">
                @csrf

                <section class="rounded-lg border border-gray-100 p-6">
                    <h2 class="font-display text-xl font-bold uppercase text-dark">1 · Datos de contacto</h2>

                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="customer_name" class="mb-1 block text-sm font-semibold">Nombre completo *</label>
                            <input id="customer_name" type="text" name="customer_name" value="{{ old('customer_name') }}"
                                class="input" required>
                        </div>
                        <div>
                            <label for="customer_phone" class="mb-1 block text-sm font-semibold">Celular (WhatsApp) *</label>
                            <input id="customer_phone" type="tel" name="customer_phone" value="{{ old('customer_phone') }}"
                                placeholder="999 888 777" class="input" required>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="customer_email" class="mb-1 block text-sm font-semibold">Correo electrónico
                                (opcional)</label>
                            <input id="customer_email" type="email" name="customer_email"
                                value="{{ old('customer_email') }}" class="input">
                        </div>
                    </div>
                </section>

                <section class="rounded-lg border border-gray-100 p-6">
                    <h2 class="font-display text-xl font-bold uppercase text-dark">2 · Dirección de entrega</h2>

                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="department" class="mb-1 block text-sm font-semibold">Departamento *</label>
                            <select id="department" name="department" class="input" required>
                                <option value="">Selecciona...</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department }}" @selected(old('department') === $department)>
                                        {{ $department }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="province" class="mb-1 block text-sm font-semibold">Provincia *</label>
                            <input id="province" type="text" name="province" value="{{ old('province') }}" class="input"
                                required>
                        </div>
                        <div>
                            <label for="district" class="mb-1 block text-sm font-semibold">Distrito *</label>
                            <input id="district" type="text" name="district" value="{{ old('district') }}" class="input"
                                required>
                        </div>
                        <div>
                            <label for="reference" class="mb-1 block text-sm font-semibold">Referencia (opcional)</label>
                            <input id="reference" type="text" name="reference" value="{{ old('reference') }}"
                                class="input">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="address" class="mb-1 block text-sm font-semibold">Dirección completa *</label>
                            <input id="address" type="text" name="address" value="{{ old('address') }}"
                                placeholder="Calle, número, departamento..." class="input" required>
                        </div>
                    </div>
                </section>

                <section class="rounded-lg border border-gray-100 p-6">
                    <h2 class="font-display text-xl font-bold uppercase text-dark">3 · Método de envío</h2>

                    <div class="mt-4 space-y-3">
                        @foreach ($shippingMethods as $key => $method)
                            @php($cost = app(\App\Services\CartService::class)->shippingCost($key))
                            <label class="flex cursor-pointer items-center justify-between gap-4 rounded-md border-2 border-gray-200 px-4 py-3 transition has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <span class="flex items-center gap-3">
                                    <input type="radio" name="shipping_method" value="{{ $key }}"
                                        data-shipping-price="{{ $cost }}"
                                        @checked(old('shipping_method', array_key_first($shippingMethods)) === $key)>
                                    <span>
                                        <span class="block font-display font-bold uppercase text-dark">{{ $method['label'] }}</span>
                                        <span class="block text-xs text-gray-500">{{ $method['description'] }}</span>
                                    </span>
                                </span>
                                <span class="font-display font-bold">
                                    @if ($cost <= 0)
                                        <span class="text-success">Gratis</span>
                                    @else
                                        @money($cost)
                                    @endif
                                </span>
                            </label>
                        @endforeach

                        @if ($freeShippingOver > 0)
                            <p class="text-xs text-gray-400">
                                🚚 ¡Envío gratis automáticamente en pedidos desde S/{{ number_format($freeShippingOver, 0) }}!
                            </p>
                        @endif
                    </div>
                </section>

                <section class="rounded-lg border border-gray-100 p-6">
                    <h2 class="font-display text-xl font-bold uppercase text-dark">4 · Método de pago</h2>

                    <div class="mt-4 space-y-3">
                        @foreach ($paymentMethods as $key => $method)
                            <label class="flex cursor-pointer items-start gap-3 rounded-md border-2 border-gray-200 px-4 py-3 transition has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <input type="radio" name="payment_method" value="{{ $key }}"
                                    @checked(old('payment_method', array_key_first($paymentMethods)) === $key)>
                                <span>
                                    <span class="block font-display font-bold uppercase text-dark">{{ $method['icon'] }}
                                        {{ $method['label'] }}</span>
                                    <span class="block text-xs text-gray-500">{{ $method['description'] }}</span>
                                </span>
                            </label>
                        @endforeach

                        <div>
                            <label for="payment_ref" class="mb-1 block text-sm font-semibold">Referencia de pago
                                (opcional)</label>
                            <input id="payment_ref" type="text" name="payment_ref" value="{{ old('payment_ref') }}"
                                placeholder="Ej. código de operación de Yape" class="input">
                        </div>

                        <div>
                            <label for="notes" class="mb-1 block text-sm font-semibold">Notas para tu pedido (opcional)</label>
                            <textarea id="notes" name="notes" rows="2" class="input">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </section>

                <button type="submit" class="btn-primary btn-lg w-full sm:w-auto">Confirmar pedido</button>
            </form>

            {{-- Resumen --}}
            <aside class="h-fit rounded-lg border border-gray-100 p-6" data-checkout-summary
                data-discount="{{ $discount }}">
                <h2 class="font-display text-xl font-bold uppercase text-dark">Tu pedido</h2>

                <ul class="mt-4 space-y-3 text-sm">
                    @foreach ($lines as $line)
                        <li class="flex justify-between gap-3">
                            <span>
                                {{ $line['quantity'] }}× {{ $line['product']->name }}
                                @if ($line['variant'])
                                    <span class="text-gray-400">({{ $line['variant']->label() }})</span>
                                @endif
                            </span>
                            <span class="font-semibold whitespace-nowrap">@money($line['subtotal'])</span>
                        </li>
                    @endforeach
                </ul>

                <dl class="mt-4 space-y-2 border-t border-gray-100 pt-4 text-sm">
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

                    <div class="flex justify-between">
                        <dt class="text-gray-500">Envío</dt>
                        <dd class="font-semibold" data-shipping-total>Según método</dd>
                    </div>

                    <div class="flex justify-between border-t border-gray-100 pt-2 text-base">
                        <dt class="font-display font-bold uppercase">Total</dt>
                        <dd class="font-display font-black" data-grand-total>@money($subtotal - $discount)</dd>
                    </div>
                </dl>
            </aside>
        </div>
    </div>

    <script>
        // Actualiza el envío y el total según el método elegido (el servidor revalida igual).
        const summary = document.querySelector('[data-checkout-summary]');
        const discount = Number(summary?.dataset.discount ?? 0);
        const subtotal = {{ $subtotal }};
        const money = (value) => 'S/ ' + value.toLocaleString('es-PE', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        document.querySelectorAll('input[name="shipping_method"]').forEach((radio) => {
            radio.addEventListener('change', () => {
                const price = Number(radio.dataset.shippingPrice);
                summary.querySelector('[data-shipping-total]').textContent = price > 0 ? money(price) : 'Gratis';
                summary.querySelector('[data-grand-total]').textContent = money(Math.max(0, subtotal - discount) + price);
            });
        });
    </script>
@endsection

@php($whatsapp = \App\Models\StoreConfig::get('whatsapp', config('store.whatsapp')))

<footer class="mt-16 bg-dark text-gray-300">
    <div class="shop-container grid gap-10 py-14 md:grid-cols-[1.2fr_2fr]">
        <div>
            <div class="inline-block rounded-lg bg-white p-2">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Forcha Sport"
                    class="h-12 w-12 object-contain">
            </div>
            <p class="mt-4 max-w-sm text-sm leading-relaxed">
                Equípate para cada desafío. Calidad deportiva premium para el mercado peruano.
            </p>
            <div class="mt-6 flex gap-3">
                <a href="#" aria-label="Instagram"
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-dark-3 transition hover:bg-primary">📸</a>
                <a href="#" aria-label="Facebook"
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-dark-3 transition hover:bg-primary">👍</a>
                <a href="https://wa.me/{{ $whatsapp }}" aria-label="WhatsApp"
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-dark-3 transition hover:bg-primary">💬</a>
            </div>
        </div>

        <div class="grid gap-8 sm:grid-cols-3">
            <div>
                <h3 class="font-display text-base font-bold uppercase tracking-wide text-white">Tienda</h3>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><a href="{{ route('products.index') }}" class="transition hover:text-primary">Todos los Productos</a></li>
                    <li><a href="{{ route('products.index', ['categoria' => 'ropa-deportiva']) }}" class="transition hover:text-primary">Ropa Deportiva</a></li>
                    <li><a href="{{ route('products.index', ['categoria' => 'zapatillas']) }}" class="transition hover:text-primary">Zapatillas</a></li>
                    <li><a href="{{ route('products.index', ['categoria' => 'accesorios']) }}" class="transition hover:text-primary">Accesorios</a></li>
                    <li><a href="{{ route('products.index', ['onSale' => 1]) }}" class="transition hover:text-primary">Ofertas</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-display text-base font-bold uppercase tracking-wide text-white">Ayuda</h3>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><a href="{{ route('home') }}#faq" class="transition hover:text-primary">Preguntas Frecuentes</a></li>
                    <li><a href="{{ route('home') }}#envios" class="transition hover:text-primary">Envíos y Entregas</a></li>
                    <li><a href="{{ route('home') }}#faq" class="transition hover:text-primary">Cambios y Devoluciones</a></li>
                    <li><a href="{{ route('home') }}#faq" class="transition hover:text-primary">Guía de Tallas</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-display text-base font-bold uppercase tracking-wide text-white">Contacto</h3>
                <ul class="mt-4 space-y-2 text-sm">
                    <li>📱 <a href="https://wa.me/{{ $whatsapp }}" class="transition hover:text-primary">WhatsApp:
                            {{ $whatsapp }}</a></li>
                    <li>✉️ <a href="mailto:hola@forchasport.com" class="transition hover:text-primary">hola@forchasport.com</a></li>
                    <li>📍 Lima, Perú</li>
                    <li>🕐 Lun – Sáb: 9am – 7pm</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="border-t border-dark-3">
        <div class="shop-container flex flex-wrap items-center justify-between gap-4 py-5 text-sm">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-gray-400">Métodos de pago seguros:</span>
                @foreach (array_keys(config('store.payment_methods')) as $method)
                    <span
                        class="rounded bg-dark-3 px-2.5 py-1 font-display text-xs font-bold uppercase tracking-wide text-white">
                        {{ str($method)->upper()->replace('YAPE', 'Yape / Plin')->replace('COD', 'Contra entrega')->replace('TRANSFER', 'Transferencia')->value() }}
                    </span>
                @endforeach
                <span class="rounded bg-dark-3 px-2.5 py-1 font-display text-xs font-bold uppercase tracking-wide text-white">Visa</span>
                <span class="rounded bg-dark-3 px-2.5 py-1 font-display text-xs font-bold uppercase tracking-wide text-white">Mastercard</span>
            </div>

            <p class="text-gray-400">© {{ date('Y') }} FORCHA - SPORT. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

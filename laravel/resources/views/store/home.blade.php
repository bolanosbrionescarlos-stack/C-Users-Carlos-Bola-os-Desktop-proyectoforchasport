@extends('layouts.app')

@section('title', 'Forcha Sport · Ropa y equipo deportivo premium')

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-dark text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_20%,rgba(255,85,0,0.35),transparent_55%)]"></div>
        <div
            class="absolute inset-0 opacity-[0.06] bg-[repeating-linear-gradient(45deg,#fff_0_2px,transparent_2px_14px)]">
        </div>

        <div class="shop-container relative grid items-center gap-12 py-20 lg:grid-cols-2 lg:py-28">
            <div class="animate-fade-in-up">
                <span
                    class="inline-flex items-center gap-2 rounded-full border border-primary/40 bg-primary/10 px-4 py-1.5 text-sm font-semibold uppercase tracking-wide text-primary">
                    Nueva Colección 2025
                </span>

                <h1 class="mt-6 text-6xl font-black uppercase leading-[0.95] tracking-tight md:text-8xl">
                    SUPERA<br>
                    <span class="text-primary">TUS</span><br>
                    LÍMITES.
                </h1>

                <p class="mt-6 max-w-lg text-lg text-gray-300">
                    Equípate para cada desafío. Descubre el estilo y el rendimiento que te acompañan en cada
                    entrenamiento.
                </p>

                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('products.index') }}" class="btn-primary btn-lg">Explorar colección</a>
                    <a href="{{ route('products.index', ['onSale' => 1]) }}" class="btn-outline-light btn-lg">Ver
                        ofertas</a>
                </div>

                <div class="mt-12 flex items-center gap-8">
                    <div>
                        <span class="block font-display text-3xl font-black text-primary">500+</span>
                        <span class="text-sm uppercase tracking-wide text-gray-400">Productos</span>
                    </div>
                    <div class="h-10 w-px bg-dark-3"></div>
                    <div>
                        <span class="block font-display text-3xl font-black text-primary">5K+</span>
                        <span class="text-sm uppercase tracking-wide text-gray-400">Clientes</span>
                    </div>
                    <div class="h-10 w-px bg-dark-3"></div>
                    <div>
                        <span class="block font-display text-3xl font-black text-primary">100%</span>
                        <span class="text-sm uppercase tracking-wide text-gray-400">Garantía</span>
                    </div>
                </div>
            </div>

            <div class="relative hidden lg:block">
                <div class="rounded-2xl border border-dark-3 bg-dark-2 p-10 text-center shadow-[0_16px_64px_rgba(0,0,0,0.45)]">
                    <div class="text-6xl">⚡</div>
                    <div class="mt-6 flex items-center justify-center gap-4">
                        <div class="h-24 w-24 rounded-full bg-primary/80"></div>
                        <div class="h-32 w-32 rounded-full bg-primary/50"></div>
                        <div class="h-20 w-20 rounded-full bg-primary/30"></div>
                    </div>
                    <div class="mt-8 font-display text-2xl font-black uppercase tracking-widest">
                        Rendimiento<br><span class="text-primary">Premium</span>
                    </div>
                </div>

                <div
                    class="absolute -left-8 top-10 rounded-full bg-white px-4 py-2 text-sm font-semibold text-dark shadow-lg">
                    🚚 Envío gratis +S/{{ number_format((float) config('store.free_shipping_over'), 0) }}
                </div>
                <div
                    class="absolute -right-6 bottom-12 rounded-full bg-primary px-4 py-2 text-sm font-semibold text-white shadow-lg">
                    ⭐ Calidad garantizada
                </div>
            </div>
        </div>
    </section>

    {{-- Beneficios --}}
    <section id="envios" class="border-b border-gray-100 bg-white py-12">
        <div class="shop-container grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['icon' => '🚚', 'title' => 'Envío Rápido', 'desc' => 'Lima: 24-48h. Nacional: 3-7 días con Olva Courier y Shalom.'],
                ['icon' => '🔒', 'title' => 'Pago Seguro', 'desc' => 'Yape, Plin, transferencia bancaria y pago contra entrega disponibles.'],
                ['icon' => '↩️', 'title' => 'Cambios Fáciles', 'desc' => 'Tienes 7 días para cambiar o devolver tu producto sin complicaciones.'],
                ['icon' => '🏆', 'title' => 'Calidad Premium', 'desc' => 'Productos seleccionados con los mejores materiales deportivos del mercado.'],
            ] as $benefit)
                <div class="flex items-start gap-4 rounded-lg border border-gray-100 p-5 transition hover:border-primary/30 hover:shadow-sm">
                    <span class="text-3xl">{{ $benefit['icon'] }}</span>
                    <div>
                        <h3 class="font-display text-lg font-bold uppercase text-dark">{{ $benefit['title'] }}</h3>
                        <p class="mt-1 text-sm leading-relaxed text-gray-500">{{ $benefit['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Categorías --}}
    @if ($categories->isNotEmpty())
        <section class="py-20">
            <div class="shop-container">
                <div class="section-heading">
                    <h2>Categorías</h2>
                    <div class="divider"></div>
                    <p>Encuentra exactamente lo que necesitas para tu deporte</p>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-5">
                    @foreach ($categories as $category)
                        <a href="{{ route('products.index', ['categoria' => $category->slug]) }}"
                            class="group relative overflow-hidden rounded-lg bg-dark p-6 text-white transition hover:-translate-y-1 hover:shadow-[0_8px_32px_rgba(0,0,0,0.2)]"
                            style="background: linear-gradient(135deg, {{ $category->color ?? '#111111' }}, #111111);">
                            <span class="text-4xl">{{ $category->icon ?? '🏅' }}</span>
                            <h3 class="mt-4 font-display text-xl font-bold uppercase">{{ $category->name }}</h3>
                            <p class="mt-1 text-sm text-gray-300">{{ $category->description }}</p>
                            <span class="mt-4 inline-flex items-center gap-2 font-display text-sm font-bold uppercase tracking-wide text-primary">
                                Ver productos
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                </svg>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Productos destacados --}}
    @foreach ([
        ['title' => 'MÁS VENDIDOS', 'subtitle' => 'Los productos favoritos de nuestros clientes', 'products' => $featured, 'filters' => ['featured' => 1]],
        ['title' => 'NUEVOS PRODUCTOS', 'subtitle' => 'Las últimas novedades que llegaron a la tienda', 'products' => $newProducts, 'filters' => ['isNew' => 1]],
        ['title' => 'OFERTAS ESPECIALES', 'subtitle' => 'Precios increíbles por tiempo limitado', 'products' => $onSale, 'filters' => ['onSale' => 1]],
    ] as $section)
        @if ($section['products']->isNotEmpty())
            <section class="border-t border-gray-100 py-20">
                <div class="shop-container">
                    <div class="mb-10 flex flex-wrap items-end justify-between gap-4">
                        <div>
                            <h2 class="text-4xl font-black uppercase text-dark">{{ $section['title'] }}</h2>
                            <p class="mt-2 text-gray-500">{{ $section['subtitle'] }}</p>
                            <div class="divider !mx-0"></div>
                        </div>
                        <a href="{{ route('products.index', $section['filters']) }}" class="btn-outline btn-sm">Ver
                            todos →</a>
                    </div>

                    <div class="grid grid-cols-2 gap-5 md:grid-cols-3 lg:grid-cols-4">
                        @foreach ($section['products'] as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    @endforeach

    {{-- FAQ --}}
    <section id="faq" class="bg-surface py-20">
        <div class="shop-container max-w-3xl">
            <div class="section-heading">
                <h2>Preguntas Frecuentes</h2>
                <div class="divider"></div>
                <p>Resolvemos tus dudas más comunes</p>
            </div>

            <div class="space-y-3">
                @foreach ([
                    ['q' => '¿Cuánto tiempo demora el envío?', 'a' => 'En Lima Metropolitana: 24 a 48 horas hábiles. Para provincias: 3 a 7 días hábiles mediante Olva Courier o Shalom. Recibirás un número de seguimiento una vez despachado tu pedido.'],
                    ['q' => '¿Cómo puedo pagar mi pedido?', 'a' => 'Aceptamos Yape, Plin, transferencia bancaria (BCP, Interbank, BBVA) y pago contra entrega en Lima. Una vez realizado el pedido, te enviamos los datos de pago por WhatsApp.'],
                    ['q' => '¿Puedo cambiar o devolver un producto?', 'a' => 'Sí. Tienes 7 días calendario desde que recibes tu pedido para solicitar un cambio o devolución, siempre que el producto esté sin uso, con etiquetas y empaque original. Escríbenos por WhatsApp para coordinar.'],
                    ['q' => '¿Los productos tienen garantía?', 'a' => 'Todos nuestros productos son de calidad verificada. Si recibes un artículo defectuoso o dañado, contáctanos dentro de las 48 horas de recibido y lo reemplazamos sin costo.'],
                    ['q' => '¿Cómo sé qué talla elegir?', 'a' => 'En cada producto encontrarás una guía de tallas con medidas en centímetros. Si tienes dudas, escríbenos por WhatsApp y te asesoramos para que elijas la talla correcta.'],
                    ['q' => '¿Realizan envíos a todo el Perú?', 'a' => 'Sí, enviamos a todos los departamentos del Perú a través de empresas de transporte confiables. El costo y el tiempo de entrega varía según la ubicación.'],
                ] as $faq)
                    <details class="group rounded-lg border border-gray-200 bg-white">
                        <summary
                            class="flex cursor-pointer list-none items-center justify-between gap-4 px-5 py-4 font-display text-lg font-bold uppercase text-dark">
                            {{ $faq['q'] }}
                            <span class="text-primary transition group-open:rotate-45">＋</span>
                        </summary>
                        <p class="px-5 pb-5 leading-relaxed text-gray-500">{{ $faq['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
@endsection

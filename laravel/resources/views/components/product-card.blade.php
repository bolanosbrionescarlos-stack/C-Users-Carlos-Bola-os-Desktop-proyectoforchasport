@props(['product'])

@php($image = $product->mainImage())
@php($discount = $product->discountPercent())

<a href="{{ route('products.show', $product) }}"
    class="group block overflow-hidden rounded-lg border border-gray-100 bg-white transition hover:border-gray-200 hover:shadow-[0_8px_32px_rgba(0,0,0,0.12)]">
    <div class="relative aspect-square overflow-hidden bg-surface">
        @if ($image)
            <img src="{{ $image }}" alt="{{ $product->name }}" loading="lazy"
                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                onerror="this.classList.add('hidden'); this.parentElement.querySelector('[data-fallback]').classList.remove('hidden');">
        @endif

        <div @class([
            'absolute inset-0 flex flex-col items-center justify-center gap-2 bg-gradient-to-br from-dark to-dark-3 text-white',
            'hidden' => $image,
        ]) data-fallback>
            <span class="text-5xl">📦</span>
            <span class="font-display text-lg font-bold uppercase tracking-wide">{{ $product->name }}</span>
        </div>

        <div class="absolute left-3 top-3 flex flex-col gap-1.5">
            @if ($product->is_new)
                <span class="badge-new">Nuevo</span>
            @endif
            @if ($discount > 0)
                <span class="badge-sale">-{{ $discount }}%</span>
            @endif
            @if (! $product->hasStock())
                <span class="badge bg-gray-500 text-white">Agotado</span>
            @endif
        </div>
    </div>

    <div class="p-4">
        @if ($product->category)
            <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">{{ $product->category->name }}</span>
        @endif
        <h3 class="mt-1 font-display text-lg font-bold uppercase leading-tight text-dark">{{ $product->name }}</h3>
        <div class="mt-2 flex items-baseline gap-2">
            <span class="font-display text-xl font-bold text-dark">@money($product->price)</span>
            @if ($product->compare_price && $product->compare_price > $product->price)
                <span class="text-sm text-gray-400 line-through">@money($product->compare_price)</span>
            @endif
        </div>
    </div>
</a>

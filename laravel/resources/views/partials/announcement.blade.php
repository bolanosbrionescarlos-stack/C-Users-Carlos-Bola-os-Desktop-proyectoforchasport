@php($announcement = \App\Models\StoreConfig::get('announcement'))

@if ($announcement)
    <div class="bg-dark py-2.5 text-center text-sm font-medium tracking-wide text-white">
        {{ $announcement }}
    </div>
@else
    <div class="bg-dark py-2.5 text-center text-sm font-medium tracking-wide text-white">
        🚚 Envío gratis en pedidos desde <strong>S/ {{ number_format((float) config('store.free_shipping_over'), 0) }}</strong>
        · Cambios fáciles en 7 días · Paga con Yape, Plin o contra entrega
    </div>
@endif

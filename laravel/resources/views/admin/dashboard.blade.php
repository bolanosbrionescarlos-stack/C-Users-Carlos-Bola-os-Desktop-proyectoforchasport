@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            ['label' => 'Productos activos', 'value' => $stats['products'], 'icon' => '👕'],
            ['label' => 'Pedidos totales', 'value' => $stats['orders'], 'icon' => '🧾'],
            ['label' => 'Pedidos pendientes', 'value' => $stats['pending_orders'], 'icon' => '⏳'],
            ['label' => 'Ventas acumuladas', 'value' => 'S/ '.number_format((float) $stats['revenue'], 2), 'icon' => '💰'],
        ] as $stat)
            <div class="rounded-lg border border-gray-200 bg-white p-4 md:p-6">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-2xl">{{ $stat['icon'] }}</span>
                    <span class="font-display text-2xl font-black text-dark md:text-3xl">{{ $stat['value'] }}</span>
                </div>
                <p class="mt-2 text-sm uppercase tracking-wide text-gray-500">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-[1.6fr_1fr]">
        {{-- Pedidos recientes --}}
        <section class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
            <div class="flex items-center justify-between border-b border-gray-100 px-3 py-4 md:px-6">
                <h2 class="font-display text-lg font-bold uppercase text-dark">Pedidos recientes</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-primary hover:underline">Ver
                    todos →</a>
            </div>

            <table class="min-w-full text-sm">
                <thead class="bg-surface text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-3 py-3 md:px-6">Código</th>
                        <th class="px-3 py-3 md:px-6">Cliente</th>
                        <th class="px-3 py-3 md:px-6">Total</th>
                        <th class="px-3 py-3 md:px-6">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($recentOrders as $order)
                        <tr class="hover:bg-surface">
                            <td class="px-3 py-3 md:px-6">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="font-display font-bold text-primary hover:underline">{{ $order->code }}</a>
                            </td>
                            <td class="px-3 py-3 md:px-6">{{ $order->customer_name }}</td>
                            <td class="px-3 py-3 font-semibold md:px-6">@money($order->total)</td>
                            <td class="px-3 py-3 md:px-6">
                                <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-bold text-white"
                                    style="background: {{ $order->order_status->color() }}">
                                    {{ $order->statusLabel() }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-3 py-8 text-center text-gray-400 md:px-6">Aún no hay pedidos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        {{-- Stock bajo --}}
        <section class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
            <div class="border-b border-gray-100 px-3 py-4 md:px-6">
                <h2 class="font-display text-lg font-bold uppercase text-dark">Stock bajo (≤ 5)</h2>
            </div>

            <ul class="divide-y divide-gray-100 text-sm">
                @forelse ($lowStock as $variant)
                    <li class="flex items-center justify-between gap-3 px-3 py-3 md:px-6">
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-dark">{{ $variant->product->name }}</p>
                            <p class="truncate text-xs text-gray-500">{{ $variant->label() ?: 'Sin opciones' }}</p>
                        </div>
                        <span @class([
                            'shrink-0 rounded px-2 py-0.5 text-xs font-bold',
                            'bg-danger/10 text-danger' => $variant->stock === 0,
                            'bg-warning/10 text-warning' => $variant->stock > 0,
                        ])>
                            {{ $variant->stock }} uds
                        </span>
                    </li>
                @empty
                    <li class="px-3 py-8 text-center text-gray-400 md:px-6">Todo el stock está en niveles sanos. ✅</li>
                @endforelse
            </ul>
        </section>
    </div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Pedidos')

@section('content')
    {{-- Filtros: apilados en móvil, en línea en escritorio --}}
    <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-6">
        <div class="grid grid-cols-1 items-end gap-3 sm:grid-cols-2 lg:flex lg:flex-wrap">
            <div>
                <label for="q" class="mb-1 block text-sm font-semibold">Buscar</label>
                <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="Código, cliente o celular"
                    class="input !w-full !py-2 text-sm lg:!w-72">
            </div>

            <div>
                <label for="estado" class="mb-1 block text-sm font-semibold">Estado</label>
                <select id="estado" name="estado" class="input !w-full !py-2 text-sm lg:!w-56">
                    <option value="">Todos</option>
                    @foreach (\App\Enums\OrderStatus::options() as $value => $label)
                        <option value="{{ $value }}" @selected(request('estado') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="btn-dark btn-sm">Filtrar</button>
                <a href="{{ route('admin.orders.index') }}" class="btn-outline btn-sm">Limpiar</a>
            </div>
        </div>
    </form>

    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-surface text-left text-xs uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-3 py-3 md:px-6">Código</th>
                    <th class="px-3 py-3 md:px-6">Cliente</th>
                    <th class="hidden px-3 py-3 md:table-cell md:px-6">Envío</th>
                    <th class="hidden px-3 py-3 sm:table-cell md:px-6">Pago</th>
                    <th class="px-3 py-3 md:px-6">Total</th>
                    <th class="px-3 py-3 md:px-6">Estado</th>
                    <th class="hidden px-3 py-3 sm:table-cell md:px-6">Fecha</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($orders as $order)
                    <tr class="hover:bg-surface">
                        <td class="px-3 py-3 md:px-6">
                            <a href="{{ route('admin.orders.show', $order) }}"
                                class="font-display font-bold text-primary hover:underline">{{ $order->code }}</a>
                            {{-- Metadatos compactos solo en móvil --}}
                            <p class="mt-1 text-xs text-gray-400 sm:hidden">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </td>
                        <td class="px-3 py-3 md:px-6">
                            <p class="font-semibold text-dark">{{ $order->customer_name }}</p>
                            <p class="text-xs text-gray-400">{{ $order->customer_phone }} · {{ $order->items_count }}
                                productos</p>
                        </td>
                        <td class="hidden px-3 py-3 text-gray-500 md:table-cell md:px-6">
                            {{ config("store.shipping_methods.{$order->shipping_method}.label", $order->shipping_method) }}
                        </td>
                        <td class="hidden px-3 py-3 sm:table-cell md:px-6">
                            <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-bold text-white"
                                style="background: {{ $order->payment_status->color() }}">
                                {{ $order->paymentStatusLabel() }}
                            </span>
                        </td>
                        <td class="px-3 py-3 font-semibold md:px-6">@money($order->total)</td>
                        <td class="px-3 py-3 md:px-6">
                            <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-bold text-white"
                                style="background: {{ $order->order_status->color() }}">
                                {{ $order->statusLabel() }}
                            </span>
                        </td>
                        <td class="hidden px-3 py-3 text-gray-500 sm:table-cell md:px-6">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-400">No hay pedidos con esos filtros.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
@endsection

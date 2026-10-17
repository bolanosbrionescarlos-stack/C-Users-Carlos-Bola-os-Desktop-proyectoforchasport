@extends('admin.layouts.app')

@section('title', 'Cupones')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm text-gray-500">{{ $coupons->total() }} cupones</p>
        <a href="{{ route('admin.cupones.create') }}" class="btn-primary btn-sm">+ Nuevo cupón</a>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-surface text-left text-xs uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-3 py-3 md:px-6">Código</th>
                    <th class="px-3 py-3 md:px-6">Beneficio</th>
                    <th class="px-3 py-3 md:px-6">Pedido mínimo</th>
                    <th class="px-3 py-3 md:px-6">Usos</th>
                    <th class="px-3 py-3 md:px-6">Expira</th>
                    <th class="px-3 py-3 md:px-6">Estado</th>
                    <th class="px-3 py-3 text-right md:px-6">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($coupons as $coupon)
                    <tr class="hover:bg-surface">
                        <td class="px-3 py-3 font-display font-bold uppercase text-dark md:px-6">{{ $coupon->code }}</td>
                        <td class="px-3 py-3 font-semibold text-primary md:px-6">{{ $coupon->description() }}</td>
                        <td class="px-3 py-3 md:px-6">@money($coupon->min_order)</td>
                        <td class="px-3 py-3 md:px-6">
                            {{ $coupon->used_count }}{{ $coupon->max_uses ? ' / '.$coupon->max_uses : '' }}
                        </td>
                        <td class="px-3 py-3 text-gray-500 md:px-6">
                            {{ $coupon->expires_at?->format('d/m/Y') ?? '—' }}
                        </td>
                        <td class="px-3 py-3 md:px-6">
                            @if ($coupon->active)
                                <span class="badge-success">Activo</span>
                            @else
                                <span class="badge bg-gray-400 text-white">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-3 py-3 text-right md:px-6">
                            <a href="{{ route('admin.cupones.edit', $coupon) }}"
                                class="font-semibold text-info hover:underline">Editar</a>
                            <form method="POST" action="{{ route('admin.cupones.destroy', $coupon) }}" class="inline"
                                onsubmit="return confirm('¿Eliminar el cupón {{ $coupon->code }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 font-semibold text-danger hover:underline">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-400">No hay cupones todavía.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $coupons->links() }}
    </div>
@endsection

@extends('admin.layouts.app')

@section('title', $coupon->exists ? 'Editar cupón' : 'Nuevo cupón')

@section('content')
    <form method="POST"
        action="{{ $coupon->exists ? route('admin.cupones.update', $coupon) : route('admin.cupones.store') }}"
        class="max-w-2xl rounded-lg border border-gray-200 bg-white p-4 md:p-6">
        @csrf
        @if ($coupon->exists)
            @method('PUT')
        @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="code" class="mb-1 block text-sm font-semibold">Código *</label>
                <input id="code" type="text" name="code" value="{{ old('code', $coupon->code) }}"
                    placeholder="BIENVENIDO10" class="input uppercase" required>
            </div>

            <div>
                <label for="type" class="mb-1 block text-sm font-semibold">Tipo *</label>
                <select id="type" name="type" class="input" required>
                    @foreach (\App\Enums\CouponType::options() as $value => $label)
                        <option value="{{ $value }}" @selected(old('type', $coupon->type?->value) === $value)>
                            {{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="value" class="mb-1 block text-sm font-semibold">Valor *</label>
                <input id="value" type="number" name="value" step="0.01" min="0"
                    value="{{ old('value', $coupon->value) }}" class="input" required>
            </div>

            <div>
                <label for="min_order" class="mb-1 block text-sm font-semibold">Pedido mínimo (S/)</label>
                <input id="min_order" type="number" name="min_order" step="0.01" min="0"
                    value="{{ old('min_order', $coupon->min_order ?? 0) }}" class="input">
            </div>

            <div>
                <label for="max_uses" class="mb-1 block text-sm font-semibold">Usos máximos (vacío = ilimitado)</label>
                <input id="max_uses" type="number" name="max_uses" min="1"
                    value="{{ old('max_uses', $coupon->max_uses) }}" class="input">
            </div>

            <div>
                <label for="expires_at" class="mb-1 block text-sm font-semibold">Expira (vacío = nunca)</label>
                <input id="expires_at" type="date" name="expires_at"
                    value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}" class="input">
            </div>
        </div>

        <label class="mt-4 flex items-center gap-2 text-sm font-semibold">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" name="active" value="1" @checked(old('active', $coupon->active ?? true))>
            Cupón activo
        </label>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <button type="submit" class="btn-primary">Guardar</button>
            <a href="{{ route('admin.cupones.index') }}" class="btn-outline">Cancelar</a>
        </div>
    </form>
@endsection

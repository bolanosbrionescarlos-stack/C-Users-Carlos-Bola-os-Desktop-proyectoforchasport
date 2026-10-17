@extends('admin.layouts.app')

@section('title', 'Configuración de la tienda')

@section('content')
    <form method="POST" action="{{ route('admin.config.update') }}" class="max-w-2xl rounded-lg border border-gray-200 bg-white p-4 md:p-6">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            <div>
                <label for="whatsapp" class="mb-1 block text-sm font-semibold">WhatsApp de la tienda (con código de
                    país)</label>
                <input id="whatsapp" type="text" name="whatsapp" value="{{ old('whatsapp', $config['whatsapp']) }}"
                    class="input" required>
                <p class="mt-1 text-xs text-gray-400">Se usa en el footer y en la página de confirmación de pedidos.</p>
            </div>

            <div>
                <label for="announcement" class="mb-1 block text-sm font-semibold">Barra de anuncio superior</label>
                <input id="announcement" type="text" name="announcement"
                    value="{{ old('announcement', $config['announcement']) }}" class="input"
                    placeholder="Déjalo vacío para usar el mensaje predeterminado">
            </div>

            <div>
                <label for="free_shipping_over" class="mb-1 block text-sm font-semibold">Envío gratis desde (S/)</label>
                <input id="free_shipping_over" type="number" name="free_shipping_over" step="1" min="0"
                    value="{{ old('free_shipping_over', $config['free_shipping_over']) }}" class="input" required>
            </div>
        </div>

        <button type="submit" class="btn-primary mt-6">Guardar configuración</button>
    </form>
@endsection

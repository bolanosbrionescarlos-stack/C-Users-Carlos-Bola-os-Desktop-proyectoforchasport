@extends('admin.layouts.app')

@section('title', 'Productos')

@section('content')
    {{-- Filtros: apilados en móvil, en línea en escritorio --}}
    <form method="GET" action="{{ route('admin.productos.index') }}" class="mb-6">
        <div class="grid grid-cols-1 items-end gap-3 sm:grid-cols-2 lg:flex lg:flex-wrap">
            <div>
                <label for="q" class="mb-1 block text-sm font-semibold">Buscar</label>
                <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="Nombre o SKU"
                    class="input !w-full !py-2 text-sm lg:!w-64">
            </div>

            <div>
                <label for="categoria" class="mb-1 block text-sm font-semibold">Categoría</label>
                <select id="categoria" name="categoria" class="input !w-full !py-2 text-sm lg:!w-48">
                    <option value="">Todas</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected(request('categoria') === $category->slug)>
                            {{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <label class="flex items-center gap-2 pb-2 text-sm font-semibold">
                <input type="checkbox" name="inactive" value="1" @checked(request()->boolean('inactive'))>
                Solo inactivos
            </label>

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="btn-dark btn-sm">Filtrar</button>
                <a href="{{ route('admin.productos.index') }}" class="btn-outline btn-sm">Limpiar</a>
            </div>

            <a href="{{ route('admin.productos.create') }}"
                class="btn-primary btn-sm sm:ml-auto sm:w-auto w-full">+ Nuevo producto</a>
        </div>
    </form>

    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-surface text-left text-xs uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-3 py-3 md:px-6">Producto</th>
                    <th class="px-3 py-3 md:px-6">Categoría</th>
                    <th class="px-3 py-3 md:px-6">Precio</th>
                    <th class="px-3 py-3 md:px-6">Stock</th>
                    <th class="px-3 py-3 md:px-6">Estado</th>
                    <th class="hidden px-3 py-3 md:table-cell md:px-6">Señales</th>
                    <th class="px-3 py-3 text-right md:px-6">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($products as $product)
                    <tr class="hover:bg-surface">
                        <td class="px-3 py-3 md:px-6">
                            <div class="flex items-center gap-3">
                                @if ($product->mainImage())
                                    <img src="{{ $product->mainImage() }}" alt=""
                                        class="h-10 w-10 shrink-0 rounded-md object-cover">
                                @else
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-surface">📦</span>
                                @endif
                                <div>
                                    <p class="font-semibold text-dark">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-400">SKU: {{ $product->sku ?: '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-3 text-gray-500 md:px-6">{{ $product->category->name ?? '—' }}</td>
                        <td class="px-3 py-3 font-semibold md:px-6">@money($product->price)</td>
                        <td class="px-3 py-3 md:px-6">
                            @php($stock = $product->variants->sum('stock'))
                            <span @class(['font-semibold', 'text-danger' => $stock === 0, 'text-warning' => $stock > 0 && $stock <= 5])>
                                {{ $stock }}
                            </span>
                        </td>
                        <td class="px-3 py-3 md:px-6">
                            @if ($product->active)
                                <span class="badge-success">Activo</span>
                            @else
                                <span class="badge bg-gray-400 text-white">Inactivo</span>
                            @endif
                        </td>
                        <td class="hidden px-3 py-3 md:table-cell md:px-6">
                            <div class="flex flex-wrap gap-1">
                                @if ($product->featured)
                                    <span class="badge bg-dark text-white">Destacado</span>
                                @endif
                                @if ($product->is_new)
                                    <span class="badge-info">Nuevo</span>
                                @endif
                                @if ($product->on_sale)
                                    <span class="badge-sale">Oferta</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-3 py-3 text-right md:px-6">
                            <a href="{{ route('admin.productos.edit', $product) }}"
                                class="font-semibold text-info hover:underline">Editar</a>
                            <form method="POST" action="{{ route('admin.productos.destroy', $product) }}" class="inline"
                                onsubmit="return confirm('¿Eliminar el producto {{ $product->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 font-semibold text-danger hover:underline">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-400">No hay productos con esos filtros.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
@endsection

@extends('admin.layouts.app')

@section('title', $product->exists ? 'Editar producto' : 'Nuevo producto')

@section('content')
    @php($existingVariants = $product->exists ? $product->variants->all() : [])

    <form method="POST"
        action="{{ $product->exists ? route('admin.productos.update', $product) : route('admin.productos.store') }}"
        class="grid gap-6 lg:grid-cols-[1fr_360px]">
        @csrf
        @if ($product->exists)
            @method('PUT')
        @endif

        {{-- Columna principal --}}
        <div class="min-w-0 space-y-6">
            <section class="rounded-lg border border-gray-200 bg-white p-4 md:p-6">
                <h2 class="font-display text-lg font-bold uppercase text-dark">Información básica</h2>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="mb-1 block text-sm font-semibold">Nombre *</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $product->name) }}"
                            class="input" required>
                    </div>

                    <div>
                        <label for="slug" class="mb-1 block text-sm font-semibold">Slug *</label>
                        <input id="slug" type="text" name="slug" value="{{ old('slug', $product->slug) }}"
                            placeholder="camiseta-running-pro" class="input" required>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="description" class="mb-1 block text-sm font-semibold">Descripción</label>
                        <textarea id="description" name="description" rows="3" class="input">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="details" class="mb-1 block text-sm font-semibold">Detalles (ficha técnica)</label>
                        <textarea id="details" name="details" rows="4" class="input">{{ old('details', $product->details) }}</textarea>
                    </div>
                </div>
            </section>

            <section class="rounded-lg border border-gray-200 bg-white p-4 md:p-6">
                <h2 class="font-display text-lg font-bold uppercase text-dark">Variantes y stock</h2>
                <p class="mt-1 text-sm text-gray-500">Cada variante necesita stock. Las filas vacías se ignoran.</p>

                <div class="mt-4 min-w-0 overflow-x-auto">
                    <table class="w-full min-w-[460px] text-sm" data-variants-table>
                        <thead class="text-left text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="pb-2 pr-3">Talla</th>
                                <th class="pb-2 pr-3">Color</th>
                                <th class="pb-2 pr-3">Stock *</th>
                                <th class="pb-2">SKU</th>
                                <th class="pb-2"></th>
                            </tr>
                        </thead>
                        <tbody class="space-y-2">
                            @foreach ($existingVariants ?: [[]] as $variant)
                                <tr class="variant-row">
                                    <td class="pb-2 pr-3">
                                        <input type="text" name="variants[{{ $loop->index }}][size]"
                                            value="{{ old("variants.$loop->index.size", $variant['size'] ?? null) }}"
                                            class="input !w-24 !py-2 text-sm" placeholder="M">
                                    </td>
                                    <td class="pb-2 pr-3">
                                        <input type="text" name="variants[{{ $loop->index }}][color]"
                                            value="{{ old("variants.$loop->index.color", $variant['color'] ?? null) }}"
                                            class="input !w-32 !py-2 text-sm" placeholder="Negro">
                                    </td>
                                    <td class="pb-2 pr-3">
                                        <input type="number" name="variants[{{ $loop->index }}][stock]" min="0"
                                            value="{{ old("variants.$loop->index.stock", $variant['stock'] ?? 0) }}"
                                            class="input !w-24 !py-2 text-sm" required>
                                    </td>
                                    <td class="pb-2">
                                        <input type="text" name="variants[{{ $loop->index }}][sku]"
                                            value="{{ old("variants.$loop->index.sku", $variant['sku'] ?? null) }}"
                                            class="input !w-32 !py-2 text-sm">
                                    </td>
                                    <td class="pb-2 text-right">
                                        <button type="button" class="text-xs text-gray-400 hover:text-danger"
                                            onclick="this.closest('tr').remove()">✕</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <button type="button" class="btn-outline btn-sm mt-3" data-add-variant>+ Agregar variante</button>
            </section>
        </div>

        {{-- Columna lateral --}}
        <div class="min-w-0 space-y-6">
            <section class="rounded-lg border border-gray-200 bg-white p-4 md:p-6">
                <h2 class="font-display text-lg font-bold uppercase text-dark">Precio</h2>

                <div class="mt-4 space-y-4">
                    <div>
                        <label for="price" class="mb-1 block text-sm font-semibold">Precio (S/) *</label>
                        <input id="price" type="number" name="price" step="0.01" min="0"
                            value="{{ old('price', $product->price) }}" class="input" required>
                    </div>

                    <div>
                        <label for="compare_price" class="mb-1 block text-sm font-semibold">Precio de comparación
                            (tachado)</label>
                        <input id="compare_price" type="number" name="compare_price" step="0.01" min="0"
                            value="{{ old('compare_price', $product->compare_price) }}" class="input">
                    </div>

                    <div>
                        <label for="sku" class="mb-1 block text-sm font-semibold">SKU del producto</label>
                        <input id="sku" type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="input">
                    </div>
                </div>
            </section>

            <section class="rounded-lg border border-gray-200 bg-white p-4 md:p-6">
                <h2 class="font-display text-lg font-bold uppercase text-dark">Clasificación</h2>

                <div class="mt-4 space-y-4">
                    <div>
                        <label for="category_id" class="mb-1 block text-sm font-semibold">Categoría *</label>
                        <select id="category_id" name="category_id" class="input" required>
                            <option value="">Selecciona...</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    @selected(old('category_id', $product->category_id) === $category->id)>
                                    {{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="images" class="mb-1 block text-sm font-semibold">Imágenes (una URL por línea)</label>
                        <textarea id="images" name="images[]" rows="3" class="input"
                            placeholder="https://...">{{ old('images.0', $product->mainImage()) }}</textarea>
                    </div>

                    <div>
                        <label for="tags" class="mb-1 block text-sm font-semibold">Etiquetas (separadas por coma)</label>
                        <input id="tags" type="text" name="tags"
                            value="{{ old('tags', implode(', ', $product->tags ?? [])) }}" class="input">
                    </div>

                    <div class="space-y-2 text-sm">
                        <label class="flex items-center gap-2">
                            <input type="hidden" name="active" value="0">
                            <input type="checkbox" name="active" value="1" @checked(old('active', $product->active ?? true))>
                            Activo en tienda
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="hidden" name="featured" value="0">
                            <input type="checkbox" name="featured" value="1" @checked(old('featured', $product->featured ?? false))>
                            Destacado (más vendidos)
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="hidden" name="is_new" value="0">
                            <input type="checkbox" name="is_new" value="1" @checked(old('is_new', $product->is_new ?? false))>
                            Marcar como nuevo
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="hidden" name="on_sale" value="0">
                            <input type="checkbox" name="on_sale" value="1" @checked(old('on_sale', $product->on_sale ?? false))>
                            En oferta
                        </label>
                    </div>
                </div>
            </section>

            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" class="btn-primary flex-1">Guardar</button>
                <a href="{{ route('admin.productos.index') }}" class="btn-outline">Cancelar</a>
            </div>
        </div>
    </form>

    <script>
        // Agrega filas nuevas de variantes sin recargar la página.
        const table = document.querySelector('[data-variants-table] tbody');
        document.querySelector('[data-add-variant]')?.addEventListener('click', () => {
            const index = table.querySelectorAll('.variant-row').length;
            const row = document.createElement('tr');
            row.className = 'variant-row';
            row.innerHTML = `
                <td class="pb-2 pr-3"><input type="text" name="variants[${index}][size]" class="input !w-24 !py-2 text-sm" placeholder="M"></td>
                <td class="pb-2 pr-3"><input type="text" name="variants[${index}][color]" class="input !w-32 !py-2 text-sm" placeholder="Negro"></td>
                <td class="pb-2 pr-3"><input type="number" name="variants[${index}][stock]" min="0" value="0" class="input !w-24 !py-2 text-sm" required></td>
                <td class="pb-2"><input type="text" name="variants[${index}][sku]" class="input !w-32 !py-2 text-sm"></td>
                <td class="pb-2 text-right"><button type="button" class="text-xs text-gray-400 hover:text-danger" onclick="this.closest('tr').remove()">✕</button></td>
            `;
            table.appendChild(row);
        });
    </script>
@endsection

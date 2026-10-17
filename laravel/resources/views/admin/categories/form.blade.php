@extends('admin.layouts.app')

@section('title', $category->exists ? 'Editar categoría' : 'Nueva categoría')

@section('content')
    <form method="POST"
        action="{{ $category->exists ? route('admin.categorias.update', $category) : route('admin.categorias.store') }}"
        class="max-w-2xl rounded-lg border border-gray-200 bg-white p-4 md:p-6">
        @csrf
        @if ($category->exists)
            @method('PUT')
        @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="name" class="mb-1 block text-sm font-semibold">Nombre *</label>
                <input id="name" type="text" name="name" value="{{ old('name', $category->name) }}" class="input"
                    required>
            </div>

            <div>
                <label for="slug" class="mb-1 block text-sm font-semibold">Slug *</label>
                <input id="slug" type="text" name="slug" value="{{ old('slug', $category->slug) }}"
                    placeholder="ropa-deportiva" class="input" required>
            </div>

            <div class="sm:col-span-2">
                <label for="description" class="mb-1 block text-sm font-semibold">Descripción</label>
                <textarea id="description" name="description" rows="2" class="input">{{ old('description', $category->description) }}</textarea>
            </div>

            <div>
                <label for="image" class="mb-1 block text-sm font-semibold">URL de imagen</label>
                <input id="image" type="text" name="image" value="{{ old('image', $category->image) }}"
                    class="input">
            </div>

            <div>
                <label for="icon" class="mb-1 block text-sm font-semibold">Icono (emoji)</label>
                <input id="icon" type="text" name="icon" value="{{ old('icon', $category->icon) }}" placeholder="👕"
                    class="input">
            </div>

            <div>
                <label for="color" class="mb-1 block text-sm font-semibold">Color de acento</label>
                <input id="color" type="color" name="color" value="{{ old('color', $category->color ?? '#FF5500') }}"
                    class="h-11 w-full rounded-md border-[1.5px] border-gray-200">
            </div>

            <div>
                <label for="sort_order" class="mb-1 block text-sm font-semibold">Orden</label>
                <input id="sort_order" type="number" name="sort_order" min="0"
                    value="{{ old('sort_order', $category->sort_order ?? 0) }}" class="input">
            </div>
        </div>

        <label class="mt-4 flex items-center gap-2 text-sm font-semibold">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" name="active" value="1" @checked(old('active', $category->active ?? true))>
            Categoría activa (visible en la tienda)
        </label>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <button type="submit" class="btn-primary">Guardar</button>
            <a href="{{ route('admin.categorias.index') }}" class="btn-outline">Cancelar</a>
        </div>
    </form>
@endsection

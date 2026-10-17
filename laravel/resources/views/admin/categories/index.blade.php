@extends('admin.layouts.app')

@section('title', 'Categorías')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm text-gray-500">{{ $categories->total() }} categorías</p>
        <a href="{{ route('admin.categorias.create') }}" class="btn-primary btn-sm">+ Nueva categoría</a>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-surface text-left text-xs uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-3 py-3 md:px-6">Categoría</th>
                    <th class="px-3 py-3 md:px-6">Slug</th>
                    <th class="px-3 py-3 md:px-6">Productos</th>
                    <th class="px-3 py-3 md:px-6">Orden</th>
                    <th class="px-3 py-3 md:px-6">Estado</th>
                    <th class="px-3 py-3 text-right md:px-6">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($categories as $category)
                    <tr class="hover:bg-surface">
                        <td class="px-3 py-3 md:px-6">
                            <span class="font-semibold text-dark">{{ $category->icon }} {{ $category->name }}</span>
                        </td>
                        <td class="px-3 py-3 text-gray-500 md:px-6">{{ $category->slug }}</td>
                        <td class="px-3 py-3 md:px-6">{{ $category->products_count }}</td>
                        <td class="px-3 py-3 md:px-6">{{ $category->sort_order }}</td>
                        <td class="px-3 py-3 md:px-6">
                            @if ($category->active)
                                <span class="badge-success">Activa</span>
                            @else
                                <span class="badge bg-gray-400 text-white">Inactiva</span>
                            @endif
                        </td>
                        <td class="px-3 py-3 text-right md:px-6">
                            <a href="{{ route('admin.categorias.edit', $category) }}"
                                class="font-semibold text-info hover:underline">Editar</a>
                            <form method="POST" action="{{ route('admin.categorias.destroy', $category) }}"
                                class="inline" onsubmit="return confirm('¿Eliminar la categoría {{ $category->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 font-semibold text-danger hover:underline">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400">No hay categorías todavía.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $categories->links() }}
    </div>
@endsection

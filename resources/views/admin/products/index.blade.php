@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 bg-white rounded-xl shadow-xl">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-blue-50 rounded-2xl text-blue-600 shadow-sm border border-green-100/50">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Products</h2>
                <p class="text-sm font-medium text-gray-400 mt-1">Manage shop products & variants</p>
            </div>
        </div>
        
        <div class="flex gap-3 w-full md:w-auto">
            <a href="{{ route('admin.orders.index') }}"
               class="inline-flex items-center justify-center bg-gray-100 text-gray-700 px-6 py-3.5 rounded-2xl hover:bg-gray-200 transition-all duration-300 text-sm font-bold w-full md:w-auto">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Orders
            </a>
            <a href="{{ route('admin.products.create') }}"
               class="inline-flex items-center justify-center bg-blue-600 text-white px-8 py-3.5 rounded-2xl hover:bg-blue-700 transition-all duration-300 text-sm font-bold group w-full md:w-auto transform hover:-translate-y-1 active:scale-95">
                <svg class="w-5 h-5 mr-3 transition-transform duration-300 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Product
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
        {{-- Desktop Table --}}
        <div class="hidden md:block">
            <table class="w-full text-sm text-left text-gray-700 bg-white">
                <thead class="bg-blue-600 text-white text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Image</th>
                        <th class="px-6 py-4">Product Name</th>
                        <th class="px-6 py-4">Variants</th>
                        <th class="px-6 py-4">Price Range</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($products as $product)
                        <tr class="hover:bg-blue-50 transition duration-150">
                            <td class="px-6 py-4">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-12 rounded-lg object-cover" alt="{{ $product->name }}">
                                @else
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $product->name }}</div>
                                @if($product->subtitle)
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $product->subtitle }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-semibold">
                                    {{ $product->variants->count() }} variants
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($product->variants->count() > 0)
                                    <span class="font-semibold">£{{ number_format($product->variants->min('price'), 2) }} - £{{ number_format($product->variants->max('price'), 2) }}</span>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($product->is_active)
                                    <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-semibold">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 text-xs px-3 py-1 rounded-full font-semibold">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center gap-3">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="p-2.5 bg-gray-50 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition-all border border-transparent hover:border-blue-100 shadow-sm">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline delete-product-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-product-btn p-2.5 bg-gray-50 text-rose-600 rounded-xl hover:bg-rose-50 transition-all border border-transparent hover:border-rose-100 shadow-sm">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg> 
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 italic">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                No products yet. Click "Add Product" to get started!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card Layout --}}
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse ($products as $product)
                <div class="p-6 space-y-4 hover:bg-gray-50 transition-colors">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-14 h-14 rounded-xl object-cover" alt="{{ $product->name }}">
                            @else
                                <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $product->name }}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-sm font-semibold text-green-600">{{ $product->variants->count() }} variants</span>
                                    <span class="text-gray-300">·</span>
                                    @if($product->is_active)
                                        <span class="text-xs text-green-600 font-semibold">Active</span>
                                    @else
                                        <span class="text-xs text-gray-500 font-semibold">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="p-3 bg-blue-50 text-blue-600 rounded-xl active:scale-95 transition-all shadow-sm border border-blue-100">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline delete-product-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="delete-product-btn p-3 bg-rose-50 text-rose-600 rounded-xl active:scale-95 transition-all shadow-sm border border-rose-100">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-gray-500 italic bg-white">
                    No products yet.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white p-6 rounded-xl shadow-lg max-w-md text-center border-t-4 border-red-600 mx-4">
        <div class="flex justify-center mb-4">
            <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a1 1 0 00.86 1.5h18.64a1 1 0 00.86-1.5L13.71 3.86a1 1 0 00-1.72 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-red-700 mb-2">Delete Product?</h3>
        <p class="text-sm text-red-600">This will permanently delete this product and all its variants. This cannot be undone.</p>
        <div class="flex justify-center gap-4 mt-6">
            <button id="confirmDeleteBtn" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg font-semibold transition-colors">Yes, Delete</button>
            <button id="cancelDeleteBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2.5 rounded-lg font-semibold transition-colors">Cancel</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let formToSubmit = null;
    const modal = document.getElementById('deleteModal');
    
    document.querySelectorAll('.delete-product-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            formToSubmit = this.closest('form');
            modal.classList.remove('hidden');
        });
    });

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (formToSubmit) formToSubmit.submit();
    });

    document.getElementById('cancelDeleteBtn').addEventListener('click', function() {
        modal.classList.add('hidden');
        formToSubmit = null;
    });
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            formToSubmit = null;
        }
    });
});
</script>
@endsection

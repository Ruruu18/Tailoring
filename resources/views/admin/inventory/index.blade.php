<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <div class="flex-1">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Inventory Gallery
                </h2>
                <p class="text-sm text-gray-600 mt-1">Manage your tailoring materials and supplies</p>
            </div>
            <div class="ml-auto">
                <a href="{{ route('admin.inventory.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Item
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters and Search -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                <form method="GET" action="{{ route('admin.inventory.index') }}" class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-64">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input type="text"
                                   name="search"
                                   id="search"
                                   value="{{ request('search') }}"
                                   placeholder="Search by name, SKU, or supplier..."
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="min-w-48">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category" id="category" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Categories</option>
                                <option value="garters" {{ request('category') === 'garters' ? 'selected' : '' }}>Garters</option>
                                <option value="threads" {{ request('category') === 'threads' ? 'selected' : '' }}>Threads</option>
                                <option value="fabrics" {{ request('category') === 'fabrics' ? 'selected' : '' }}>Fabrics/Tela</option>
                            </select>
                        </div>

                        <div class="min-w-32">
                            <label for="design_type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select name="design_type" id="design_type" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Types</option>
                                <option value="pre_made" {{ request('design_type') === 'pre_made' ? 'selected' : '' }}>Pre-made</option>
                                <option value="custom" {{ request('design_type') === 'custom' ? 'selected' : '' }}>Custom</option>
                                <option value="both" {{ request('design_type') === 'both' ? 'selected' : '' }}>Both</option>
                            </select>
                        </div>

                        <div class="min-w-32">
                            <label for="stock_status" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                            <select name="stock_status" id="stock_status" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Stock</option>
                                <option value="in_stock" {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                <option value="low_stock" {{ request('stock_status') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                                <option value="out_of_stock" {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Filter
                            </button>
                            <a href="{{ route('admin.inventory.index') }}"
                               class="px-4 py-2 bg-gray-500 text-white text-sm rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Inventory Items Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($items as $item)
                    <div class="bg-white overflow-hidden shadow-xl rounded-lg hover:shadow-2xl transition-shadow duration-300">
                        <!-- Image -->
                        <div class="relative">
                            <img src="{{ $item->primary_image_url }}"
                                 alt="{{ $item->name }}"
                                 class="w-full h-64 object-cover">

                            <!-- Status badges -->
                            <div class="absolute top-2 right-2 flex flex-col gap-1">
                                @if($item->featured)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Featured
                                    </span>
                                @endif
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{
                                    $item->stock_status === 'in_stock' ? 'bg-green-100 text-green-800' :
                                    ($item->stock_status === 'low_stock' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')
                                }}">
                                    {{ $item->quantity }} {{ $item->unit }}
                                </span>
                            </div>

                            <!-- Design Type Badge -->
                            <div class="absolute bottom-2 left-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst(str_replace('_', ' ', $item->design_type)) }}
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $item->name }}</h3>
                                <span class="text-lg font-bold text-indigo-600">₱{{ number_format($item->unit_price, 2) }}</span>
                            </div>

                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $item->description }}</p>

                            <!-- Meta info -->
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $item->category_display }}
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    SKU: {{ $item->sku }}
                                </span>
                            </div>

                            <!-- Colors -->
                            @if($item->colors)
                                <div class="flex flex-wrap gap-1 mb-3">
                                    @foreach(array_slice($item->colors, 0, 3) as $color)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ trim($color) }}
                                        </span>
                                    @endforeach
                                    @if(count($item->colors) > 3)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            +{{ count($item->colors) - 3 }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.inventory.show', $item) }}"
                                       class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View</a>
                                    <a href="{{ route('admin.inventory.edit', $item) }}"
                                       class="text-blue-600 hover:text-blue-900 text-sm font-medium">Edit</a>
                                </div>

                                <form method="POST" action="{{ route('admin.inventory.destroy', $item) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this inventory item? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-900">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No inventory items</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new inventory item.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.inventory.create') }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    New Inventory Item
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($items->hasPages())
                <div class="mt-8">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-admin-layout>
<x-admin-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <div>
                <a href="{{ route('admin.inventory.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Inventory
                </a>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $inventory->name }}</h1>
                <p class="text-sm text-gray-600">SKU: {{ $inventory->sku }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-x-6 lg:items-start">
                <!-- Image gallery -->
                <div class="space-y-6">
                    @php
                        $hasImages = false;
                        $imageArray = [];

                        if ($inventory->images) {
                            if (is_array($inventory->images)) {
                                $imageArray = array_filter($inventory->images);
                                $hasImages = count($imageArray) > 0;
                            } elseif (is_string($inventory->images) && !empty($inventory->images)) {
                                $imageArray = [$inventory->images];
                                $hasImages = true;
                            }
                        }
                    @endphp

                    @if($hasImages)
                        <div class="w-full aspect-w-1 aspect-h-1">
                            <img
                                id="main-image"
                                src="{{ asset('storage/' . $imageArray[0]) }}"
                                alt="{{ $inventory->name }}"
                                class="w-full h-full object-center object-cover sm:rounded-lg max-h-60 lg:max-h-72"
                            >
                        </div>

                        <!-- Image selector -->
                        @if(count($imageArray) > 1)
                            <div class="hidden w-full max-w-2xl mx-auto sm:block lg:max-w-none">
                                <div class="grid grid-cols-4 gap-2" role="tablist" aria-orientation="horizontal">
                                    @foreach($imageArray as $index => $image)
                                        <button
                                            class="relative h-16 bg-white rounded-md flex items-center justify-center text-sm font-medium uppercase text-gray-900 cursor-pointer hover:bg-gray-50 focus:outline-none focus:ring focus:ring-offset-4 focus:ring-indigo-500"
                                            onclick="showImage('{{ asset('storage/' . $image) }}')"
                                            role="tab"
                                            type="button"
                                            aria-controls="tabs-{{ $index }}-panel"
                                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                                        >
                                            <span class="sr-only">Image {{ $index + 1 }}</span>
                                            <span class="absolute inset-0 rounded-md overflow-hidden">
                                                <img src="{{ asset('storage/' . $image) }}" alt="Item image {{ $index + 1 }}" class="w-full h-full object-center object-cover">
                                            </span>
                                            <span class="absolute inset-0 rounded-md ring-2 ring-offset-2 pointer-events-none {{ $index === 0 ? 'ring-indigo-500' : 'ring-transparent' }}" id="tabs-{{ $index }}-ring" aria-hidden="true"></span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="w-full aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No images available</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Product info -->
                <div class="mt-4 px-4 sm:px-0 sm:mt-6 lg:mt-0">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-extrabold tracking-tight text-gray-900">{{ $inventory->name }}</h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $inventory->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $inventory->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="mt-2">
                        <h2 class="sr-only">Item information</h2>
                        <p class="text-2xl text-gray-900 font-bold">₱{{ number_format($inventory->unit_price, 2) }}</p>
                        <p class="text-sm text-gray-500 mt-1">per {{ $inventory->unit }}</p>
                    </div>

                    @if($inventory->description)
                        <div class="mt-4">
                            <h3 class="sr-only">Description</h3>
                            <div class="text-base text-gray-700">
                                <p>{{ $inventory->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Item details -->
                    <div class="mt-4 space-y-3">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Details</h3>
                            <dl class="grid grid-cols-1 gap-x-3 gap-y-2 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">SKU</dt>
                                    <dd class="mt-0.5 text-sm text-gray-900">{{ $inventory->sku }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Category</dt>
                                    <dd class="mt-0.5 text-sm text-gray-900">{{ ucwords($inventory->category) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Unit</dt>
                                    <dd class="mt-0.5 text-sm text-gray-900">{{ ucwords($inventory->unit) }}</dd>
                                </div>
                                @if($inventory->supplier)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Supplier</dt>
                                        <dd class="mt-0.5 text-sm text-gray-900">{{ $inventory->supplier }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>

                        <!-- Stock Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Stock</h3>
                            <dl class="grid grid-cols-1 gap-x-3 gap-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Current Stock</dt>
                                    <dd class="mt-0.5 flex items-center space-x-2">
                                        <span class="text-xl font-bold text-gray-900">{{ $inventory->quantity }}</span>
                                        <span class="text-sm text-gray-500">{{ $inventory->unit }}</span>
                                        @if($inventory->stock_status === 'out_of_stock')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Out of Stock
                                            </span>
                                        @elseif($inventory->stock_status === 'low_stock')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Low Stock
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                In Stock
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Min Qty</dt>
                                    <dd class="mt-0.5 text-sm text-gray-900">{{ $inventory->min_quantity }} {{ $inventory->unit }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Value</dt>
                                    <dd class="mt-0.5 text-lg font-semibold text-gray-900">₱{{ number_format($inventory->quantity * $inventory->unit_price, 2) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <a href="{{ route('admin.inventory.edit', $inventory) }}"
                           class="bg-indigo-600 border border-transparent rounded-md py-2 px-6 flex items-center justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Item
                        </a>

                        <form method="POST" action="{{ route('admin.inventory.destroy', $inventory) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this inventory item? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 border border-transparent rounded-md py-2 px-6 flex items-center justify-center text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Item
                            </button>
                        </form>
                    </div>

                    <!-- Stock Management -->
                    <div class="mt-4">
                        <div class="bg-blue-50 rounded-lg p-3">
                            <h3 class="text-sm font-medium text-gray-900 mb-2">Stock Management</h3>

                            <form method="POST" action="{{ route('admin.inventory.update-stock', $inventory) }}" class="space-y-2">
                                @csrf
                                @method('PATCH')

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <select name="action" id="action" required
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="add">Add Stock</option>
                                            <option value="subtract">Remove Stock</option>
                                            <option value="set">Set Stock</option>
                                        </select>
                                    </div>

                                    <div>
                                        <input type="number" name="quantity" id="quantity" min="0" required placeholder="Quantity"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>

                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-1.5 px-3 rounded-md transition-colors duration-200">
                                    Update Stock
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Item History -->
                    <div class="mt-4">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <h3 class="text-sm font-medium text-gray-900 mb-1">History</h3>
                            <div class="text-xs text-gray-600 space-y-0.5">
                                <p>Created: {{ $inventory->created_at->format('M d, Y') }}</p>
                                <p>Updated: {{ $inventory->updated_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showImage(imageUrl) {
            document.getElementById('main-image').src = imageUrl;

            // Update ring indicators
            document.querySelectorAll('[id^="tabs-"][id$="-ring"]').forEach(ring => {
                ring.classList.remove('ring-indigo-500');
                ring.classList.add('ring-transparent');
            });

            // Add ring to selected image
            const buttons = document.querySelectorAll('[role="tab"]');
            buttons.forEach((button, index) => {
                if (button.onclick.toString().includes(imageUrl.split('/').pop())) {
                    document.getElementById(`tabs-${index}-ring`).classList.remove('ring-transparent');
                    document.getElementById(`tabs-${index}-ring`).classList.add('ring-indigo-500');
                    button.setAttribute('aria-selected', 'true');
                } else {
                    button.setAttribute('aria-selected', 'false');
                }
            });
        }
    </script>
</x-admin-layout>
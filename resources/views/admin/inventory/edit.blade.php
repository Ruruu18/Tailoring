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
                <h1 class="text-2xl font-bold text-gray-900">Edit Inventory Item</h1>
                <p class="text-sm text-gray-600">Update item details and stock information</p>
            </div>
        </div>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-4">
                <form method="POST" action="{{ route('admin.inventory.update', $inventory) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <!-- Item Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Item Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $inventory->name) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SKU -->
                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700">SKU</label>
                            <input type="text" name="sku" id="sku" value="{{ old('sku', $inventory->sku) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('sku')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <input type="text" name="category" id="category" value="{{ old('category', $inventory->category) }}" required
                               list="categories"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <datalist id="categories">
                            @foreach($categories as $category)
                                <option value="{{ $category }}">
                            @endforeach
                        </datalist>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit -->
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700">Unit</label>
                        <select name="unit" id="unit" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Unit</option>
                            <option value="pieces" {{ old('unit', $inventory->unit) == 'pieces' ? 'selected' : '' }}>Pieces</option>
                            <option value="meters" {{ old('unit', $inventory->unit) == 'meters' ? 'selected' : '' }}>Meters</option>
                            <option value="yards" {{ old('unit', $inventory->unit) == 'yards' ? 'selected' : '' }}>Yards</option>
                            <option value="rolls" {{ old('unit', $inventory->unit) == 'rolls' ? 'selected' : '' }}>Rolls</option>
                            <option value="kg" {{ old('unit', $inventory->unit) == 'kg' ? 'selected' : '' }}>Kilograms</option>
                            <option value="boxes" {{ old('unit', $inventory->unit) == 'boxes' ? 'selected' : '' }}>Boxes</option>
                        </select>
                        @error('unit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Current Quantity</label>
                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $inventory->quantity) }}" min="0" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Minimum Quantity -->
                    <div>
                        <label for="min_quantity" class="block text-sm font-medium text-gray-700">Minimum Quantity (Low Stock Alert)</label>
                        <input type="number" name="min_quantity" id="min_quantity" value="{{ old('min_quantity', $inventory->min_quantity) }}" min="0" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('min_quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit Price -->
                    <div>
                        <label for="unit_price" class="block text-sm font-medium text-gray-700">Unit Price (₱)</label>
                        <input type="number" name="unit_price" id="unit_price" value="{{ old('unit_price', $inventory->unit_price) }}" step="0.01" min="0" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('unit_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Supplier -->
                    <div>
                        <label for="supplier" class="block text-sm font-medium text-gray-700">Supplier</label>
                        <input type="text" name="supplier" id="supplier" value="{{ old('supplier', $inventory->supplier) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('supplier')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Design Type -->
                    <div>
                        <label for="design_type" class="block text-sm font-medium text-gray-700">Design Type</label>
                        <select name="design_type" id="design_type" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Design Type</option>
                            <option value="pre_made" {{ old('design_type', $inventory->design_type) == 'pre_made' ? 'selected' : '' }}>Pre-made</option>
                            <option value="custom" {{ old('design_type', $inventory->design_type) == 'custom' ? 'selected' : '' }}>Custom</option>
                            <option value="both" {{ old('design_type', $inventory->design_type) == 'both' ? 'selected' : '' }}>Both</option>
                        </select>
                        @error('design_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Featured -->
                    <div>
                        <label for="featured" class="block text-sm font-medium text-gray-700">Featured Item</label>
                        <select name="featured" id="featured"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="0" {{ old('featured', $inventory->featured ?? 0) == '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('featured', $inventory->featured ?? 0) == '1' ? 'selected' : '' }}>Yes</option>
                        </select>
                        @error('featured')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="is_active" id="is_active"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="1" {{ old('is_active', $inventory->is_active) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !old('is_active', $inventory->is_active) ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                    <!-- Description -->
                    <div class="mt-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="2"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $inventory->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                <!-- Current Images -->
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
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Images</label>
                        <div class="grid grid-cols-4 md:grid-cols-6 gap-1">
                            @foreach($imageArray as $index => $image)
                            @if($image)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $image) }}" alt="Item image" class="w-full h-16 object-cover rounded shadow-sm">
                                <div class="absolute top-0.5 right-0.5">
                                    <button type="button" onclick="removeImage({{ $index }})" class="bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs hover:bg-red-600">
                                        ×
                                    </button>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Upload New Images -->
                    <div class="mt-3">
                        <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Upload New Images</label>
                        <div class="flex justify-center px-2 py-2 border-2 border-gray-300 border-dashed rounded hover:border-gray-400 transition-colors">
                            <div class="text-center">
                                <svg class="mx-auto h-5 w-5 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <label for="images" class="cursor-pointer text-xs text-indigo-600 hover:text-indigo-500">
                                    <span>Upload</span>
                                    <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*">
                                </label>
                            </div>
                        </div>
                        @error('images')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preview area for new images -->
                    <div id="image-preview" class="mt-2 grid grid-cols-4 md:grid-cols-6 gap-1" style="display: none;">
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-3 flex justify-end space-x-3">
                        <a href="{{ route('admin.inventory.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Item
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for image handling -->
    <script>
        document.getElementById('images').addEventListener('change', function(e) {
            previewImages(e.target.files, 'image-preview');
        });

        function previewImages(files, containerId) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';

            if (files.length > 0) {
                container.style.display = 'grid';

                Array.from(files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'relative';
                            div.innerHTML = `
                                <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg shadow-md">
                                <div class="absolute top-1 left-1 bg-black bg-opacity-50 text-white text-xs px-1 py-0.5 rounded">
                                    ${file.name}
                                </div>
                            `;
                            container.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                container.style.display = 'none';
            }
        }

        function removeImage(index) {
            if (confirm('Are you sure you want to remove this image? This cannot be undone.')) {
                const imageElement = document.querySelector(`[onclick="removeImage(${index})"]`).closest('.relative');

                // Show loading state
                imageElement.style.opacity = '0.5';
                const button = document.querySelector(`[onclick="removeImage(${index})"]`);
                button.innerHTML = '⌛';
                button.disabled = true;

                // Make AJAX request to delete image
                fetch(`{{ route('admin.inventory.delete-image', $inventory) }}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        image_index: index
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the image element from DOM
                        imageElement.remove();

                        // Show success message
                        const successDiv = document.createElement('div');
                        successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                        successDiv.innerHTML = '<strong>Success!</strong> Image deleted successfully.';
                        document.body.appendChild(successDiv);

                        // Remove success message after 3 seconds
                        setTimeout(() => {
                            successDiv.remove();
                        }, 3000);
                    } else {
                        // Show error message
                        alert('Error: ' + data.message);
                        // Restore original state
                        imageElement.style.opacity = '1';
                        button.innerHTML = '×';
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the image.');
                    // Restore original state
                    imageElement.style.opacity = '1';
                    button.innerHTML = '×';
                    button.disabled = false;
                });
            }
        }
    </script>
</x-admin-layout>
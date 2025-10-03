<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <div>
                <a href="{{ route('orders.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Orders
                </a>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Edit Order #{{ $order->order_number }}</h1>
                <p class="text-sm text-gray-600">Modify your order details while it's still pending.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form 1: Items Management -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Order Items</h3>
                        </div>
                        <div class="p-6">
                            <div id="items-container">
                                @if(isset($order->items) && is_array($order->items))
                                    @foreach($order->items as $index => $item)
                                        <div class="item-row border border-gray-200 rounded-lg p-4 mb-4" data-original-item='@json($item)'>
                                            <div class="grid grid-cols-1 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Item Name *</label>
                                                    <input type="text" name="items[{{ $index }}][name]" value="{{ $item['name'] ?? '' }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Custom Suit" required>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                                                    <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] ?? 1 }}" min="1" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0" required>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                                    <textarea name="items[{{ $index }}][description]" rows="2" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Additional details about this item...">{{ $item['description'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-8 text-gray-500">
                                        <p>No items found for this order.</p>
                                    </div>
                                @endif
                            </div>


                        </div>
                    </div>
                </div>

                <!-- Form 2: Order Details & Submission -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form action="{{ route('orders.update', $order) }}" method="POST" enctype="multipart/form-data" class="p-6">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="items-data" name="items" value="">

                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Details</h3>

                        <!-- Pre-made Design Selection (if applicable) -->
                        @if($order->design_type === 'pre_made')
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Selected Pre-Made Design</label>
                                
                                <!-- Current Design Display -->
                                @if($order->design_brochure_id && $order->designBrochure)
                                    <div id="current-design-display" class="mb-4 p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                                        <div class="flex items-start space-x-4">
                                            <img src="{{ $order->designBrochure->featured_image_url }}" 
                                                 alt="{{ $order->designBrochure->title }}" 
                                                 class="w-20 h-20 object-cover rounded-lg">
                                            <div class="flex-1">
                                                <h4 class="text-lg font-medium text-gray-900">{{ $order->designBrochure->title }}</h4>
                                                <p class="text-sm text-gray-600 mb-2">{{ $order->designBrochure->description }}</p>
                                                <div class="flex flex-wrap gap-2">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        {{ ucwords($order->designBrochure->category) }}
                                                    </span>
                                                    @if($order->designBrochure->price)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            ₱{{ number_format($order->designBrochure->price, 0) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <button type="button" onclick="showDesignSelection()" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                                Change Design
                                            </button>
                                        </div>
                                        <input type="hidden" name="design_brochure_id" value="{{ $order->design_brochure_id }}">
                                    </div>
                                @endif

                                <!-- Design Selection Grid (Hidden by default) -->
                                <div id="design-selection-container" class="{{ $order->design_brochure_id ? 'hidden' : '' }}">
                                    <!-- Quick Search -->
                                    <div class="mb-4">
                                        <input type="text"
                                               id="design-search"
                                               placeholder="Search designs..."
                                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>

                                    <!-- Category Filter -->
                                    <div class="mb-6">
                                        <div class="flex flex-wrap gap-2">
                                            <button type="button" onclick="filterDesigns('')" class="design-filter-btn bg-indigo-600 text-white px-3 py-1 rounded-full text-sm hover:bg-indigo-700 transition-colors duration-200">
                                                All
                                            </button>
                                            @foreach($categories as $category)
                                                <button type="button" onclick="filterDesigns('{{ $category }}')" class="design-filter-btn bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm hover:bg-gray-300 transition-colors duration-200">
                                                    {{ ucwords($category) }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Design Grid -->
                                    <div id="designs-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-96 overflow-y-auto">
                                        @foreach($designBrochures as $brochure)
                                            <div class="design-option border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-indigo-500 hover:shadow-md transition-all duration-200"
                                                 data-design-id="{{ $brochure->id }}"
                                                 data-category="{{ $brochure->category }}"
                                                 data-title="{{ strtolower($brochure->title) }}"
                                                 onclick="selectDesign({{ $brochure->id }}, '{{ $brochure->title }}', '{{ $brochure->description }}', '{{ $brochure->featured_image_url }}', '{{ ucwords($brochure->category) }}', {{ $brochure->price ?? 0 }})">
                                                <img src="{{ $brochure->featured_image_url }}"
                                                     alt="{{ $brochure->title }}"
                                                     class="w-full h-32 object-cover rounded-md mb-2">
                                                <h4 class="text-sm font-medium text-gray-900">{{ $brochure->title }}</h4>
                                                <p class="text-xs text-gray-600 mb-2 line-clamp-2">{{ $brochure->description }}</p>
                                                <div class="flex justify-between items-center">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ ucwords($brochure->category) }}
                                                    </span>
                                                    @if($brochure->price)
                                                        <span class="text-sm font-bold text-indigo-600">₱{{ number_format($brochure->price, 0) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                @if($order->design_notes)
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Design Notes</label>
                                        <p class="text-sm text-gray-600 p-3 bg-gray-50 rounded-md">{{ $order->design_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Delivery Date</label>
                                <input type="date" name="delivery_date" value="{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') : '' }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('delivery_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>



                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Design Reference Images</label>
                                <div id="design-images-dropzone" class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors duration-200 cursor-pointer">
                                    <input type="file" id="design-images-input" name="design_images[]" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                    <div class="space-y-2">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="text-sm text-gray-600">
                                            <span class="font-medium text-blue-600 hover:text-blue-500">Click to upload</span> or drag and drop
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB each</p>
                                    </div>
                                </div>
                                <div id="design-images-preview" class="mt-3 grid-cols-2 md:grid-cols-3 gap-3" style="display: none;"></div>
                                @error('design_images.*')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                @if($order->design_images && count($order->design_images) > 0)
                                    <div class="mt-3">
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Current Images:</h4>
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                            @foreach($order->design_images as $index => $image)
                                                <div class="relative group">
                                                    <img src="{{ Storage::url($image) }}" alt="Design Image" class="w-full h-20 object-cover rounded border">
                                                    <button type="button" onclick="deleteCurrentImage('{{ $image }}', this)" class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold transition-colors duration-200 opacity-0 group-hover:opacity-100">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Special Notes</label>
                                <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Any special instructions...">{{ $order->notes }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex flex-col space-y-3 mt-6">
                            <button type="submit" id="submit-order" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                                Update Order
                            </button>
                            <a href="{{ route('orders.show', $order) }}" class="w-full text-center bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        console.log('Order edit JavaScript initializing...');

        // Sync items data between forms
        function updateItemsData() {
            const items = [];
            const itemRows = document.querySelectorAll('.item-row');

            itemRows.forEach(row => {
                const nameInput = row.querySelector('input[name*="[name]"]');
                const quantityInput = row.querySelector('input[name*="[quantity]"]');
                const descriptionInput = row.querySelector('textarea[name*="[description]"]');

                if (nameInput && quantityInput && nameInput.value.trim()) {
                    const itemData = {
                        name: nameInput.value.trim(),
                        quantity: parseInt(quantityInput.value) || 1,
                        description: descriptionInput ? descriptionInput.value.trim() : ''
                    };
                    items.push(itemData);
                }
            });

            const itemsDataInput = document.getElementById('items-data');
            if (itemsDataInput) {
                itemsDataInput.value = JSON.stringify(items);
                console.log('Items data updated:', items);
            } else {
                console.error('items-data input not found');
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing...');
            updateItemsData();
        });

        // Listen for changes in item inputs
        document.addEventListener('input', function(e) {
            if (e.target.name && e.target.name.includes('items[')) {
                console.log('Item input changed:', e.target.name);
                updateItemsData();
            }
        });

        // Ensure data is updated before form submission
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                console.log('Form submitting, updating items data...');
                updateItemsData();
            });
        } else {
            console.error('Form not found');
        }

        // Image handling functionality
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Initializing image handling...');
            
            const dropzone = document.getElementById('design-images-dropzone');
            const fileInput = document.getElementById('design-images-input');
            const preview = document.getElementById('design-images-preview');
            
            console.log('Image elements:', { dropzone, fileInput, preview });
            
            if (!dropzone || !fileInput || !preview) {
                console.error('Missing image handling elements');
                return;
            }

            let selectedFiles = [];

            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            // Highlight drop area when item is dragged over it
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                dropzone.classList.add('border-blue-500', 'bg-blue-50');
            }

            function unhighlight(e) {
                dropzone.classList.remove('border-blue-500', 'bg-blue-50');
            }

            // Handle dropped files
            dropzone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                console.log('Files dropped');
                const dt = e.dataTransfer;
                const files = dt.files;
                handleFiles(files);
            }

            // Handle file input change
            fileInput.addEventListener('change', function(e) {
                console.log('File input changed');
                handleFiles(e.target.files);
            });

            function handleFiles(files) {
                console.log('Handling files:', files);
                selectedFiles = Array.from(files).filter(file => {
                    const isImage = file.type.startsWith('image/');
                    const isValidSize = file.size <= 2 * 1024 * 1024; // 2MB
                    if (!isImage) console.warn('Skipping non-image file:', file.name);
                    if (!isValidSize) console.warn('Skipping oversized file:', file.name);
                    return isImage && isValidSize;
                });
                console.log('Filtered files:', selectedFiles);
                updateFileInput();
                showPreview();
            }

            function updateFileInput() {
                try {
                    const dt = new DataTransfer();
                    selectedFiles.forEach(file => dt.items.add(file));
                    fileInput.files = dt.files;
                    console.log('File input updated with', selectedFiles.length, 'files');
                } catch (error) {
                    console.error('Error updating file input:', error);
                }
            }

            function showPreview() {
                console.log('Showing preview for', selectedFiles.length, 'files');
                preview.innerHTML = '';

                if (selectedFiles.length > 0) {
                    preview.style.display = 'grid';

                    selectedFiles.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            console.log('File loaded for preview:', file.name);
                            const div = document.createElement('div');
                            div.className = 'relative group';
                            div.innerHTML = `
                                <img src="${e.target.result}" alt="Preview" class="w-full h-20 object-cover rounded border">
                                <button type="button" class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity" onclick="removeFile(${index})">
                                    ×
                                </button>
                            `;
                            preview.appendChild(div);
                        };
                        reader.onerror = function(error) {
                            console.error('Error reading file:', error);
                        };
                        reader.readAsDataURL(file);
                    });
                } else {
                    preview.style.display = 'none';
                }
            }

            // Global functions for button clicks
            window.removeFile = function(index) {
                console.log('Removing file at index:', index);
                selectedFiles.splice(index, 1);
                updateFileInput();
                showPreview();
            };

            window.deleteCurrentImage = function(imagePath, buttonElement) {
                console.log('Deleting current image:', imagePath);
                if (confirm('Are you sure you want to delete this image?')) {
                    // Show loading state
                    const originalHTML = buttonElement.innerHTML;
                    buttonElement.innerHTML = '<svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
                    buttonElement.disabled = true;

                    // Get CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        console.error('CSRF token not found');
                        alert('Security token not found. Please refresh the page.');
                        buttonElement.innerHTML = originalHTML;
                        buttonElement.disabled = false;
                        return;
                    }

                    // Make AJAX request to delete image immediately
                    fetch(`/orders/{{ $order->id }}/delete-image`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            image_path: imagePath
                        })
                    })
                    .then(response => {
                        console.log('Delete response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Delete response data:', data);
                        if (data.success) {
                            // Remove the image container from DOM
                            const imageContainer = buttonElement.closest('.relative');
                            if (imageContainer) {
                                imageContainer.remove();
                                console.log('Image container removed from DOM');
                            }
                        } else {
                            throw new Error(data.message || 'Unknown error');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting image:', error);
                        alert('Failed to delete image: ' + error.message);
                        // Reset button
                        buttonElement.innerHTML = originalHTML;
                        buttonElement.disabled = false;
                    });
                }
            };

            console.log('Image handling initialized successfully');
        });

        // Design selection functionality for pre-made designs
        function showDesignSelection() {
            document.getElementById('current-design-display').classList.add('hidden');
            document.getElementById('design-selection-container').classList.remove('hidden');
        }

        function selectDesign(id, title, description, image, category, price) {
            const currentDisplay = document.getElementById('current-design-display');
            const selectionContainer = document.getElementById('design-selection-container');

            // Update the current design display
            const selectedHtml = `
                <div class="flex items-start space-x-4">
                    <img src="${image}" alt="${title}" class="w-20 h-20 object-cover rounded-lg">
                    <div class="flex-1">
                        <h4 class="text-lg font-medium text-gray-900">${title}</h4>
                        <p class="text-sm text-gray-600 mb-2">${description}</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                ${category}
                            </span>
                            ${price > 0 ? `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">₱${price.toLocaleString()}</span>` : ''}
                        </div>
                    </div>
                    <button type="button" onclick="showDesignSelection()" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                        Change Design
                    </button>
                </div>
                <input type="hidden" name="design_brochure_id" value="${id}">
            `;

            currentDisplay.innerHTML = selectedHtml;
            currentDisplay.classList.remove('hidden');
            selectionContainer.classList.add('hidden');
        }

        // Design search and filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('design-search');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const designs = document.querySelectorAll('.design-option');

                    designs.forEach(design => {
                        const title = design.getAttribute('data-title');
                        if (title.includes(searchTerm)) {
                            design.style.display = 'block';
                        } else {
                            design.style.display = 'none';
                        }
                    });
                });
            }
        });

        function filterDesigns(category) {
            const designs = document.querySelectorAll('.design-option');
            const buttons = document.querySelectorAll('.design-filter-btn');

            // Update button styles
            buttons.forEach(btn => {
                btn.className = 'design-filter-btn bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm hover:bg-gray-300 transition-colors duration-200';
            });
            event.target.className = 'design-filter-btn bg-indigo-600 text-white px-3 py-1 rounded-full text-sm hover:bg-indigo-700 transition-colors duration-200';

            // Filter designs
            designs.forEach(design => {
                const designCategory = design.getAttribute('data-category');
                if (category === '' || designCategory === category) {
                    design.style.display = 'block';
                } else {
                    design.style.display = 'none';
                }
            });
        }

        console.log('Order edit JavaScript loaded');
    </script>
</x-app-layout>

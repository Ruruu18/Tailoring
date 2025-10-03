<x-admin-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <div>
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Order Management
                </a>
            </div>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Order #{{ $order->order_number }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Modify order details and information</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Please fix the following errors:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm p-6">
                <form method="POST" action="{{ route('admin.orders.update', $order) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Customer Selection -->
                    <div class="mb-6">
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Customer *</label>
                        <select name="user_id" id="user_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select a customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ (old('user_id', $order->user_id) == $customer->id) ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Items *</label>
                        <div id="items-container">
                            @if(is_array($order->items) && count($order->items) > 0)
                                @foreach($order->items as $index => $item)
                                    <div class="item-row border border-gray-200 rounded-lg p-4 mb-4">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Item Name *</label>
                                                <input type="text" name="items[{{ $index }}][name]" value="{{ old("items.{$index}.name", $item['name'] ?? $item['type'] ?? '') }}" required
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g., Custom Suit">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                                                <input type="number" name="items[{{ $index }}][quantity]" min="1" value="{{ old("items.{$index}.quantity", $item['quantity'] ?? 1) }}" required
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            </div>
                                            <div class="flex items-end">
                                                <button type="button" onclick="removeItem(this)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md transition duration-200">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                            <textarea name="items[{{ $index }}][description]" rows="2" placeholder="Additional details about this item..."
                                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old("items.{$index}.description", $item['description'] ?? '') }}</textarea>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="item-row border border-gray-200 rounded-lg p-4 mb-4">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Item Type *</label>
                                            <select name="items[0][type]" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                <option value="">Select item type</option>
                                                <option value="Shirt">Shirt</option>
                                                <option value="Pants">Pants</option>
                                                <option value="Dress">Dress</option>
                                                <option value="Suit">Suit</option>
                                                <option value="Blouse">Blouse</option>
                                                <option value="Skirt">Skirt</option>
                                                <option value="Jacket">Jacket</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                                            <input type="number" name="items[0][quantity]" min="1" value="1" required
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div class="flex items-end">
                                            <button type="button" onclick="removeItem(this)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md transition duration-200">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                        <textarea name="items[0][description]" rows="2" placeholder="Additional details about this item..."
                                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addItem()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition duration-200">
                            <i class="fas fa-plus mr-2"></i>Add Another Item
                        </button>
                    </div>

                    <!-- Status -->
                    <div class="mb-6">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" id="status" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ old('status', $order->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="ready" {{ old('status', $order->status) == 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <!-- Pricing -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-2">Total Amount (₱) *</label>
                            <input type="number" name="total_amount" id="total_amount" step="0.01" min="0" value="{{ old('total_amount', $order->total_amount) }}" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="paid_amount" class="block text-sm font-medium text-gray-700 mb-2">Paid Amount (₱)</label>
                            <input type="number" name="paid_amount" id="paid_amount" step="0.01" min="0" value="{{ old('paid_amount', $order->paid_amount) }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Delivery Date -->
                    <div class="mb-6">
                        <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-2">Delivery Date</label>
                        <input type="date" name="delivery_date" id="delivery_date" value="{{ old('delivery_date', $order->delivery_date ? $order->delivery_date->format('Y-m-d') : '') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Design Images (Read-Only) -->
                    @if($order->design_images && count($order->design_images) > 0)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Customer Design Images</label>
                            <p class="text-sm text-gray-500 mb-3">These are the customer's original design requests and cannot be modified.</p>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                @foreach($order->design_images as $index => $image)
                                    <div class="relative">
                                        <img src="{{ Storage::url($image) }}" alt="Design Image" class="w-full h-24 object-cover rounded-lg border border-gray-200 cursor-pointer"
                                             onclick="openImageModal('{{ Storage::url($image) }}')">
                                        <div class="absolute top-1 right-1 bg-blue-500 text-white text-xs px-2 py-1 rounded">
                                            View
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Notes -->
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea name="notes" id="notes" rows="4" placeholder="Additional notes or special instructions..."
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $order->notes) }}</textarea>
                    </div>

                    <!-- Hidden field for deleted images -->
                    <!-- Removed: Images are now read-only and cannot be deleted by admin -->

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.orders.show', $order) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                            <i class="fas fa-save mr-2"></i>Update Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let itemIndex = {{ is_array($order->items) ? count($order->items) : 1 }};

        function addItem() {
            const container = document.getElementById('items-container');
            const newItem = document.createElement('div');
            newItem.className = 'item-row border border-gray-200 rounded-lg p-4 mb-4';
            newItem.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Item Type *</label>
                        <select name="items[${itemIndex}][type]" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select item type</option>
                            <option value="Shirt">Shirt</option>
                            <option value="Pants">Pants</option>
                            <option value="Dress">Dress</option>
                            <option value="Suit">Suit</option>
                            <option value="Blouse">Blouse</option>
                            <option value="Skirt">Skirt</option>
                            <option value="Jacket">Jacket</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                        <input type="number" name="items[${itemIndex}][quantity]" min="1" value="1" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="flex items-end">
                        <button type="button" onclick="removeItem(this)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md transition duration-200">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="items[${itemIndex}][description]" rows="2" placeholder="Additional details about this item..."
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
            `;
            container.appendChild(newItem);
            itemIndex++;
        }

        function removeItem(button) {
            const itemRows = document.querySelectorAll('.item-row');
            if (itemRows.length > 1) {
                button.closest('.item-row').remove();
            } else {
                alert('You must have at least one item in the order.');
            }
        }

        function openImageModal(imageUrl) {
            // Create modal overlay
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
            modal.onclick = function() { document.body.removeChild(modal); };

            // Create modal content
            const modalContent = document.createElement('div');
            modalContent.className = 'max-w-4xl max-h-full p-4';
            modalContent.onclick = function(e) { e.stopPropagation(); };

            // Create image
            const img = document.createElement('img');
            img.src = imageUrl;
            img.className = 'max-w-full max-h-full object-contain rounded-lg';

            // Create close button
            const closeBtn = document.createElement('button');
            closeBtn.innerHTML = '×';
            closeBtn.className = 'absolute top-4 right-4 text-white text-3xl font-bold bg-black bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-75';
            closeBtn.onclick = function() { document.body.removeChild(modal); };

            modalContent.appendChild(img);
            modal.appendChild(modalContent);
            modal.appendChild(closeBtn);
            document.body.appendChild(modal);
        }
    </script>
</x-admin-layout>

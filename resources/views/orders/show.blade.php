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
                <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
                <p class="text-gray-600 mt-1">Order placed on {{ $order->created_at->format('F j, Y') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                <!-- Order Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-3">
                        <div class="px-3 py-2 border-b border-gray-200">
                            <h3 class="text-sm font-medium text-gray-900">Order Items</h3>
                        </div>
                        <div class="p-3">
                            @if(isset($order->items) && is_array($order->items) && count($order->items) > 0)
                                <div class="space-y-2">
                                    @foreach($order->items as $item)
                                        <div class="border border-gray-200 rounded-lg p-2">
                                            <div class="flex justify-between items-start">
                                                    <div class="flex-1">
                                                        <h4 class="text-md font-medium text-gray-900">{{ $item['type'] ?? $item['name'] ?? 'Item' }}</h4>
                                                        @if(isset($item['description']) && $item['description'])
                                                            <p class="text-gray-600 mt-1 text-sm">{{ $item['description'] }}</p>
                                                        @endif
                                                        <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                                            <span>Quantity: {{ $item['quantity'] ?? 1 }}</span>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No items found for this order.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Pre-made Design Display -->
                    @if($order->design_brochure_id && $order->designBrochure)
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-3">
                            <div class="px-3 py-2 border-b border-gray-200">
                                <h3 class="text-sm font-medium text-gray-900">Selected Pre-Made Design</h3>
                            </div>
                            <div class="p-3">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Large Design Image -->
                                    <div class="space-y-2">
                                        <div class="aspect-square w-full max-w-xs mx-auto">
                                            <img src="{{ $order->designBrochure->featured_image_url }}"
                                                 alt="{{ $order->designBrochure->title }}"
                                                 class="w-full h-full object-cover rounded-lg border border-gray-200 shadow-md cursor-pointer"
                                                 onclick="openImageModal('{{ $order->designBrochure->featured_image_url }}')">
                                        </div>
                                        @if($order->designBrochure->images && count($order->designBrochure->images) > 1)
                                            <div class="grid grid-cols-3 gap-1">
                                                @foreach(array_slice($order->designBrochure->images, 1, 3) as $image)
                                                    <img src="{{ Storage::url($image) }}"
                                                         alt="Design variation"
                                                         class="w-full h-16 object-cover rounded border border-gray-200 cursor-pointer hover:shadow-md transition-shadow"
                                                         onclick="openImageModal('{{ Storage::url($image) }}')">
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Design Details -->
                                    <div class="space-y-3">
                                        <div>
                                            <h4 class="text-lg font-bold text-gray-900">{{ $order->designBrochure->title }}</h4>
                                            <p class="text-gray-600 mt-1 text-sm leading-relaxed">{{ $order->designBrochure->description }}</p>
                                        </div>

                                        <div class="flex flex-wrap gap-1">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ ucwords($order->designBrochure->category) }}
                                            </span>
                                            @if($order->designBrochure->price)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    ₱{{ number_format($order->designBrochure->price, 0) }}
                                                </span>
                                            @endif
                                        </div>

                                        @if($order->design_notes)
                                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-2">
                                                <h5 class="text-xs font-medium text-gray-900 mb-1">Your Design Notes:</h5>
                                                <p class="text-xs text-gray-700 leading-relaxed">{{ $order->design_notes }}</p>
                                            </div>
                                        @endif

                                        @if($order->designBrochure->specifications)
                                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-2">
                                                <h5 class="text-xs font-medium text-blue-900 mb-1">Design Specifications:</h5>
                                                <div class="text-xs text-blue-800">
                                                    @if(is_array($order->designBrochure->specifications))
                                                        <ul class="space-y-0.5">
                                                            @foreach($order->designBrochure->specifications as $spec)
                                                                <li>• {{ $spec }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <p>{{ $order->designBrochure->specifications }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Quick Actions - Moved here below pre-made design -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-3">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                        </div>
                        <div class="p-4">
                            <!-- Primary Actions - Horizontal Layout -->
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('orders.track', $order) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors duration-200 flex items-center justify-center text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Track Order
                                </a>

                                @if($order->status === 'pending')
                                    <a href="{{ route('orders.edit', $order) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-medium transition-colors duration-200 flex items-center justify-center text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit Order
                                    </a>
                                @else
                                    <div></div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if(isset($order->design_images) && is_array($order->design_images) && count($order->design_images) > 0)
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">Design Reference Images</h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach($order->design_images as $image)
                                        <div class="relative group cursor-pointer" onclick="openImageModal('{{ Storage::url($image) }}')">
                                            <img src="{{ Storage::url($image) }}" alt="Design Reference" class="w-full h-32 object-cover rounded-lg border border-gray-200 hover:shadow-md transition-shadow duration-200">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                                                <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(isset($order->notes) && $order->notes)
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">Special Notes</h3>
                            </div>
                            <div class="p-6">
                                <p class="text-gray-700">{{ $order->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-2">
                        <div class="px-3 py-2 border-b border-gray-200">
                            <h3 class="text-md font-medium text-gray-900">Order Summary</h3>
                        </div>
                        <div class="p-3">
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Order Number:</span>
                                    <span class="font-medium">#{{ $order->order_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{
                                        $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                        ($order->status === 'quoted' ? 'bg-orange-100 text-orange-800' :
                                        ($order->status === 'confirmed' ? 'bg-purple-100 text-purple-800' :
                                        ($order->status === 'paid' ? 'bg-green-100 text-green-800' :
                                        ($order->status === 'partial_payment' ? 'bg-blue-100 text-blue-800' :
                                        ($order->status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
                                        ($order->status === 'ready' ? 'bg-green-100 text-green-800' :
                                        ($order->status === 'completed' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800')))))))
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Order Date:</span>
                                    <span class="font-medium">{{ $order->created_at->format('M j, Y') }}</span>
                                </div>
                                @if($order->delivery_date)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Delivery Date:</span>
                                        <span class="font-medium">{{ $order->delivery_date->format('M j, Y') }}</span>
                                    </div>
                                @endif
                                <hr class="my-2">
                                @if($order->total_amount > 0)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Total Amount:</span>
                                        <span class="text-lg font-bold text-blue-600">₱{{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Paid Amount:</span>
                                        <span class="font-medium text-green-600">₱{{ number_format($order->paid_amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Payment Status:</span>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{
                                            $order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                            ($order->payment_status === 'partial' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800')
                                        }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </div>
                                    @if($order->total_amount > $order->paid_amount)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Balance:</span>
                                            <span class="font-medium text-red-600">₱{{ number_format($order->total_amount - $order->paid_amount, 2) }}</span>
                                        </div>
                                    @endif
                                @else
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-2">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-yellow-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-xs font-medium text-yellow-800">Pricing Pending</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Payment Actions -->
                    {{--
                        PAYMENT FLOW LOGIC:
                        1. pending → No payment (waiting for quote)
                        2. quoted/confirmed/in_progress → Downpayment option only (optional)
                        3. ready/completed → Full payment required (urgent)
                        4. partial → Remaining balance payment (any status)

                        Status changes are dynamic - payment options adjust automatically
                    --}}
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="px-3 py-2 border-b border-gray-200">
                            <h3 class="text-md font-medium text-gray-900">Payment & Actions</h3>
                        </div>
                        <div class="p-3">
                            <!-- Info banner for quoted orders -->
                            @if(in_array($order->status, ['quoted', 'confirmed', 'in_progress']) && $order->payment_status === 'pending' && $order->paid_amount == 0)
                                <div class="mb-3 bg-blue-50 border border-blue-200 rounded-lg p-2">
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="text-xs text-blue-800">
                                            <span class="font-medium">Your order has been quoted!</span>
                                            <p class="mt-1">Full payment will be required when your order is ready for pickup. You may make an optional downpayment below.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Downpayment Option - Only show if order has been quoted (total_amount > 0) and payment_status is pending, but NOT if ready/completed -->
                            @if($order->total_amount > 0 && $order->payment_status === 'pending' && $order->paid_amount == 0 && $order->status !== 'pending' && !in_array($order->status, ['ready', 'completed']))
                                <div class="mb-3">
                                    <h4 class="text-sm font-medium text-gray-900 mb-1">Make Downpayment (Optional)</h4>
                                    <p class="text-xs text-gray-600 mb-2">Downpayment is optional but recommended as reassurance. You can pay the full amount when your order is ready.</p>

                                    <div class="space-y-2">
                                        <div>
                                            <label for="downpayment_amount" class="block text-xs text-gray-700 mb-1">Amount (₱)</label>
                                            <input type="number"
                                                   id="downpayment_amount"
                                                   min="1"
                                                   max="{{ $order->total_amount - 1 }}"
                                                   step="0.01"
                                                   class="w-full border border-gray-300 rounded-md px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                                   placeholder="Enter amount"
                                                   value="{{ round($order->total_amount * 0.5, 2) }}"
                                                   required>
                                            <p class="text-xs text-gray-500 mt-1">Min: ₱1 | Max: ₱{{ number_format($order->total_amount - 1, 2) }}</p>
                                        </div>

                                        <div class="grid grid-cols-2 gap-1">
                                            <!-- GCash Downpayment Button -->
                                            <form action="{{ route('payment.downpayment.gcash', $order) }}" method="POST" id="gcash-downpayment-form" onsubmit="return validateDownpayment()">
                                                @csrf
                                                <input type="hidden" name="downpayment_amount" id="gcash_amount" value="{{ round($order->total_amount * 0.5, 2) }}">
                                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-md font-medium transition-colors duration-200 flex items-center justify-center text-xs">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                                    </svg>
                                                    GCash
                                                </button>
                                            </form>

                                            <!-- PayMaya Downpayment Button -->
                                            <form action="{{ route('payment.downpayment.paymaya', $order) }}" method="POST" id="paymaya-downpayment-form" onsubmit="return validateDownpayment()">
                                                @csrf
                                                <input type="hidden" name="downpayment_amount" id="paymaya_amount" value="{{ round($order->total_amount * 0.5, 2) }}">
                                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-md font-medium transition-colors duration-200 flex items-center justify-center text-xs">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                                    </svg>
                                                    PayMaya
                                                </button>
                                            </form>
                                        </div>

                                        <script>
                                            const totalAmount = {{ $order->total_amount }};

                                            // Update hidden inputs when amount changes
                                            document.getElementById('downpayment_amount').addEventListener('input', function() {
                                                const amount = this.value;
                                                document.getElementById('gcash_amount').value = amount;
                                                document.getElementById('paymaya_amount').value = amount;
                                            });

                                            // Validate downpayment amount
                                            function validateDownpayment() {
                                                const amount = parseFloat(document.getElementById('downpayment_amount').value);

                                                if (isNaN(amount) || amount < 1) {
                                                    alert('Please enter a valid amount. Minimum downpayment is ₱1.');
                                                    return false;
                                                }

                                                if (amount >= totalAmount) {
                                                    alert('Downpayment must be less than the total amount (₱' + totalAmount.toLocaleString('en-PH', {minimumFractionDigits: 2}) + '). Please pay the full amount when your order is ready for pickup.');
                                                    return false;
                                                }

                                                return true;
                                            }
                                        </script>
                                    </div>
                                </div>
                            @endif

                            <!-- Payment Options - Only show if order is ready/completed or has partial payment -->
                            @if($order->total_amount > 0 && $order->total_amount > $order->paid_amount && $order->status !== 'pending' && (
                                in_array($order->status, ['ready', 'completed']) ||
                                ($order->payment_status === 'partial')
                            ))
                                <div class="mb-3">
                                    @if(in_array($order->status, ['ready', 'completed']))
                                        <h4 class="text-sm font-medium text-red-600 mb-1">⚠️ Payment Required</h4>
                                        <p class="text-xs text-red-500 mb-2">Your order is ready! Please complete payment to collect your items.</p>
                                    @elseif($order->payment_status === 'partial')
                                        <h4 class="text-sm font-medium text-blue-600 mb-2">Pay Remaining Balance</h4>
                                        <p class="text-xs text-blue-500 mb-2">Complete your payment for the remaining ₱{{ number_format($order->total_amount - $order->paid_amount, 2) }}</p>
                                    @else
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Pay Now</h4>
                                    @endif
                                    <div class="grid grid-cols-2 gap-1">
                                        <!-- GCash Payment Button -->
                                        <form action="{{ route('payment.gcash', $order) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full @if(in_array($order->status, ['ready', 'completed'])) bg-red-500 hover:bg-red-600 @else bg-blue-500 hover:bg-blue-600 @endif text-white px-3 py-1.5 rounded-md font-medium transition-colors duration-200 flex items-center justify-center text-xs">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                                </svg>
                                                Pay via GCash
                                            </button>
                                        </form>

                                        <!-- PayMaya Payment Button -->
                                        <form action="{{ route('payment.paymaya', $order) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full @if(in_array($order->status, ['ready', 'completed'])) bg-red-500 hover:bg-red-600 @else bg-green-500 hover:bg-green-600 @endif text-white px-3 py-1.5 rounded-md font-medium transition-colors duration-200 flex items-center justify-center text-xs">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                                </svg>
                                                Pay via PayMaya
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif

                            <!-- Message for orders waiting for quote -->
                            @if($order->status === 'pending' && $order->total_amount == 0)
                                <div class="mb-3">
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-2">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-yellow-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div class="text-xs">
                                                <div class="font-medium text-yellow-800">Waiting for Quote</div>
                                                <div class="text-yellow-700 mt-1">Payment options will be available once your order has been quoted by our team.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Secondary Actions -->
                            <div class="space-y-2">
                                <a href="{{ route('appointments.create') }}?order_id={{ $order->id }}" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-3 py-1.5 rounded-md font-medium transition-colors duration-200 flex items-center justify-center text-sm">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Schedule Appointment
                                </a>

                                @if($order->status === 'pending')
                                    <form action="{{ route('orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this order? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-md font-medium transition-colors duration-200 flex items-center justify-center text-sm">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete Order
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="modalImage" src="" alt="Design Reference" class="max-w-full max-h-full object-contain rounded-lg">
        </div>
    </div>

    <script>
        function openImageModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            
            modalImage.src = imageSrc;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        
        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
        
        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });

        // Real-time order updates
        let lastUpdated = '{{ $order->updated_at->toISOString() }}';
        let orderStatus = '{{ $order->status }}';
        let paymentStatus = '{{ $order->payment_status }}';
        let totalAmount = {{ $order->total_amount }};
        let paidAmount = {{ $order->paid_amount }};

        function checkForUpdates() {
            fetch(`/api/orders/{{ $order->id }}/status`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                // Check if order has been updated
                if (data.updated_at !== lastUpdated) {
                    updateOrderDisplay(data);
                    lastUpdated = data.updated_at;
                }
            })
            .catch(error => {
                console.error('Error checking for updates:', error);
            });
        }

        function updateOrderDisplay(data) {
            let needsReload = false;

            // Check for significant changes that require page reload
            if (data.status !== orderStatus ||
                data.payment_status !== paymentStatus ||
                data.total_amount !== totalAmount ||
                data.paid_amount !== paidAmount) {
                needsReload = true;
            }

            if (needsReload) {
                // Show notification about update
                showUpdateNotification(data);

                // Auto-reload after 3 seconds or allow user to reload immediately
                setTimeout(() => {
                    location.reload();
                }, 3000);
            }
        }

        function showUpdateNotification(data) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-blue-600 text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-sm';
            notification.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span class="font-medium">Order Updated!</span>
                    </div>
                    <button onclick="location.reload()" class="ml-4 text-white hover:text-gray-200 underline text-sm">
                        Refresh Now
                    </button>
                </div>
                <p class="text-sm mt-1 text-blue-100">
                    ${getUpdateMessage(data)}
                </p>
            `;

            document.body.appendChild(notification);

            // Remove notification after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 5000);
        }

        function getUpdateMessage(data) {
            if (data.status !== orderStatus) {
                return `Status changed to ${data.status.replace('_', ' ')}`;
            } else if (data.payment_status !== paymentStatus) {
                return `Payment status updated to ${data.payment_status}`;
            } else if (data.total_amount !== totalAmount) {
                return `Order has been quoted: ₱${new Intl.NumberFormat().format(data.total_amount)}`;
            } else if (data.paid_amount !== paidAmount) {
                return `Payment received: ₱${new Intl.NumberFormat().format(data.paid_amount)}`;
            }
            return 'Order information has been updated';
        }

        // Start polling every 30 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setInterval(checkForUpdates, 30000);

            // Also check when the page becomes visible again
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    checkForUpdates();
                }
            });
        });
    </script>
</x-app-layout>
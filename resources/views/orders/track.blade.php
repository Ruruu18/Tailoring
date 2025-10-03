<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <div>
                <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Order
                </a>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Track Order #{{ $order->order_number }}</h1>
                <p class="text-gray-600 mt-1">Monitor your order progress and status updates.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Order Status Timeline -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Order Status</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <!-- Pending -->
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $order->status === 'pending' ? 'bg-yellow-500 text-white' : (in_array($order->status, ['quoted', 'confirmed', 'in_progress', 'ready', 'completed']) ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium mt-2">Pending</span>
                            <span class="text-xs text-gray-500">Order received</span>
                        </div>

                        <!-- Progress Line -->
                        <div class="flex-1 h-1 mx-4 {{ in_array($order->status, ['quoted', 'confirmed', 'in_progress', 'ready', 'completed']) ? 'bg-green-500' : 'bg-gray-300' }}"></div>

                        <!-- Quoted -->
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $order->status === 'quoted' ? 'bg-orange-500 text-white' : (in_array($order->status, ['confirmed', 'in_progress', 'ready', 'completed']) ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium mt-2">Quoted</span>
                            <span class="text-xs text-gray-500">Price set</span>
                        </div>

                        <!-- Progress Line -->
                        <div class="flex-1 h-1 mx-4 {{ in_array($order->status, ['confirmed', 'in_progress', 'ready', 'completed']) ? 'bg-green-500' : 'bg-gray-300' }}"></div>

                        <!-- Confirmed -->
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $order->status === 'confirmed' ? 'bg-purple-500 text-white' : (in_array($order->status, ['in_progress', 'ready', 'completed']) ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium mt-2">Confirmed</span>
                            <span class="text-xs text-gray-500">Order confirmed</span>
                        </div>

                        <!-- Progress Line -->
                        <div class="flex-1 h-1 mx-4 {{ in_array($order->status, ['in_progress', 'ready', 'completed']) ? 'bg-green-500' : 'bg-gray-300' }}"></div>

                        <!-- In Progress -->
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $order->status === 'in_progress' ? 'bg-blue-500 text-white' : (in_array($order->status, ['ready', 'completed']) ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium mt-2">In Progress</span>
                            <span class="text-xs text-gray-500">Being crafted</span>
                        </div>

                        <!-- Progress Line -->
                        <div class="flex-1 h-1 mx-4 {{ in_array($order->status, ['ready', 'completed']) ? 'bg-green-500' : 'bg-gray-300' }}"></div>

                        <!-- Ready -->
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $order->status === 'ready' ? 'bg-green-500 text-white' : ($order->status === 'completed' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium mt-2">Ready</span>
                            <span class="text-xs text-gray-500">Ready for pickup</span>
                        </div>

                        <!-- Progress Line -->
                        <div class="flex-1 h-1 mx-4 {{ $order->status === 'completed' ? 'bg-green-500' : 'bg-gray-300' }}"></div>

                        <!-- Completed -->
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $order->status === 'completed' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium mt-2">Completed</span>
                            <span class="text-xs text-gray-500">Order delivered</span>
                        </div>
                    </div>

                    <!-- Current Status Description -->
                    <div class="mt-8 p-4 rounded-lg {{ $order->status === 'pending' ? 'bg-yellow-50 border border-yellow-200' : ($order->status === 'in_progress' ? 'bg-blue-50 border border-blue-200' : ($order->status === 'ready' ? 'bg-green-50 border border-green-200' : ($order->status === 'completed' ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200'))) }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 {{ $order->status === 'pending' ? 'text-yellow-600' : ($order->status === 'in_progress' ? 'text-blue-600' : ($order->status === 'ready' ? 'text-green-600' : ($order->status === 'completed' ? 'text-green-600' : 'text-gray-600'))) }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium {{ $order->status === 'pending' ? 'text-yellow-800' : ($order->status === 'in_progress' ? 'text-blue-800' : ($order->status === 'ready' ? 'text-green-800' : ($order->status === 'completed' ? 'text-green-800' : 'text-gray-800'))) }}">
                                @if($order->status === 'pending')
                                    Order Received
                                @elseif($order->status === 'in_progress')
                                    Order In Progress
                                @elseif($order->status === 'ready')
                                    Order Ready for Pickup
                                @elseif($order->status === 'completed')
                                    Order Completed
                                @else
                                    Order Status: {{ ucfirst($order->status) }}
                                @endif
                            </span>
                        </div>
                        <p class="text-sm {{ $order->status === 'pending' ? 'text-yellow-700' : ($order->status === 'in_progress' ? 'text-blue-700' : ($order->status === 'ready' ? 'text-green-700' : ($order->status === 'completed' ? 'text-green-700' : 'text-gray-700'))) }} mt-1">
                            @if($order->status === 'pending')
                                We have received your order and our team is reviewing the details. You will be contacted soon with pricing and timeline information.
                            @elseif($order->status === 'in_progress')
                                Your order is currently being crafted by our skilled tailors. We'll notify you once it's ready for pickup.
                            @elseif($order->status === 'ready')
                                Great news! Your order is ready for pickup. Please contact us to schedule a convenient pickup time.
                            @elseif($order->status === 'completed')
                                Your order has been completed and delivered. Thank you for choosing our services!
                            @else
                                Order status: {{ ucfirst($order->status) }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Order Details Summary -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Order Details</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Order Information</h4>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Order Number:</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $order->order_number }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Order Date:</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $order->created_at->format('M j, Y') }}</dd>
                                </div>
                                @if($order->delivery_date)
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Delivery Date:</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($order->delivery_date)->format('M j, Y') }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Items</h4>
                            <div class="space-y-2">
                                @foreach($order->items as $item)
                                    <div class="text-sm">
                                        <span class="font-medium text-gray-900">{{ $item['name'] }}</span>
                                        <span class="text-gray-600"> × {{ $item['quantity'] }}</span>
                                        @if(!empty($item['description']))
                                            <p class="text-xs text-gray-500 mt-1">{{ $item['description'] }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Real-time order status updates
        let lastUpdated = '{{ $order->updated_at->toISOString() }}';
        let currentOrderStatus = '{{ $order->status }}';

        function checkForStatusUpdates() {
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
                // Check if order status has been updated
                if (data.updated_at !== lastUpdated && data.status !== currentOrderStatus) {
                    showStatusUpdateNotification(data.status);

                    // Update the timeline after a short delay
                    setTimeout(() => {
                        location.reload();
                    }, 2000);

                    lastUpdated = data.updated_at;
                    currentOrderStatus = data.status;
                }
            })
            .catch(error => {
                console.error('Error checking for status updates:', error);
            });
        }

        function showStatusUpdateNotification(newStatus) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-600 text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-sm animate-pulse';
            notification.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <div class="font-medium">Status Updated!</div>
                        <div class="text-sm text-green-100">Your order is now: ${newStatus.replace('_', ' ')}</div>
                    </div>
                </div>
            `;

            document.body.appendChild(notification);

            // Remove notification after 4 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 4000);
        }

        // Start polling every 30 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setInterval(checkForStatusUpdates, 30000);

            // Also check when the page becomes visible again
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    checkForStatusUpdates();
                }
            });

            // Check for updates when user focuses on the page
            window.addEventListener('focus', function() {
                checkForStatusUpdates();
            });
        });
    </script>
</x-app-layout>
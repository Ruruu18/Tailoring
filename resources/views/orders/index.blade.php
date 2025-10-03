<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center w-full space-y-4 sm:space-y-0">
            <div class="min-w-0 flex-1">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">My Orders</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Track and manage your tailoring orders.</p>
            </div>
            <div class="flex-shrink-0">
                <!-- Add New Order Button -->
                <a href="{{ route('orders.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center w-full sm:w-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="hidden sm:inline">Add New Order</span>
                    <span class="sm:hidden">New Order</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm mb-4 sm:mb-6 p-4 sm:p-6">
                <form method="GET" action="{{ route('orders.index') }}" class="space-y-4 sm:space-y-0 sm:flex sm:flex-wrap sm:gap-4">
                    <div class="flex-1 sm:min-w-64">
                        <label for="search" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Search Orders</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               placeholder="Search by order number..."
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div class="sm:min-w-48">
                        <label for="status" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="quoted" {{ request('status') == 'quoted' ? 'selected' : '' }}>Quoted</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-end gap-2 sm:gap-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 rounded-md transition duration-200 text-sm font-medium">
                            <i class="fas fa-search mr-1"></i>Filter
                        </button>
                        <a href="{{ route('orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 sm:px-4 py-2 rounded-md transition duration-200 text-sm font-medium text-center">
                            <i class="fas fa-times mr-1"></i>Clear
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                @if($orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="pl-4 pr-3 sm:pl-6 sm:pr-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">Order</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell min-w-[90px]">Date</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell min-w-[100px]">Items</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[70px]">Status</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[80px]">Total</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell min-w-[80px]">Delivery</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[80px]">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($orders as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="pl-4 pr-3 sm:pl-6 sm:pr-6 py-3 sm:py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">#{{ $order->order_number ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden md:table-cell">
                                            {{ isset($order->created_at) ? $order->created_at->format('M j, Y') : 'N/A' }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-500 hidden lg:table-cell">
                                            @if(isset($order->items) && is_array($order->items) && count($order->items) > 0)
                                                <div class="max-w-xs">
                                                    @foreach(array_slice($order->items, 0, 1) as $item)
                                                        <div class="truncate">
                                                            {{ $item['quantity'] ?? 1 }}x {{ $item['type'] ?? $item['name'] ?? 'Item' }}
                                                        </div>
                                                    @endforeach
                                                    @if(count($order->items) > 1)
                                                        <div class="text-xs text-gray-400">+{{ count($order->items) - 1 }} more</div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-400">No items</span>
                                            @endif
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{
                                            isset($order->status) && $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                            (isset($order->status) && $order->status === 'quoted' ? 'bg-orange-100 text-orange-800' :
                                            (isset($order->status) && $order->status === 'confirmed' ? 'bg-purple-100 text-purple-800' :
                                            (isset($order->status) && $order->status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
                                            (isset($order->status) && $order->status === 'ready' ? 'bg-green-100 text-green-800' :
                                            (isset($order->status) && $order->status === 'completed' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800')))))
                                        }}">
                                                <span class="hidden sm:inline">{{ isset($order->status) ? ucfirst(str_replace('_', ' ', $order->status)) : 'N/A' }}</span>
                                                <span class="sm:hidden">
                                                    @if(isset($order->status))
                                                        @if($order->status === 'pending')
                                                            ⏳
                                                        @elseif($order->status === 'quoted')
                                                            💰
                                                        @elseif($order->status === 'confirmed')
                                                            ✓
                                                        @elseif($order->status === 'in_progress')
                                                            🔨
                                                        @elseif($order->status === 'ready')
                                                            📦
                                                        @elseif($order->status === 'completed')
                                                            ✅
                                                        @elseif($order->status === 'cancelled')
                                                            ✖
                                                        @else
                                                            {{ substr(ucfirst(str_replace('_', ' ', $order->status)), 0, 1) }}
                                                        @endif
                                                    @else
                                                        N
                                                    @endif
                                                </span>
                                            </span>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                            <div class="text-xs sm:text-sm text-gray-900">
                                                @if(isset($order->total_amount) && $order->total_amount > 0)
                                                    <div class="font-medium">₱{{ number_format($order->total_amount, 2) }}</div>
                                                    @if(isset($order->paid_amount) && $order->paid_amount > 0)
                                                        <div class="text-xs text-green-600 hidden sm:block">Paid: ₱{{ number_format($order->paid_amount, 2) }}</div>
                                                        @if(isset($order->pending_amount) && $order->pending_amount > 0)
                                                            <div class="text-xs text-red-600 hidden sm:block">Balance: ₱{{ number_format($order->pending_amount, 2) }}</div>
                                                        @endif
                                                    @else
                                                        <div class="text-xs text-red-600">Unpaid</div>
                                                    @endif
                                                @else
                                                    <span class="text-yellow-600 text-xs">Quote</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden sm:table-cell">
                                            {{ $order->delivery_date ? $order->delivery_date->format('M j, Y') : 'TBD' }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-900" title="View Order">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($order->status === 'pending')
                                                    <a href="{{ route('orders.edit', $order) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit Order">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ route('orders.track', $order) }}" class="text-green-600 hover:text-green-900" title="Track Order">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($orders->hasPages())
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No orders found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if(request('search') || request('status'))
                                Try adjusting your filters or create a new order.
                            @else
                                Get started by creating your first order.
                            @endif
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('orders.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                New Order
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
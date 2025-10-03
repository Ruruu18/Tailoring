<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-start w-full">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <svg class="h-8 w-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                    Admin Dashboard
                </h1>
                <p class="text-gray-600 mt-1 text-lg">Welcome back, {{ Auth::user()->name }}! Here's your business overview.</p>
            </div>
            <div class="ml-8 flex-shrink-0">
                <div class="text-right bg-white bg-opacity-90 backdrop-blur-sm rounded-lg px-4 py-2 shadow-lg">
                    <div id="current-date" class="text-sm font-medium text-gray-900">
                        <!-- Date will be populated by JavaScript -->
                    </div>
                    <div id="current-time" class="text-xs text-gray-500 mt-1">
                        <!-- Time will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="p-6 bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="max-w-7xl mx-auto">
            <!-- Summary Cards Section -->
             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                 <!-- Total Customers Card -->
                 <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-200">
                     <div class="p-6 text-white">
                         <div class="flex items-center justify-between">
                             <div>
                                 <p class="text-blue-100 text-sm font-medium">Total Customers</p>
                                 <p class="text-3xl font-bold mt-2">{{ $totalCustomers }}</p>
                             </div>
                             <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                 <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                 </svg>
                             </div>
                         </div>
                     </div>
                 </div>

                 <!-- Total Orders Card -->
                 <div class="bg-gradient-to-br from-orange-400 to-orange-500 rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-200">
                     <div class="p-6 text-white">
                         <div class="flex items-center justify-between">
                             <div>
                                 <p class="text-orange-100 text-sm font-medium">Total Orders</p>
                                 <p class="text-3xl font-bold mt-2">{{ $totalOrders }}</p>
                             </div>
                             <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                 <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                 </svg>
                             </div>
                         </div>
                     </div>
                 </div>

                <!-- Total Revenue Card -->
                 <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-200">
                     <div class="p-6 text-white">
                         <div class="flex items-center justify-between">
                             <div>
                                 <p class="text-green-100 text-sm font-medium">Total Revenue</p>
                                 <p class="text-3xl font-bold mt-2">₱{{ number_format($totalRevenue, 2) }}</p>
                             </div>
                             <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                 <img src="{{ asset('images/PESO.png') }}" alt="Peso" class="h-8 w-8">
                             </div>
                         </div>
                     </div>
                 </div>

                 <!-- Pending Orders Card -->
                 <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-200">
                     <div class="p-6 text-white">
                         <div class="flex items-center justify-between">
                             <div>
                                 <p class="text-purple-100 text-sm font-medium">Pending Orders</p>
                                 <p class="text-3xl font-bold mt-2">{{ $pendingOrders }}</p>
                             </div>
                             <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                 <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                 </svg>
                             </div>
                         </div>
                     </div>
                 </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                 <!-- Main Content Area -->
                 <div class="lg:col-span-2 space-y-6">
                     <!-- Management Panel -->
                     <div class="bg-white rounded-xl shadow-lg overflow-hidden border-l-4 border-indigo-500">
                         <div class="p-6">
                             <h3 class="text-xl font-bold text-gray-900 mb-2">Management Center</h3>
                             <p class="text-gray-600 text-sm mb-6">Access all administrative functions from here</p>
                             <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                 <a href="{{ route('admin.orders.index') }}" class="group bg-gradient-to-br from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-medium py-5 px-4 rounded-xl transition-all duration-200 flex flex-col items-center space-y-3 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                     <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                         <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                         </svg>
                                     </div>
                                     <span class="text-sm font-semibold text-center">Order Management</span>
                                 </a>
                                 <a href="{{ route('measurements.index') }}" class="group bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium py-5 px-4 rounded-xl transition-all duration-200 flex flex-col items-center space-y-3 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                     <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                         <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21l3-3m-3 3l-3-3m3 3V9a2 2 0 012-2h1m1-4H6a2 2 0 00-2 2v6a2 2 0 002 2h1m10-9.5V17a2 2 0 01-2 2H9.5"></path>
                                         </svg>
                                     </div>
                                     <span class="text-sm font-semibold text-center">Measurement Management</span>
                                 </a>
                                 <a href="{{ route('appointments.index') }}" class="group bg-gradient-to-br from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-medium py-5 px-4 rounded-xl transition-all duration-200 flex flex-col items-center space-y-3 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                     <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                         <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                         </svg>
                                     </div>
                                     <span class="text-sm font-semibold text-center">Appointment Management</span>
                                 </a>
                                 <a href="{{ route('admin.customers.index') }}" class="group bg-gradient-to-br from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-medium py-5 px-4 rounded-xl transition-all duration-200 flex flex-col items-center space-y-3 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                     <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                         <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                         </svg>
                                     </div>
                                     <span class="text-sm font-semibold text-center">User Management</span>
                                 </a>
                             </div>
                         </div>
                     </div>

                    <!-- Recent Orders Widget -->
                     <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                         <div class="p-6">
                             <div class="flex justify-between items-center mb-6">
                                 <h3 class="text-xl font-bold text-gray-900">Recent Orders</h3>
                                 <a href="{{ route('admin.orders.index') }}" class="bg-blue-100 text-blue-600 hover:bg-blue-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">View All Orders</a>
                             </div>
                            @if($recentOrders->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($recentOrders->take(3) as $order)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->order_number }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->user->name ?? 'N/A' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M j, Y') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @php
                                                            $statusClasses = match($order->status) {
                                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                                'in_progress' => 'bg-blue-100 text-blue-800',
                                                                'ready' => 'bg-green-100 text-green-800',
                                                                'completed' => 'bg-gray-100 text-gray-800',
                                                                default => 'bg-red-100 text-red-800'
                                                            };
                                                        @endphp
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClasses }}">
                                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        @if($order->total_amount > 0)
                                                            ₱{{ number_format($order->total_amount, 2) }}
                                                        @else
                                                            <span class="text-yellow-600 text-xs">Pending Quote</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-800 font-medium">View</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">No orders yet</p>
                                    <p class="text-xs text-gray-400">Orders will appear here when customers place them!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                 <div class="space-y-6">
                     <!-- Business Summary Card -->
                     <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                         <div class="p-6">
                             <h3 class="text-xl font-bold text-gray-900 mb-6">Business Summary</h3>
                             <div class="flex items-center space-x-4 mb-6">
                                 <div class="flex-shrink-0">
                                     <div class="h-16 w-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                         <img src="{{ asset('images/ICON.png') }}" alt="Square A Logo" class="w-10 h-10 object-contain">
                                     </div>
                                 </div>
                                 <div class="flex-1">
                                     <h4 class="text-lg font-bold text-gray-900">Square A Tailoring</h4>
                                     <p class="text-sm text-gray-500">Administrator Panel</p>
                                     <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                                 </div>
                             </div>
                             <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                 <div class="flex justify-between items-center mb-2">
                                     <span class="text-sm font-medium text-gray-600">Monthly Revenue:</span>
                                     <span class="text-sm font-bold text-gray-900">₱{{ number_format(\App\Models\Order::whereMonth('created_at', now()->month)->sum('total_amount'), 2) }}</span>
                                 </div>
                                 <div class="flex justify-between items-center">
                                     <span class="text-sm font-medium text-gray-600">Active Customers:</span>
                                     <span class="text-sm font-bold text-gray-900">{{ \App\Models\User::where('role', 'customer')->whereHas('orders', function($q) { $q->whereMonth('created_at', now()->month); })->count() }}</span>
                                 </div>
                             </div>
                             <a href="{{ route('admin.reports.index') }}" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 text-center block transform hover:scale-105">
                                 View Detailed Reports
                             </a>
                         </div>
                     </div>

                     <!-- System Alerts Section -->
                     <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                         <div class="p-6">
                             <div class="flex justify-between items-center mb-6">
                                 <h3 class="text-xl font-bold text-gray-900">System Alerts</h3>
                                 @php
                                     $lowStockCount = \App\Models\InventoryItem::whereRaw('quantity <= min_quantity')->count();
                                     $pendingOrdersCount = \App\Models\Order::where('status', 'pending')->count();
                                     $totalAlerts = $lowStockCount + $pendingOrdersCount;
                                 @endphp
                                 @if($totalAlerts > 0)
                                     <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $totalAlerts }} Alert{{ $totalAlerts > 1 ? 's' : '' }}</span>
                                 @endif
                             </div>

                             <div class="space-y-4">
                                 @if($lowStockCount > 0)
                                     <div class="flex items-start space-x-4 p-4 rounded-xl bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 transition-all duration-200 hover:shadow-md">
                                         <div class="flex-shrink-0 mt-1">
                                             <div class="h-3 w-3 rounded-full bg-gradient-to-r from-red-500 to-pink-500"></div>
                                         </div>
                                         <div class="flex-1 min-w-0">
                                             <p class="text-sm font-bold text-gray-900">Low Stock Alert</p>
                                             <p class="text-sm text-gray-600 mt-1">{{ $lowStockCount }} items need restocking</p>
                                             <a href="{{ route('admin.inventory.low-stock') }}" class="text-xs text-red-600 font-medium mt-2 inline-block hover:text-red-800">View Items →</a>
                                         </div>
                                     </div>
                                 @endif

                                 @if($pendingOrdersCount > 0)
                                     <div class="flex items-start space-x-4 p-4 rounded-xl bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 transition-all duration-200 hover:shadow-md">
                                         <div class="flex-shrink-0 mt-1">
                                             <div class="h-3 w-3 rounded-full bg-gradient-to-r from-yellow-500 to-amber-500"></div>
                                         </div>
                                         <div class="flex-1 min-w-0">
                                             <p class="text-sm font-bold text-gray-900">Pending Orders</p>
                                             <p class="text-sm text-gray-600 mt-1">{{ $pendingOrdersCount }} orders awaiting processing</p>
                                             <a href="{{ route('admin.orders.index') }}?status=pending" class="text-xs text-yellow-600 font-medium mt-2 inline-block hover:text-yellow-800">Process Orders →</a>
                                         </div>
                                     </div>
                                 @endif

                                 @if($totalAlerts == 0)
                                     <div class="text-center py-8">
                                         <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                         </svg>
                                         <p class="mt-2 text-sm text-gray-500">All systems running smoothly</p>
                                         <p class="text-xs text-gray-400">No alerts at this time</p>
                                     </div>
                                 @endif
                             </div>
                         </div>
                     </div>
                 </div>
            </div>
        </div>
    </div>

    <script>
        function updateDateTime() {
            const now = new Date();

            // Format date
            const dateOptions = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                timeZone: 'Asia/Manila'
            };
            const formattedDate = now.toLocaleDateString('en-US', dateOptions);

            // Format time
            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true,
                timeZone: 'Asia/Manila'
            };
            const formattedTime = now.toLocaleTimeString('en-US', timeOptions);

            // Update the DOM elements
            document.getElementById('current-date').textContent = formattedDate;
            document.getElementById('current-time').textContent = formattedTime;
        }

        // Update immediately when page loads
        updateDateTime();

        // Update every second
        setInterval(updateDateTime, 1000);
    </script>
</x-admin-layout>

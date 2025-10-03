<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center w-full space-y-4 sm:space-y-0">
            <div class="min-w-0 flex-1">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 truncate">Welcome back, {{ $user->name }}!</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Here's what's happening with your orders today.</p>
            </div>
            <div class="flex-shrink-0 bg-white bg-opacity-90 backdrop-blur-sm rounded-lg px-3 py-2 shadow-lg">
                <div id="current-date" class="text-xs sm:text-sm font-medium text-gray-900 text-center sm:text-right">
                    <!-- Date will be populated by JavaScript -->
                </div>
                <div id="current-time" class="text-xs text-gray-500 mt-1 text-center sm:text-right">
                    <!-- Time will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </x-slot>

    <div class="p-3 sm:p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Summary Cards Section -->
             <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
                 <!-- Active Orders Card -->
                 <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg overflow-hidden">
                     <div class="p-4 sm:p-6 text-white">
                         <div class="flex items-center justify-between">
                             <div>
                                 <p class="text-blue-100 text-sm font-medium">Active Orders</p>
                                 <p class="text-3xl font-bold mt-2">{{ $activeOrdersCount }}</p>
                             </div>
                             <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                 <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                 </svg>
                             </div>
                         </div>
                     </div>
                 </div>

                 <!-- Pending Payments Card -->
                 <div class="bg-gradient-to-br from-orange-400 to-orange-500 rounded-xl shadow-lg overflow-hidden">
                     <div class="p-4 sm:p-6 text-white">
                         <div class="flex items-center justify-between">
                             <div>
                                 <p class="text-orange-100 text-sm font-medium">Pending Payments</p>
                                 <p class="text-3xl font-bold mt-2">₱{{ number_format($pendingPayments, 2) }}</p>
                             </div>
                             <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                 <img src="{{ asset('images/PESO.png') }}" alt="Peso" class="h-8 w-8">
                             </div>
                         </div>
                     </div>
                 </div>

                <!-- Completed Orders Card -->
                 <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg overflow-hidden">
                     <div class="p-4 sm:p-6 text-white">
                         <div class="flex items-center justify-between">
                             <div>
                                 <p class="text-green-100 text-sm font-medium">Completed Orders</p>
                                 <p class="text-3xl font-bold mt-2">{{ $completedOrdersCount }}</p>
                             </div>
                             <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                 <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                 </svg>
                             </div>
                         </div>
                     </div>
                 </div>

                 <!-- Next Appointment Card -->
                 <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg overflow-hidden">
                     <div class="p-4 sm:p-6 text-white">
                         <div class="flex items-center justify-between">
                             <div>
                                 <p class="text-purple-100 text-sm font-medium">Next Appointment</p>
                                 @if($nextAppointment)
                                     <p class="text-2xl font-bold mt-2">{{ $nextAppointment->appointment_date->format('M j') }}</p>
                                     <p class="text-purple-200 text-sm">{{ $nextAppointment->appointment_date->format('g:i A') }}</p>
                                 @else
                                     <p class="text-2xl font-bold mt-2">None</p>
                                     <p class="text-purple-200 text-sm">No appointments</p>
                                 @endif
                             </div>
                             <div class="bg-white bg-opacity-20 rounded-lg p-3">
                                 <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                 </svg>
                             </div>
                         </div>
                     </div>
                 </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 items-start">
                 <!-- Main Content Area -->
                 <div class="space-y-4 sm:space-y-6">
                     <!-- Quick Actions Panel -->
                     <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                         <div class="p-4 sm:p-6">
                             <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Quick Actions</h3>
                             <div class="grid grid-cols-2 gap-3 sm:gap-4">
                                 <a href="{{ route('orders.create') }}" class="group bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium py-3 sm:py-4 px-2 sm:px-4 rounded-xl transition-all duration-200 flex flex-col items-center space-y-2 sm:space-y-3 transform hover:scale-105 shadow-lg">
                                     <div class="bg-white bg-opacity-20 rounded-lg p-2">
                                         <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                         </svg>
                                     </div>
                                     <span class="text-xs sm:text-sm font-medium text-center">Place New Order</span>
                                 </a>
                                 <a href="{{ $user->measurements ? route('measurements.edit', $user->measurements) : route('measurements.create') }}" class="group bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium py-3 sm:py-4 px-2 sm:px-4 rounded-xl transition-all duration-200 flex flex-col items-center space-y-2 sm:space-y-3 transform hover:scale-105 shadow-lg">
                                     <div class="bg-white bg-opacity-20 rounded-lg p-2">
                                         <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                         </svg>
                                     </div>
                                     <span class="text-xs sm:text-sm font-medium text-center">{{ $user->measurements ? 'Update' : 'Add' }} Measurements</span>
                                 </a>
                                 <a href="{{ route('orders.index') }}" class="group bg-gradient-to-br from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-medium py-3 sm:py-4 px-2 sm:px-4 rounded-xl transition-all duration-200 flex flex-col items-center space-y-2 sm:space-y-3 transform hover:scale-105 shadow-lg">
                                     <div class="bg-white bg-opacity-20 rounded-lg p-2">
                                         <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                         </svg>
                                     </div>
                                     <span class="text-xs sm:text-sm font-medium text-center">Track Order</span>
                                 </a>
                                 <a href="{{ route('appointments.create') }}" class="group bg-gradient-to-br from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-medium py-3 sm:py-4 px-2 sm:px-4 rounded-xl transition-all duration-200 flex flex-col items-center space-y-2 sm:space-y-3 transform hover:scale-105 shadow-lg">
                                     <div class="bg-white bg-opacity-20 rounded-lg p-2">
                                         <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                         </svg>
                                     </div>
                                     <span class="text-xs sm:text-sm font-medium text-center">Schedule Appointment</span>
                                 </a>
                             </div>
                         </div>
                     </div>

                    <!-- Recent Orders Widget -->
                     <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                         <div class="p-4 sm:p-6">
                             <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 space-y-2 sm:space-y-0">
                                 <h3 class="text-lg sm:text-xl font-bold text-gray-900">Recent Orders</h3>
                                 <a href="{{ route('orders.index') }}" class="bg-blue-100 text-blue-600 hover:bg-blue-200 px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors duration-200 text-center">View All Orders</a>
                             </div>
                            @if($recentOrders->count() > 0)
                                <div class="overflow-x-auto -mx-4 sm:mx-0">
                                    <table class="w-full divide-y divide-gray-200 min-w-full">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[80px]">Order ID</th>
                                                <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell min-w-[90px]">Date</th>
                                                <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[60px]">Items</th>
                                                <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[80px]">Status</th>
                                                <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[70px]">Total</th>
                                                <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[60px]">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($recentOrders as $order)
                                                <tr>
                                                    <td class="px-2 sm:px-3 py-2 sm:py-3 whitespace-nowrap text-xs sm:text-sm font-medium text-gray-900">#{{ $order->order_number }}</td>
                                                    <td class="px-2 sm:px-3 py-2 sm:py-3 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden md:table-cell">{{ $order->created_at->format('M j, Y') }}</td>
                                                    <td class="px-2 sm:px-3 py-2 sm:py-3 text-xs sm:text-sm text-gray-500">
                                                        @if(is_array($order->items))
                                                            {{ count($order->items) }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td class="px-2 sm:px-3 py-2 sm:py-3 whitespace-nowrap">
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
                                                            <span class="hidden sm:inline">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                                                            <span class="sm:hidden">
                                                                @if($order->status === 'pending')
                                                                    ⏳
                                                                @elseif($order->status === 'in_progress')
                                                                    🔨
                                                                @elseif($order->status === 'ready')
                                                                    📦
                                                                @elseif($order->status === 'completed')
                                                                    ✅
                                                                @else
                                                                    {{ substr(ucfirst(str_replace('_', ' ', $order->status)), 0, 1) }}
                                                                @endif
                                                            </span>
                                                        </span>
                                                    </td>
                                                    <td class="px-2 sm:px-3 py-2 sm:py-3 whitespace-nowrap text-xs sm:text-sm text-gray-900">
                                                        @if($order->total_amount > 0)
                                                            ₱{{ number_format($order->total_amount, 2) }}
                                                        @else
                                                            <span class="text-yellow-600 text-xs">Quote</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-2 sm:px-3 py-2 sm:py-3 whitespace-nowrap text-xs sm:text-sm">
                                                        <a href="{{ route('orders.track', $order) }}" class="text-blue-600 hover:text-blue-800 font-medium">Track</a>
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
                                    <p class="text-xs text-gray-400">Place your first order to get started!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Notifications Section -->
                 <div class="lg:sticky lg:top-6">
                     <div class="bg-white rounded-xl shadow-lg overflow-hidden {{ $unreadNotificationsCount > 0 ? 'ring-2 ring-blue-200 ring-opacity-50' : '' }}">
                         <div class="p-4 sm:p-6">
                             <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 space-y-2 sm:space-y-0">
                                 <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                                     Notifications
                                     <span id="notification-loading" class="ml-2 hidden">
                                         <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                             <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                             <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                         </svg>
                                     </span>
                                 </h3>
                                 <div class="flex flex-wrap items-center gap-2 sm:space-x-3">
                                     @if($unreadNotificationsCount > 0)
                                         <button id="mark-all-read-dashboard" class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium">
                                             Mark All Read
                                         </button>
                                         <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-red-500 to-red-600 text-white">
                                             {{ $unreadNotificationsCount }} unread
                                         </span>
                                     @endif
                                     <a href="{{ route('notifications.index') }}" class="bg-blue-100 text-blue-600 hover:bg-blue-200 px-2 sm:px-3 py-1 rounded-lg text-xs font-medium transition-colors duration-200">
                                         View All
                                     </a>
                                 </div>
                             </div>
                             @if($recentNotifications->count() > 0)
                                 <div class="space-y-3 sm:space-y-4 max-h-[20rem] sm:max-h-[28rem] overflow-y-auto pr-2" style="scrollbar-width: thin; scrollbar-color: #d1d5db #f3f4f6;">
                                     @foreach($recentNotifications as $notification)
                                         <div class="notification-item flex items-start space-x-3 sm:space-x-4 p-3 sm:p-4 rounded-xl {{ $notification->is_read ? 'bg-gray-50' : 'bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200' }} transition-all duration-200 hover:shadow-md cursor-pointer" data-id="{{ $notification->id }}" onclick="markNotificationAsRead({{ $notification->id }})">
                                             <div class="flex-shrink-0 mt-1">
                                                 <div class="h-6 w-6 sm:h-8 sm:w-8 rounded-full {{ $notification->is_read ? 'bg-gray-100' : 'bg-gradient-to-r from-blue-100 to-indigo-100' }} flex items-center justify-center">
                                                     @if($notification->type === 'order_update')
                                                         <svg class="w-3 h-3 sm:w-4 sm:h-4 {{ $notification->is_read ? 'text-gray-500' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                         </svg>
                                                     @elseif($notification->type === 'payment_update')
                                                         <svg class="w-3 h-3 sm:w-4 sm:h-4 {{ $notification->is_read ? 'text-gray-500' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                         </svg>
                                                     @elseif($notification->type === 'payment_reminder')
                                                         <svg class="w-3 h-3 sm:w-4 sm:h-4 {{ $notification->is_read ? 'text-gray-500' : 'text-yellow-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                         </svg>
                                                     @elseif($notification->type === 'appointment')
                                                         <svg class="w-3 h-3 sm:w-4 sm:h-4 {{ $notification->is_read ? 'text-gray-500' : 'text-purple-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                         </svg>
                                                     @else
                                                         <svg class="w-3 h-3 sm:w-4 sm:h-4 {{ $notification->is_read ? 'text-gray-500' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                         </svg>
                                                     @endif
                                                 </div>
                                             </div>
                                             <div class="flex-1 min-w-0">
                                                 <div class="flex items-start justify-between">
                                                     <div class="flex-1 min-w-0">
                                                         <p class="text-xs sm:text-sm font-bold text-gray-900 truncate">{{ $notification->title }}</p>
                                                         <p class="text-xs sm:text-sm text-gray-600 mt-1 leading-relaxed">{{ Str::limit($notification->message, 80) }}</p>
                                                     </div>
                                                     @if(!$notification->is_read)
                                                         <div class="ml-2 flex-shrink-0">
                                                             <div class="h-2 w-2 bg-blue-500 rounded-full animate-pulse"></div>
                                                         </div>
                                                     @endif
                                                 </div>
                                                 <div class="flex items-center justify-between mt-2 space-x-2">
                                                     <p class="text-xs text-gray-400 font-medium flex-shrink-0">{{ $notification->created_at->diffForHumans() }}</p>
                                                     @if($notification->type)
                                                         <span class="inline-flex items-center px-1 sm:px-2 py-1 rounded-full text-xs font-medium bg-{{ $notification->type_color }}-100 text-{{ $notification->type_color }}-800 truncate">
                                                             <span class="hidden sm:inline">{{ ucfirst(str_replace('_', ' ', $notification->type)) }}</span>
                                                             <span class="sm:hidden">{{ substr(ucfirst(str_replace('_', ' ', $notification->type)), 0, 3) }}</span>
                                                         </span>
                                                     @endif
                                                 </div>
                                             </div>
                                         </div>
                                     @endforeach
                                 </div>
                             @else
                                 <div class="text-center py-8">
                                     <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                         <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h8v-2H4v2zM4 11h8V9H4v2z"></path>
                                         </svg>
                                     </div>
                                     <p class="text-sm font-medium text-gray-500">No notifications</p>
                                     <p class="text-xs text-gray-400 mt-1">You're all caught up!</p>
                                 </div>
                             @endif
                         </div>
                     </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Windows-specific grid fix */
        @media (min-width: 1024px) {
            .lg\:grid-cols-2 {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1.5rem;
            }

            .lg\:grid-cols-2 > * {
                width: 100%;
                min-width: 0;
            }
        }
    </style>

    <script>
        function updateDateTime() {
            // Get current time and format for Philippine timezone
            const now = new Date();

            // Format date (e.g., "Monday, September 15, 2025")
            const dateOptions = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                timeZone: 'Asia/Manila'
            };
            const formattedDate = now.toLocaleDateString('en-US', dateOptions);

            // Format time (e.g., "2:30 PM")
            const timeOptions = {
                hour: 'numeric',
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

        // Notification functions
        function markNotificationAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/read`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
                    if (notificationItem) {
                        notificationItem.classList.remove('bg-gradient-to-r', 'from-blue-50', 'to-indigo-50', 'border', 'border-blue-200');
                        notificationItem.classList.add('bg-gray-50');

                        const indicator = notificationItem.querySelector('.h-3.w-3');
                        if (indicator) {
                            indicator.classList.remove('bg-gradient-to-r', 'from-blue-500', 'to-indigo-500');
                            indicator.classList.add('bg-gray-400');
                        }
                    }

                    // Refresh page to update unread count
                    setTimeout(() => location.reload(), 500);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Mark all notifications as read
        document.addEventListener('DOMContentLoaded', function() {
            const markAllReadBtn = document.getElementById('mark-all-read-dashboard');
            if (markAllReadBtn) {
                markAllReadBtn.addEventListener('click', function() {
                    fetch('/notifications/mark-all-read', {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            }

            // Real-time dashboard updates
            let currentUnreadCount = {{ $unreadNotificationsCount }};
            let currentActiveOrders = {{ $activeOrdersCount }};
            let currentPendingPayments = {{ $pendingPayments }};
            let currentCompletedOrders = {{ $completedOrdersCount }};

            function checkForDashboardUpdates() {
                // Show loading indicator
                const loadingIndicator = document.getElementById('notification-loading');
                if (loadingIndicator) {
                    loadingIndicator.classList.remove('hidden');
                }

                // Check dashboard stats
                fetch('/api/dashboard/stats', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    updateDashboardStats(data);
                })
                .catch(error => {
                    console.error('Error checking dashboard updates:', error);
                });

                // Check notifications
                fetch('/api/notifications/unread-count', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    updateNotifications(data);
                })
                .catch(error => {
                    console.error('Error checking notification updates:', error);
                })
                .finally(() => {
                    // Hide loading indicator
                    const loadingIndicator = document.getElementById('notification-loading');
                    if (loadingIndicator) {
                        loadingIndicator.classList.add('hidden');
                    }
                });
            }

            function updateDashboardStats(data) {
                // Update active orders count
                if (data.active_orders_count !== currentActiveOrders) {
                    updateStatCard('active-orders', data.active_orders_count);
                    currentActiveOrders = data.active_orders_count;
                }

                // Update pending payments
                if (data.pending_payments !== currentPendingPayments) {
                    updateStatCard('pending-payments', '₱' + new Intl.NumberFormat().format(data.pending_payments));
                    currentPendingPayments = data.pending_payments;
                }

                // Update completed orders
                if (data.completed_orders_count !== currentCompletedOrders) {
                    updateStatCard('completed-orders', data.completed_orders_count);
                    currentCompletedOrders = data.completed_orders_count;
                }
            }

            function updateStatCard(cardType, newValue) {
                let selector;
                switch(cardType) {
                    case 'active-orders':
                        selector = '.bg-gradient-to-br.from-blue-500 .text-3xl.font-bold';
                        break;
                    case 'pending-payments':
                        selector = '.bg-gradient-to-br.from-orange-400 .text-3xl.font-bold';
                        break;
                    case 'completed-orders':
                        selector = '.bg-gradient-to-br.from-green-500 .text-3xl.font-bold';
                        break;
                }

                const element = document.querySelector(selector);
                if (element) {
                    element.textContent = newValue;

                    // Add a subtle animation to indicate update
                    element.parentElement.classList.add('animate-pulse');
                    setTimeout(() => {
                        element.parentElement.classList.remove('animate-pulse');
                    }, 1000);
                }
            }

            function updateNotifications(data) {
                if (data.unread_count !== currentUnreadCount) {
                    updateNotificationCount(data.unread_count);

                    // Show new notification badge if count increased
                    if (data.unread_count > currentUnreadCount) {
                        showNewNotificationAlert();
                    }

                    currentUnreadCount = data.unread_count;
                }
            }

            function updateNotificationCount(count) {
                const unreadBadge = document.querySelector('.bg-gradient-to-r.from-red-500.to-red-600');
                const markAllButton = document.getElementById('mark-all-read-dashboard');

                if (count > 0) {
                    if (unreadBadge) {
                        unreadBadge.textContent = count + ' unread';
                    }
                    if (markAllButton && markAllButton.style.display === 'none') {
                        markAllButton.style.display = 'inline-block';
                    }
                } else {
                    if (unreadBadge) {
                        unreadBadge.style.display = 'none';
                    }
                    if (markAllButton) {
                        markAllButton.style.display = 'none';
                    }
                }
            }

            function showNewNotificationAlert() {
                const alert = document.createElement('div');
                alert.className = 'fixed top-4 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-bounce';
                alert.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h8v-2H4v2zM4 11h8V9H4v2z"></path>
                        </svg>
                        <span class="font-medium">New notification received!</span>
                    </div>
                `;

                document.body.appendChild(alert);

                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 3000);
            }

            // Start polling every 45 seconds (longer interval for dashboard)
            setInterval(checkForDashboardUpdates, 45000);

            // Check when page becomes visible
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    checkForDashboardUpdates();
                }
            });

            // Check when user returns to page
            window.addEventListener('focus', function() {
                checkForDashboardUpdates();
            });
        });
    </script>
</x-app-layout>

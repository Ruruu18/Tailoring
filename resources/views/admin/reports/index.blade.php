<x-admin-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                📊 Business Summary
            </h2>
            <p class="text-gray-600 mt-1">Your business overview and key performance metrics</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Period Selector -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">📅 Quick Overview</h3>
                <select id="periodSelect" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 min-w-32">
                    <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="7days" {{ $period == '7days' ? 'selected' : '' }}>Last 7 days</option>
                    <option value="30days" {{ $period == '30days' ? 'selected' : '' }}>Last 30 days</option>
                    <option value="all" {{ $period == 'all' ? 'selected' : '' }}>All time</option>
                </select>
            </div>

            <!-- Key Business Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total Revenue -->
                <div class="bg-white p-5 rounded-lg shadow border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">💰 Total Revenue</p>
                            <p class="text-2xl font-bold text-green-600">₱{{ number_format($stats['total_revenue'], 2) }}</p>
                            <p class="text-xs text-gray-500 mt-1">Money earned</p>
                        </div>
                        <div class="text-green-500">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="bg-white p-5 rounded-lg shadow border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">📋 Total Orders</p>
                            <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_orders']) }}</p>
                            <p class="text-xs text-gray-500 mt-1">All orders received</p>
                        </div>
                        <div class="text-blue-500">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Completed Orders -->
                <div class="bg-white p-5 rounded-lg shadow border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">✅ Completed</p>
                            <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['completed_orders']) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $stats['total_orders'] > 0 ? number_format(($stats['completed_orders'] / $stats['total_orders']) * 100, 1) : 0 }}% success rate</p>
                        </div>
                        <div class="text-purple-500">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pending Orders -->
                <div class="bg-white p-5 rounded-lg shadow border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">⏳ Pending</p>
                            <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending_orders']) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $stats['pending_orders'] > 0 ? 'Needs attention' : 'All caught up!' }}</p>
                        </div>
                        <div class="text-yellow-500">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">📋 Orders</h3>
                            <p class="text-sm text-gray-600">View detailed order analysis and trends</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.reports.orders') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-flex items-center">
                            View Orders Report
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">💰 Payments</h3>
                            <p class="text-sm text-gray-600">Track payment transactions and cash flow</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.reports.payments') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-flex items-center">
                            View Payments Report
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">📦 Inventory</h3>
                            <p class="text-sm text-gray-600">Monitor stock levels and material usage</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.reports.inventory') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-flex items-center">
                            View Inventory Report
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Summary -->
            @if($stats['pending_orders'] > 0 || $stats['low_stock_items'] > 0)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">⚠️ Attention Needed</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            @if($stats['pending_orders'] > 0)
                                <p>• {{ $stats['pending_orders'] }} pending orders need processing</p>
                            @endif
                            @if($stats['low_stock_items'] > 0)
                                <p>• {{ $stats['low_stock_items'] }} items are running low on stock</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">✅ All Good!</h3>
                        <div class="mt-2 text-sm text-green-700">
                            <p>No pending orders or low stock items. Everything is running smoothly!</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('periodSelect').addEventListener('change', function() {
            window.location.href = '{{ route("admin.reports.index") }}?period=' + this.value;
        });
    </script>
</x-admin-layout>
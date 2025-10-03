<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <div class="flex-1">
                <div class="space-y-2">
                    <div>
                        <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Back to Customers
                        </a>
                    </div>
                    <div>
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            Customer Details
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">{{ $customer->name }} - Member since {{ $customer->created_at->format('M Y') }}</p>
                    </div>
                </div>
            </div>
            <div class="ml-auto">
                <a href="{{ route('admin.customers.edit', $customer) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                    Edit Customer
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Customer Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Info -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <p class="text-sm text-gray-900">{{ $customer->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <p class="text-sm text-gray-900">{{ $customer->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <p class="text-sm text-gray-900">{{ $customer->phone ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Member Since</label>
                                <p class="text-sm text-gray-900">{{ $customer->created_at->format('F d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Orders</h3>
                        @if($customer->orders->count() > 0)
                            <div class="space-y-4">
                                @foreach($customer->orders->take(5) as $order)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-medium text-gray-900">#{{ $order->order_number }}</h4>
                                                <p class="text-sm text-gray-600">{{ $order->design_type === 'pre_made' ? 'Pre-made Design' : 'Custom Design' }}</p>
                                                <p class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{
                                                    $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                                    ($order->status === 'quoted' ? 'bg-orange-100 text-orange-800' :
                                                    ($order->status === 'confirmed' ? 'bg-purple-100 text-purple-800' :
                                                    ($order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800')))
                                                }}">
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                                @if($order->total_amount > 0)
                                                    <div class="text-sm font-medium text-gray-900 mt-1">₱{{ number_format($order->total_amount, 2) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @if($customer->orders->count() > 5)
                                    <div class="text-center">
                                        <a href="{{ route('admin.orders.index', ['customer' => $customer->id]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View All Orders ({{ $customer->orders->count() }}) →
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <h4 class="mt-2 text-sm font-medium text-gray-900">No orders yet</h4>
                                <p class="mt-1 text-sm text-gray-500">This customer hasn't placed any orders.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Recent Appointments -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Appointments</h3>
                        @if($customer->appointments->count() > 0)
                            <div class="space-y-4">
                                @foreach($customer->appointments->take(5) as $appointment)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $appointment->service_type)) }}</h4>
                                                <p class="text-sm text-gray-600">{{ $appointment->appointment_date->format('F d, Y \a\t g:i A') }}</p>
                                                @if($appointment->notes)
                                                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($appointment->notes, 100) }}</p>
                                                @endif
                                            </div>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{
                                                $appointment->status === 'scheduled' ? 'bg-yellow-100 text-yellow-800' :
                                                ($appointment->status === 'confirmed' ? 'bg-blue-100 text-blue-800' :
                                                ($appointment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'))
                                            }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                                @if($customer->appointments->count() > 5)
                                    <div class="text-center">
                                        <a href="{{ route('admin.appointments.index') }}?customer={{ $customer->id }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View All Appointments ({{ $customer->appointments->count() }}) →
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4h-8zM6 21h12a2 2 0 002-2v-8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <h4 class="mt-2 text-sm font-medium text-gray-900">No appointments yet</h4>
                                <p class="mt-1 text-sm text-gray-500">This customer hasn't scheduled any appointments.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                        <div class="space-y-4">
                            <div>
                                <div class="text-2xl font-bold text-blue-600">{{ $customer->orders->count() }}</div>
                                <div class="text-sm text-gray-600">Total Orders</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-green-600">₱{{ number_format($customer->orders->sum('total_amount'), 2) }}</div>
                                <div class="text-sm text-gray-600">Total Spent</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-purple-600">{{ $customer->appointments->count() }}</div>
                                <div class="text-sm text-gray-600">Appointments</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-orange-600">{{ $customer->measurements->count() }}</div>
                                <div class="text-sm text-gray-600">Measurements</div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.appointments.create') }}?user_id={{ $customer->id }}" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                                Schedule Appointment
                            </a>
                            <a href="{{ route('admin.measurements.create') }}?user_id={{ $customer->id }}" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                                Add Measurements
                            </a>
                        </div>
                    </div>

                    <!-- Account Actions -->
                    @if($customer->orders->count() == 0)
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Actions</h3>
                            <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                    Delete Customer
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
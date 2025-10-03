<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <div class="flex-1">
                <div class="space-y-2">
                    <div>
                        <a href="{{ route('admin.appointments.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Back to Appointments
                        </a>
                    </div>
                    <div>
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            Appointment Details
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">{{ $appointment->appointment_date->format('F d, Y \a\t g:i A') }}</p>
                    </div>
                </div>
            </div>
            <div class="ml-auto">
                <a href="{{ route('admin.appointments.edit', $appointment) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                    Edit Appointment
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Appointment Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Main Information -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex justify-between items-start mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Appointment Information</h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{
                                $appointment->status === 'scheduled' ? 'bg-yellow-100 text-yellow-800' :
                                ($appointment->status === 'confirmed' ? 'bg-blue-100 text-blue-800' :
                                ($appointment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'))
                            }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date & Time</label>
                                <p class="text-sm text-gray-900">{{ $appointment->appointment_date->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Service Type</label>
                                <p class="text-sm text-gray-900">{{ ucwords(str_replace('_', ' ', $appointment->service_type)) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                                <p class="text-sm text-gray-900">{{ $appointment->created_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                                <p class="text-sm text-gray-900">{{ $appointment->updated_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>

                        @if($appointment->notes)
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-900">{{ $appointment->notes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Related Order -->
                    @if($appointment->order)
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Related Order</h3>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium text-gray-900">Order #{{ $appointment->order->order_number }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ $appointment->order->design_type === 'pre_made' ? 'Pre-made Design' : 'Custom Design' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">Created {{ $appointment->order->created_at->format('M d, Y') }}</p>

                                        @if($appointment->order->total_amount > 0)
                                            <div class="mt-2 text-sm">
                                                <span class="text-gray-600">Total: </span>
                                                <span class="font-medium text-gray-900">₱{{ number_format($appointment->order->total_amount, 2) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col items-end space-y-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{
                                            $appointment->order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                            ($appointment->order->status === 'quoted' ? 'bg-orange-100 text-orange-800' :
                                            ($appointment->order->status === 'confirmed' ? 'bg-purple-100 text-purple-800' :
                                            ($appointment->order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800')))
                                        }}">
                                            {{ ucfirst(str_replace('_', ' ', $appointment->order->status)) }}
                                        </span>
                                        <a href="{{ route('admin.orders.show', $appointment->order) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View Order →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Customer Information -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <p class="text-sm text-gray-900">{{ $appointment->user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="text-sm text-gray-900">{{ $appointment->user->email }}</p>
                            </div>
                            @if($appointment->user->phone)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                                    <p class="text-sm text-gray-900">{{ $appointment->user->phone }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.customers.show', $appointment->user) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View Customer Profile →
                            </a>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            @if($appointment->status === 'scheduled')
                                <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="user_id" value="{{ $appointment->user_id }}">
                                    <input type="hidden" name="order_id" value="{{ $appointment->order_id }}">
                                    <input type="hidden" name="appointment_date" value="{{ $appointment->appointment_date->format('Y-m-d') }}">
                                    <input type="hidden" name="appointment_time" value="{{ $appointment->appointment_date->format('H:i') }}">
                                    <input type="hidden" name="service_type" value="{{ $appointment->service_type }}">
                                    <input type="hidden" name="status" value="confirmed">
                                    <input type="hidden" name="notes" value="{{ $appointment->notes }}">
                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                        Confirm Appointment
                                    </button>
                                </form>
                            @endif

                            @if(in_array($appointment->status, ['scheduled', 'confirmed']))
                                <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="user_id" value="{{ $appointment->user_id }}">
                                    <input type="hidden" name="order_id" value="{{ $appointment->order_id }}">
                                    <input type="hidden" name="appointment_date" value="{{ $appointment->appointment_date->format('Y-m-d') }}">
                                    <input type="hidden" name="appointment_time" value="{{ $appointment->appointment_date->format('H:i') }}">
                                    <input type="hidden" name="service_type" value="{{ $appointment->service_type }}">
                                    <input type="hidden" name="status" value="completed">
                                    <input type="hidden" name="notes" value="{{ $appointment->notes }}">
                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                        Mark as Completed
                                    </button>
                                </form>

                                <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="user_id" value="{{ $appointment->user_id }}">
                                    <input type="hidden" name="order_id" value="{{ $appointment->order_id }}">
                                    <input type="hidden" name="appointment_date" value="{{ $appointment->appointment_date->format('Y-m-d') }}">
                                    <input type="hidden" name="appointment_time" value="{{ $appointment->appointment_date->format('H:i') }}">
                                    <input type="hidden" name="service_type" value="{{ $appointment->service_type }}">
                                    <input type="hidden" name="status" value="cancelled">
                                    <input type="hidden" name="notes" value="{{ $appointment->notes }}">
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                        Cancel Appointment
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this appointment? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                    Delete Appointment
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
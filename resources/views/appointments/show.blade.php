<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <div>
                <a href="{{ route('appointments.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Appointments
                </a>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Appointment Details</h1>
                <p class="text-sm text-gray-600 mt-1">{{ $appointment->appointment_date->format('F d, Y \a\t g:i A') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Appointment Details -->
                <div class="lg:col-span-2">
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
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{
                                        $appointment->service_type === 'consultation' ? 'bg-blue-100 text-blue-800' :
                                        ($appointment->service_type === 'fitting' ? 'bg-green-100 text-green-800' :
                                        ($appointment->service_type === 'measurement' ? 'bg-purple-100 text-purple-800' :
                                        ($appointment->service_type === 'pickup' ? 'bg-yellow-100 text-yellow-800' : 'bg-indigo-100 text-indigo-800')))
                                    }}">
                                        {{ ucwords(str_replace('_', ' ', $appointment->service_type)) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled</label>
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
                        <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Related Order</h3>
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
                                        <a href="{{ route('orders.show', $appointment->order) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
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
                    <!-- Status Information -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Information</h3>
                        <div class="space-y-3">
                            @if($appointment->status === 'scheduled')
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-yellow-800">Appointment Scheduled</span>
                                    </div>
                                    <p class="text-xs text-yellow-700 mt-1">Your appointment is scheduled and awaiting confirmation.</p>
                                </div>
                            @elseif($appointment->status === 'confirmed')
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-blue-800">Appointment Confirmed</span>
                                    </div>
                                    <p class="text-xs text-blue-700 mt-1">Your appointment has been confirmed. Please arrive on time.</p>
                                </div>
                            @elseif($appointment->status === 'completed')
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-green-800">Appointment Completed</span>
                                    </div>
                                    <p class="text-xs text-green-700 mt-1">This appointment has been completed successfully.</p>
                                </div>
                            @else
                                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-red-800">Appointment {{ ucfirst($appointment->status) }}</span>
                                    </div>
                                    <p class="text-xs text-red-700 mt-1">This appointment has been cancelled.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    @if($appointment->status === 'scheduled' && $appointment->appointment_date > now())
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <a href="{{ route('appointments.edit', $appointment) }}" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center block">
                                    Edit Appointment
                                </a>

                                <form action="{{ route('appointments.cancel', $appointment) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                        Cancel Appointment
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Guidelines -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Guidelines</h3>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Arrive 5-10 minutes early
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Bring reference materials
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Wear appropriate undergarments
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                24-hour rescheduling notice required
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
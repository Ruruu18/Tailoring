<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center w-full space-y-4 sm:space-y-0">
            <div class="min-w-0 flex-1">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">My Appointments</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Schedule and manage your tailoring appointments.</p>
            </div>
            <div class="flex-shrink-0">
                <!-- Add New Appointment Button -->
                <a href="{{ route('appointments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center w-full sm:w-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="hidden sm:inline">Schedule Appointment</span>
                    <span class="sm:hidden">Schedule</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filters -->
            @if(isset($upcomingAppointments) && $upcomingAppointments->count() > 0)
                <div class="bg-white overflow-hidden shadow-xl rounded-lg mb-4 sm:mb-6">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                        <h3 class="text-base sm:text-lg font-medium text-gray-900">Upcoming Appointments</h3>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                            @foreach($upcomingAppointments as $appointment)
                                <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow duration-200">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-2 gap-2">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{
                                            isset($appointment->service_type) && $appointment->service_type === 'consultation' ? 'bg-blue-100 text-blue-800' :
                                            (isset($appointment->service_type) && $appointment->service_type === 'fitting' ? 'bg-green-100 text-green-800' :
                                            (isset($appointment->service_type) && $appointment->service_type === 'measurement' ? 'bg-purple-100 text-purple-800' :
                                            (isset($appointment->service_type) && $appointment->service_type === 'pickup' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')))
                                        }} self-start">
                                            <span class="hidden sm:inline">{{ isset($appointment->service_type) ? ucfirst($appointment->service_type) : 'N/A' }}</span>
                                            <span class="sm:hidden">
                                                @if(isset($appointment->service_type))
                                                    @if($appointment->service_type === 'measurement')
                                                        Measurement
                                                    @elseif($appointment->service_type === 'consultation')
                                                        Consultation
                                                    @elseif($appointment->service_type === 'fitting')
                                                        Fitting
                                                    @elseif($appointment->service_type === 'pickup')
                                                        Pickup
                                                    @else
                                                        {{ ucfirst($appointment->service_type) }}
                                                    @endif
                                                @else
                                                    N/A
                                                @endif
                                            </span>
                                        </span>
                                        <span class="text-xs text-gray-500 flex-shrink-0">
                                            {{ isset($appointment->appointment_date) ? $appointment->appointment_date->diffForHumans() : 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="text-xs sm:text-sm text-gray-900 font-medium mb-1">
                                        <span class="hidden sm:inline">{{ isset($appointment->appointment_date) ? $appointment->appointment_date->format('F j, Y') : 'N/A' }}</span>
                                        <span class="sm:hidden">{{ isset($appointment->appointment_date) ? $appointment->appointment_date->format('M j, Y') : 'N/A' }}</span>
                                    </div>
                                    <div class="text-xs sm:text-sm text-gray-600 mb-2">
                                        {{ isset($appointment->appointment_date) ? $appointment->appointment_date->format('g:i A') : 'N/A' }}
                                    </div>
                                    @if(isset($appointment->order) && $appointment->order)
                                        <div class="text-xs text-gray-500 mb-2">
                                            Order: #{{ $appointment->order->order_number ?? 'N/A' }}
                                        </div>
                                    @endif
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('appointments.show', $appointment) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                            View
                                        </a>
                                        @if(isset($appointment->status) && isset($appointment->appointment_date) && $appointment->status === 'scheduled' && $appointment->appointment_date > now())
                                            <a href="{{ route('appointments.edit', $appointment) }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">
                                                Edit
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- All Appointments -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900">All Appointments</h3>
                </div>
                @if(isset($appointments) && $appointments->count() > 0)
                    <div class="overflow-x-auto px-0">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="pl-4 pr-3 sm:pl-6 sm:pr-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">Date & Time</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[90px]">Service</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell min-w-[80px]">Order</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[80px]">Status</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[80px]">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($appointments as $appointment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="pl-4 pr-3 sm:pl-6 sm:pr-6 py-3 sm:py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ isset($appointment->appointment_date) ? $appointment->appointment_date->format('M j, Y') : 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-600 font-medium">
                                                {{ isset($appointment->appointment_date) ? $appointment->appointment_date->format('g:i A') : 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{
                                            isset($appointment->service_type) && $appointment->service_type === 'consultation' ? 'bg-blue-100 text-blue-800' :
                                            (isset($appointment->service_type) && $appointment->service_type === 'fitting' ? 'bg-green-100 text-green-800' :
                                            (isset($appointment->service_type) && $appointment->service_type === 'measurement' ? 'bg-purple-100 text-purple-800' :
                                            (isset($appointment->service_type) && $appointment->service_type === 'pickup' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')))
                                        }}">
                                                <span class="hidden sm:inline">{{ isset($appointment->service_type) ? ucfirst($appointment->service_type) : 'N/A' }}</span>
                                                <span class="sm:hidden">
                                                    @if(isset($appointment->service_type))
                                                        @if($appointment->service_type === 'measurement')
                                                            Measure
                                                        @elseif($appointment->service_type === 'consultation')
                                                            Consult
                                                        @elseif($appointment->service_type === 'fitting')
                                                            Fit
                                                        @elseif($appointment->service_type === 'pickup')
                                                            Pickup
                                                        @else
                                                            {{ substr(ucfirst($appointment->service_type), 0, 6) }}
                                                        @endif
                                                    @else
                                                        N/A
                                                    @endif
                                                </span>
                                            </span>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden md:table-cell">
                                            @if(isset($appointment->order) && $appointment->order)
                                                <a href="{{ route('orders.show', $appointment->order) }}" class="text-blue-600 hover:text-blue-800">
                                                    #{{ $appointment->order->order_number ?? 'N/A' }}
                                                </a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{
                                            isset($appointment->status) && $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                            (isset($appointment->status) && $appointment->status === 'confirmed' ? 'bg-green-100 text-green-800' :
                                            (isset($appointment->status) && $appointment->status === 'completed' ? 'bg-gray-100 text-gray-800' :
                                            (isset($appointment->status) && $appointment->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')))
                                        }}">
                                                <span class="hidden sm:inline">{{ isset($appointment->status) ? ucfirst($appointment->status) : 'N/A' }}</span>
                                                <span class="sm:hidden">
                                                    @if(isset($appointment->status))
                                                        @if($appointment->status === 'confirmed')
                                                            ✓
                                                        @elseif($appointment->status === 'pending')
                                                            ⏳
                                                        @elseif($appointment->status === 'completed')
                                                            ✅
                                                        @elseif($appointment->status === 'cancelled')
                                                            ✖
                                                        @else
                                                            {{ substr(ucfirst($appointment->status), 0, 1) }}
                                                        @endif
                                                    @else
                                                        N
                                                    @endif
                                                </span>
                                            </span>
                                        </td>
                                        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium">
                                            <div class="flex flex-wrap gap-2">
                                                <a href="{{ route('appointments.show', $appointment) }}" class="text-blue-600 hover:text-blue-800">View</a>
                                                @if(isset($appointment->status) && isset($appointment->appointment_date) && $appointment->status === 'scheduled' && $appointment->appointment_date > now())
                                                    <a href="{{ route('appointments.edit', $appointment) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                                                    <form action="{{ route('appointments.cancel', $appointment) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-red-600 hover:text-red-800">Cancel</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                        {{ $appointments->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No appointments scheduled</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by scheduling your first appointment.</p>
                        <div class="mt-6">
                            <a href="{{ route('appointments.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Schedule Appointment
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
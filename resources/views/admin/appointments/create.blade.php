<x-admin-layout>
    <x-slot name="header">
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
                    Create New Appointment
                </h2>
                <p class="text-sm text-gray-600 mt-1">Schedule a new appointment for a customer</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.appointments.store') }}" method="POST" space-y-6>
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Customer Selection -->
                            <div class="md:col-span-2">
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Customer *</label>
                                <select name="user_id" id="user_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Select a customer</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Order Selection (Optional) -->
                            <div class="md:col-span-2">
                                <label for="order_id" class="block text-sm font-medium text-gray-700 mb-2">Related Order (Optional)</label>
                                <select name="order_id" id="order_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">No related order</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                            #{{ $order->order_number }} - {{ $order->user->name }} ({{ ucfirst(str_replace('_', ' ', $order->status)) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('order_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Appointment Date -->
                            <div>
                                <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                                <input type="date"
                                       name="appointment_date"
                                       id="appointment_date"
                                       value="{{ old('appointment_date') }}"
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                                @error('appointment_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Appointment Time -->
                            <div>
                                <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-2">Time *</label>
                                <select name="appointment_time" id="appointment_time" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Select time</option>
                                    @foreach($availableSlots as $slot)
                                        <option value="{{ $slot }}" {{ old('appointment_time') == $slot ? 'selected' : '' }}>
                                            {{ date('g:i A', strtotime($slot)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('appointment_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Service Type -->
                            <div>
                                <label for="service_type" class="block text-sm font-medium text-gray-700 mb-2">Service Type *</label>
                                <select name="service_type" id="service_type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Select service type</option>
                                    <option value="consultation" {{ old('service_type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                    <option value="fitting" {{ old('service_type') == 'fitting' ? 'selected' : '' }}>Fitting</option>
                                    <option value="measurement" {{ old('service_type') == 'measurement' ? 'selected' : '' }}>Measurement</option>
                                    <option value="pickup" {{ old('service_type') == 'pickup' ? 'selected' : '' }}>Pickup</option>
                                    <option value="delivery" {{ old('service_type') == 'delivery' ? 'selected' : '' }}>Delivery</option>
                                </select>
                                @error('service_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="no_show" {{ old('status') == 'no_show' ? 'selected' : '' }}>No Show</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                <textarea name="notes"
                                          id="notes"
                                          rows="4"
                                          class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                          placeholder="Any additional notes for this appointment...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.appointments.index') }}"
                               class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition duration-200">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition duration-200">
                                Create Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-filter orders by selected customer
        document.getElementById('user_id').addEventListener('change', function() {
            const userId = this.value;
            const orderSelect = document.getElementById('order_id');
            const options = orderSelect.querySelectorAll('option[value!=""]');

            // Show all options first
            options.forEach(option => {
                option.style.display = 'block';
            });

            if (userId) {
                // Filter options to show only orders from selected customer
                const userOrders = @json($orders->groupBy('user_id'));
                const availableOrders = userOrders[userId] || [];

                options.forEach(option => {
                    const orderId = option.value;
                    const orderExists = availableOrders.some(order => order.id == orderId);
                    option.style.display = orderExists ? 'block' : 'none';
                });

                // Reset selection if current selection is not available
                if (orderSelect.value && !availableOrders.some(order => order.id == orderSelect.value)) {
                    orderSelect.value = '';
                }
            }
        });
    </script>
</x-admin-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <div>
                <a href="{{ route('appointments.show', $appointment) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Appointment
                </a>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Edit Appointment</h1>
                <p class="text-sm text-gray-600">Update your appointment details</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form action="{{ route('appointments.update', $appointment) }}" method="POST" space-y-6>
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Service Type -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-3">Service Type *</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    <div class="relative">
                                        <input type="radio" name="service_type" value="consultation" id="consultation"
                                               class="sr-only" {{ old('service_type', $appointment->service_type) == 'consultation' ? 'checked' : '' }} required>
                                        <label for="consultation" class="service-option flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition-colors duration-200 {{ old('service_type', $appointment->service_type) == 'consultation' ? 'border-blue-500 bg-blue-50' : '' }}">
                                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            <div>
                                                <span class="font-medium text-gray-900">Consultation</span>
                                                <p class="text-xs text-gray-500">Discuss requirements</p>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="relative">
                                        <input type="radio" name="service_type" value="fitting" id="fitting"
                                               class="sr-only" {{ old('service_type', $appointment->service_type) == 'fitting' ? 'checked' : '' }}>
                                        <label for="fitting" class="service-option flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition-colors duration-200 {{ old('service_type', $appointment->service_type) == 'fitting' ? 'border-blue-500 bg-blue-50' : '' }}">
                                            <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <div>
                                                <span class="font-medium text-gray-900">Fitting</span>
                                                <p class="text-xs text-gray-500">Try on garments</p>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="relative">
                                        <input type="radio" name="service_type" value="measurement" id="measurement"
                                               class="sr-only" {{ old('service_type', $appointment->service_type) == 'measurement' ? 'checked' : '' }}>
                                        <label for="measurement" class="service-option flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition-colors duration-200 {{ old('service_type', $appointment->service_type) == 'measurement' ? 'border-blue-500 bg-blue-50' : '' }}">
                                            <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                            <div>
                                                <span class="font-medium text-gray-900">Measurement</span>
                                                <p class="text-xs text-gray-500">Body measurements</p>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="relative">
                                        <input type="radio" name="service_type" value="pickup" id="pickup"
                                               class="sr-only" {{ old('service_type', $appointment->service_type) == 'pickup' ? 'checked' : '' }}>
                                        <label for="pickup" class="service-option flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition-colors duration-200 {{ old('service_type', $appointment->service_type) == 'pickup' ? 'border-blue-500 bg-blue-50' : '' }}">
                                            <svg class="w-5 h-5 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h1.586a1 1 0 01.707.293l1.414 1.414a1 1 0 00.707.293H15a2 2 0 012 2v2M5 8v10a2 2 0 002 2h10a2 2 0 002-2V10m-9 4h4"></path>
                                            </svg>
                                            <div>
                                                <span class="font-medium text-gray-900">Pickup</span>
                                                <p class="text-xs text-gray-500">Collect order</p>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="relative">
                                        <input type="radio" name="service_type" value="delivery" id="delivery"
                                               class="sr-only" {{ old('service_type', $appointment->service_type) == 'delivery' ? 'checked' : '' }}>
                                        <label for="delivery" class="service-option flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition-colors duration-200 {{ old('service_type', $appointment->service_type) == 'delivery' ? 'border-blue-500 bg-blue-50' : '' }}">
                                            <svg class="w-5 h-5 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                            <div>
                                                <span class="font-medium text-gray-900">Delivery</span>
                                                <p class="text-xs text-gray-500">Schedule delivery</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                @error('service_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Appointment Date -->
                            <div>
                                <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                                <input type="date"
                                       name="appointment_date"
                                       id="appointment_date"
                                       value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                                <p class="text-xs text-gray-500 mt-1">Monday to Friday only</p>
                                @error('appointment_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Appointment Time -->
                            <div>
                                <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-2">Time *</label>
                                <select name="appointment_time" id="appointment_time" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @foreach($availableSlots as $slot)
                                        <option value="{{ $slot }}" {{ (old('appointment_time', $appointment->appointment_date->format('H:i')) == $slot) ? 'selected' : '' }}>
                                            {{ date('g:i A', strtotime($slot)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">9:00 AM - 5:00 PM</p>
                                @error('appointment_time')
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
                                          placeholder="Any additional notes for this appointment...">{{ old('notes', $appointment->notes) }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('appointments.show', $appointment) }}"
                               class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition duration-200">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition duration-200">
                                Update Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Handle service type selection styling
        document.querySelectorAll('input[name="service_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Reset all labels
                document.querySelectorAll('.service-option').forEach(label => {
                    label.classList.remove('border-blue-500', 'bg-blue-50');
                    label.classList.add('border-gray-200');
                });

                // Highlight selected option
                if (this.checked) {
                    const label = document.querySelector(`label[for="${this.id}"]`);
                    label.classList.remove('border-gray-200');
                    label.classList.add('border-blue-500', 'bg-blue-50');
                }
            });
        });

        // Disable weekends in date picker
        document.querySelector('input[name="appointment_date"]').addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const dayOfWeek = selectedDate.getDay();

            if (dayOfWeek === 0 || dayOfWeek === 6) { // Sunday = 0, Saturday = 6
                alert('Appointments are only available Monday through Friday. Please select a weekday.');
                this.value = '';
            }
        });
    </script>
</x-app-layout>
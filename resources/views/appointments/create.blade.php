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
                <h1 class="text-xl font-bold text-gray-900">Schedule Appointment</h1>
                <p class="text-sm text-gray-600">Book a consultation, fitting, or measurement session.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form action="{{ route('appointments.store') }}" method="POST" class="p-6">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column: Service & Schedule Selection -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Service Type *</h3>
                            <div class="grid grid-cols-1 gap-3 mb-6">
                                <div class="relative">
                                    <input type="radio" name="service_type" value="consultation" id="consultation" class="sr-only" required {{ old('service_type') == 'consultation' ? 'checked' : '' }}>
                                    <label for="consultation" class="service-option flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition-colors duration-200">
                                        <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        <div>
                                            <span class="font-medium text-gray-900">Consultation</span>
                                            <p class="text-xs text-gray-500">Discuss requirements and design</p>
                                        </div>
                                    </label>
                                </div>

                                <div class="relative">
                                    <input type="radio" name="service_type" value="fitting" id="fitting" class="sr-only" {{ old('service_type') == 'fitting' ? 'checked' : '' }}>
                                    <label for="fitting" class="service-option flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition-colors duration-200">
                                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <div>
                                            <span class="font-medium text-gray-900">Fitting</span>
                                            <p class="text-xs text-gray-500">Try on and adjust garments</p>
                                        </div>
                                    </label>
                                </div>

                                <div class="relative">
                                    <input type="radio" name="service_type" value="measurement" id="measurement" class="sr-only" {{ old('service_type') == 'measurement' ? 'checked' : '' }}>
                                    <label for="measurement" class="service-option flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition-colors duration-200">
                                        <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        <div>
                                            <span class="font-medium text-gray-900">Measurement</span>
                                            <p class="text-xs text-gray-500">Professional body measurements</p>
                                        </div>
                                    </label>
                                </div>

                                <div class="relative">
                                    <input type="radio" name="service_type" value="pickup" id="pickup" class="sr-only" {{ old('service_type') == 'pickup' ? 'checked' : '' }}>
                                    <label for="pickup" class="service-option flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition-colors duration-200">
                                        <svg class="w-5 h-5 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h1.586a1 1 0 01.707.293l1.414 1.414a1 1 0 00.707.293H15a2 2 0 012 2v2M5 8v10a2 2 0 002 2h10a2 2 0 002-2V10m-9 4h4"></path>
                                        </svg>
                                        <div>
                                            <span class="font-medium text-gray-900">Pickup</span>
                                            <p class="text-xs text-gray-500">Collect completed order</p>
                                        </div>
                                    </label>
                                </div>

                                <div class="relative">
                                    <input type="radio" name="service_type" value="delivery" id="delivery" class="sr-only" {{ old('service_type') == 'delivery' ? 'checked' : '' }}>
                                    <label for="delivery" class="service-option flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition-colors duration-200">
                                        <svg class="w-5 h-5 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        <div>
                                            <span class="font-medium text-gray-900">Delivery</span>
                                            <p class="text-xs text-gray-500">Schedule order delivery</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            @error('service_type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror

                            <h3 class="text-lg font-medium text-gray-900 mb-4">Date & Time *</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Appointment Date *</label>
                                    <input type="date" name="appointment_date" value="{{ old('appointment_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    <p class="text-xs text-gray-500 mt-1">Monday to Friday only</p>
                                    @error('appointment_date')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Appointment Time *</label>
                                    <select name="appointment_time" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                        <option value="">Select a time</option>
                                        @foreach($availableSlots as $slot)
                                            <option value="{{ $slot }}" {{ old('appointment_time') == $slot ? 'selected' : '' }}>{{ date('g:i A', strtotime($slot)) }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">9:00 AM - 5:00 PM</p>
                                    @error('appointment_time')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Order Details & Submission -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Order Details</h3>

                            <div class="space-y-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Related Order (Optional)</label>
                                    <select name="order_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">No specific order</option>
                                        @foreach(auth()->user()->orders()->whereIn('status', ['pending', 'in_progress', 'ready'])->get() as $order)
                                            <option value="{{ $order->id }}" {{ request('order_id') == $order->id ? 'selected' : '' }}>
                                                #{{ $order->order_number }} - {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Link to a specific order if applicable</p>
                                    @error('order_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Special Notes</label>
                                    <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Any special requirements or questions...">{{ old('notes') }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">Include any specific requirements</p>
                                    @error('notes')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Appointment Guidelines -->
                            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <h4 class="text-sm font-medium text-blue-900 mb-2">Guidelines</h4>
                                <ul class="text-blue-700 text-xs space-y-1 list-disc list-inside">
                                    <li>Arrive 5-10 minutes early</li>
                                    <li>Bring reference materials</li>
                                    <li>Wear appropriate undergarments</li>
                                    <li>24-hour rescheduling notice required</li>
                                </ul>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="flex flex-col space-y-3">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                                    Schedule Appointment
                                </button>
                                <a href="{{ route('appointments.index') }}" class="w-full text-center bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                                    Cancel
                                </a>
                            </div>
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
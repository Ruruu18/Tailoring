<x-admin-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <div>
                <a href="{{ route('admin.sms.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to SMS
                </a>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Compose SMS</h1>
                <p class="text-sm text-gray-600 mt-1">Send SMS to selected customers</p>
            </div>
        </div>
    </x-slot>

    <div class="p-6 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">New SMS Message</h3>
                </div>

                <form method="POST" action="{{ route('admin.sms.send') }}" class="p-6">
                    @csrf

                    <!-- Recipients Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Recipients
                        </label>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-4">
                            <div class="md:col-span-2 mb-2">
                                <button type="button" id="select-all" class="text-sm text-blue-600 hover:text-blue-800">
                                    Select All
                                </button>
                                |
                                <button type="button" id="select-none" class="text-sm text-blue-600 hover:text-blue-800">
                                    Select None
                                </button>
                                <span class="text-sm text-gray-500 ml-4">
                                    Total customers with phone numbers: {{ $customers->count() }}
                                </span>
                            </div>

                            @foreach($customers as $customer)
                                <div class="flex items-center">
                                    <input type="checkbox"
                                           name="recipients[]"
                                           value="{{ $customer->id }}"
                                           class="recipient-checkbox h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                                           id="customer-{{ $customer->id }}">
                                    <label for="customer-{{ $customer->id }}" class="ml-3 text-sm">
                                        <div class="font-medium text-gray-900">{{ $customer->name }}</div>
                                        <div class="text-gray-500">{{ $customer->phone }}</div>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        @error('recipients')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div class="mb-6">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Message
                        </label>
                        <textarea name="message"
                                  id="message"
                                  rows="6"
                                  class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                  placeholder="Type your SMS message here..."
                                  maxlength="500"
                                  required>{{ old('message') }}</textarea>

                        <div class="flex justify-between mt-2">
                            <span class="text-sm text-gray-500">
                                <span id="char-count">0</span>/500 characters
                            </span>
                            <span class="text-sm text-gray-500">
                                Estimated cost: ₱<span id="estimated-cost">0.00</span>
                            </span>
                        </div>

                        @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preview -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Preview</h4>
                        <div class="bg-white border border-gray-200 rounded-lg p-3">
                            <div class="text-xs text-gray-500 mb-1">From: TAILORSHOP</div>
                            <div class="text-sm" id="message-preview">Your message will appear here...</div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            Selected recipients: <span id="selected-count">0</span>
                        </div>

                        <div class="flex gap-3">
                            <button type="button"
                                    onclick="window.history.back()"
                                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                                Send SMS
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messageTextarea = document.getElementById('message');
            const charCount = document.getElementById('char-count');
            const estimatedCost = document.getElementById('estimated-cost');
            const messagePreview = document.getElementById('message-preview');
            const recipientCheckboxes = document.querySelectorAll('.recipient-checkbox');
            const selectedCount = document.getElementById('selected-count');
            const selectAllBtn = document.getElementById('select-all');
            const selectNoneBtn = document.getElementById('select-none');

            // Update character count and preview
            function updateMessage() {
                const text = messageTextarea.value;
                charCount.textContent = text.length;
                messagePreview.textContent = text || 'Your message will appear here...';

                // Estimate cost (assuming ₱1.00 per SMS)
                const selectedRecipients = document.querySelectorAll('.recipient-checkbox:checked').length;
                const cost = selectedRecipients * 1.00;
                estimatedCost.textContent = cost.toFixed(2);
            }

            // Update selected count
            function updateSelectedCount() {
                const selected = document.querySelectorAll('.recipient-checkbox:checked').length;
                selectedCount.textContent = selected;
                updateMessage(); // Update cost when selection changes
            }

            // Event listeners
            messageTextarea.addEventListener('input', updateMessage);

            recipientCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            selectAllBtn.addEventListener('click', function() {
                recipientCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                updateSelectedCount();
            });

            selectNoneBtn.addEventListener('click', function() {
                recipientCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                updateSelectedCount();
            });

            // Initial updates
            updateMessage();
            updateSelectedCount();
        });
    </script>
</x-admin-layout>
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
                <h1 class="text-2xl font-bold text-gray-900">SMS Settings</h1>
                <p class="text-sm text-gray-600 mt-1">Configure your Semaphore SMS settings</p>
            </div>
        </div>
    </x-slot>

    <div class="p-6 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto space-y-6">

            <!-- Configuration Status -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Configuration Status</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center justify-between p-4 border rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">API Configuration</h4>
                                <p class="text-sm text-gray-500">Semaphore API key status</p>
                            </div>
                            <div class="flex items-center">
                                @if($isConfigured)
                                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-2 text-green-700 font-medium">Configured</span>
                                @else
                                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-2 text-red-700 font-medium">Not Configured</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 border rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">API Connection</h4>
                                <p class="text-sm text-gray-500">Connection to Semaphore API</p>
                            </div>
                            <div class="flex items-center">
                                @if(isset($isRateLimited) && $isRateLimited)
                                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-2 text-yellow-700 font-medium">Rate Limited</span>
                                @elseif($isValidKey)
                                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-2 text-green-700 font-medium">Connected</span>
                                @else
                                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-2 text-red-700 font-medium">Disconnected</span>
                                @endif
                                <button onclick="testConnection()" class="ml-3 text-sm text-blue-600 hover:text-blue-800">
                                    Test
                                </button>
                            </div>
                        </div>
                    </div>

                    @if($isConfigured)
                        <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">SMS service is ready!</h3>
                                    <p class="mt-1 text-sm text-green-700">Your Semaphore SMS configuration is working properly.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Configuration Required</h3>
                                    <p class="mt-1 text-sm text-yellow-700">Please add your Semaphore API credentials to your .env file to enable SMS functionality.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Account Information -->
            @if($balance)
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Account Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ $balance['balance'] ?? 'N/A' }}</div>
                            <div class="text-sm text-purple-500">Current Balance</div>
                        </div>
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $balance['account_name'] ?? 'N/A' }}</div>
                            <div class="text-sm text-blue-500">Account Name</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $balance['status'] ?? 'N/A' }}</div>
                            <div class="text-sm text-green-500">Account Status</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Configuration Instructions -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Setup Instructions</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-semibold text-sm">
                                1
                            </div>
                            <div class="ml-4">
                                <h4 class="font-medium text-gray-900">Create Semaphore Account</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    Visit <a href="https://semaphore.co" target="_blank" class="text-blue-600 hover:text-blue-800">semaphore.co</a>
                                    and create an account to get your API key.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-semibold text-sm">
                                2
                            </div>
                            <div class="ml-4">
                                <h4 class="font-medium text-gray-900">Add Environment Variables</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    Add the following variables to your <code class="bg-gray-100 px-1 rounded">.env</code> file:
                                </p>
                                <div class="mt-2 bg-gray-100 rounded-lg p-3 font-mono text-sm">
                                    <div>SEMAPHORE_API_KEY=your_api_key_here</div>
                                    <div>SEMAPHORE_SENDER_ID=TAILORSHOP</div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-semibold text-sm">
                                3
                            </div>
                            <div class="ml-4">
                                <h4 class="font-medium text-gray-900">Test Configuration</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    Use the "Test" button above to verify your API connection is working properly.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SMS Features -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Available Features</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="font-medium text-gray-900">Automatic Notifications</h4>
                                <p class="text-sm text-gray-600">Order status updates, appointment reminders, and payment notifications sent automatically.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="font-medium text-gray-900">Manual SMS Composer</h4>
                                <p class="text-sm text-gray-600">Send custom messages to selected customers with our easy-to-use compose interface.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="font-medium text-gray-900">Bulk SMS</h4>
                                <p class="text-sm text-gray-600">Send messages to multiple customers at once with filtering options.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="font-medium text-gray-900">SMS History & Logs</h4>
                                <p class="text-sm text-gray-600">Track all sent messages with detailed delivery status and analytics.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function testConnection() {
            const button = event.target;
            const originalText = button.textContent;

            button.textContent = 'Testing...';
            button.disabled = true;

            fetch('{{ route("admin.sms.test") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ ' + data.message);
                } else {
                    alert('❌ ' + data.message);
                }
            })
            .catch(error => {
                alert('❌ Connection test failed: ' + error.message);
            })
            .finally(() => {
                button.textContent = originalText;
                button.disabled = false;
            });
        }
    </script>
</x-admin-layout>
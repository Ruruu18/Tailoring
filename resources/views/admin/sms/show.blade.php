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
                <h1 class="text-2xl font-bold text-gray-900">SMS Details</h1>
                <p class="text-sm text-gray-600 mt-1">View SMS message details and delivery status</p>
            </div>
        </div>
    </x-slot>

    <div class="p-6 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">SMS Message #{{ $smsLog->id }}</h3>
                        @if($smsLog->status === 'sent')
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Sent Successfully
                            </span>
                        @elseif($smsLog->status === 'failed')
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                Failed
                            </span>
                        @else
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ ucfirst($smsLog->status) }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Message Information</h4>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $smsLog->phone_number }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Sent At</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $smsLog->sent_at->format('F j, Y \a\t g:i:s A') }}
                                        <span class="text-gray-500">({{ $smsLog->sent_at->diffForHumans() }})</span>
                                    </dd>
                                </div>
                                @if($smsLog->message_id)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Message ID</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $smsLog->message_id }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Delivery Status</h4>
                            <div class="p-4 rounded-lg {{ $smsLog->status === 'sent' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                                <div class="flex items-center">
                                    @if($smsLog->status === 'sent')
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-green-800 font-medium">Message sent successfully</span>
                                    @else
                                        <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-red-800 font-medium">Message failed to send</span>
                                    @endif
                                </div>
                                <div class="mt-2 text-sm {{ $smsLog->status === 'sent' ? 'text-green-700' : 'text-red-700' }}">
                                    Status: {{ ucfirst($smsLog->status) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message Content -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Message Content</h4>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="text-xs text-gray-500 mb-2">From: TAILORSHOP</div>
                            <div class="text-sm text-gray-900 whitespace-pre-wrap">{{ $smsLog->message }}</div>
                            <div class="text-xs text-gray-500 mt-2">
                                Character count: {{ strlen($smsLog->message) }}
                            </div>
                        </div>
                    </div>

                    <!-- API Response -->
                    @if($smsLog->response)
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-3">API Response</h4>
                        <div class="bg-gray-900 text-white rounded-lg p-4 overflow-x-auto">
                            <pre class="text-sm"><code>{{ json_encode(json_decode($smsLog->response), JSON_PRETTY_PRINT) }}</code></pre>
                        </div>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="border-t pt-6">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                Created: {{ $smsLog->created_at->format('F j, Y \a\t g:i:s A') }}
                            </div>
                            <div class="flex gap-3">
                                @if($smsLog->status === 'failed')
                                <button onclick="retryMessage()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                    Retry Send
                                </button>
                                @endif
                                <button onclick="copyMessage()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                    Copy Message
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyMessage() {
            const message = @json($smsLog->message);
            navigator.clipboard.writeText(message).then(function() {
                alert('Message copied to clipboard!');
            }, function() {
                alert('Failed to copy message');
            });
        }

        function retryMessage() {
            if (confirm('Are you sure you want to retry sending this message?')) {
                // Implementation for retrying message would go here
                // This would require a new route and controller method
                alert('Retry functionality would be implemented here');
            }
        }
    </script>
</x-admin-layout>
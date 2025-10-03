<x-admin-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                💰 Payment Transactions
            </h2>
            <p class="text-gray-600 mt-1">Monitor all payment activity and cash flow</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter and Export Controls -->
            <div class="flex justify-between items-center mb-6">
                <select id="periodSelect" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 min-w-36 w-auto">
                    <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="7days" {{ $period == '7days' ? 'selected' : '' }}>Last 7 days</option>
                    <option value="30days" {{ $period == '30days' ? 'selected' : '' }}>Last 30 days</option>
                    <option value="all" {{ $period == 'all' ? 'selected' : '' }}>All time</option>
                </select>
                <a href="{{ route('admin.reports.export', 'payments') }}?period={{ $period }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors flex items-center">
                    📥 Export CSV
                </a>
            </div>
            <!-- Payment Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <!-- Total Collected -->
                <div class="bg-white p-5 rounded-lg shadow border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Collected</p>
                            <p class="text-2xl font-bold text-green-600">₱{{ number_format($paymentStats['total_payments'], 2) }}</p>
                        </div>
                        <div class="text-green-500">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Transactions -->
                <div class="bg-white p-5 rounded-lg shadow border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Transactions</p>
                            <p class="text-2xl font-bold text-blue-600">{{ number_format($paymentStats['total_transactions']) }}</p>
                        </div>
                        <div class="text-blue-500">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Average Payment -->
                <div class="bg-white p-5 rounded-lg shadow border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Average Payment</p>
                            <p class="text-2xl font-bold text-purple-600">₱{{ number_format($paymentStats['average_payment'] ?: 0, 2) }}</p>
                        </div>
                        <div class="text-purple-500">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pending Payments -->
                <div class="bg-white p-5 rounded-lg shadow border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Still Pending</p>
                            <p class="text-2xl font-bold text-yellow-600">₱{{ number_format($paymentStats['pending_payments'], 2) }}</p>
                        </div>
                        <div class="text-yellow-500">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily Payment Summary -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">📈 Daily Payment Summary</h3>
                    <p class="text-sm text-gray-600">Daily breakdown of received payments</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($dailyPayments->take(12) as $day)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($day->date)->format('M j') }}</h4>
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">{{ $day->transactions }} txns</span>
                            </div>
                            <div class="text-lg font-bold text-green-600">₱{{ number_format($day->total_paid, 2) }}</div>
                            <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($day->date)->format('l') }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Payment Transactions -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">💳 Recent Transactions</h3>
                    <p class="text-sm text-gray-600">Latest payment activity</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Paid</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentPayments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $payment->order_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $payment->created_at->format('M j, Y g:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                                    @if($payment->paid_amount > 0)
                                        <span class="text-green-600">₱{{ number_format($payment->paid_amount, 2) }}</span>
                                    @else
                                        <span class="text-gray-400">₱0.00</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    ₱{{ number_format($payment->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($payment->paid_amount >= $payment->total_amount && $payment->paid_amount > 0)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            ✅ Paid
                                        </span>
                                    @elseif($payment->paid_amount > 0 && $payment->paid_amount < $payment->total_amount)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            📊 Partial
                                        </span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            ⏳ Pending
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('periodSelect').addEventListener('change', function() {
            window.location.href = '{{ route("admin.reports.payments") }}?period=' + this.value;
        });
    </script>
</x-admin-layout>
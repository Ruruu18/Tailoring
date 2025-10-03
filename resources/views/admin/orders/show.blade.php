<x-admin-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <div>
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Orders Management
                </a>
            </div>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Order #{{ $order->order_number }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Order details and management</p>
                </div>
                <div class="flex space-x-2">
                    <!-- Moved confirm button will be placed after order items -->
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-6">
            <!-- Inventory Deduction Status Messages -->
            @if(session('inventory_success'))
                <div class="mb-4 bg-green-50 border border-green-200 rounded-md p-4">
                    <div class="flex">
                        <svg class="flex-shrink-0 h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('inventory_success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('inventory_warning'))
                <div class="mb-4 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex">
                        <svg class="flex-shrink-0 h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-yellow-800">{{ session('inventory_warning') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('inventory_error'))
                <div class="mb-4 bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex">
                        <svg class="flex-shrink-0 h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('inventory_error') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Main Content with Tabs -->
                <div class="lg:col-span-2">
                    <!-- Tab Navigation -->
                    <div class="bg-white rounded-lg shadow-sm mb-4">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                                <button onclick="showTab('details')" id="details-tab"
                                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm active-tab">
                                    Order Details
                                </button>
                                <button onclick="showTab('materials')" id="materials-tab"
                                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Materials
                                    @if($order->materials->count() > 0)
                                        <span class="ml-2 bg-indigo-100 text-indigo-600 py-0.5 px-2 rounded-full text-xs">{{ $order->materials->count() }}</span>
                                    @endif
                                </button>
                                <button onclick="showTab('design')" id="design-tab"
                                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Design Images
                                </button>
                                <button onclick="showTab('appointments')" id="appointments-tab"
                                        class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Appointments
                                    @if($order->appointments->count() > 0)
                                        <span class="ml-2 bg-green-100 text-green-600 py-0.5 px-2 rounded-full text-xs">{{ $order->appointments->count() }}</span>
                                    @endif
                                </button>
                            </nav>
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <div class="space-y-3">
                        <!-- Order Details Tab -->
                        <div id="details-content" class="tab-content">
                    <!-- Order Details Card -->
                    <div class="bg-white rounded-lg shadow-sm p-2">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-900">Order Details</h3>
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'quoted' => 'bg-orange-100 text-orange-800',
                                    'confirmed' => 'bg-purple-100 text-purple-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'ready' => 'bg-green-100 text-green-800',
                                    'completed' => 'bg-gray-100 text-gray-800',
                                    'cancelled' => 'bg-red-100 text-red-800'
                                ];
                                $currentStatusClass = $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $currentStatusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Order Number</label>
                                <p class="text-sm text-gray-900">#{{ $order->order_number }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Order Date</label>
                                <p class="text-xs text-gray-900">{{ $order->created_at->format('M d, Y g:i A') }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Delivery Date</label>
                                <p class="text-xs text-gray-900">{{ $order->delivery_date ? $order->delivery_date->format('M d, Y') : 'Not set' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Last Updated</label>
                                <p class="text-xs text-gray-900">{{ $order->updated_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>

                        @if($order->notes)
                            <div class="mt-2">
                                @php
                                    // Parse notes to separate system logs from user notes
                                    $notes = $order->notes;
                                    $systemLogs = [];
                                    $userNotes = [];

                                    // Extract system logs and user notes
                                    $remainingNotes = $notes;

                                    // Extract inventory deduction logs
                                    while (strpos($remainingNotes, '--- Automatic Inventory Deduction ---') !== false) {
                                        $parts = explode('--- Automatic Inventory Deduction ---', $remainingNotes, 2);

                                        if (!empty(trim($parts[0])) && empty($systemLogs)) {
                                            $userNotes[] = trim($parts[0]);
                                        }

                                        if (count($parts) > 1) {
                                            // Find the end of this log entry
                                            $logContent = $parts[1];
                                            $nextLogPos = strpos($logContent, '--- ');

                                            if ($nextLogPos !== false) {
                                                $currentLog = trim(substr($logContent, 0, $nextLogPos));
                                                $remainingNotes = substr($logContent, $nextLogPos);
                                            } else {
                                                $currentLog = trim($logContent);
                                                $remainingNotes = '';
                                            }

                                            $systemLogs[] = ['type' => 'inventory', 'content' => $currentLog];
                                        } else {
                                            break;
                                        }
                                    }

                                    // Extract status change logs
                                    while (strpos($remainingNotes, '--- Status Change Log ---') !== false) {
                                        $parts = explode('--- Status Change Log ---', $remainingNotes, 2);

                                        if (count($parts) > 1) {
                                            $logContent = $parts[1];
                                            $nextLogPos = strpos($logContent, '--- ');

                                            if ($nextLogPos !== false) {
                                                $currentLog = trim(substr($logContent, 0, $nextLogPos));
                                                $remainingNotes = substr($logContent, $nextLogPos);
                                            } else {
                                                $currentLog = trim($logContent);
                                                $remainingNotes = '';
                                            }

                                            $systemLogs[] = ['type' => 'status', 'content' => $currentLog];
                                        } else {
                                            break;
                                        }
                                    }

                                    // If no system logs were found, treat everything as user notes
                                    if (empty($systemLogs) && empty($userNotes)) {
                                        $userNotes[] = $notes;
                                    }

                                    $userNotes = array_filter($userNotes, fn($note) => !empty(trim($note)));
                                @endphp

                                @if(!empty($userNotes))
                                    <div class="mb-2">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
                                        <div class="bg-gray-50 rounded-md p-2">
                                            @foreach($userNotes as $note)
                                                <p class="text-xs text-gray-900">{{ trim($note) }}</p>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($systemLogs))
                                    <div class="space-y-1">
                                        @foreach($systemLogs as $log)
                                            @if($log['type'] === 'inventory')
                                                <div class="bg-green-50 border border-green-200 rounded-md p-2">
                                                    <div class="flex items-start">
                                                        <svg class="w-4 h-4 text-green-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <div class="flex-1">
                                                            <p class="text-xs font-medium text-green-800">Automatic Inventory Deduction</p>
                                                            @if(preg_match('/Successfully deducted (\d+) materials from inventory/', $log['content'], $matches))
                                                                <p class="text-xs text-green-700">{{ $matches[1] }} materials deducted when production started</p>
                                                            @else
                                                                <p class="text-xs text-green-700">Materials automatically deducted when production started</p>
                                                            @endif
                                                            @if(preg_match('/(20\d{2}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $log['content'], $matches))
                                                                <p class="text-xs text-green-600">{{ \Carbon\Carbon::parse($matches[1])->diffForHumans() }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($log['type'] === 'status')
                                                <div class="bg-blue-50 border border-blue-200 rounded-md p-2">
                                                    <div class="flex items-start">
                                                        <svg class="w-4 h-4 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <div class="flex-1">
                                                            <p class="text-xs font-medium text-blue-800">Status Change</p>
                                                            <p class="text-xs text-blue-700">{{ Str::limit($log['content'], 100) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Order Items Card -->
                    <div class="bg-white rounded-lg shadow-sm p-2">
                        <h3 class="text-base font-semibold text-gray-900 mb-3">Order Items</h3>

                        @if(is_array($order->items) && count($order->items) > 0)
                            <div class="space-y-2">
                                @foreach($order->items as $index => $item)
                                    <div class="border border-gray-200 rounded-md p-2">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h4 class="text-sm font-medium text-gray-900">{{ $item['name'] ?? $item['type'] ?? 'Item' }}</h4>
                                                <p class="text-xs text-gray-600 mt-1">Quantity: {{ $item['quantity'] ?? 1 }}</p>
                                                @if(!empty($item['description']))
                                                    <p class="text-sm text-gray-700 mt-2">{{ $item['description'] }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-4"></i>
                                <p>No items in this order</p>
                            </div>
                        @endif

                        @if($order->status == 'pending' && $order->design_brochure_id && $order->designBrochure && $order->designBrochure->price)
                            <!-- Pre-made design with existing price -->
                            <div class="mt-2 pt-2 border-t border-gray-200">
                                <div class="bg-green-50 border border-green-200 rounded-lg p-2">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-xs font-medium text-green-800">
                                            Price automatically set from pre-made design: ₱{{ number_format($order->designBrochure->price, 0) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @elseif($order->status == 'quoted')
                            <div class="mt-2 pt-2 border-t border-gray-200">
                                <form action="{{ route('admin.orders.confirm', $order) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                                        <i class="fas fa-check mr-2"></i>Confirm Order
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Pre-made Design Display -->
                    @if($order->design_brochure_id && $order->designBrochure)
                        <div class="bg-white rounded-lg shadow-sm p-3">
                            <h3 class="text-base font-semibold text-gray-900 mb-3">Selected Pre-Made Design</h3>
                            <div class="flex items-start space-x-3">
                                <div class="relative group cursor-pointer" onclick="openImageModal('{{ $order->designBrochure->featured_image_url }}')">
                                    <img src="{{ $order->designBrochure->featured_image_url }}"
                                         alt="{{ $order->designBrochure->title }}"
                                         class="w-20 h-20 object-cover rounded-md border border-gray-200 hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-lg flex items-center justify-center">
                                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 text-center">
                                            <svg class="w-6 h-6 text-white mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                            <p class="text-white text-xs font-medium">Enlarge</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-base font-medium text-gray-900 mb-2">{{ $order->designBrochure->title }}</h4>
                                    <p class="text-gray-600 mb-2 text-xs">{{ $order->designBrochure->description }}</p>

                                    <div class="grid grid-cols-2 gap-2 mb-2">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ ucwords($order->designBrochure->category) }}
                                            </span>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Design Price</label>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ₱{{ number_format($order->designBrochure->price, 0) }}
                                            </span>
                                        </div>
                                    </div>

                                    @if($order->designBrochure->style_type)
                                        <div class="mb-2">
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Style Type</label>
                                            <p class="text-xs text-gray-900">{{ ucwords($order->designBrochure->style_type) }}</p>
                                        </div>
                                    @endif

                                    @if($order->designBrochure->gender)
                                        <div class="mb-2">
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Gender</label>
                                            <p class="text-xs text-gray-900">{{ ucwords($order->designBrochure->gender) }}</p>
                                        </div>
                                    @endif

                                    @if($order->designBrochure->fabric_suggestions)
                                        <div class="mb-2">
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Fabric Suggestions</label>
                                            <p class="text-xs text-gray-900">{{ $order->designBrochure->fabric_suggestions }}</p>
                                        </div>
                                    @endif

                                    @if($order->design_notes)
                                        <div class="mt-2 p-2 bg-gray-50 rounded-md">
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Customer Design Notes</label>
                                            <p class="text-xs text-gray-900">{{ $order->design_notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Custom Design Display -->
                    @if(!$order->design_brochure_id && $order->design_type === 'custom')
                        <div class="bg-white rounded-lg shadow-sm p-2 mt-3">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Custom Design Order</h3>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-2 mb-2">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-xs font-medium text-blue-800">
                                        This is a custom design order. Customer has provided their own design specifications.
                                    </span>
                                </div>
                            </div>
                            
                            @if($order->design_notes)
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer Design Notes</label>
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <p class="text-sm text-gray-900">{{ $order->design_notes }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Design Images Card -->
                    @if($order->design_images && count($order->design_images) > 0)
                        <div class="bg-white rounded-lg shadow-sm p-2 mt-3">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                @if($order->design_type === 'custom')
                                    Customer Design Images
                                @else
                                    Design Images
                                @endif
                            </h3>
                            <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                                @foreach($order->design_images as $image)
                                    <div class="relative group cursor-pointer" onclick="openImageModal('{{ Storage::url($image) }}')">
                                        <img src="{{ Storage::url($image) }}" alt="Design Image"
                                             class="w-full h-24 object-cover rounded-lg border border-gray-200 hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-lg flex items-center justify-center">
                                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 text-center">
                                                <svg class="w-8 h-8 text-white mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                                <p class="text-white text-xs font-medium">Click to enlarge</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @elseif($order->design_type === 'custom')
                        <div class="bg-white rounded-lg shadow-sm p-2 mt-3">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Customer Design Images</h3>
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-lg font-medium text-gray-900 mb-2">No Design Images Uploaded</p>
                                <p class="text-sm text-gray-600">The customer has not uploaded any design images for this custom order yet.</p>
                            </div>
                        </div>
                    @endif

                        </div>

                        <!-- Materials Tab -->
                        <div id="materials-content" class="tab-content hidden">
                            <div class="bg-white rounded-lg shadow-sm p-2">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-semibold text-gray-900">Materials Used</h3>
                            @if($order->status !== 'completed' && $order->status !== 'cancelled')
                                <button type="button" onclick="openAddMaterialModal()"
                                        class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Material
                                </button>
                            @endif
                        </div>

                        <!-- Material Cost Analytics Summary -->
                        @if($order->materials->count() > 0)
                            <div class="mb-3 bg-gray-50 rounded-lg p-2">
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-indigo-600">₱{{ number_format($materialCosts['total_cost'], 2) }}</p>
                                        <p class="text-xs text-gray-600">Total Material Cost</p>
                                        @if($materialCosts['percentage_of_order_value'] > 0)
                                            <p class="text-xs text-gray-500">({{ $materialCosts['percentage_of_order_value'] }}% of order value)</p>
                                        @endif
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-green-600">{{ $materialCosts['deducted_count'] }}/{{ $materialCosts['material_count'] }}</p>
                                        <p class="text-xs text-gray-600">Materials Deducted</p>
                                        <p class="text-xs text-gray-500">₱{{ number_format($materialCosts['deducted_cost'], 2) }} value</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-yellow-600">{{ $materialCosts['pending_count'] }}</p>
                                        <p class="text-xs text-gray-600">Pending Deduction</p>
                                        <p class="text-xs text-gray-500">₱{{ number_format($materialCosts['pending_cost'], 2) }} value</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-purple-600">{{ $materialCosts['cost_by_category']->count() }}</p>
                                        <p class="text-xs text-gray-600">Categories Used</p>
                                        @if($materialCosts['most_expensive_material'])
                                            <p class="text-xs text-gray-500">Top: {{ $materialCosts['most_expensive_material']->inventoryItem->name }}</p>
                                        @endif
                                    </div>
                                </div>

                                @if($materialCosts['pending_count'] > 0 && in_array($order->status, ['confirmed', 'in_progress']))
                                    <div class="mt-3 flex justify-center">
                                        <form action="{{ route('admin.orders.materials.deduct-all', $order) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200"
                                                    onclick="return confirm('This will deduct all pending materials from inventory. Continue?')">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Deduct All Materials
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if($order->materials->count() > 0)
                            <div class="space-y-2">
                                @foreach($order->materials as $material)
                                    <div class="border border-gray-200 rounded-lg p-2 {{ $material->isDeducted() ? 'bg-green-50' : 'bg-yellow-50' }}">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <img src="{{ $material->inventoryItem->primary_image_url }}"
                                                     alt="{{ $material->inventoryItem->name }}"
                                                     class="w-12 h-12 rounded-lg object-cover">
                                                <div>
                                                    <h4 class="font-medium text-gray-900">{{ $material->inventoryItem->name }}</h4>
                                                    <p class="text-sm text-gray-600">
                                                        Quantity: {{ $material->quantity_used }} {{ $material->unit }}
                                                        @if($material->notes)
                                                            • {{ $material->notes }}
                                                        @endif
                                                    </p>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        ₱{{ number_format($material->unit_price_at_time, 2) }} per {{ $material->unit }} =
                                                        <span class="text-indigo-600">₱{{ number_format($material->total_cost, 2) }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                @if($material->isDeducted())
                                                    <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Deducted
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Pending
                                                    </span>
                                                @endif
                                                @if($order->status !== 'completed' && $order->status !== 'cancelled')
                                                    @if(!$material->isDeducted() && in_array($order->status, ['confirmed', 'in_progress']))
                                                        <button type="button" onclick="deductMaterial({{ $material->id }})"
                                                                class="text-green-600 hover:text-green-800 p-1 rounded-full hover:bg-green-100 transition-colors duration-200"
                                                                title="Deduct from inventory">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                    <button type="button" onclick="removeMaterial({{ $material->id }})"
                                                            class="text-red-600 hover:text-red-800 p-1 rounded-full hover:bg-red-100 transition-colors duration-200"
                                                            title="Remove material">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Materials Summary -->
                            <div class="border-t border-gray-200 pt-3 mt-3">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="font-medium text-gray-900">Total Material Cost:</span>
                                    <span class="font-bold text-lg text-indigo-600">₱{{ number_format($order->total_material_cost, 2) }}</span>
                                </div>
                                @if($order->materials->where('deducted_at', null)->count() > 0)
                                    <p class="text-xs text-yellow-600 mt-1">
                                        {{ $order->materials->where('deducted_at', null)->count() }} material(s) pending deduction
                                    </p>
                                @endif
                            </div>
                        @else
                            <!-- No Materials State -->
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">No Materials Assigned</h4>
                                <p class="text-gray-600 mb-4">Add materials that will be used for this order.</p>
                                @if($order->status !== 'completed' && $order->status !== 'cancelled')
                                    <button type="button" onclick="openAddMaterialModal()"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Add First Material
                                    </button>
                                @endif
                            </div>
                        @endif

                        @if($order->designBrochure && $order->designBrochure->materials->count() > 0 && $order->materials->count() === 0)
                            <!-- Pre-populate from design template -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-3">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <h5 class="font-medium text-blue-900 mb-1">Suggested Materials Available</h5>
                                        <p class="text-sm text-blue-700 mb-3">This design has {{ $order->designBrochure->materials->count() }} suggested materials. Would you like to add them automatically?</p>
                                        <button type="button" onclick="addSuggestedMaterials()"
                                                class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Add Suggested Materials
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                            </div>
                        </div>

                        <!-- Design Images Tab -->
                        <div id="design-content" class="tab-content hidden">
                            @if($order->design_images && count($order->design_images) > 0)
                                <div class="bg-white rounded-lg shadow-sm p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                        @if($order->design_type === 'custom')
                                            Customer Design Images
                                        @else
                                            Design Images
                                        @endif
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                        @foreach($order->design_images as $image)
                                            <div class="relative group">
                                                <img src="{{ asset('storage/' . $image) }}"
                                                     alt="Order Design Image"
                                                     class="w-full h-48 object-cover rounded-lg border border-gray-200 shadow-sm group-hover:shadow-md transition-shadow duration-200">
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-lg flex items-center justify-center">
                                                    <button onclick="viewImageFullscreen('{{ asset('storage/' . $image) }}')"
                                                            class="opacity-0 group-hover:opacity-100 bg-white text-gray-800 px-3 py-1 rounded-md text-sm font-medium transition-opacity duration-200 hover:bg-gray-100">
                                                        View Full Size
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @elseif($order->design_type === 'custom')
                                <div class="bg-white rounded-lg shadow-sm p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Design Images</h3>
                                    <div class="text-center py-8 text-gray-500">
                                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <h4 class="text-lg font-medium text-gray-900 mb-2">No Design Images Uploaded</h4>
                                        <p class="text-sm text-gray-600">The customer has not uploaded any design images for this custom order yet.</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Appointments Tab -->
                        <div id="appointments-content" class="tab-content hidden">
                    @if($order->appointments && $order->appointments->count() > 0)
                        <div class="bg-white rounded-lg shadow-sm p-2">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Related Appointments</h3>
                            <div class="space-y-2">
                                @foreach($order->appointments as $appointment)
                                    <div class="border border-gray-200 rounded-lg p-2">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $appointment->service_type)) }}</h4>
                                                <p class="text-sm text-gray-600">{{ $appointment->appointment_date->format('F d, Y \a\t g:i A') }}</p>
                                                @if($appointment->notes)
                                                    <p class="text-sm text-gray-700 mt-2">{{ $appointment->notes }}</p>
                                                @endif
                                            </div>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{
                                $appointment->status == 'scheduled' ? 'bg-blue-100 text-blue-800' :
                                ($appointment->status == 'confirmed' ? 'bg-green-100 text-green-800' :
                                ($appointment->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'))
                            }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-lg shadow-sm p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Appointments</h3>
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">No Appointments</h4>
                                <p class="text-sm text-gray-600">No appointments are scheduled for this order.</p>
                            </div>
                        </div>
                    @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-2">
                    <!-- Customer Information -->
                    <div class="bg-white rounded-lg shadow-sm p-2">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Customer Information</h3>
                        <div class="space-y-1">
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Name</label>
                                <p class="text-sm text-gray-900">{{ $order->user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Email</label>
                                <p class="text-xs text-gray-900">{{ $order->user->email }}</p>
                            </div>
                            @if($order->user->phone)
                                <div>
                                    <label class="block text-xs font-medium text-gray-700">Phone</label>
                                    <p class="text-xs text-gray-900">{{ $order->user->phone }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="mt-2 pt-2 border-t border-gray-200">
                            <a href="{{ route('admin.customers.show', $order->user) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                View Customer Profile →
                            </a>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="bg-white rounded-lg shadow-sm p-2">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Payment Information</h3>
                        <div class="space-y-1">
                            <div class="flex justify-between">
                                <span class="text-xs font-medium text-gray-700">Total Amount:</span>
                                <span class="text-sm font-bold text-gray-900">₱{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs font-medium text-gray-700">Paid Amount:</span>
                                <span class="text-sm font-medium text-green-600">₱{{ number_format($order->paid_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between pt-1 border-t border-gray-200">
                                <span class="text-xs font-medium text-gray-700">Balance:</span>
                                <span class="text-sm font-bold {{ $order->pending_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    ₱{{ number_format($order->pending_amount, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Actions -->
                    @if($order->status == 'pending')
                        <div class="bg-white rounded-lg shadow-sm p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Order Actions</h3>
                            <div class="space-y-2">
                                @if($order->design_brochure_id && $order->designBrochure && $order->designBrochure->price)
                                    <!-- Pre-made design - show adjust price -->
                                    <button onclick="openPricingModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-3 rounded-md transition duration-200 text-sm">
                                        <i class="fas fa-edit mr-2"></i>Adjust Price
                                    </button>
                                @else
                                    <!-- Custom design - show set price -->
                                    <button onclick="openPricingModal()" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-3 rounded-md transition duration-200 text-sm">
                                        <i class="fas fa-dollar-sign mr-2"></i>Set Price
                                    </button>
                                @endif
                                <button onclick="openCancelModal()" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-3 rounded-md transition duration-200 text-sm">
                                    <i class="fas fa-times mr-2"></i>Cancel Order
                                </button>
                            </div>
                        </div>
                    @elseif($order->status == 'quoted')
                        <div class="bg-white rounded-lg shadow-sm p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Order Actions</h3>
                            <div class="space-y-2">
                                <form action="{{ route('admin.orders.confirm', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-3 rounded-md transition duration-200 text-sm">
                                        <i class="fas fa-check mr-2"></i>Confirm Order
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif($order->status == 'confirmed')
                        <div class="bg-white rounded-lg shadow-sm p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Order Actions</h3>
                            <div class="space-y-2">
                                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="in_progress">
                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-3 rounded-md transition duration-200 text-sm">
                                        <i class="fas fa-play mr-2"></i>Start Work
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif($order->status == 'in_progress')
                        <div class="bg-white rounded-lg shadow-sm p-3">
                            <h3 class="text-base font-semibold text-gray-900 mb-2">Order Actions</h3>
                            <div class="space-y-2">
                                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="ready">
                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-3 rounded-md transition duration-200 text-sm">
                                        <i class="fas fa-check-circle mr-2"></i>Mark as Ready
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif($order->status == 'ready')
                        <div class="bg-white rounded-lg shadow-sm p-3">
                            <h3 class="text-base font-semibold text-gray-900 mb-2">Order Actions</h3>
                            <div class="space-y-2">
                                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-3 rounded-md transition duration-200 text-sm">
                                        <i class="fas fa-flag-checkered mr-2"></i>Mark as Completed
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Manual Status Update (Admin Override) -->
                    <div class="bg-white rounded-lg shadow-sm p-3">
                        <h3 class="text-base font-semibold text-gray-900 mb-2">Manual Status Update</h3>
                        <p class="text-xs text-gray-600 mb-2">Override the current status if needed for special circumstances.</p>
                        <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                            @csrf
                            @method('PATCH')
                            <div class="space-y-2">
                                <div>
                                    <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Change Status To:</label>
                                    <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="quoted" {{ $order->status == 'quoted' ? 'selected' : '' }}>Quoted</option>
                                        <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="in_progress" {{ $order->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="status_reason" class="block text-xs font-medium text-gray-700 mb-1">Reason for Change (Optional):</label>
                                    <textarea name="status_reason" id="status_reason" rows="2" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs" placeholder="Explain why you're changing the status..."></textarea>
                                </div>
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-3 rounded-md transition duration-200 text-sm">
                                    <i class="fas fa-edit mr-2"></i>Update Status
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4" style="display: none;">
        <!-- Close Button - Fixed to viewport -->
        <button onclick="closeImageModal()" class="fixed top-4 right-4 text-white hover:text-gray-300 z-50 bg-black bg-opacity-50 rounded-full p-3 transition-all duration-200 hover:bg-opacity-75 hover:scale-110">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <div class="relative max-w-6xl max-h-full w-full h-full flex items-center justify-center">
            
            <!-- Image Container -->
            <div class="relative w-full h-full flex items-center justify-center p-8">
                <img id="modalImage" src="" alt="Design Image" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
                
                <!-- Image Info Overlay -->
                <div class="absolute bottom-4 left-4 bg-black bg-opacity-75 text-white px-4 py-2 rounded-lg">
                    <p class="text-sm font-medium">Click and drag to pan • Scroll to zoom • ESC to close</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let isZoomed = false;
        let scale = 1;
        let isDragging = false;
        let startX, startY, translateX = 0, translateY = 0;

        function openImageModal(imageSrc) {
            console.log('Opening modal with image:', imageSrc);
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            
            console.log('Modal element:', modal);
            console.log('Modal image element:', modalImage);
            
            if (modal && modalImage) {
                // Reset zoom and position
                scale = 1;
                translateX = 0;
                translateY = 0;
                isZoomed = false;
                
                modalImage.src = imageSrc;
                modalImage.style.transform = 'scale(1) translate(0px, 0px)';
                modalImage.style.cursor = 'zoom-in';
                
                modal.style.display = 'flex';
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                
                console.log('Modal should be visible now');
                console.log('Modal classes:', modal.className);
                console.log('Modal display style:', modal.style.display);
            } else {
                console.error('Modal elements not found!');
                console.error('Available elements:', document.querySelectorAll('[id*="modal"]'));
            }
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            if (modal) {
                modal.style.display = 'none';
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                
                // Reset zoom and position
                scale = 1;
                translateX = 0;
                translateY = 0;
                isZoomed = false;
            }
        }

        // Add zoom functionality
        document.addEventListener('DOMContentLoaded', function() {
            const modalImage = document.getElementById('modalImage');
            
            if (modalImage) {
                // Click to zoom
                modalImage.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    if (!isZoomed) {
                        scale = 2;
                        isZoomed = true;
                        this.style.cursor = 'zoom-out';
                    } else {
                        scale = 1;
                        translateX = 0;
                        translateY = 0;
                        isZoomed = false;
                        this.style.cursor = 'zoom-in';
                    }
                    
                    this.style.transform = `scale(${scale}) translate(${translateX}px, ${translateY}px)`;
                });

                // Mouse wheel zoom
                modalImage.addEventListener('wheel', function(e) {
                    e.preventDefault();
                    
                    const delta = e.deltaY > 0 ? -0.1 : 0.1;
                    scale = Math.min(Math.max(0.5, scale + delta), 3);
                    
                    if (scale === 1) {
                        translateX = 0;
                        translateY = 0;
                        isZoomed = false;
                        this.style.cursor = 'zoom-in';
                    } else {
                        isZoomed = true;
                        this.style.cursor = 'zoom-out';
                    }
                    
                    this.style.transform = `scale(${scale}) translate(${translateX}px, ${translateY}px)`;
                });

                // Drag to pan when zoomed
                modalImage.addEventListener('mousedown', function(e) {
                    if (isZoomed) {
                        isDragging = true;
                        startX = e.clientX - translateX;
                        startY = e.clientY - translateY;
                        this.style.cursor = 'grabbing';
                        e.preventDefault();
                    }
                });

                document.addEventListener('mousemove', function(e) {
                    if (isDragging && isZoomed) {
                        translateX = e.clientX - startX;
                        translateY = e.clientY - startY;
                        modalImage.style.transform = `scale(${scale}) translate(${translateX}px, ${translateY}px)`;
                    }
                });

                document.addEventListener('mouseup', function() {
                    if (isDragging) {
                        isDragging = false;
                        modalImage.style.cursor = isZoomed ? 'zoom-out' : 'zoom-in';
                    }
                });
            }
        });

        function openPricingModal() {
            const modal = document.getElementById('pricingModal');
            modal.style.display = 'flex';
            modal.classList.remove('hidden');
        }

        function closePricingModal() {
            const modal = document.getElementById('pricingModal');
            modal.style.display = 'none';
            modal.classList.add('hidden');
        }

        function openCancelModal() {
            const modal = document.getElementById('cancelModal');
            modal.style.display = 'flex';
        }

        function closeCancelModal() {
            const modal = document.getElementById('cancelModal');
            modal.style.display = 'none';
        }

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>

    <!-- Pricing Modal -->
    <div id="pricingModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4" style="display: none;">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    @if($order->design_brochure_id && $order->designBrochure && $order->designBrochure->price)
                        <h3 class="text-lg font-semibold text-gray-900">Adjust Order Price</h3>
                    @else
                        <h3 class="text-lg font-semibold text-gray-900">Set Order Price</h3>
                    @endif
                    <button onclick="closePricingModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                @if($order->design_brochure_id && $order->designBrochure && $order->designBrochure->price)
                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center text-sm text-blue-800">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Original design price: ₱{{ number_format($order->designBrochure->price, 0) }}
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.orders.set-price', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-2">Total Amount (₱)</label>
                        <input type="number"
                               id="total_amount"
                               name="total_amount"
                               step="0.01"
                               min="0"
                               value="{{ $order->total_amount }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               required>
                    </div>

                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea id="notes"
                                  name="notes"
                                  rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Add any pricing notes or details...">{{ $order->notes }}</textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button"
                                onclick="closePricingModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition duration-200">
                            @if($order->design_brochure_id && $order->designBrochure && $order->designBrochure->price)
                                Update Price & Quote
                            @else
                                Set Price & Quote
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cancel Order Modal -->
    <div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center p-4" style="display: none;">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Cancel Order</h3>
                    <button onclick="closeCancelModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('admin.orders.cancel', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-4">Please provide a reason for cancelling this order. This will be visible to the customer.</p>
                    </div>

                    <div class="mb-6">
                        <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-2">Cancellation Reason</label>
                        <textarea id="cancellation_reason"
                                  name="cancellation_reason"
                                  rows="4"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                  placeholder="Explain why this order is being cancelled..."
                                  required></textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button"
                                onclick="closeCancelModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition duration-200">
                            Keep Order
                        </button>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition duration-200">
                            Cancel Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Material Modal -->
    <div id="addMaterialModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center p-4" style="display: none;">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Add Material to Order</h3>
                    <button onclick="closeAddMaterialModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="addMaterialForm" action="{{ route('admin.orders.materials.add', $order) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="inventory_id" class="block text-sm font-medium text-gray-700 mb-2">Select Material</label>
                        <select id="inventory_id" name="inventory_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Choose a material...</option>
                            @foreach($inventoryItems->groupBy('category') as $category => $items)
                                <optgroup label="{{ ucfirst(str_replace('_', ' ', $category)) }}">
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}"
                                                data-name="{{ $item->name }}"
                                                data-unit="{{ $item->unit }}"
                                                data-price="{{ $item->unit_price }}"
                                                data-available="{{ $item->quantity }}"
                                                data-image="{{ $item->primary_image_url }}">
                                            {{ $item->name }} ({{ $item->quantity }} {{ $item->unit }} available)
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div id="materialPreview" class="mb-4 p-3 bg-gray-50 rounded-lg" style="display: none;">
                        <div class="flex items-center space-x-3">
                            <img id="previewImage" src="" alt="" class="w-16 h-16 rounded-lg object-cover">
                            <div>
                                <h4 id="previewName" class="font-medium text-gray-900"></h4>
                                <p id="previewDetails" class="text-sm text-gray-600"></p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="quantity_used" class="block text-sm font-medium text-gray-700 mb-2">Quantity to Use</label>
                        <input type="number"
                               id="quantity_used"
                               name="quantity_used"
                               step="0.001"
                               min="0.001"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Enter quantity"
                               required>
                        <p id="quantityHelper" class="text-sm text-gray-500 mt-1"></p>
                    </div>

                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea id="notes"
                                  name="notes"
                                  rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Any special notes about this material usage..."></textarea>
                    </div>

                    <div id="costEstimate" class="mb-4 p-3 bg-blue-50 rounded-lg" style="display: none;">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-blue-800">Estimated Cost:</span>
                            <span id="estimatedCost" class="text-lg font-bold text-blue-900"></span>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button"
                                onclick="closeAddMaterialModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md transition duration-200">
                            Add Material
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Modal functions
        function openAddMaterialModal() {
            document.getElementById('addMaterialModal').style.display = 'flex';
        }

        function closeAddMaterialModal() {
            document.getElementById('addMaterialModal').style.display = 'none';
            document.getElementById('addMaterialForm').reset();
            document.getElementById('materialPreview').style.display = 'none';
            document.getElementById('costEstimate').style.display = 'none';
        }

        // Material selection and preview
        document.getElementById('inventory_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const preview = document.getElementById('materialPreview');

            if (selectedOption.value) {
                const name = selectedOption.dataset.name;
                const unit = selectedOption.dataset.unit;
                const price = selectedOption.dataset.price;
                const available = selectedOption.dataset.available;
                const image = selectedOption.dataset.image;

                document.getElementById('previewImage').src = image;
                document.getElementById('previewName').textContent = name;
                document.getElementById('previewDetails').textContent = `₱${parseFloat(price).toFixed(2)} per ${unit} • ${available} ${unit} available`;
                document.getElementById('quantityHelper').textContent = `Available: ${available} ${unit}`;

                preview.style.display = 'block';

                // Update quantity validation
                const quantityInput = document.getElementById('quantity_used');
                quantityInput.max = available;
                quantityInput.placeholder = `Enter quantity (max: ${available})`;

                // Trigger cost calculation if quantity is already entered
                calculateCost();
            } else {
                preview.style.display = 'none';
                document.getElementById('costEstimate').style.display = 'none';
            }
        });

        // Quantity input and cost calculation
        document.getElementById('quantity_used').addEventListener('input', calculateCost);

        function calculateCost() {
            const selectedOption = document.getElementById('inventory_id').options[document.getElementById('inventory_id').selectedIndex];
            const quantityInput = document.getElementById('quantity_used');
            const costEstimate = document.getElementById('costEstimate');

            if (selectedOption.value && quantityInput.value) {
                const price = parseFloat(selectedOption.dataset.price);
                const quantity = parseFloat(quantityInput.value);
                const available = parseFloat(selectedOption.dataset.available);

                if (quantity <= available && quantity > 0) {
                    const totalCost = price * quantity;
                    document.getElementById('estimatedCost').textContent = `₱${totalCost.toFixed(2)}`;
                    costEstimate.style.display = 'block';
                    quantityInput.classList.remove('border-red-300');
                    quantityInput.classList.add('border-gray-300');
                } else {
                    costEstimate.style.display = 'none';
                    if (quantity > available) {
                        quantityInput.classList.add('border-red-300');
                        quantityInput.classList.remove('border-gray-300');
                    }
                }
            } else {
                costEstimate.style.display = 'none';
            }
        }

        // Form submission with AJAX
        document.getElementById('addMaterialForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;

            submitButton.disabled = true;
            submitButton.textContent = 'Adding...';

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to show the new material
                    window.location.reload();
                } else {
                    alert(data.message || 'Error adding material');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding material. Please try again.');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        });

        // Material management functions
        function deductMaterial(orderMaterialId) {
            if (confirm('Deduct this material from inventory?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/orders/{{ $order->id }}/materials/${orderMaterialId}/deduct`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PATCH';

                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function removeMaterial(orderMaterialId) {
            if (confirm('Remove this material from the order? If it was already deducted, inventory will be restored.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/orders/{{ $order->id }}/materials/${orderMaterialId}`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Cancel modal functions (if not already defined)
        function openCancelModal() {
            document.getElementById('cancelModal').style.display = 'flex';
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').style.display = 'none';
        }

        // Add suggested materials from design template
        function addSuggestedMaterials() {
            if (confirm('Add all suggested materials from this design template?')) {
                const suggestedMaterials = @json($order->designBrochure ? $order->designBrochure->materials : []);

                if (suggestedMaterials.length === 0) {
                    alert('No suggested materials found.');
                    return;
                }

                const addPromises = suggestedMaterials.map(material => {
                    const formData = new FormData();
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                    formData.append('inventory_id', material.id);
                    formData.append('quantity_used', material.pivot.quantity_needed);
                    formData.append('notes', material.pivot.notes || `Suggested for ${material.pivot.is_required ? 'required' : 'optional'} use`);

                    return fetch('{{ route("admin.orders.materials.add", $order) }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                });

                Promise.all(addPromises)
                    .then(responses => Promise.all(responses.map(r => r.json())))
                    .then(results => {
                        const successCount = results.filter(r => r.success).length;
                        const failCount = results.length - successCount;

                        if (successCount > 0) {
                            window.location.reload();
                        } else {
                            alert(`Failed to add ${failCount} materials. Please try adding them manually.`);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error adding suggested materials. Please try again.');
                    });
            }
        }

        // Tab functionality
        function showTab(tabName) {
            // Hide all tab content
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active class from all tabs
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => {
                button.classList.remove('active-tab', 'border-indigo-500', 'text-indigo-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab content
            const selectedContent = document.getElementById(tabName + '-content');
            if (selectedContent) {
                selectedContent.classList.remove('hidden');
            }

            // Mark selected tab as active
            const selectedTab = document.getElementById(tabName + '-tab');
            if (selectedTab) {
                selectedTab.classList.add('active-tab', 'border-indigo-500', 'text-indigo-600');
                selectedTab.classList.remove('border-transparent', 'text-gray-500');
            }
        }

        // Initialize tabs on page load
        document.addEventListener('DOMContentLoaded', function() {
            showTab('details'); // Show details tab by default
        });

        // Image fullscreen viewer
        function viewImageFullscreen(imageSrc) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4';
            modal.onclick = function() { document.body.removeChild(modal); };

            const img = document.createElement('img');
            img.src = imageSrc;
            img.className = 'max-w-full max-h-full object-contain';
            img.onclick = function(e) { e.stopPropagation(); };

            const closeBtn = document.createElement('button');
            closeBtn.innerHTML = '×';
            closeBtn.className = 'absolute top-4 right-4 text-white text-4xl font-bold hover:text-gray-300';
            closeBtn.onclick = function() { document.body.removeChild(modal); };

            modal.appendChild(img);
            modal.appendChild(closeBtn);
            document.body.appendChild(modal);
        }

        // Close modals when clicking outside
        window.addEventListener('click', function(e) {
            const addModal = document.getElementById('addMaterialModal');
            const cancelModal = document.getElementById('cancelModal');

            if (e.target === addModal) {
                closeAddMaterialModal();
            }
            if (e.target === cancelModal) {
                closeCancelModal();
            }
        });
    </script>

    <style>
        .active-tab {
            border-color: #4f46e5;
            color: #4f46e5;
        }
        .tab-button:hover {
            color: #374151;
            border-color: #d1d5db;
        }
    </style>
</x-admin-layout>

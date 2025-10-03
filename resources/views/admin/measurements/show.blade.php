<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.measurements.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Customer Measurement Details') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Measurement Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Measurement Details</h3>
                            <p class="text-sm text-gray-500">Last updated: {{ $measurement->updated_at->format('F j, Y g:i A') }}</p>
                        </div>
                        <div class="p-6">
                            <!-- Measurement Type Badge -->
                            <div class="mb-6">
                                @if($measurement->is_custom)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                        </svg>
                                        Custom Measurements
                                    </span>
                                @else
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a2 2 0 012-2z"></path>
                                            </svg>
                                            Standard Size
                                        </span>
                                        @if($measurement->size)
                                            <span class="px-3 py-1 bg-blue-500 text-white rounded-lg font-bold text-lg">{{ $measurement->size }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Essential Measurements -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <h4 class="text-md font-medium text-gray-900 border-b border-gray-200 pb-2">Essential Measurements</h4>

                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded">
                                            <span class="text-gray-700 font-medium">Chest:</span>
                                            <span class="font-semibold text-lg">{{ $measurement->chest }}"</span>
                                        </div>
                                        <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded">
                                            <span class="text-gray-700 font-medium">Waist:</span>
                                            <span class="font-semibold text-lg">{{ $measurement->waist }}"</span>
                                        </div>
                                        <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded">
                                            <span class="text-gray-700 font-medium">Hip:</span>
                                            <span class="font-semibold text-lg">{{ $measurement->hip }}"</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <h4 class="text-md font-medium text-gray-900 border-b border-gray-200 pb-2">Body Measurements</h4>

                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded">
                                            <span class="text-gray-700 font-medium">Shoulder:</span>
                                            <span class="font-semibold text-lg">{{ $measurement->shoulder }}"</span>
                                        </div>
                                        <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded">
                                            <span class="text-gray-700 font-medium">Sleeve Length:</span>
                                            <span class="font-semibold text-lg">{{ $measurement->sleeve_length }}"</span>
                                        </div>
                                        <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded">
                                            <span class="text-gray-700 font-medium">Inseam:</span>
                                            <span class="font-semibold text-lg">{{ $measurement->inseam }}"</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Measurements -->
                            @if($measurement->shirt_length || $measurement->short_waist)
                                <div class="mt-8">
                                    <h4 class="text-md font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4">Additional Measurements</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @if($measurement->shirt_length)
                                            <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded">
                                                <span class="text-gray-700 font-medium">Shirt Length:</span>
                                                <span class="font-semibold">{{ $measurement->shirt_length }}"</span>
                                            </div>
                                        @endif
                                        @if($measurement->short_waist)
                                            <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded">
                                                <span class="text-gray-700 font-medium">Short Waist:</span>
                                                <span class="font-semibold">{{ $measurement->short_waist }}"</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Notes -->
                            @if($measurement->notes)
                                <div class="mt-8">
                                    <h4 class="text-md font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4">Notes</h4>
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <p class="text-gray-700">{{ $measurement->notes }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Customer Info -->
                    <div class="bg-white overflow-hidden shadow-xl rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Customer Information</h3>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0 h-16 w-16">
                                    <div class="h-16 w-16 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-xl">
                                        {{ strtoupper(substr($measurement->user->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $measurement->user->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $measurement->user->email }}</p>
                                    @if($measurement->user->phone)
                                        <p class="text-sm text-gray-600">{{ $measurement->user->phone }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Measurement Info -->
                    <div class="bg-white overflow-hidden shadow-xl rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Measurement Info</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Created:</span>
                                <span class="font-medium">{{ $measurement->created_at->format('M j, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Last Updated:</span>
                                <span class="font-medium">{{ $measurement->updated_at->format('M j, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">ID:</span>
                                <span class="font-medium">#{{ $measurement->id }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Actions</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('admin.measurements.edit', $measurement) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-semibold transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Measurement
                            </a>

                            <form method="POST" action="{{ route('admin.measurements.destroy', $measurement) }}" class="w-full" onsubmit="return confirm('Are you sure you want to delete this measurement? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-semibold transition-colors duration-200 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete Measurement
                                </button>
                            </form>

                            <a href="{{ route('admin.measurements.index') }}" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-3 rounded-lg font-semibold transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
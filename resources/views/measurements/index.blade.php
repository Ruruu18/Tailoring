<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center w-full space-y-4 sm:space-y-0">
            <div class="min-w-0 flex-1">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">My Measurements</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Manage your body measurements for accurate tailoring.</p>
            </div>
            <div class="flex-shrink-0">
                <!-- Add New Measurement Button -->
                <a href="{{ route('measurements.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-xl text-sm sm:text-base font-semibold transition-all duration-300 transform hover:scale-105 flex items-center justify-center shadow-lg w-full sm:w-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="hidden sm:inline">Add Measurements</span>
                    <span class="sm:hidden">Add</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8 bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($measurement)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                    <!-- Current Measurements -->
                    <div class="lg:col-span-2">
                        <div class="bg-white overflow-hidden shadow-xl rounded-xl sm:rounded-2xl">
                            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                                <h3 class="text-base sm:text-lg font-medium text-gray-900">Current Measurements</h3>
                                <p class="text-xs sm:text-sm text-gray-500">Last updated: {{ $measurement->updated_at->format('F j, Y') }}</p>
                            </div>
                            <div class="p-4 sm:p-6">
                                <div class="grid grid-cols-1 gap-4 sm:gap-6">
                                    <!-- Essential Measurements -->
                                    <div>
                                        <h4 class="text-sm sm:text-md font-medium text-gray-900 mb-3 sm:mb-4">Essential Measurements</h4>
                                        @if($measurement->size && !$measurement->is_custom)
                                            <!-- Standard Size Display -->
                                            <div class="mb-3 p-2 sm:p-3 bg-blue-50 border border-blue-200 rounded">
                                                <div class="flex items-center justify-center">
                                                    <span class="text-blue-700 font-medium text-xs sm:text-sm">Standard Size:</span>
                                                    <span class="ml-2 px-2 py-0.5 bg-blue-500 text-white rounded font-bold text-xs sm:text-sm">{{ $measurement->size }}</span>
                                                </div>
                                            </div>
                                        @elseif($measurement->is_custom)
                                            <!-- Custom Measurements Badge -->
                                            <div class="mb-3 p-2 sm:p-3 bg-green-50 border border-green-200 rounded">
                                                <div class="flex items-center justify-center">
                                                    <span class="text-green-700 font-medium text-xs sm:text-sm">Custom Measurements</span>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="space-y-2 sm:space-y-3">
                                            <div class="flex justify-between items-center py-1 sm:py-2 border-b border-gray-100">
                                                <span class="text-gray-600 text-xs sm:text-sm">Chest:</span>
                                                <span class="font-medium text-xs sm:text-sm">{{ $measurement->chest }}"</span>
                                            </div>
                                            <div class="flex justify-between items-center py-1 sm:py-2 border-b border-gray-100">
                                                <span class="text-gray-600 text-xs sm:text-sm">Waist:</span>
                                                <span class="font-medium text-xs sm:text-sm">{{ $measurement->waist }}"</span>
                                            </div>
                                            <div class="flex justify-between items-center py-1 sm:py-2 border-b border-gray-100">
                                                <span class="text-gray-600 text-xs sm:text-sm">Hip:</span>
                                                <span class="font-medium text-xs sm:text-sm">{{ $measurement->hip }}"</span>
                                            </div>
                                            <div class="flex justify-between items-center py-1 sm:py-2 border-b border-gray-100">
                                                <span class="text-gray-600 text-xs sm:text-sm">Shoulder:</span>
                                                <span class="font-medium text-xs sm:text-sm">{{ $measurement->shoulder }}"</span>
                                            </div>
                                            <div class="flex justify-between items-center py-1 sm:py-2 border-b border-gray-100">
                                                <span class="text-gray-600 text-xs sm:text-sm">Sleeve Length:</span>
                                                <span class="font-medium text-xs sm:text-sm">{{ $measurement->sleeve_length }}"</span>
                                            </div>
                                            <div class="flex justify-between items-center py-1 sm:py-2 border-b border-gray-100">
                                                <span class="text-gray-600 text-xs sm:text-sm">Inseam:</span>
                                                <span class="font-medium text-xs sm:text-sm">{{ $measurement->inseam }}"</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                @if($measurement->notes)
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <h4 class="text-xs sm:text-sm font-medium text-gray-900 mb-1">Notes</h4>
                                        <p class="text-gray-700 text-xs sm:text-sm">{{ $measurement->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Measurement Info & Actions -->
                    <div class="lg:col-span-1 space-y-4 sm:space-y-6 lg:space-y-8">
                        <div class="bg-white overflow-hidden shadow-xl rounded-xl sm:rounded-2xl">
                            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                                <h3 class="text-base sm:text-lg font-medium text-gray-900">Measurement Info</h3>
                            </div>
                            <div class="p-4 sm:p-6">
                                <div class="space-y-3 sm:space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 text-xs sm:text-sm">Last Updated:</span>
                                        <span class="font-medium text-xs sm:text-sm">{{ $measurement->updated_at->format('M j, Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 text-xs sm:text-sm">Created:</span>
                                        <span class="font-medium text-xs sm:text-sm">{{ $measurement->created_at->format('M j, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="bg-white overflow-hidden shadow-xl rounded-xl sm:rounded-2xl">
                            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                                <h3 class="text-base sm:text-lg font-medium text-gray-900">Quick Actions</h3>
                            </div>
                            <div class="p-4 sm:p-6 space-y-2 sm:space-y-3">
                                <a href="{{ route('measurements.edit', $measurement) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 sm:py-3 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-105 flex items-center justify-center shadow-lg">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Update Measurements</span>
                                    <span class="sm:hidden">Update</span>
                                </a>

                                <form method="POST" action="{{ route('measurements.destroy', $measurement) }}" class="w-full" onsubmit="return confirm('Are you sure you want to delete your measurements? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-3 sm:px-4 py-2 sm:py-3 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-105 flex items-center justify-center shadow-lg">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Delete Measurements</span>
                                        <span class="sm:hidden">Delete</span>
                                    </button>
                                </form>
                                
                                <a href="{{ route('orders.create') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 sm:py-3 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-105 flex items-center justify-center shadow-lg">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Create Order</span>
                                    <span class="sm:hidden">Order</span>
                                </a>
                                
                                <a href="{{ route('appointments.create') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 sm:py-3 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:scale-105 flex items-center justify-center shadow-lg">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Schedule Fitting</span>
                                    <span class="sm:hidden">Fitting</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- No Measurements State -->
                <div class="bg-white overflow-hidden shadow-xl rounded-2xl">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No measurements recorded</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by adding your body measurements for accurate tailoring.</p>
                        <div class="mt-6">
                            <a href="{{ route('measurements.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent shadow-lg text-sm font-semibold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100 transition-all duration-300 transform hover:scale-105">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Measurements
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <div>
                <a href="{{ route('orders.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Orders
                </a>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Create New Order</h1>
                <p class="text-sm text-gray-600">Choose from our pre-made designs or create a custom order.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Step Indicator -->
            <div class="mb-3">
                <div class="flex items-center justify-center space-x-4">
                    <div id="step-1-indicator" class="flex items-center space-x-2">
                        <div class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-semibold">1</div>
                        <span class="text-blue-600 font-medium text-sm">Design Selection</span>
                    </div>
                    <div class="w-12 h-0.5 bg-gray-300"></div>
                    <div id="step-2-indicator" class="flex items-center space-x-2">
                        <div class="w-6 h-6 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-xs font-semibold">2</div>
                        <span class="text-gray-500 font-medium text-sm">Order Details</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Step 1: Design Selection -->
                <div id="step-1" class="step-content">
                    <!-- Design Type Selection -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-3">
                    <div class="p-3">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Choose Design Type</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <div class="relative">
                                <input type="radio" id="pre_made" name="design_type" value="pre_made" class="peer sr-only" {{ old('design_type') === 'pre_made' || $selectedDesign ? 'checked' : '' }}>
                                <label for="pre_made" class="flex flex-col p-3 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200">
                                    <div class="flex items-center mb-1">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        <span class="ml-2 text-sm font-medium text-gray-900">Pre-Made Design</span>
                                    </div>
                                    <p class="text-xs text-gray-600">Choose from our curated collection</p>
                                </label>
                            </div>

                            <div class="relative">
                                <input type="radio" id="custom" name="design_type" value="custom" class="peer sr-only" {{ old('design_type', $selectedDesign ? 'pre_made' : 'custom') === 'custom' ? 'checked' : '' }}>
                                <label for="custom" class="flex flex-col p-3 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200">
                                    <div class="flex items-center mb-1">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <span class="ml-2 text-sm font-medium text-gray-900">Custom Design</span>
                                    </div>
                                    <p class="text-xs text-gray-600">Create something unique</p>
                                </label>
                            </div>
                        </div>

                        @error('design_type')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Pre-made Design Selection -->
                <div id="pre-made-section" class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-3" style="display: {{ old('design_type') === 'pre_made' || $selectedDesign ? 'block' : 'none' }};">
                    <div class="p-3">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-sm font-medium text-gray-900">Select a Pre-Made Design</h3>
                            <a href="{{ route('designs.index') }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">
                                Browse All Designs →
                            </a>
                        </div>

                        <!-- Selected Design Display -->
                        @if($selectedDesign)
                            <div class="mb-3 p-3 bg-indigo-50 border border-indigo-200 rounded-lg">
                                <div class="flex items-start space-x-4">
                                    <img src="{{ $selectedDesign->featured_image_url }}"
                                         alt="{{ $selectedDesign->title }}"
                                         class="w-20 h-20 object-cover rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-medium text-gray-900">{{ $selectedDesign->title }}</h4>
                                        <p class="text-sm text-gray-600 mb-2">{{ $selectedDesign->description }}</p>
                                        <div class="flex flex-wrap gap-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ ucwords($selectedDesign->category) }}
                                            </span>
                                            @if($selectedDesign->price)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    ₱{{ number_format($selectedDesign->price, 0) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <button type="button" onclick="clearSelectedDesign()" class="text-gray-400 hover:text-red-600 transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <input type="hidden" name="design_brochure_id" value="{{ $selectedDesign->id }}">
                            </div>
                        @endif

                        <!-- Design Selection Grid -->
                        <div id="design-selection-container" class="{{ $selectedDesign ? 'hidden' : '' }}">
                            <!-- Quick Search -->
                            <div class="mb-4">
                                <input type="text"
                                       id="design-search"
                                       placeholder="Search designs..."
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Category Filter -->
                            <div class="mb-6">
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" onclick="filterDesigns('')" class="design-filter-btn bg-indigo-600 text-white px-3 py-1 rounded-full text-sm hover:bg-indigo-700 transition-colors duration-200">
                                        All
                                    </button>
                                    @foreach($categories as $category)
                                        <button type="button" onclick="filterDesigns('{{ $category }}')" class="design-filter-btn bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm hover:bg-gray-300 transition-colors duration-200">
                                            {{ ucwords($category) }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Design Grid -->
                            <div id="designs-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 max-h-64 overflow-y-auto">
                                @foreach($designBrochures as $brochure)
                                    <div class="design-option border border-gray-200 rounded-lg p-2 cursor-pointer hover:border-indigo-500 hover:shadow-md transition-all duration-200"
                                         data-design-id="{{ $brochure->id }}"
                                         data-category="{{ $brochure->category }}"
                                         data-title="{{ strtolower($brochure->title) }}"
                                         onclick="selectDesign({{ $brochure->id }}, '{{ $brochure->title }}', '{{ $brochure->description }}', '{{ $brochure->featured_image_url }}', '{{ ucwords($brochure->category) }}', {{ $brochure->price ?? 0 }})">
                                        <img src="{{ $brochure->featured_image_url }}"
                                             alt="{{ $brochure->title }}"
                                             class="w-full h-20 object-cover rounded-md mb-1">
                                        <h4 class="text-xs font-medium text-gray-900">{{ $brochure->title }}</h4>
                                        <p class="text-xs text-gray-600 mb-1 line-clamp-1">{{ $brochure->description }}</p>
                                        <div class="flex justify-between items-center">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucwords($brochure->category) }}
                                            </span>
                                            @if($brochure->price)
                                                <span class="text-sm font-bold text-indigo-600">₱{{ number_format($brochure->price, 0) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>


                        <!-- Design Notes for Pre-made -->
                        <div class="mt-4">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Design Notes & Customizations</label>
                            <textarea name="design_notes"
                                      rows="2"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                                      placeholder="Any specific requests or modifications to the selected design...">{{ old('design_notes') }}</textarea>
                            @error('design_notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @error('design_brochure_id')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Custom Design Section -->
                <div id="custom-section" class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-3" style="display: {{ old('design_type', $selectedDesign ? 'pre_made' : 'custom') === 'custom' ? 'block' : 'none' }};">
                    <div class="p-3">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Custom Design Details</h3>

                        <!-- Design Images Upload -->
                        <div class="mb-4">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Design Reference Images</label>
                            <div id="design-images-dropzone" class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-indigo-400 transition-colors duration-200 cursor-pointer">
                                <input type="file" id="design-images-input" name="design_images[]" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <div class="space-y-1">
                                    <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="text-xs text-gray-600">
                                        <span class="font-medium text-indigo-600 hover:text-indigo-500">Click to upload</span> or drag and drop
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB each</p>
                                </div>
                            </div>
                            <div id="design-images-preview" class="mt-3 grid-cols-2 md:grid-cols-3 gap-3" style="display: none;"></div>
                            @error('design_images.*')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>


                        <!-- Custom Design Notes -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Design Requirements</label>
                            <textarea name="design_notes"
                                      rows="2"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-xs"
                                      placeholder="Describe your design requirements in detail...">{{ old('design_notes') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Please provide as much detail as possible about your design preferences, style, colors, and any specific requirements.</p>
                            @error('design_notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                    <!-- Step 1 Navigation -->
                    <div class="flex justify-end mb-3">
                        <button type="button" id="next-step-1" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg font-medium text-sm transition-colors duration-200">
                            Continue to Order Details →
                        </button>
                    </div>
                </div>

                <!-- Step 2: Order Details -->
                <div id="step-2" class="step-content" style="display: none;">
                    <!-- Size & Measurements Section -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-3">
                        <div class="p-3">
                            <h3 class="text-sm font-medium text-gray-900 mb-2">Size & Measurements</h3>

                            <!-- Pre-made Design Sizing -->
                            <div id="preMadeSizing" style="display: none;">
                                <!-- Sizing Type -->
                                <div class="mb-3">
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <button type="button" id="standardSizeBtn" class="size-mode-btn flex-1 px-3 py-2 rounded border-2 border-blue-500 bg-blue-500 text-white font-medium text-xs">
                                            Standard Sizes
                                        </button>
                                        <button type="button" id="bulkSizeBtn" class="size-mode-btn flex-1 px-3 py-2 rounded border-2 border-gray-300 bg-white text-gray-700 font-medium text-xs">
                                            Bulk Custom Sizes
                                        </button>
                                    </div>
                                </div>

                                <!-- Standard Size Selection -->
                                <div id="standardSizeSection" class="mb-3">
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Select Size</label>
                                    @if($userMeasurement)
                                        <div class="mb-2 p-2 bg-blue-50 border border-blue-200 rounded text-xs">
                                            <span class="font-medium text-blue-800">
                                                @if($userMeasurement->is_custom)
                                                    You have custom measurements saved
                                                @else
                                                    Your current size: {{ $userMeasurement->size }}
                                                @endif
                                            </span>
                                            <span class="text-blue-600">(select size for this order)</span>
                                        </div>
                                    @endif
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @php
                                            $sizes = ['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL'];
                                        @endphp
                                        @foreach($sizes as $size)
                                            <button type="button" class="size-btn w-8 h-8 border-2 {{ $userMeasurement && !$userMeasurement->is_custom && $userMeasurement->size == $size ? 'border-blue-500 bg-blue-500 text-white' : 'border-gray-300 bg-white text-gray-700' }} font-bold text-xs rounded" data-size="{{ $size }}">
                                                {{ $size }}
                                            </button>
                                        @endforeach
                                    </div>

                                    <!-- Selected Size Info -->
                                    <div id="sizeInfo" class="hidden bg-blue-50 border border-blue-200 rounded p-2 mb-2">
                                        <h5 class="text-xs font-semibold text-blue-900 mb-1 text-center">Size <span id="selectedSizeName" class="bg-blue-500 text-white px-1 py-0.5 rounded text-xs"></span> Measurements</h5>
                                        <div class="grid grid-cols-3 gap-1 text-xs">
                                            <div class="text-center">
                                                <div class="text-gray-600">Chest</div>
                                                <div class="font-semibold text-xs" id="sizeChest"></div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-gray-600">Waist</div>
                                                <div class="font-semibold text-xs" id="sizeWaist"></div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-gray-600">Hip</div>
                                                <div class="font-semibold text-xs" id="sizeHip"></div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-gray-600">Shoulder</div>
                                                <div class="font-semibold text-xs" id="sizeShoulder"></div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-gray-600">Sleeve</div>
                                                <div class="font-semibold text-xs" id="sizeSleeve"></div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-gray-600">Inseam</div>
                                                <div class="font-semibold text-xs" id="sizeInseam"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="selected_size" id="selectedSize" value="">
                                </div>

                                <!-- Bulk Size Section -->
                                <div id="bulkSizeSection" class="hidden mb-3">
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Bulk Size Configuration</label>

                                    <!-- Pricing info -->
                                    <div class="mb-2 p-2 bg-blue-50 border border-blue-200 rounded text-xs" id="preMadePricing" style="display: none;">
                                        <span class="font-medium text-blue-800">Selected Design Price: ₱<span id="designPrice">0.00</span> per item</span>
                                    </div>

                                    <!-- Scrollable container for bulk sizes -->
                                    <div id="bulkSizeContainer" class="max-h-24 overflow-y-auto border border-gray-200 rounded p-1 mb-2 bg-gray-50">
                                        <div class="bulk-size-row border border-gray-200 rounded p-2 mb-1 bg-white">
                                            <div class="grid grid-cols-5 gap-1 items-center">
                                                <div>
                                                    <label class="block text-xs text-gray-500 mb-1">Size</label>
                                                    <select name="bulk_sizes[0][size]" class="w-full border border-gray-300 rounded px-1 py-1 text-xs">
                                                        <option value="">Size</option>
                                                        @foreach($sizes as $size)
                                                            <option value="{{ $size }}">{{ $size }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-xs text-gray-500 mb-1">Qty</label>
                                                    <input type="number" name="bulk_sizes[0][quantity]" min="1" class="w-full border border-gray-300 rounded px-1 py-1 text-xs" placeholder="0">
                                                </div>
                                                <div class="text-xs text-gray-600">
                                                    <div class="text-xs text-gray-500 mb-1">Items</div>
                                                    <div id="bulkQty0" class="font-medium">0</div>
                                                </div>
                                                <div class="text-xs text-gray-600">
                                                    <div class="text-xs text-gray-500 mb-1">Subtotal</div>
                                                    <div id="bulkTotal0" class="font-medium text-green-600">₱0.00</div>
                                                </div>
                                                <div class="text-right">
                                                    <button type="button" class="remove-bulk-size bg-red-500 hover:bg-red-600 text-white px-1 py-1 rounded text-xs" style="display: none;">×</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Summary and Add button -->
                                    <div class="flex justify-between items-center mb-2">
                                        <div class="text-xs text-gray-600">
                                            <div><span id="bulkSummary">Total: 0 items across 0 sizes</span></div>
                                            <div class="font-semibold text-green-600 text-sm" id="bulkGrandTotal">Grand Total: ₱0.00</div>
                                        </div>
                                        <button type="button" id="add-bulk-size" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">
                                            + Add Size
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Custom Design Sizing -->
                            <div id="customSizing" style="display: none;">
                                <!-- Measurement Type -->
                                <div class="mb-3">
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <button type="button" id="customStandardSizeBtn" class="custom-size-mode-btn flex-1 px-3 py-2 rounded border-2 border-blue-500 bg-blue-500 text-white font-medium text-xs">
                                            Standard Sizes
                                        </button>
                                        <button type="button" id="customMeasurementBtn" class="custom-size-mode-btn flex-1 px-3 py-2 rounded border-2 border-gray-300 bg-white text-gray-700 font-medium text-xs">
                                            Custom Measurements
                                        </button>
                                    </div>
                                </div>

                                <!-- Standard Size Selection for Custom -->
                                <div id="customStandardSizeSection" class="mb-3">
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Select Base Size</label>
                                    @if($userMeasurement && !$userMeasurement->is_custom)
                                        <div class="mb-2 p-2 bg-blue-50 border border-blue-200 rounded text-xs">
                                            <span class="font-medium text-blue-800">Your current size: {{ $userMeasurement->size }}</span>
                                            <span class="text-blue-600">(click to select or choose a different size)</span>
                                        </div>
                                    @endif
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @foreach($sizes as $size)
                                            <button type="button" class="custom-size-btn w-8 h-8 border-2 {{ $userMeasurement && !$userMeasurement->is_custom && $userMeasurement->size == $size ? 'border-blue-500 bg-blue-500 text-white' : 'border-gray-300 bg-white text-gray-700' }} font-bold text-xs rounded" data-size="{{ $size }}">
                                                {{ $size }}
                                            </button>
                                        @endforeach
                                    </div>

                                    <!-- Selected Size Info for Custom -->
                                    <div id="customSizeInfo" class="hidden bg-blue-50 border border-blue-200 rounded p-2 mb-3">
                                        <h5 class="text-xs font-semibold text-blue-900 mb-1 text-center">Base Size <span id="customSelectedSizeName" class="bg-blue-500 text-white px-1 py-0.5 rounded text-xs"></span> Measurements</h5>
                                        <div class="grid grid-cols-6 gap-1 text-xs">
                                            <div class="text-center">
                                                <div class="text-gray-600">Chest</div>
                                                <div class="font-semibold text-xs" id="customSizeChest"></div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-gray-600">Waist</div>
                                                <div class="font-semibold text-xs" id="customSizeWaist"></div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-gray-600">Hip</div>
                                                <div class="font-semibold text-xs" id="customSizeHip"></div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-gray-600">Shoulder</div>
                                                <div class="font-semibold text-xs" id="customSizeShoulder"></div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-gray-600">Sleeve</div>
                                                <div class="font-semibold text-xs" id="customSizeSleeve"></div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-gray-600">Inseam</div>
                                                <div class="font-semibold text-xs" id="customSizeInseam"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="custom_selected_size" id="customSelectedSize" value="">
                                </div>

                                <!-- Custom Measurements Section -->
                                <div id="customMeasurementSection" class="hidden mb-3">
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Enter Custom Measurements</label>
                                    @if($userMeasurement && $userMeasurement->is_custom)
                                        <div class="mb-2 p-2 bg-green-50 border border-green-200 rounded text-xs">
                                            <span class="font-medium text-green-800">Using your saved measurements</span>
                                            <span class="text-green-600">(you can modify them below if needed)</span>
                                        </div>
                                    @endif
                                    <div class="grid grid-cols-3 gap-2">
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-1">Chest *</label>
                                            <input type="number" name="custom_chest" step="0.1" min="0" max="200"
                                                   value="{{ $userMeasurement && $userMeasurement->is_custom ? $userMeasurement->chest : '' }}"
                                                   class="w-full border border-gray-300 rounded px-1 py-1 text-xs" placeholder="40.5">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-1">Waist *</label>
                                            <input type="number" name="custom_waist" step="0.1" min="0" max="200"
                                                   value="{{ $userMeasurement && $userMeasurement->is_custom ? $userMeasurement->waist : '' }}"
                                                   class="w-full border border-gray-300 rounded px-1 py-1 text-xs" placeholder="32.0">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-1">Hip *</label>
                                            <input type="number" name="custom_hip" step="0.1" min="0" max="200"
                                                   value="{{ $userMeasurement && $userMeasurement->is_custom ? $userMeasurement->hip : '' }}"
                                                   class="w-full border border-gray-300 rounded px-1 py-1 text-xs" placeholder="38.0">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-1">Shoulder *</label>
                                            <input type="number" name="custom_shoulder" step="0.1" min="0" max="100"
                                                   value="{{ $userMeasurement && $userMeasurement->is_custom ? $userMeasurement->shoulder : '' }}"
                                                   class="w-full border border-gray-300 rounded px-1 py-1 text-xs" placeholder="18.0">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-1">Sleeve *</label>
                                            <input type="number" name="custom_sleeve" step="0.1" min="0" max="100"
                                                   value="{{ $userMeasurement && $userMeasurement->is_custom ? $userMeasurement->sleeve_length : '' }}"
                                                   class="w-full border border-gray-300 rounded px-1 py-1 text-xs" placeholder="25.0">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-1">Inseam *</label>
                                            <input type="number" name="custom_inseam" step="0.1" min="0" max="150"
                                                   value="{{ $userMeasurement && $userMeasurement->is_custom ? $userMeasurement->inseam : '' }}"
                                                   class="w-full border border-gray-300 rounded px-1 py-1 text-xs" placeholder="32.0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Order Items -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg h-fit">
                        <div class="p-4">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Order Items</h3>

                            <!-- Pre-made design item info -->
                            <div id="preMadeItemInfo" class="mb-3 p-2 bg-blue-50 border border-blue-200 rounded" style="display: none;">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="font-medium text-blue-800" id="selectedDesignTitle">Selected Design</div>
                                        <div class="text-xs text-blue-600">Auto-added to your order items</div>
                                    </div>
                                    <div class="text-sm font-semibold text-green-600" id="selectedDesignPriceDisplay">₱0.00</div>
                                </div>
                            </div>
                            <div id="items-container">
                                <div class="item-row border border-gray-200 rounded-lg p-3 mb-3">
                                    <div class="grid grid-cols-1 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Item Name *</label>
                                            <input type="text" name="items[0][name]" class="w-full border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="e.g., Custom Suit" required>
                                            @error('items.0.name')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Quantity *</label>
                                            <input type="number" name="items[0][quantity]" min="1" class="w-full border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="1" required>
                                            @error('items.0.quantity')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                            <textarea name="items[0][description]" rows="2" class="w-full border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="Additional details about this item..."></textarea>
                                            @error('items.0.description')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="flex justify-end">
                                            <button type="button" class="remove-item bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm" style="display: none;">
                                                Remove Item
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" id="add-item" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium mt-4">
                                + Add Another Item
                            </button>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-4">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Order Details</h3>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Preferred Delivery Date</label>
                                    <input type="date" name="delivery_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                                    @error('delivery_date')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Additional Notes</label>
                                    <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="Any additional requirements, preferences, or special instructions...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Submit Buttons -->
                                <div class="pt-3 space-y-2">
                                    <button type="button" id="back-step-2" class="w-full bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-3 rounded-md transition duration-200 text-sm">
                                        ← Back to Design Selection
                                    </button>
                                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-3 rounded-md transition duration-200 text-sm">
                                        Place Order
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // User measurement data from server
        const userMeasurement = @json($userMeasurement);

        // Design brochures data
        const designBrochures = @json($designBrochures);

        // Current selected design data
        let selectedDesignData = null;

        // Predefined sizes data
        const predefinedSizes = {
            'XS': { chest: 34, waist: 28, hip: 34, shoulder: 16.5, sleeve_length: 23, inseam: 30 },
            'S': { chest: 36, waist: 30, hip: 36, shoulder: 17, sleeve_length: 24, inseam: 31 },
            'M': { chest: 40, waist: 34, hip: 40, shoulder: 18, sleeve_length: 25, inseam: 32 },
            'L': { chest: 44, waist: 38, hip: 44, shoulder: 19, sleeve_length: 26, inseam: 33 },
            'XL': { chest: 48, waist: 42, hip: 48, shoulder: 20, sleeve_length: 27, inseam: 33.5 },
            '2XL': { chest: 52, waist: 46, hip: 52, shoulder: 21, sleeve_length: 28, inseam: 34 },
            '3XL': { chest: 56, waist: 50, hip: 56, shoulder: 22, sleeve_length: 29, inseam: 34.5 }
        };


        // Initial state - hide add item button if pre-made is selected
        function updateAddItemButtonVisibility() {
            const designType = document.querySelector('input[name="design_type"]:checked');
            const addItemButton = document.getElementById('add-item');

            if (designType && designType.value === 'pre_made') {
                addItemButton.style.display = 'none';
            } else {
                addItemButton.style.display = 'block';
            }
        }

        // Call on page load
        updateAddItemButtonVisibility();

        // Multi-step form navigation
        let currentStep = 1;

        function updateStepIndicator(step) {
            const step1Indicator = document.getElementById('step-1-indicator');
            const step2Indicator = document.getElementById('step-2-indicator');

            if (step === 1) {
                // Step 1 active
                step1Indicator.querySelector('div').className = 'w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-semibold';
                step1Indicator.querySelector('span').className = 'text-blue-600 font-medium text-sm';
                step2Indicator.querySelector('div').className = 'w-6 h-6 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-xs font-semibold';
                step2Indicator.querySelector('span').className = 'text-gray-500 font-medium text-sm';
            } else {
                // Step 2 active
                step1Indicator.querySelector('div').className = 'w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs font-semibold';
                step1Indicator.querySelector('span').className = 'text-green-600 font-medium text-sm';
                step2Indicator.querySelector('div').className = 'w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-semibold';
                step2Indicator.querySelector('span').className = 'text-blue-600 font-medium text-sm';
            }
        }

        function showStep(step) {
            document.getElementById('step-1').style.display = step === 1 ? 'block' : 'none';
            document.getElementById('step-2').style.display = step === 2 ? 'block' : 'none';
            updateStepIndicator(step);
            currentStep = step;
        }

        function validateStep1() {
            // Check if design type is selected
            const designType = document.querySelector('input[name="design_type"]:checked');
            if (!designType) {
                alert('Please select a design type (Pre-Made or Custom).');
                return false;
            }

            // If pre-made design is selected, ensure a design is chosen
            if (designType.value === 'pre_made') {
                const selectedDesign = document.querySelector('input[name="design_brochure_id"]');
                if (!selectedDesign || !selectedDesign.value) {
                    alert('Please select a pre-made design.');
                    return false;
                }
            }

            return true;
        }

        function validateStep2() {
            // Get the selected design type
            const designType = document.querySelector('input[name="design_type"]:checked');
            if (!designType) return false;

            // Validate sizing based on design type
            if (designType.value === 'pre_made') {
                if (!validatePreMadeSizing()) {
                    return false;
                }
            } else {
                if (!validateCustomSizing()) {
                    return false;
                }
            }

            // Validate items (existing validation)
            const items = [];
            const itemRows = document.querySelectorAll('.item-row');

            itemRows.forEach((row, index) => {
                const nameInput = row.querySelector('input[name*="[name]"]');
                const quantityInput = row.querySelector('input[name*="[quantity]"]');

                if (nameInput && quantityInput) {
                    const name = nameInput.value.trim();
                    const quantity = quantityInput.value.trim();

                    if (name && quantity && parseInt(quantity) > 0) {
                        items.push({ name, quantity: parseInt(quantity) });
                    }
                }
            });

            if (items.length === 0) {
                alert('Please fill in at least one item with a name and quantity.');
                return false;
            }

            return true;
        }

        function validatePreMadeSizing() {
            const standardSizeSection = document.getElementById('standardSizeSection');
            const bulkSizeSection = document.getElementById('bulkSizeSection');

            if (!standardSizeSection.classList.contains('hidden')) {
                // Standard size mode
                const selectedSize = document.getElementById('selectedSize').value;
                if (!selectedSize) {
                    alert('Please select a size for your order.');
                    return false;
                }
            } else if (!bulkSizeSection.classList.contains('hidden')) {
                // Bulk size mode
                const bulkRows = document.querySelectorAll('.bulk-size-row');
                let hasValidRow = false;

                for (let row of bulkRows) {
                    const sizeSelect = row.querySelector('select[name*="[size]"]');
                    const quantityInput = row.querySelector('input[name*="[quantity]"]');

                    if (sizeSelect.value && quantityInput.value && parseInt(quantityInput.value) > 0) {
                        hasValidRow = true;
                        break;
                    }
                }

                if (!hasValidRow) {
                    alert('Please add at least one valid size configuration with size and quantity.');
                    return false;
                }
            }

            return true;
        }

        function validateCustomSizing() {
            const customStandardSizeSection = document.getElementById('customStandardSizeSection');
            const customMeasurementSection = document.getElementById('customMeasurementSection');

            if (!customStandardSizeSection.classList.contains('hidden')) {
                // Standard size mode for custom
                const selectedSize = document.getElementById('customSelectedSize').value;
                if (!selectedSize) {
                    alert('Please select a base size for your custom design.');
                    return false;
                }
            } else if (!customMeasurementSection.classList.contains('hidden')) {
                // Custom measurements mode
                const requiredFields = ['custom_chest', 'custom_waist', 'custom_hip', 'custom_shoulder', 'custom_sleeve', 'custom_inseam'];
                const missingFields = [];

                for (let field of requiredFields) {
                    const input = document.querySelector(`input[name="${field}"]`);
                    if (!input || !input.value || parseFloat(input.value) <= 0) {
                        missingFields.push(field.replace('custom_', '').replace('_', ' '));
                    }
                }

                if (missingFields.length > 0) {
                    alert(`Please provide measurements for: ${missingFields.join(', ')}`);
                    return false;
                }
            }

            return true;
        }

        // Step navigation event listeners
        document.getElementById('next-step-1').addEventListener('click', function() {
            if (validateStep1()) {
                showStep(2);

                // Show appropriate sizing section based on design type
                updateSizingSectionVisibility();

                window.scrollTo(0, 0);
            }
        });

        document.getElementById('back-step-2').addEventListener('click', function() {
            showStep(1);
            window.scrollTo(0, 0);
        });

        function updateSizingSectionVisibility() {
            const designType = document.querySelector('input[name="design_type"]:checked');
            const preMadeSizing = document.getElementById('preMadeSizing');
            const customSizing = document.getElementById('customSizing');

            if (designType && designType.value === 'pre_made') {
                preMadeSizing.style.display = 'block';
                customSizing.style.display = 'none';
            } else if (designType && designType.value === 'custom') {
                preMadeSizing.style.display = 'none';
                customSizing.style.display = 'block';
            }
        }

        // Size selection for pre-made designs
        document.querySelectorAll('.size-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const size = this.dataset.size;
                selectSize(size);
            });
        });

        function selectSize(size) {
            // Update button states
            document.querySelectorAll('.size-btn').forEach(btn => {
                btn.classList.remove('border-blue-500', 'bg-blue-500', 'text-white');
                btn.classList.add('border-gray-300', 'bg-white', 'text-gray-700');
            });

            // Highlight selected button
            const selectedBtn = document.querySelector(`[data-size="${size}"]`);
            selectedBtn.classList.remove('border-gray-300', 'bg-white', 'text-gray-700');
            selectedBtn.classList.add('border-blue-500', 'bg-blue-500', 'text-white');

            // Show size info
            showSizeInfo(size);

            // Update hidden input
            document.getElementById('selectedSize').value = size;
        }

        function showSizeInfo(size) {
            const sizeData = predefinedSizes[size];

            document.getElementById('selectedSizeName').textContent = size;
            document.getElementById('sizeChest').textContent = sizeData.chest + '"';
            document.getElementById('sizeWaist').textContent = sizeData.waist + '"';
            document.getElementById('sizeHip').textContent = sizeData.hip + '"';
            document.getElementById('sizeShoulder').textContent = sizeData.shoulder + '"';
            document.getElementById('sizeSleeve').textContent = sizeData.sleeve_length + '"';
            document.getElementById('sizeInseam').textContent = sizeData.inseam + '"';

            document.getElementById('sizeInfo').classList.remove('hidden');
        }

        // Size selection for custom designs
        document.querySelectorAll('.custom-size-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const size = this.dataset.size;
                selectCustomSize(size);
            });
        });

        function selectCustomSize(size) {
            // Update button states
            document.querySelectorAll('.custom-size-btn').forEach(btn => {
                btn.classList.remove('border-blue-500', 'bg-blue-500', 'text-white');
                btn.classList.add('border-gray-300', 'bg-white', 'text-gray-700');
            });

            // Highlight selected button
            const selectedBtn = document.querySelector(`.custom-size-btn[data-size="${size}"]`);
            selectedBtn.classList.remove('border-gray-300', 'bg-white', 'text-gray-700');
            selectedBtn.classList.add('border-blue-500', 'bg-blue-500', 'text-white');

            // Show size info
            showCustomSizeInfo(size);

            // Update hidden input
            document.getElementById('customSelectedSize').value = size;
        }

        function showCustomSizeInfo(size) {
            const sizeData = predefinedSizes[size];

            document.getElementById('customSelectedSizeName').textContent = size;
            document.getElementById('customSizeChest').textContent = sizeData.chest + '"';
            document.getElementById('customSizeWaist').textContent = sizeData.waist + '"';
            document.getElementById('customSizeHip').textContent = sizeData.hip + '"';
            document.getElementById('customSizeShoulder').textContent = sizeData.shoulder + '"';
            document.getElementById('customSizeSleeve').textContent = sizeData.sleeve_length + '"';
            document.getElementById('customSizeInseam').textContent = sizeData.inseam + '"';

            document.getElementById('customSizeInfo').classList.remove('hidden');
        }

        // Pre-made design sizing mode toggle
        document.getElementById('standardSizeBtn').addEventListener('click', function() {
            this.className = 'size-mode-btn flex-1 px-3 py-2 rounded border-2 border-blue-500 bg-blue-500 text-white font-medium text-xs';
            document.getElementById('bulkSizeBtn').className = 'size-mode-btn flex-1 px-3 py-2 rounded border-2 border-gray-300 bg-white text-gray-700 font-medium text-xs';

            document.getElementById('standardSizeSection').classList.remove('hidden');
            document.getElementById('bulkSizeSection').classList.add('hidden');

            // Hide bulk mode notice and reset quantity to 1
            hideBulkModeNotice();
        });

        document.getElementById('bulkSizeBtn').addEventListener('click', function() {
            this.className = 'size-mode-btn flex-1 px-3 py-2 rounded border-2 border-blue-500 bg-blue-500 text-white font-medium text-xs';
            document.getElementById('standardSizeBtn').className = 'size-mode-btn flex-1 px-3 py-2 rounded border-2 border-gray-300 bg-white text-gray-700 font-medium text-xs';

            document.getElementById('standardSizeSection').classList.add('hidden');
            document.getElementById('bulkSizeSection').classList.remove('hidden');

            // Update bulk totals when switching to bulk mode
            updateBulkTotals();

            // Add a helpful notice about auto-population
            showBulkModeNotice();
        });

        // Custom design sizing mode toggle
        document.getElementById('customStandardSizeBtn').addEventListener('click', function() {
            this.className = 'custom-size-mode-btn flex-1 px-3 py-2 rounded border-2 border-blue-500 bg-blue-500 text-white font-medium text-xs';
            document.getElementById('customMeasurementBtn').className = 'custom-size-mode-btn flex-1 px-3 py-2 rounded border-2 border-gray-300 bg-white text-gray-700 font-medium text-xs';

            document.getElementById('customStandardSizeSection').classList.remove('hidden');
            document.getElementById('customMeasurementSection').classList.add('hidden');
        });

        document.getElementById('customMeasurementBtn').addEventListener('click', function() {
            this.className = 'custom-size-mode-btn flex-1 px-3 py-2 rounded border-2 border-blue-500 bg-blue-500 text-white font-medium text-xs';
            document.getElementById('customStandardSizeBtn').className = 'custom-size-mode-btn flex-1 px-3 py-2 rounded border-2 border-gray-300 bg-white text-gray-700 font-medium text-xs';

            document.getElementById('customStandardSizeSection').classList.add('hidden');
            document.getElementById('customMeasurementSection').classList.remove('hidden');
        });

        // Bulk size management
        let bulkSizeCount = 1;

        document.getElementById('add-bulk-size').addEventListener('click', function() {
            const container = document.getElementById('bulkSizeContainer');
            const newRow = document.querySelector('.bulk-size-row').cloneNode(true);

            // Update field names and clear values
            const selects = newRow.querySelectorAll('select');
            const inputs = newRow.querySelectorAll('input');

            selects.forEach(select => {
                select.name = select.name.replace(/\[0\]/, `[${bulkSizeCount}]`);
                select.value = '';
            });

            inputs.forEach(input => {
                if (input.type === 'number') {
                    input.name = input.name.replace(/\[0\]/, `[${bulkSizeCount}]`);
                    input.value = '';
                }
            });

            // Update total display IDs
            const qtyDisplay = newRow.querySelector('[id^="bulkQty"]');
            const totalDisplay = newRow.querySelector('[id^="bulkTotal"]');
            qtyDisplay.id = `bulkQty${bulkSizeCount}`;
            totalDisplay.id = `bulkTotal${bulkSizeCount}`;
            qtyDisplay.textContent = '0';
            totalDisplay.textContent = '₱0.00';

            // Show remove button
            newRow.querySelector('.remove-bulk-size').style.display = 'inline-block';

            container.appendChild(newRow);
            bulkSizeCount++;
            updateBulkSizeRemoveButtons();
            updateBulkTotals();
        });

        // Handle remove bulk size
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-bulk-size')) {
                e.target.closest('.bulk-size-row').remove();
                updateBulkSizeRemoveButtons();
                updateBulkSizeIndices();
                updateBulkTotals();
            }
        });

        // Handle bulk size changes for live total calculation
        document.addEventListener('input', function(e) {
            if (e.target.matches('input[name*="bulk_sizes"][name*="[quantity]"]')) {
                updateBulkTotals();
            }
        });

        document.addEventListener('change', function(e) {
            if (e.target.matches('select[name*="bulk_sizes"][name*="[size]"]')) {
                updateBulkTotals();
            }
        });

        function updateBulkSizeRemoveButtons() {
            const rows = document.querySelectorAll('.bulk-size-row');
            rows.forEach((row, index) => {
                const removeBtn = row.querySelector('.remove-bulk-size');
                if (rows.length > 1) {
                    removeBtn.style.display = 'inline-block';
                } else {
                    removeBtn.style.display = 'none';
                }
            });
        }

        function updateBulkSizeIndices() {
            const rows = document.querySelectorAll('.bulk-size-row');
            rows.forEach((row, index) => {
                const selects = row.querySelectorAll('select');
                const inputs = row.querySelectorAll('input[type="number"]');
                const totalDisplay = row.querySelector('[id^="bulkTotal"]');

                selects.forEach(select => {
                    select.name = select.name.replace(/\[\d+\]/, `[${index}]`);
                });

                inputs.forEach(input => {
                    input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
                });

                const qtyDisplay = row.querySelector('[id^="bulkQty"]');
                if (totalDisplay) {
                    totalDisplay.id = `bulkTotal${index}`;
                }
                if (qtyDisplay) {
                    qtyDisplay.id = `bulkQty${index}`;
                }
            });
            bulkSizeCount = rows.length;
        }

        function updateBulkTotals() {
            const rows = document.querySelectorAll('.bulk-size-row');
            let totalItems = 0;
            let validSizes = 0;
            let grandTotal = 0;
            const designPrice = selectedDesignData ? selectedDesignData.price : 0;

            console.log('Updating bulk totals, designPrice:', designPrice, 'selectedDesignData:', selectedDesignData);

            rows.forEach((row, index) => {
                const sizeSelect = row.querySelector('select[name*="[size]"]');
                const quantityInput = row.querySelector('input[name*="[quantity]"]');
                const qtyDisplay = row.querySelector(`#bulkQty${index}`);
                const totalDisplay = row.querySelector(`#bulkTotal${index}`);

                const size = sizeSelect.value;
                const quantity = parseInt(quantityInput.value) || 0;

                if (size && quantity > 0) {
                    totalItems += quantity;
                    validSizes++;
                    const subtotal = quantity * designPrice;
                    grandTotal += subtotal;

                    if (qtyDisplay) {
                        qtyDisplay.textContent = quantity.toString();
                    }
                    if (totalDisplay) {
                        totalDisplay.textContent = `₱${subtotal.toFixed(2)}`;
                    }
                } else {
                    if (qtyDisplay) {
                        qtyDisplay.textContent = '0';
                    }
                    if (totalDisplay) {
                        totalDisplay.textContent = '₱0.00';
                    }
                }
            });

            // Update summary
            const summary = document.getElementById('bulkSummary');
            const grandTotalElement = document.getElementById('bulkGrandTotal');

            if (summary) {
                summary.textContent = `Total: ${totalItems} items across ${validSizes} sizes`;
            }
            if (grandTotalElement) {
                grandTotalElement.textContent = `Grand Total: ₱${grandTotal.toFixed(2)}`;
            }

            // Auto-populate order items with total bulk quantity
            autoPopulateBulkOrderItems(totalItems);
        }

        function showBulkModeNotice() {
            // Show a temporary notice about bulk mode auto-population
            const orderItemsSection = document.querySelector('.bg-white .p-4');
            if (orderItemsSection) {
                // Remove any existing notice
                const existingNotice = orderItemsSection.querySelector('.bulk-mode-notice');
                if (existingNotice) {
                    existingNotice.remove();
                }

                // Add new notice
                const notice = document.createElement('div');
                notice.className = 'bulk-mode-notice bg-yellow-50 border border-yellow-200 rounded p-2 mb-3 text-xs text-yellow-800';
                notice.innerHTML = '<strong>Bulk Mode Active:</strong> Order quantity will automatically update as you configure bulk sizes above.';

                const itemsContainer = document.getElementById('items-container');
                if (itemsContainer) {
                    itemsContainer.parentNode.insertBefore(notice, itemsContainer);
                }
            }
        }

        function hideBulkModeNotice() {
            // Remove bulk mode notice
            const existingNotice = document.querySelector('.bulk-mode-notice');
            if (existingNotice) {
                existingNotice.remove();
            }

            // Reset quantity to 1 when switching back to standard mode
            const firstItemQuantity = document.querySelector('input[name="items[0][quantity]"]');
            if (firstItemQuantity && firstItemQuantity.readOnly) {
                firstItemQuantity.value = '1';
            }
        }

        function autoPopulateBulkOrderItems(totalQuantity) {
            // Only auto-populate when in pre-made design mode with bulk sizing
            if (!selectedDesignData) return;

            const bulkSizeSection = document.getElementById('bulkSizeSection');
            if (bulkSizeSection.classList.contains('hidden')) return; // Not in bulk mode

            // Find the first item row (auto-populated design item)
            const firstItemQuantity = document.querySelector('input[name="items[0][quantity]"]');
            if (firstItemQuantity && firstItemQuantity.readOnly) {
                // This is the auto-populated design item, update its quantity
                firstItemQuantity.value = totalQuantity;

                // Update the item description to show bulk details
                const firstItemDescription = document.querySelector('textarea[name="items[0][description]"]');
                if (firstItemDescription) {
                    const rows = document.querySelectorAll('.bulk-size-row');
                    let sizeBreakdown = [];

                    rows.forEach((row) => {
                        const sizeSelect = row.querySelector('select[name*="[size]"]');
                        const quantityInput = row.querySelector('input[name*="[quantity]"]');

                        const size = sizeSelect.value;
                        const quantity = parseInt(quantityInput.value) || 0;

                        if (size && quantity > 0) {
                            sizeBreakdown.push(`${size}: ${quantity} pcs`);
                        }
                    });

                    if (sizeBreakdown.length > 0) {
                        firstItemDescription.value = `Bulk order breakdown: ${sizeBreakdown.join(', ')}`;
                    }
                }
            }
        }

        // Design type toggle
        document.querySelectorAll('input[name="design_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const preMadeSection = document.getElementById('pre-made-section');
                const customSection = document.getElementById('custom-section');
                const addItemButton = document.getElementById('add-item');

                if (this.value === 'pre_made') {
                    preMadeSection.style.display = 'block';
                    customSection.style.display = 'none';
                    // Hide "Add Another Item" button for pre-made designs
                    addItemButton.style.display = 'none';
                } else {
                    preMadeSection.style.display = 'none';
                    customSection.style.display = 'block';
                    // Show "Add Another Item" button for custom designs
                    addItemButton.style.display = 'block';
                }
            });
        });

        // Design selection
        function selectDesign(id, title, description, image, category, price) {
            const container = document.getElementById('design-selection-container');
            const selectedArea = container.previousElementSibling;

            // Store selected design data
            selectedDesignData = {
                id: id,
                title: title,
                description: description,
                image: image,
                category: category,
                price: parseFloat(price)
            };

            // Create selected design display
            const selectedHtml = `
                <div class="mb-6 p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                    <div class="flex items-start space-x-4">
                        <img src="${image}" alt="${title}" class="w-20 h-20 object-cover rounded-lg">
                        <div class="flex-1">
                            <h4 class="text-lg font-medium text-gray-900">${title}</h4>
                            <p class="text-sm text-gray-600 mb-2">${description}</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    ${category}
                                </span>
                                ${price > 0 ? `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">₱${parseFloat(price).toFixed(2)}</span>` : ''}
                            </div>
                        </div>
                        <button type="button" onclick="clearSelectedDesign()" class="text-gray-400 hover:text-red-600 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <input type="hidden" name="design_brochure_id" value="${id}">
                </div>
            `;

            selectedArea.innerHTML = selectedHtml;
            container.classList.add('hidden');

            // Update pricing displays
            updateDesignPricing();
        }

        function updateDesignPricing() {
            if (!selectedDesignData) return;

            // Update bulk pricing display
            document.getElementById('designPrice').textContent = selectedDesignData.price.toFixed(2);
            document.getElementById('preMadePricing').style.display = 'block';

            // Update pre-made item info
            document.getElementById('selectedDesignTitle').textContent = selectedDesignData.title;
            document.getElementById('selectedDesignPriceDisplay').textContent = '₱' + selectedDesignData.price.toFixed(2);
            document.getElementById('preMadeItemInfo').style.display = 'block';

            // Auto-populate first item with design info
            autoPopulateDesignItem();

            // Update bulk totals
            updateBulkTotals();
        }

        function autoPopulateDesignItem() {
            if (!selectedDesignData) return;

            const firstItemName = document.querySelector('input[name="items[0][name]"]');
            const firstItemQuantity = document.querySelector('input[name="items[0][quantity]"]');

            if (firstItemName && !firstItemName.value) {
                firstItemName.value = selectedDesignData.title;
                firstItemName.readOnly = true;
                firstItemName.classList.add('bg-gray-100');

                // Also make quantity read-only and set default value
                if (firstItemQuantity) {
                    firstItemQuantity.readOnly = true;
                    firstItemQuantity.classList.add('bg-gray-100');
                    firstItemQuantity.value = '1'; // Default value, will be updated by bulk
                }

                // Add a note that this is auto-populated
                const noteElement = firstItemName.parentElement.querySelector('.auto-populated-note');
                if (!noteElement) {
                    const note = document.createElement('div');
                    note.className = 'auto-populated-note text-xs text-blue-600 mt-1';
                    note.textContent = 'Auto-populated from selected design. Quantity will update based on bulk sizing.';
                    firstItemName.parentElement.appendChild(note);
                }
            }
        }

        function clearSelectedDesign() {
            const container = document.getElementById('design-selection-container');
            const selectedArea = container.previousElementSibling;

            selectedArea.innerHTML = '';
            container.classList.remove('hidden');

            // Clear selected design data
            selectedDesignData = null;

            // Hide pricing displays
            document.getElementById('preMadePricing').style.display = 'none';
            document.getElementById('preMadeItemInfo').style.display = 'none';

            // Clear auto-populated item name and quantity
            const firstItemName = document.querySelector('input[name="items[0][name]"]');
            const firstItemQuantity = document.querySelector('input[name="items[0][quantity]"]');

            if (firstItemName && firstItemName.readOnly) {
                firstItemName.value = '';
                firstItemName.readOnly = false;
                firstItemName.classList.remove('bg-gray-100');

                const noteElement = firstItemName.parentElement.querySelector('.auto-populated-note');
                if (noteElement) {
                    noteElement.remove();
                }
            }

            if (firstItemQuantity && firstItemQuantity.readOnly) {
                firstItemQuantity.value = '1';
                firstItemQuantity.readOnly = false;
                firstItemQuantity.classList.remove('bg-gray-100');
            }

            // Update bulk totals
            updateBulkTotals();
        }

        // Design search and filter
        document.getElementById('design-search').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const designs = document.querySelectorAll('.design-option');

            designs.forEach(design => {
                const title = design.getAttribute('data-title');
                if (title.includes(searchTerm)) {
                    design.style.display = 'block';
                } else {
                    design.style.display = 'none';
                }
            });
        });

        function filterDesigns(category) {
            const designs = document.querySelectorAll('.design-option');
            const buttons = document.querySelectorAll('.design-filter-btn');

            // Update button styles
            buttons.forEach(btn => {
                btn.className = 'design-filter-btn bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm hover:bg-gray-300 transition-colors duration-200';
            });
            event.target.className = 'design-filter-btn bg-indigo-600 text-white px-3 py-1 rounded-full text-sm hover:bg-indigo-700 transition-colors duration-200';

            // Filter designs
            designs.forEach(design => {
                const designCategory = design.getAttribute('data-category');
                if (category === '' || designCategory === category) {
                    design.style.display = 'block';
                } else {
                    design.style.display = 'none';
                }
            });
        }

        // Item management (existing functionality)
        let itemCount = 1;

        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const newItem = document.querySelector('.item-row').cloneNode(true);

            // Update field names and clear values
            const inputs = newItem.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                input.name = input.name.replace(/\[0\]/, `[${itemCount}]`);
                input.value = '';
                if (input.type === 'number') {
                    input.value = '1';
                }
            });

            // Show remove button
            newItem.querySelector('.remove-item').style.display = 'block';

            container.appendChild(newItem);
            itemCount++;
            updateRemoveButtons();
        });

        // Handle remove item
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('.item-row').remove();
                updateRemoveButtons();
                updateItemIndices();
            }
        });

        function updateRemoveButtons() {
            const items = document.querySelectorAll('.item-row');
            items.forEach((item, index) => {
                const removeBtn = item.querySelector('.remove-item');
                if (items.length > 1) {
                    removeBtn.style.display = 'block';
                } else {
                    removeBtn.style.display = 'none';
                }
            });
        }

        function updateItemIndices() {
            const items = document.querySelectorAll('.item-row');
            items.forEach((item, index) => {
                const inputs = item.querySelectorAll('input, textarea');
                inputs.forEach(input => {
                    input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
                });
            });
            itemCount = items.length;
        }

        // Initialize form on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded, initializing form...');

            // Remove any existing items-data hidden field to avoid conflicts
            const existingHiddenField = document.getElementById('items-data');
            if (existingHiddenField) {
                existingHiddenField.remove();
                console.log('Removed existing hidden field to avoid conflicts');
            }

            // Initialize pre-selected design data if any
            initializeSelectedDesign();

            // Initialize user measurement defaults
            initializeUserMeasurementDefaults();
        });

        function initializeSelectedDesign() {
            // Check if there's a pre-selected design
            const designInput = document.querySelector('input[name="design_brochure_id"]');
            if (designInput && designInput.value) {
                // Find the design data from the brochures list
                const designId = parseInt(designInput.value);
                const selectedDesign = designBrochures.find(d => d.id === designId);

                if (selectedDesign) {
                    selectedDesignData = {
                        id: selectedDesign.id,
                        title: selectedDesign.title,
                        description: selectedDesign.description,
                        image: selectedDesign.featured_image_url,
                        category: selectedDesign.category,
                        price: parseFloat(selectedDesign.price || 0)
                    };

                    // Update pricing displays immediately
                    updateDesignPricing();
                    console.log('Initialized pre-selected design:', selectedDesignData);
                }
            }
        }

        function initializeUserMeasurementDefaults() {
            if (!userMeasurement) return;

            // For pre-made designs: Set default size if user has standard size measurement
            if (!userMeasurement.is_custom && userMeasurement.size) {
                const selectedSizeBtn = document.querySelector(`.size-btn[data-size="${userMeasurement.size}"]`);
                if (selectedSizeBtn) {
                    selectSize(userMeasurement.size);
                }
            }

            // For custom designs: Handle both standard size and custom measurements
            if (userMeasurement.is_custom) {
                // Switch to custom measurements mode by default for users with custom measurements
                document.getElementById('customMeasurementBtn').click();
            } else if (userMeasurement.size) {
                // Set standard size for custom design section
                const customSizeBtn = document.querySelector(`.custom-size-btn[data-size="${userMeasurement.size}"]`);
                if (customSizeBtn) {
                    selectCustomSize(userMeasurement.size);
                }
                // Set the hidden input value
                document.getElementById('customSelectedSize').value = userMeasurement.size;
            }
        }

        // Collect items data on form submit
        document.querySelector('form').addEventListener('submit', function(e) {
            // If we're not on step 2, prevent submission
            if (currentStep !== 2) {
                e.preventDefault();
                alert('Please complete all steps before submitting the order.');
                return false;
            }

            const items = [];
            const itemRows = document.querySelectorAll('.item-row');

            itemRows.forEach((row, index) => {
                const nameInput = row.querySelector('input[name*="[name]"]');
                const quantityInput = row.querySelector('input[name*="[quantity]"]');
                const descriptionInput = row.querySelector('textarea[name*="[description]"]');

                if (nameInput && quantityInput) {
                    const name = nameInput.value.trim();
                    const quantity = quantityInput.value.trim();
                    const description = descriptionInput ? descriptionInput.value.trim() : '';

                    if (name && quantity && parseInt(quantity) > 0) {
                        const item = {
                            name: name,
                            quantity: parseInt(quantity),
                            description: description || ''
                        };
                        items.push(item);
                    }
                }
            });

            // Ensure we have at least one item
            if (items.length === 0) {
                e.preventDefault();
                alert('Please fill in at least one item with a name and quantity.');
                return false;
            }

            // Validate both steps before final submission
            if (!validateStep1()) {
                e.preventDefault();
                showStep(1);
                return false;
            }

            if (!validateStep2()) {
                e.preventDefault();
                return false;
            }

            return true;
        });

        // Image preview functionality (existing)
        document.getElementById('design-images-input').addEventListener('change', function(e) {
            const files = e.target.files;
            const preview = document.getElementById('design-images-preview');

            if (files.length > 0) {
                preview.innerHTML = '';
                preview.style.display = 'grid';

                Array.from(files).forEach((file, index) => {
                    // Validate file type
                    if (!file.type.match('image.*')) {
                        alert(`File "${file.name}" is not a valid image file.`);
                        return;
                    }

                    // Validate file size (2MB limit)
                    if (file.size > 2 * 1024 * 1024) {
                        alert(`File "${file.name}" is too large. Maximum size is 2MB.`);
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('div');
                        img.className = 'relative';
                        img.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-24 object-cover rounded-md">
                            <div class="absolute top-1 left-1 bg-black bg-opacity-50 text-white text-xs px-1 rounded">${file.name}</div>
                        `;
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            } else {
                preview.style.display = 'none';
            }
        });
    </script>
</x-app-layout>

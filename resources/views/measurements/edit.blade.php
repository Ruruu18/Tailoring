<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <div>
                <a href="{{ route('measurements.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-blue-600 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Measurements
                </a>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Update Your Measurements</h1>
                <p class="text-sm text-gray-600">Modify your measurements or change to a different size.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('measurements.update', $measurement) }}" method="POST" id="measurementForm">
                @csrf
                @method('PUT')

                <!-- Mode Selection -->
                <div class="bg-white rounded-lg shadow-md mb-3">
                    <div class="p-3">
                        <h2 class="text-md font-semibold text-gray-900 mb-3">How would you like to update measurements?</h2>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <button type="button" id="standardSizeBtn" class="size-mode-btn {{ !$measurement->is_custom ? 'active' : '' }} flex-1 px-3 py-2 rounded border-2 {{ !$measurement->is_custom ? 'border-blue-500 bg-blue-500 text-white' : 'border-gray-300 bg-white text-gray-700' }} font-medium text-sm">
                                Standard Sizes
                            </button>
                            <button type="button" id="customSizeBtn" class="size-mode-btn {{ $measurement->is_custom ? 'active' : '' }} flex-1 px-3 py-2 rounded border-2 {{ $measurement->is_custom ? 'border-blue-500 bg-blue-500 text-white' : 'border-gray-300 bg-white text-gray-700' }} font-medium text-sm">
                                Custom Measurements
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Standard Size Selection -->
                <div id="standardSizeSection" class="bg-white rounded-lg shadow-md {{ $measurement->is_custom ? 'hidden' : '' }}">
                    <div class="p-3">
                        <!-- T-Shirt Visual -->
                        <div class="text-center mb-3">
                            <img src="{{ asset('images/t-shirt.png') }}" alt="T-shirt Size Guide" class="w-16 h-16 mx-auto mb-1">
                            <h3 class="text-md font-semibold text-gray-900">Select Your Size</h3>
                        </div>

                        <!-- Size Buttons -->
                        <div class="flex flex-wrap justify-center gap-1 mb-3">
                            @php
                                $sizes = ['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL'];
                            @endphp
                            @foreach($sizes as $size)
                                <button type="button" class="size-btn w-10 h-10 rounded border-2 {{ $measurement->size == $size ? 'border-blue-500 bg-blue-500 text-white' : 'border-gray-300 bg-white text-gray-700' }} font-bold text-xs" data-size="{{ $size }}">
                                    {{ $size }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Selected Size Info -->
                        <div id="sizeInfo" class="{{ $measurement->size ? '' : 'hidden' }} bg-blue-50 border border-blue-200 rounded p-2 mb-3">
                            <h4 class="text-xs font-semibold text-blue-900 mb-1 text-center">Size <span id="selectedSizeName" class="bg-blue-500 text-white px-1 py-0.5 rounded text-xs">{{ $measurement->size }}</span></h4>
                            <div class="grid grid-cols-6 gap-1 text-xs">
                                <div class="text-center">
                                    <div class="text-gray-600">Chest</div>
                                    <div class="font-semibold text-xs" id="sizeChest">{{ $measurement->chest }}"</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-gray-600">Waist</div>
                                    <div class="font-semibold text-xs" id="sizeWaist">{{ $measurement->waist }}"</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-gray-600">Hip</div>
                                    <div class="font-semibold text-xs" id="sizeHip">{{ $measurement->hip }}"</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-gray-600">Shoulder</div>
                                    <div class="font-semibold text-xs" id="sizeShoulder">{{ $measurement->shoulder }}"</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-gray-600">Sleeve</div>
                                    <div class="font-semibold text-xs" id="sizeSleeve">{{ $measurement->sleeve_length }}"</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-gray-600">Inseam</div>
                                    <div class="font-semibold text-xs" id="sizeInseam">{{ $measurement->inseam }}"</div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
                            <textarea name="notes" rows="1" class="w-full border border-gray-300 rounded px-2 py-1 text-xs" placeholder="Any notes...">{{ $measurement->notes }}</textarea>
                        </div>

                        <!-- Hidden inputs for standard size -->
                        <input type="hidden" name="size" id="selectedSize" value="{{ $measurement->size }}">
                        <input type="hidden" name="is_custom" value="0">
                        <input type="hidden" name="chest" id="standardChest" value="{{ $measurement->chest }}">
                        <input type="hidden" name="waist" id="standardWaist" value="{{ $measurement->waist }}">
                        <input type="hidden" name="hip" id="standardHip" value="{{ $measurement->hip }}">
                        <input type="hidden" name="shoulder" id="standardShoulder" value="{{ $measurement->shoulder }}">
                        <input type="hidden" name="sleeve_length" id="standardSleeve" value="{{ $measurement->sleeve_length }}">
                        <input type="hidden" name="shirt_length" id="standardShirtLength" value="{{ $measurement->shirt_length }}">
                        <input type="hidden" name="short_waist" id="standardShortWaist" value="{{ $measurement->short_waist }}">
                        <input type="hidden" name="inseam" id="standardInseam" value="{{ $measurement->inseam }}">
                    </div>
                </div>

                <!-- Custom Measurements Section -->
                <div id="customSizeSection" class="{{ !$measurement->is_custom ? 'hidden' : '' }} bg-white rounded-lg shadow-md">
                    <div class="p-3">
                        <h3 class="text-md font-semibold text-gray-900 mb-3">Enter Your Measurements</h3>
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Chest *</label>
                                <input type="number" name="custom_chest" step="0.1" min="0" max="200" value="{{ $measurement->is_custom ? $measurement->chest : '' }}" class="w-full border border-gray-300 rounded px-1 py-1 text-xs" placeholder="40.5">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Waist *</label>
                                <input type="number" name="custom_waist" step="0.1" min="0" max="200" value="{{ $measurement->is_custom ? $measurement->waist : '' }}" class="w-full border border-gray-300 rounded px-1 py-1 text-xs" placeholder="32.0">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Hip *</label>
                                <input type="number" name="custom_hip" step="0.1" min="0" max="200" value="{{ $measurement->is_custom ? $measurement->hip : '' }}" class="w-full border border-gray-300 rounded px-1 py-1 text-xs" placeholder="38.0">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Shoulder *</label>
                                <input type="number" name="custom_shoulder" step="0.1" min="0" max="100" value="{{ $measurement->is_custom ? $measurement->shoulder : '' }}" class="w-full border border-gray-300 rounded px-1 py-1 text-xs" placeholder="18.0">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Sleeve *</label>
                                <input type="number" name="custom_sleeve_length" step="0.1" min="0" max="100" value="{{ $measurement->is_custom ? $measurement->sleeve_length : '' }}" class="w-full border border-gray-300 rounded px-1 py-1 text-xs" placeholder="25.0">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Inseam *</label>
                                <input type="number" name="custom_inseam" step="0.1" min="0" max="150" value="{{ $measurement->is_custom ? $measurement->inseam : '' }}" class="w-full border border-gray-300 rounded px-1 py-1 text-xs" placeholder="32.0">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Short Waist</label>
                                <input type="number" name="custom_short_waist" step="0.1" min="0" max="200" value="{{ $measurement->is_custom ? $measurement->short_waist : '' }}" class="w-full border border-gray-300 rounded px-1 py-1 text-xs" placeholder="30.0">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Shirt Length</label>
                                <input type="number" name="custom_shirt_length" step="0.1" min="0" max="100" value="{{ $measurement->is_custom ? $measurement->shirt_length : '' }}" class="w-full border border-gray-300 rounded px-1 py-1 text-xs" placeholder="28.0">
                            </div>
                        </div>

                        <!-- Custom Notes -->
                        <div class="mt-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
                            <textarea name="custom_notes" rows="1" class="w-full border border-gray-300 rounded px-1 py-1 text-xs" placeholder="Any notes...">{{ $measurement->is_custom ? $measurement->notes : '' }}</textarea>
                        </div>

                        <!-- Hidden field for custom mode -->
                        <input type="hidden" name="custom_is_custom" value="1">
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-3 flex gap-2">
                    <button type="submit" id="submitBtn" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium">
                        Update Measurements
                    </button>
                    <a href="{{ route('measurements.index') }}" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded text-sm font-medium">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Predefined sizes data
        const predefinedSizes = @json(\App\Models\Measurement::getPredefinedSizes());

        let selectedSize = "{{ $measurement->size }}";
        let isCustomMode = {{ $measurement->is_custom ? 'true' : 'false' }};

        // Mode switching
        document.getElementById('standardSizeBtn').addEventListener('click', function() {
            switchToStandardMode();
        });

        document.getElementById('customSizeBtn').addEventListener('click', function() {
            switchToCustomMode();
        });

        function switchToStandardMode() {
            isCustomMode = false;

            // Update buttons
            document.getElementById('standardSizeBtn').className = 'size-mode-btn active flex-1 px-3 py-2 rounded border-2 border-blue-500 bg-blue-500 text-white font-medium text-sm';
            document.getElementById('customSizeBtn').className = 'size-mode-btn flex-1 px-3 py-2 rounded border-2 border-gray-300 bg-white text-gray-700 font-medium text-sm';

            // Show/hide sections
            document.getElementById('standardSizeSection').classList.remove('hidden');
            document.getElementById('customSizeSection').classList.add('hidden');

            updateSubmitButton();
        }

        function switchToCustomMode() {
            isCustomMode = true;

            // Update buttons
            document.getElementById('customSizeBtn').className = 'size-mode-btn active flex-1 px-3 py-2 rounded border-2 border-blue-500 bg-blue-500 text-white font-medium text-sm';
            document.getElementById('standardSizeBtn').className = 'size-mode-btn flex-1 px-3 py-2 rounded border-2 border-gray-300 bg-white text-gray-700 font-medium text-sm';

            // Show/hide sections
            document.getElementById('standardSizeSection').classList.add('hidden');
            document.getElementById('customSizeSection').classList.remove('hidden');

            updateSubmitButton();
        }

        // Size button handlers
        document.querySelectorAll('.size-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const size = this.dataset.size;
                selectSize(size);
            });
        });

        function selectSize(size) {
            selectedSize = size;

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

            // Update hidden inputs
            updateStandardSizeInputs(size);

            // Update submit button
            updateSubmitButton();
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

        function updateStandardSizeInputs(size) {
            const sizeData = predefinedSizes[size];

            document.getElementById('selectedSize').value = size;
            document.getElementById('standardChest').value = sizeData.chest;
            document.getElementById('standardWaist').value = sizeData.waist;
            document.getElementById('standardHip').value = sizeData.hip;
            document.getElementById('standardShoulder').value = sizeData.shoulder;
            document.getElementById('standardSleeve').value = sizeData.sleeve_length;
            document.getElementById('standardShirtLength').value = sizeData.shirt_length;
            document.getElementById('standardShortWaist').value = sizeData.short_waist;
            document.getElementById('standardInseam').value = sizeData.inseam;
        }

        function updateSubmitButton() {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = false; // Always enabled for edit
        }

        // Form submission handler
        document.getElementById('measurementForm').addEventListener('submit', function(e) {
            if (isCustomMode) {
                // Copy custom values to main fields
                const customFields = {
                    'custom_chest': 'chest',
                    'custom_waist': 'waist',
                    'custom_hip': 'hip',
                    'custom_shoulder': 'shoulder',
                    'custom_sleeve_length': 'sleeve_length',
                    'custom_inseam': 'inseam',
                    'custom_short_waist': 'short_waist',
                    'custom_shirt_length': 'shirt_length',
                    'custom_notes': 'notes',
                    'custom_is_custom': 'is_custom'
                };

                Object.keys(customFields).forEach(customField => {
                    const customInput = document.querySelector(`[name="${customField}"]`);
                    if (customInput) {
                        let mainInput = document.querySelector(`[name="${customFields[customField]}"]`);
                        if (!mainInput) {
                            mainInput = document.createElement('input');
                            mainInput.type = 'hidden';
                            mainInput.name = customFields[customField];
                            this.appendChild(mainInput);
                        }
                        mainInput.value = customInput.value || '';
                    }
                });

                // Set size to null for custom measurements
                let sizeInput = document.querySelector('[name="size"]');
                if (!sizeInput) {
                    sizeInput = document.createElement('input');
                    sizeInput.type = 'hidden';
                    sizeInput.name = 'size';
                    this.appendChild(sizeInput);
                }
                sizeInput.value = '';
            }
        });

        // Initialize
        updateSubmitButton();
    </script>
</x-app-layout>
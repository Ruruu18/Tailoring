<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.measurements.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Customer Measurement') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.measurements.store') }}" method="POST" id="measurementForm">
                @csrf

                <!-- Customer Selection -->
                <div class="bg-white rounded-lg shadow-md mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Customer *</label>
                                <select name="user_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Choose a customer...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mode Selection -->
                <div class="bg-white rounded-lg shadow-md mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Measurement Type</h3>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <button type="button" id="standardSizeBtn" class="size-mode-btn active flex-1 px-6 py-4 rounded-lg border-2 border-blue-500 bg-blue-500 text-white font-medium transition-all duration-200 hover:bg-blue-600">
                                Standard Sizes
                            </button>
                            <button type="button" id="customSizeBtn" class="size-mode-btn flex-1 px-6 py-4 rounded-lg border-2 border-gray-300 bg-white text-gray-700 font-medium transition-all duration-200 hover:border-gray-400">
                                Custom Measurements
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Standard Size Selection -->
                <div id="standardSizeSection" class="bg-white rounded-lg shadow-md mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Select Standard Size</h3>

                        <!-- Size Buttons -->
                        <div class="flex flex-wrap justify-center gap-3 mb-6">
                            @php
                                $sizes = ['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL'];
                            @endphp
                            @foreach($sizes as $size)
                                <button type="button" class="size-btn w-16 h-16 rounded-lg border-2 border-gray-300 font-bold text-lg bg-white transition-all duration-200 hover:border-blue-500 hover:bg-blue-50" data-size="{{ $size }}">
                                    {{ $size }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Selected Size Info -->
                        <div id="sizeInfo" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                            <h4 class="text-lg font-semibold text-blue-900 mb-4 text-center">Size <span id="selectedSizeName" class="bg-blue-500 text-white px-3 py-1 rounded"></span> Measurements</h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                                <div class="text-center">
                                    <div class="text-gray-600">Chest</div>
                                    <div class="font-semibold text-lg" id="sizeChest"></div>
                                </div>
                                <div class="text-center">
                                    <div class="text-gray-600">Waist</div>
                                    <div class="font-semibold text-lg" id="sizeWaist"></div>
                                </div>
                                <div class="text-center">
                                    <div class="text-gray-600">Hip</div>
                                    <div class="font-semibold text-lg" id="sizeHip"></div>
                                </div>
                                <div class="text-center">
                                    <div class="text-gray-600">Shoulder</div>
                                    <div class="font-semibold text-lg" id="sizeShoulder"></div>
                                </div>
                                <div class="text-center">
                                    <div class="text-gray-600">Sleeve</div>
                                    <div class="font-semibold text-lg" id="sizeSleeve"></div>
                                </div>
                                <div class="text-center">
                                    <div class="text-gray-600">Inseam</div>
                                    <div class="font-semibold text-lg" id="sizeInseam"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                            <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Any special notes or adjustments...">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Hidden inputs for standard size -->
                        <input type="hidden" name="size" id="selectedSize" value="{{ old('size') }}">
                        <input type="hidden" name="is_custom" value="0">
                        <input type="hidden" name="chest" id="standardChest" value="{{ old('chest') }}">
                        <input type="hidden" name="waist" id="standardWaist" value="{{ old('waist') }}">
                        <input type="hidden" name="hip" id="standardHip" value="{{ old('hip') }}">
                        <input type="hidden" name="shoulder" id="standardShoulder" value="{{ old('shoulder') }}">
                        <input type="hidden" name="sleeve_length" id="standardSleeve" value="{{ old('sleeve_length') }}">
                        <input type="hidden" name="shirt_length" id="standardShirtLength" value="{{ old('shirt_length') }}">
                        <input type="hidden" name="short_waist" id="standardShortWaist" value="{{ old('short_waist') }}">
                        <input type="hidden" name="inseam" id="standardInseam" value="{{ old('inseam') }}">
                    </div>
                </div>

                <!-- Custom Measurements Section -->
                <div id="customSizeSection" class="hidden bg-white rounded-lg shadow-md mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Enter Custom Measurements</h3>

                        <!-- Essential Measurements -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Chest (inches) *</label>
                                <input type="number" name="custom_chest" step="0.1" min="0" max="200" value="{{ old('custom_chest') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="40.5">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Waist (inches) *</label>
                                <input type="number" name="custom_waist" step="0.1" min="0" max="200" value="{{ old('custom_waist') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="32.0">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Hip (inches) *</label>
                                <input type="number" name="custom_hip" step="0.1" min="0" max="200" value="{{ old('custom_hip') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="38.0">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Shoulder (inches) *</label>
                                <input type="number" name="custom_shoulder" step="0.1" min="0" max="100" value="{{ old('custom_shoulder') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="18.0">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sleeve Length (inches) *</label>
                                <input type="number" name="custom_sleeve_length" step="0.1" min="0" max="100" value="{{ old('custom_sleeve_length') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="25.0">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Inseam (inches) *</label>
                                <input type="number" name="custom_inseam" step="0.1" min="0" max="150" value="{{ old('custom_inseam') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="32.0">
                            </div>
                        </div>

                        <!-- Additional Measurements -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Shirt Length (inches)</label>
                                <input type="number" name="custom_shirt_length" step="0.1" min="0" max="100" value="{{ old('custom_shirt_length') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="28.0">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Short Waist (inches)</label>
                                <input type="number" name="custom_short_waist" step="0.1" min="0" max="200" value="{{ old('custom_short_waist') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="30.0">
                            </div>
                        </div>

                        <!-- Custom Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                            <textarea name="custom_notes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Any special notes or adjustments...">{{ old('custom_notes') }}</textarea>
                        </div>

                        <!-- Hidden field for custom mode -->
                        <input type="hidden" name="custom_is_custom" value="1">
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" id="submitBtn" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        Create Measurement
                    </button>
                    <a href="{{ route('admin.measurements.index') }}" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Predefined sizes data
        const predefinedSizes = @json(\App\Models\Measurement::getPredefinedSizes());

        let selectedSize = null;
        let isCustomMode = false;

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
            document.getElementById('standardSizeBtn').className = 'size-mode-btn active flex-1 px-6 py-4 rounded-lg border-2 border-blue-500 bg-blue-500 text-white font-medium transition-all duration-200 hover:bg-blue-600';
            document.getElementById('customSizeBtn').className = 'size-mode-btn flex-1 px-6 py-4 rounded-lg border-2 border-gray-300 bg-white text-gray-700 font-medium transition-all duration-200 hover:border-gray-400';

            // Show/hide sections
            document.getElementById('standardSizeSection').classList.remove('hidden');
            document.getElementById('customSizeSection').classList.add('hidden');

            updateSubmitButton();
        }

        function switchToCustomMode() {
            isCustomMode = true;

            // Update buttons
            document.getElementById('customSizeBtn').className = 'size-mode-btn active flex-1 px-6 py-4 rounded-lg border-2 border-blue-500 bg-blue-500 text-white font-medium transition-all duration-200 hover:bg-blue-600';
            document.getElementById('standardSizeBtn').className = 'size-mode-btn flex-1 px-6 py-4 rounded-lg border-2 border-gray-300 bg-white text-gray-700 font-medium transition-all duration-200 hover:border-gray-400';

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
            const userSelected = document.querySelector('[name="user_id"]').value;

            if (isCustomMode) {
                submitBtn.disabled = !userSelected;
            } else {
                submitBtn.disabled = !selectedSize || !userSelected;
            }
        }

        // User selection change handler
        document.querySelector('[name="user_id"]').addEventListener('change', updateSubmitButton);

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
                    'custom_shirt_length': 'shirt_length',
                    'custom_short_waist': 'short_waist',
                    'custom_inseam': 'inseam',
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
</x-admin-layout>
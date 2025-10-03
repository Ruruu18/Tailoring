<x-admin-layout>
    <x-slot name="header">
        <div class="space-y-4">
            <!-- Navigation Buttons -->
            <div>
                <a href="{{ route('admin.design-brochures.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Design Brochures
                </a>
            </div>

            <!-- Title Section -->
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Create Design Brochure') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Add a new design to your collection</p>
            </div>
        </div>
    </x-slot>

    <div class="py-3">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4">
                    <form method="POST" action="{{ route('admin.design-brochures.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            <!-- Left Column -->
                            <div class="space-y-4">
                                <!-- Title -->
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                                    <input type="text"
                                           name="title"
                                           id="title"
                                           value="{{ old('title') }}"
                                           required
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-500 @enderror"
                                           placeholder="Enter design title">
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                    <textarea name="description"
                                              id="description"
                                              rows="4"
                                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror"
                                              placeholder="Enter design description">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                                    <input type="text"
                                           name="category"
                                           id="category"
                                           value="{{ old('category') }}"
                                           required
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('category') border-red-500 @enderror"
                                           placeholder="e.g., Traditional, Modern, Formal">
                                    @error('category')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Style Type -->
                                <div>
                                    <label for="style_type" class="block text-sm font-medium text-gray-700 mb-2">Style Type</label>
                                    <input type="text"
                                           name="style_type"
                                           id="style_type"
                                           value="{{ old('style_type') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('style_type') border-red-500 @enderror"
                                           placeholder="e.g., Casual, Business, Evening">
                                    @error('style_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Gender -->
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                                    <select name="gender" id="gender" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('gender') border-red-500 @enderror">
                                        <option value="">Select Gender</option>
                                        <option value="men" {{ old('gender') === 'men' ? 'selected' : '' }}>Men</option>
                                        <option value="women" {{ old('gender') === 'women' ? 'selected' : '' }}>Women</option>
                                        <option value="unisex" {{ old('gender') === 'unisex' ? 'selected' : '' }}>Unisex</option>
                                    </select>
                                    @error('gender')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tags -->
                                <div>
                                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                                    <input type="text"
                                           name="tags"
                                           id="tags"
                                           value="{{ old('tags') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('tags') border-red-500 @enderror"
                                           placeholder="Enter tags separated by commas">
                                    <p class="mt-1 text-xs text-gray-500">Separate tags with commas (e.g., "elegant, formal, wedding")</p>
                                    @error('tags')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-4">
                                <!-- Fabric Suggestions -->
                                <div>
                                    <label for="fabric_suggestions" class="block text-sm font-medium text-gray-700 mb-2">Fabric Suggestions</label>
                                    <textarea name="fabric_suggestions"
                                              id="fabric_suggestions"
                                              rows="2"
                                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('fabric_suggestions') border-red-500 @enderror"
                                              placeholder="Suggest appropriate fabrics for this design">{{ old('fabric_suggestions') }}</textarea>
                                    @error('fabric_suggestions')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Price -->
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Base Price ($)</label>
                                    <input type="number"
                                           name="price"
                                           id="price"
                                           value="{{ old('price') }}"
                                           step="0.01"
                                           min="0"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('price') border-red-500 @enderror"
                                           placeholder="0.00">
                                    @error('price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Sort Order -->
                                <div>
                                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                                    <input type="number"
                                           name="sort_order"
                                           id="sort_order"
                                           value="{{ old('sort_order') }}"
                                           min="0"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('sort_order') border-red-500 @enderror"
                                           placeholder="Leave empty for auto">
                                    <p class="mt-1 text-xs text-gray-500">Lower numbers appear first.</p>
                                    @error('sort_order')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Third Column -->
                            <div class="space-y-4">
                                <!-- Status Options -->
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox"
                                               name="is_active"
                                               id="is_active"
                                               value="1"
                                               {{ old('is_active', true) ? 'checked' : '' }}
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="hidden" name="is_featured" value="0">
                                        <input type="checkbox"
                                               name="is_featured"
                                               id="is_featured"
                                               value="1"
                                               {{ old('is_featured') ? 'checked' : '' }}
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="is_featured" class="ml-2 block text-sm text-gray-900">Featured Design</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Images Section -->
                        <div class="mt-4 space-y-3">
                            <!-- Design Images -->
                            <div>
                                <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Design Images *</label>
                                <div class="mt-1 flex justify-center px-3 py-3 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors duration-200">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Upload images</span>
                                                <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" required>
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
                                    </div>
                                </div>
                                @error('images')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @error('images.*')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Preview area -->
                            <div id="image-preview" class="grid grid-cols-2 md:grid-cols-4 gap-3" style="display: none;">
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-4 flex justify-end space-x-3">
                            <a href="{{ route('admin.design-brochures.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create Design Brochure
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for image preview -->
    <script>
        document.getElementById('images').addEventListener('change', function(e) {
            previewImages(e.target.files, 'image-preview');
        });

        function previewImages(files, containerId) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';

            if (files.length > 0) {
                container.style.display = 'grid';

                Array.from(files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'relative';
                            div.innerHTML = `
                                <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg shadow-md">
                                <div class="absolute top-1 left-1 bg-black bg-opacity-50 text-white text-xs px-1 py-0.5 rounded">
                                    ${file.name}
                                </div>
                            `;
                            container.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                container.style.display = 'none';
            }
        }
    </script>
</x-admin-layout>
<x-admin-layout>
    <x-slot name="header">
        <div class="space-y-4">
            <!-- Navigation Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('admin.design-brochures.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Design Brochures
                </a>
                <a href="{{ route('admin.design-brochures.show', $designBrochure) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View Design
                </a>
            </div>

            <!-- Title Section -->
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Edit Design Brochure') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Editing: {{ $designBrochure->title }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.design-brochures.update', $designBrochure) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')



                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <!-- Title -->
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                                    <input type="text"
                                           name="title"
                                           id="title"
                                           value="{{ old('title', $designBrochure->title) }}"
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
                                              placeholder="Enter design description">{{ old('description', $designBrochure->description) }}</textarea>
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
                                           value="{{ old('category', $designBrochure->category) }}"
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
                                           value="{{ old('style_type', $designBrochure->style_type) }}"
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
                                        <option value="men" {{ old('gender', $designBrochure->gender) === 'men' ? 'selected' : '' }}>Men</option>
                                        <option value="women" {{ old('gender', $designBrochure->gender) === 'women' ? 'selected' : '' }}>Women</option>
                                        <option value="unisex" {{ old('gender', $designBrochure->gender) === 'unisex' ? 'selected' : '' }}>Unisex</option>
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
                                           value="{{ old('tags', $designBrochure->tags) }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('tags') border-red-500 @enderror"
                                           placeholder="Enter tags separated by commas">
                                    <p class="mt-1 text-xs text-gray-500">Separate tags with commas (e.g., "elegant, formal, wedding")</p>
                                    @error('tags')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <!-- Fabric Suggestions -->
                                <div>
                                    <label for="fabric_suggestions" class="block text-sm font-medium text-gray-700 mb-2">Fabric Suggestions</label>
                                    <textarea name="fabric_suggestions"
                                              id="fabric_suggestions"
                                              rows="3"
                                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('fabric_suggestions') border-red-500 @enderror"
                                              placeholder="Suggest appropriate fabrics for this design">{{ old('fabric_suggestions', $designBrochure->fabric_suggestions) }}</textarea>
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
                                           value="{{ old('price', $designBrochure->price) }}"
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
                                           value="{{ old('sort_order', $designBrochure->sort_order) }}"
                                           min="0"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('sort_order') border-red-500 @enderror"
                                           placeholder="0">
                                    <p class="mt-1 text-xs text-gray-500">Lower numbers appear first.</p>
                                    @error('sort_order')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Status Options -->
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox"
                                               name="is_active"
                                               id="is_active"
                                               value="1"
                                               {{ old('is_active', $designBrochure->is_active) ? 'checked' : '' }}
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="hidden" name="is_featured" value="0">
                                        <input type="checkbox"
                                               name="is_featured"
                                               id="is_featured"
                                               value="1"
                                               {{ old('is_featured', $designBrochure->is_featured) ? 'checked' : '' }}
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="is_featured" class="ml-2 block text-sm text-gray-900">Featured Design</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Current Images Section -->
                        @if($designBrochure->images)
                            <div class="mt-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Current Images</h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    @foreach($designBrochure->images as $image)
                                        <div class="relative">
                                            <img src="{{ asset('storage/' . $image) }}" class="w-full h-32 object-cover rounded-lg shadow-md">
                                            <div class="absolute top-2 right-2">
                                                <button type="button"
                                                        onclick="deleteCurrentImage('{{ $image }}', this)"
                                                        class="bg-red-500 hover:bg-red-600 text-white rounded-full p-1 transition-colors duration-200"
                                                        title="Delete image">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- New Images Section -->
                        <div class="mt-8 space-y-6">
                            <!-- Design Images -->
                            <div>
                                <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Add New Images</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors duration-200">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Upload new images</span>
                                                <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB each</p>
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
                            <div id="image-preview" class="grid grid-cols-2 md:grid-cols-4 gap-4" style="display: none;">
                            </div>
                        </div>


                        <!-- Submit Buttons -->
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('admin.design-brochures.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Update Design Brochure
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for image preview and removal -->
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
                                <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg shadow-md">
                                <div class="absolute top-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
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

        function deleteCurrentImage(imagePath, buttonElement) {
            if (confirm('Are you sure you want to delete this image?')) {
                // Show loading state
                buttonElement.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';

                // Make AJAX request to delete image immediately
                fetch(`/admin/design-brochures/{{ $designBrochure->id }}/delete-image`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        image_path: imagePath
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the image container from the page
                        buttonElement.closest('.relative').remove();

                        // Show success message
                        alert('Image deleted successfully!');
                    } else {
                        alert('Error: ' + data.message);
                        // Restore button
                        buttonElement.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the image.');
                    // Restore button
                    buttonElement.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                });
            }
        }


    </script>
</x-admin-layout>
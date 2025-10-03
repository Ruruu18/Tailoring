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
            </div>

            <!-- Title Section -->
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $designBrochure->title }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">{{ ucwords($designBrochure->category) }} Design</p>
            </div>
        </div>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-x-6 lg:items-start">
                <!-- Image gallery -->
                <div class="flex flex-col-reverse">
                    <!-- Image selector -->
                    @if(count($designBrochure->images) > 1)
                        <div class="hidden mt-3 w-full max-w-2xl mx-auto sm:block lg:max-w-none">
                            <div class="grid grid-cols-4 gap-3" role="tablist" aria-orientation="horizontal">
                                @foreach($designBrochure->images as $index => $image)
                                    <button
                                        class="relative h-20 bg-white rounded-md flex items-center justify-center text-sm font-medium uppercase text-gray-900 cursor-pointer hover:bg-gray-50 focus:outline-none focus:ring focus:ring-offset-4 focus:ring-indigo-500"
                                        onclick="showImage('{{ asset('storage/' . $image) }}')"
                                        role="tab"
                                        type="button"
                                        aria-controls="tabs-{{ $index }}-panel"
                                        aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                                    >
                                        <span class="sr-only">Image {{ $index + 1 }}</span>
                                        <span class="absolute inset-0 rounded-md overflow-hidden">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Design image {{ $index + 1 }}" class="w-full h-full object-center object-cover">
                                        </span>
                                        <span class="absolute inset-0 rounded-md ring-2 ring-offset-2 pointer-events-none {{ $index === 0 ? 'ring-indigo-500' : 'ring-transparent' }}" id="tabs-{{ $index }}-ring" aria-hidden="true"></span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="w-full aspect-w-1 aspect-h-1">
                        <img
                            id="main-image"
                            src="{{ $designBrochure->featured_image_url }}"
                            alt="{{ $designBrochure->title }}"
                            class="w-full h-full object-center object-cover sm:rounded-lg max-h-80 lg:max-h-96"
                        >
                    </div>
                </div>

                <!-- Design details -->
                <div class="mt-6 px-4 sm:px-0 sm:mt-8 lg:mt-0">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex gap-2">
                            @if($designBrochure->is_featured)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    Featured
                                </span>
                            @endif

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $designBrochure->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $designBrochure->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <!-- Quick Actions -->
                        <div class="flex gap-2">
                            <button onclick="toggleFeatured({{ $designBrochure->id }})"
                                    class="p-2 {{ $designBrochure->is_featured ? 'bg-yellow-100 text-yellow-600 hover:bg-yellow-200' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }} rounded-lg transition-colors duration-200"
                                    title="Toggle Featured">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </button>

                            <button onclick="toggleActive({{ $designBrochure->id }})"
                                    class="p-2 {{ $designBrochure->is_active ? 'bg-green-100 text-green-600 hover:bg-green-200' : 'bg-red-100 text-red-600 hover:bg-red-200' }} rounded-lg transition-colors duration-200"
                                    title="Toggle Active Status">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    @if($designBrochure->price)
                        <div class="mb-4">
                            <h2 class="sr-only">Price information</h2>
                            <p class="text-2xl text-gray-900 font-bold">₱{{ number_format($designBrochure->price, 2) }}</p>
                            <p class="text-sm text-gray-500 mt-1">Base price</p>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h3 class="sr-only">Description</h3>
                        <div class="text-base text-gray-700">
                            <p>{{ $designBrochure->description ?: 'No description provided.' }}</p>
                        </div>
                    </div>

                    <!-- Design Information -->
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Design Information</h3>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Category</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucwords($designBrochure->category) }}</dd>
                                </div>

                                @if($designBrochure->style_type)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Style Type</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ ucwords($designBrochure->style_type) }}</dd>
                                    </div>
                                @endif

                                @if($designBrochure->gender)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ ucwords($designBrochure->gender) }}</dd>
                                    </div>
                                @endif

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Sort Order</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $designBrochure->sort_order }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $designBrochure->created_at->format('M d, Y') }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $designBrochure->updated_at->format('M d, Y') }}</dd>
                                </div>

                                @if($designBrochure->fabric_suggestions)
                                    <div class="sm:col-span-2">
                                        <dt class="text-sm font-medium text-gray-500">Fabric Suggestions</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $designBrochure->fabric_suggestions }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>

                        @if($designBrochure->tags_array)
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tags</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($designBrochure->tags_array as $tag)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            {{ trim($tag) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Usage Statistics -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Usage Statistics</h3>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Orders Using This Design</dt>
                                    <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $designBrochure->orders->count() }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Images</dt>
                                    <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ count($designBrochure->images) }}</dd>
                                </div>
                            </dl>

                            @if($designBrochure->orders->count() > 0)
                                <div class="mt-4">
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Recent Orders</h4>
                                    <div class="space-y-1">
                                        @foreach($designBrochure->orders->take(5) as $order)
                                            <div class="flex justify-between items-center text-sm">
                                                <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-800">
                                                    {{ $order->order_number }}
                                                </a>
                                                <span class="text-gray-500">{{ $order->created_at->format('M d, Y') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <a href="{{ route('admin.design-brochures.edit', $designBrochure) }}"
                               class="bg-indigo-600 border border-transparent rounded-md py-2 px-4 flex items-center justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Design
                            </a>

                            <a href="{{ route('designs.show', $designBrochure) }}"
                               target="_blank"
                               class="bg-blue-600 border border-transparent rounded-md py-2 px-4 flex items-center justify-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                View Customer Page
                            </a>

                            <form method="POST" action="{{ route('admin.design-brochures.destroy', $designBrochure) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Are you sure you want to delete this design brochure? This action cannot be undone.')"
                                        class="w-full bg-red-600 border border-transparent rounded-md py-2 px-4 flex items-center justify-center text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                        {{ $designBrochure->orders->count() > 0 ? 'disabled title="Cannot delete design brochure with associated orders"' : '' }}>
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    {{ $designBrochure->orders->count() > 0 ? 'Cannot Delete' : 'Delete Design' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showImage(imageUrl) {
            document.getElementById('main-image').src = imageUrl;

            // Update ring indicators
            document.querySelectorAll('[id^="tabs-"][id$="-ring"]').forEach(ring => {
                ring.classList.remove('ring-indigo-500');
                ring.classList.add('ring-transparent');
            });

            // Add ring to selected image
            const buttons = document.querySelectorAll('[role="tab"]');
            buttons.forEach((button, index) => {
                if (button.onclick.toString().includes(imageUrl.split('/').pop())) {
                    document.getElementById(`tabs-${index}-ring`).classList.remove('ring-transparent');
                    document.getElementById(`tabs-${index}-ring`).classList.add('ring-indigo-500');
                    button.setAttribute('aria-selected', 'true');
                } else {
                    button.setAttribute('aria-selected', 'false');
                }
            });
        }

        function toggleFeatured(brochureId) {
            fetch(`/admin/design-brochures/${brochureId}/toggle-featured`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function toggleActive(brochureId) {
            fetch(`/admin/design-brochures/${brochureId}/toggle-active`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</x-admin-layout>
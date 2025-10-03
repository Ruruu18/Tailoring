<x-app-layout>
    <x-slot name="header">
        <div class="space-y-2">
            <div>
                <a href="{{ route('designs.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Design Gallery
                </a>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $designBrochure->title }}</h1>
                <p class="text-sm text-gray-600">{{ ucwords($designBrochure->category) }} Design</p>
            </div>
        </div>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-x-6 lg:items-start">
                <!-- Image gallery -->
                <div class="space-y-6">
                    <div class="w-full aspect-w-1 aspect-h-1">
                        <img
                            id="main-image"
                            src="{{ $designBrochure->featured_image_url }}"
                            alt="{{ $designBrochure->title }}"
                            class="w-full h-full object-center object-cover sm:rounded-lg max-h-80 lg:max-h-96"
                        >
                    </div>

                    <!-- Image selector -->
                    @if(count($designBrochure->images) > 1)
                        <div class="hidden w-full max-w-2xl mx-auto sm:block lg:max-w-none">
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

                    <!-- Additional Information -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 mb-3">Additional Information</h2>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-900 mb-2">Customization Process</h3>
                            <div class="space-y-1 text-sm text-gray-600">
                                <p>• We'll work with you to customize this design to your exact measurements</p>
                                <p>• Choose from our premium fabric selection or bring your own materials</p>
                                <p>• Multiple fittings included to ensure perfect fit</p>
                                <p>• Professional alterations available after completion</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product info -->
                <div class="mt-6 px-4 sm:px-0 sm:mt-8 lg:mt-0">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-extrabold tracking-tight text-gray-900">{{ $designBrochure->title }}</h1>
                        @if($designBrochure->is_featured)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                Featured Design
                            </span>
                        @endif
                    </div>

                    @if($designBrochure->price)
                        <div class="mt-2">
                            <h2 class="sr-only">Product information</h2>
                            <p class="text-2xl text-gray-900 font-bold">₱{{ number_format($designBrochure->price, 2) }}</p>
                            <p class="text-sm text-gray-500 mt-1">Base price - final cost depends on materials and customizations</p>
                        </div>
                    @endif

                    <div class="mt-4">
                        <h3 class="sr-only">Description</h3>
                        <div class="text-base text-gray-700">
                            <p>{{ $designBrochure->description }}</p>
                        </div>
                    </div>

                    <!-- Design details -->
                    <div class="mt-6 space-y-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Design Details</h3>
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

                                @if($designBrochure->fabric_suggestions)
                                    <div class="sm:col-span-2">
                                        <dt class="text-sm font-medium text-gray-500">Suggested Fabrics</dt>
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
                    </div>

                    <!-- Action buttons -->
                    <div class="mt-6 flex flex-col sm:flex-row gap-3">
                        @auth
                            <a href="{{ route('orders.create', ['design_id' => $designBrochure->id]) }}"
                               class="flex-1 bg-indigo-600 border border-transparent rounded-md py-2 px-6 flex items-center justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                Order This Design
                            </a>

                            <a href="{{ route('orders.create') }}"
                               class="flex-1 bg-white border border-gray-300 rounded-md py-2 px-6 flex items-center justify-center text-sm font-medium text-gray-900 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create Custom Order
                            </a>
                        @else
                            <div class="space-y-4">
                                <p class="text-sm text-gray-600">Please log in to place an order</p>
                                <div class="flex gap-4">
                                    <a href="{{ route('login') }}"
                                       class="flex-1 bg-indigo-600 border border-transparent rounded-md py-2 px-6 flex items-center justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                        Login to Order
                                    </a>
                                    <a href="{{ route('register') }}"
                                       class="flex-1 bg-white border border-gray-300 rounded-md py-2 px-6 flex items-center justify-center text-sm font-medium text-gray-900 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                        Create Account
                                    </a>
                                </div>
                            </div>
                        @endauth
                    </div>

                    <!-- Ordering Process - moved back to right side -->
                    <div class="mt-6">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-900 mb-2">Ordering Process</h3>
                            <div class="space-y-1 text-sm text-gray-600">
                                <p>• Place your order online with this design as reference</p>
                                <p>• Schedule a consultation to discuss details and measurements</p>
                                <p>• We'll provide a detailed quote based on your requirements</p>
                                <p>• Production begins after confirmation and payment</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related designs -->
            @if($relatedDesigns->count() > 0)
                <section class="mt-10">
                    <h2 class="text-xl font-extrabold tracking-tight text-gray-900 mb-6">You might also like</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($relatedDesigns as $relatedDesign)
                            <div class="group bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                <div class="relative">
                                    <img src="{{ $relatedDesign->featured_image_url }}"
                                         alt="{{ $relatedDesign->title }}"
                                         class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300">

                                    @if($relatedDesign->price)
                                        <div class="absolute top-2 right-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-sm font-bold bg-white/90 text-indigo-600 backdrop-blur-sm">
                                                ₱{{ number_format($relatedDesign->price, 0) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-3">
                                    <h3 class="text-base font-semibold text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors duration-200">
                                        {{ $relatedDesign->title }}
                                    </h3>

                                    <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ $relatedDesign->description }}</p>

                                    <div class="flex justify-between items-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ ucwords($relatedDesign->category) }}
                                        </span>

                                        <a href="{{ route('designs.show', $relatedDesign) }}"
                                           class="inline-flex items-center text-indigo-600 hover:text-indigo-800 text-sm font-medium transition-colors duration-200">
                                            View
                                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
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
    </script>
</x-app-layout>
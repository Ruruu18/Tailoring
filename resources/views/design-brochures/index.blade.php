<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center w-full space-y-4 sm:space-y-0">
            <div class="min-w-0 flex-1">
                <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 leading-tight">
                    {{ __('Design Gallery') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Explore our curated collection of tailoring designs</p>
            </div>
            @auth
                <div class="flex-shrink-0">
                    <a href="{{ route('orders.create') }}"
                       class="inline-flex items-center px-3 sm:px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:w-auto justify-center">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span class="hidden sm:inline">CREATE CUSTOM ORDER</span>
                        <span class="sm:hidden">CUSTOM ORDER</span>
                    </a>
                </div>
            @endauth
        </div>
    </x-slot>

    <!-- Featured Designs Hero Section -->
    @if($featuredDesigns->count() > 0)
        <section class="py-4 sm:py-6 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-4 sm:mb-6">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-1">Featured Designs</h3>
                    <p class="text-xs sm:text-sm text-gray-600">Hand-picked selections from our master tailors</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
                    @foreach($featuredDesigns as $design)
                        <div class="group relative bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="relative">
                                <img src="{{ $design->featured_image_url }}"
                                     alt="{{ $design->title }}"
                                     class="w-full h-32 sm:h-48 lg:h-64 object-cover group-hover:scale-105 transition-transform duration-300">

                                <!-- Featured badge -->
                                <div class="absolute top-1 sm:top-2 left-1 sm:left-2">
                                    <span class="inline-flex items-center px-1.5 sm:px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <span class="hidden sm:inline">Featured</span>
                                        <span class="sm:hidden">★</span>
                                    </span>
                                </div>

                                @if($design->price)
                                    <div class="absolute top-1 sm:top-2 right-1 sm:right-2">
                                        <span class="inline-flex items-center px-1.5 sm:px-2.5 py-0.5 rounded text-xs sm:text-sm font-bold bg-white text-blue-600">
                                            ₱{{ number_format($design->price, 0) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-2 sm:p-3 lg:p-4">
                                <div class="flex justify-between items-start mb-1 sm:mb-2">
                                    <h4 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-900 truncate pr-2">{{ $design->title }}</h4>
                                    @if($design->price)
                                        <span class="text-xs sm:text-sm lg:text-lg font-bold text-blue-600 flex-shrink-0">₱{{ number_format($design->price, 2) }}</span>
                                    @endif
                                </div>
                                <p class="text-xs sm:text-sm text-gray-600 mb-2 sm:mb-3 line-clamp-2 hidden sm:block">{{ $design->description }}</p>

                                <div class="flex flex-wrap gap-1 sm:gap-2 mb-2 sm:mb-3">
                                    <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucwords($design->category) }}
                                    </span>
                                </div>

                                <div class="flex justify-between items-center pt-2 sm:pt-3 border-t border-gray-100">
                                    <div class="flex gap-2 w-full">
                                        <a href="{{ route('designs.show', $design) }}"
                                           class="text-blue-600 hover:text-blue-900 text-xs sm:text-sm font-medium flex-1 text-center sm:text-left">
                                            View
                                        </a>
                                        @auth
                                            <a href="{{ route('orders.create', ['design_id' => $design->id]) }}"
                                               class="text-indigo-600 hover:text-indigo-900 text-xs sm:text-sm font-medium flex-1 text-center sm:text-left">
                                                Order
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Main Design Gallery -->
    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search and Filters -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4">
                <div class="p-3 sm:p-4">
                    <form method="GET" action="{{ route('designs.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
                            <!-- Search -->
                            <div class="sm:col-span-2 lg:col-span-2">
                                <label for="search" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Search Designs</label>
                                <input type="text"
                                       name="search"
                                       id="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search designs..."
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                            </div>

                            <!-- Category Filter -->
                            <div>
                                <label for="category" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Category</label>
                                <select name="category" id="category" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                                            {{ ucwords($category) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Gender Filter -->
                            <div>
                                <label for="gender" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Gender</label>
                                <select name="gender" id="gender" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    <option value="">All Genders</option>
                                    @foreach($genders as $gender)
                                        <option value="{{ $gender }}" {{ request('gender') === $gender ? 'selected' : '' }}>
                                            {{ ucwords($gender) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sort -->
                            <div>
                                <label for="sort" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Sort By</label>
                                <select name="sort" id="sort" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    <option value="sort_order" {{ request('sort') === 'sort_order' ? 'selected' : '' }}>Default</option>
                                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                    <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Featured First</option>
                                </select>
                            </div>
                        </div>


                        <!-- Filter Buttons -->
                        <div class="flex flex-col sm:flex-row justify-end gap-2">
                            <a href="{{ route('designs.index') }}"
                               class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 rounded text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Clear
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-3 py-2 border border-transparent rounded text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Apply
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($designBrochures->count() > 0)
                <!-- Design Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
                    @foreach($designBrochures as $brochure)
                        <div class="group bg-white rounded-lg shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="relative">
                                <img src="{{ $brochure->featured_image_url }}"
                                     alt="{{ $brochure->title }}"
                                     class="w-full h-32 sm:h-48 lg:h-64 object-cover group-hover:scale-105 transition-transform duration-300">

                                @if($brochure->is_featured)
                                    <div class="absolute top-1 sm:top-2 left-1 sm:left-2">
                                        <span class="inline-flex items-center px-1.5 sm:px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <span class="hidden sm:inline">Featured</span>
                                            <span class="sm:hidden">★</span>
                                        </span>
                                    </div>
                                @endif

                                @if($brochure->price)
                                    <div class="absolute top-1 sm:top-2 right-1 sm:right-2">
                                        <span class="inline-flex items-center px-1.5 sm:px-2.5 py-0.5 rounded text-xs sm:text-sm font-bold bg-white text-blue-600">
                                            ₱{{ number_format($brochure->price, 0) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-2 sm:p-3 lg:p-4">
                                <div class="flex justify-between items-start mb-1 sm:mb-2">
                                    <h3 class="text-xs sm:text-sm lg:text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors duration-200 truncate pr-2">
                                        {{ $brochure->title }}
                                    </h3>
                                    @if($brochure->price)
                                        <span class="text-xs sm:text-sm lg:text-lg font-bold text-blue-600 flex-shrink-0">₱{{ number_format($brochure->price, 2) }}</span>
                                    @endif
                                </div>

                                <p class="text-xs sm:text-sm text-gray-600 mb-2 sm:mb-3 line-clamp-2 hidden sm:block">{{ $brochure->description }}</p>

                                <!-- Meta info -->
                                <div class="flex flex-wrap gap-1 sm:gap-2 mb-2 sm:mb-3">
                                    <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucwords($brochure->category) }}
                                    </span>
                                    @if($brochure->gender)
                                        <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ ucwords($brochure->gender) }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex justify-between items-center pt-2 sm:pt-3 border-t border-gray-100">
                                    <div class="flex gap-2 w-full">
                                        <a href="{{ route('designs.show', $brochure) }}"
                                           class="text-blue-600 hover:text-blue-900 text-xs sm:text-sm font-medium flex-1 text-center sm:text-left">
                                            View
                                        </a>
                                        @auth
                                            <a href="{{ route('orders.create', ['design_id' => $brochure->id]) }}"
                                               class="text-indigo-600 hover:text-indigo-900 text-xs sm:text-sm font-medium flex-1 text-center sm:text-left">
                                                Order
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}"
                                               class="text-blue-600 hover:text-blue-900 text-xs sm:text-sm font-medium flex-1 text-center sm:text-left">
                                                Login
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- No Designs Found - Centered -->
                <div class="flex items-center justify-center" style="min-height: 50vh;">
                    <div class="text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No designs found</h3>
                        <p class="text-gray-500">
                            @if(request()->hasAny(['search', 'category', 'gender', 'min_price', 'max_price']))
                                Try adjusting your filters to see more designs.
                            @else
                                Our design collection is coming soon.
                            @endif
                        </p>
                        @if(request()->hasAny(['search', 'category', 'gender', 'min_price', 'max_price']))
                            <div class="mt-6">
                                <a href="{{ route('designs.index') }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Clear All Filters
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Pagination -->
            @if($designBrochures->hasPages())
                <div class="mt-8">
                    {{ $designBrochures->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <div class="flex-1">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Design Brochures') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Manage your design collection</p>
            </div>
            <div class="ml-auto">
                <a href="{{ route('admin.design-brochures.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New Design
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filters -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.design-brochures.index') }}" class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-64">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input type="text"
                                   name="search"
                                   id="search"
                                   value="{{ request('search') }}"
                                   placeholder="Search by title, description, category, or tags..."
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="min-w-48">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category" id="category" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                                        {{ ucwords($category) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="min-w-32">
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <select name="gender" id="gender" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Genders</option>
                                @foreach($genders as $gender)
                                    <option value="{{ $gender }}" {{ request('gender') === $gender ? 'selected' : '' }}>
                                        {{ ucwords($gender) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="min-w-32">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Filter
                            </button>
                            <a href="{{ route('admin.design-brochures.index') }}"
                               class="px-4 py-2 bg-gray-500 text-white text-sm rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Design Brochures Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($designBrochures as $brochure)
                    <div class="bg-white overflow-hidden shadow-xl rounded-lg hover:shadow-2xl transition-shadow duration-300">
                        <!-- Image -->
                        <div class="relative">
                            <img src="{{ $brochure->featured_image_url }}"
                                 alt="{{ $brochure->title }}"
                                 class="w-full h-64 object-cover">

                            <!-- Status badges -->
                            <div class="absolute top-2 right-2 flex flex-col gap-1">
                                @if($brochure->is_featured)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Featured
                                    </span>
                                @endif
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $brochure->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $brochure->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <!-- Quick actions -->
                            <div class="absolute top-2 left-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="flex gap-1">
                                    <button onclick="toggleFeatured({{ $brochure->id }})"
                                            class="p-2 bg-white rounded-full shadow-md hover:bg-yellow-50 transition-colors duration-200"
                                            title="Toggle Featured">
                                        <svg class="w-4 h-4 {{ $brochure->is_featured ? 'text-yellow-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $brochure->title }}</h3>
                                @if($brochure->price)
                                    <span class="text-lg font-bold text-indigo-600">₱{{ number_format($brochure->price, 2) }}</span>
                                @endif
                            </div>

                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $brochure->description }}</p>

                            <!-- Meta info -->
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucwords($brochure->category) }}
                                </span>
                                @if($brochure->gender)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ ucwords($brochure->gender) }}
                                    </span>
                                @endif
                            </div>

                            <!-- Tags -->
                            @if($brochure->tags_array)
                                <div class="flex flex-wrap gap-1 mb-3">
                                    @foreach(array_slice($brochure->tags_array, 0, 3) as $tag)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ trim($tag) }}
                                        </span>
                                    @endforeach
                                    @if(count($brochure->tags_array) > 3)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            +{{ count($brochure->tags_array) - 3 }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.design-brochures.show', $brochure) }}"
                                       class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View</a>
                                    <a href="{{ route('admin.design-brochures.edit', $brochure) }}"
                                       class="text-blue-600 hover:text-blue-900 text-sm font-medium">Edit</a>
                                </div>

                                <button onclick="toggleActive({{ $brochure->id }})"
                                        class="text-sm font-medium {{ $brochure->is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}">
                                    {{ $brochure->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No design brochures</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new design brochure.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.design-brochures.create') }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    New Design Brochure
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($designBrochures->hasPages())
                <div class="mt-8">
                    {{ $designBrochures->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript for AJAX actions -->
    <script>
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
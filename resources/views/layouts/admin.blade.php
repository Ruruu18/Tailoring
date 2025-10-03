<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/ICON.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
        <!-- Admin Navigation -->
        <nav class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 shadow-xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('admin.dashboard') }}" class="text-white text-xl font-bold hover:text-yellow-200 transition-colors duration-200">
                                {{ config('app.name', 'Laravel') }} Admin
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-white hover:text-yellow-200 border-b-2 border-transparent hover:border-yellow-200 transition-all duration-200">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.customers.index')" :active="request()->routeIs('admin.customers.*')" class="text-white hover:text-yellow-200 border-b-2 border-transparent hover:border-yellow-200 transition-all duration-200">
                                {{ __('Customers') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')" class="text-white hover:text-yellow-200 border-b-2 border-transparent hover:border-yellow-200 transition-all duration-200">
                                {{ __('Orders') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.design-brochures.index')" :active="request()->routeIs('admin.design-brochures.*')" class="text-white hover:text-yellow-200 border-b-2 border-transparent hover:border-yellow-200 transition-all duration-200">
                                {{ __('Design Brochures') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.inventory.index')" :active="request()->routeIs('admin.inventory.*')" class="text-white hover:text-yellow-200 border-b-2 border-transparent hover:border-yellow-200 transition-all duration-200">
                                {{ __('Inventory') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.sms.index')" :active="request()->routeIs('admin.sms.*')" class="text-white hover:text-yellow-200 border-b-2 border-transparent hover:border-yellow-200 transition-all duration-200">
                                {{ __('SMS') }}
                            </x-nav-link>
                            <!-- Reports Dropdown -->
                            <div class="relative group">
                                <button class="inline-flex items-center px-1 pt-1 text-white hover:text-yellow-200 border-b-2 border-transparent hover:border-yellow-200 transition-all duration-200 text-sm font-medium">
                                    {{ __('Reports & Analytics') }}
                                    <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div class="absolute z-50 mt-2 w-56 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                    <div class="py-1">
                                        <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.reports.index') ? 'bg-gray-100 font-medium' : '' }}">
                                            Overview Dashboard
                                        </a>
                                        <a href="{{ route('admin.reports.revenue') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.reports.revenue') ? 'bg-gray-100 font-medium' : '' }}">
                                            Revenue Analytics
                                        </a>
                                        <a href="{{ route('admin.reports.orders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.reports.orders') ? 'bg-gray-100 font-medium' : '' }}">
                                            Order Analytics
                                        </a>
                                        <a href="{{ route('admin.reports.inventory') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.reports.inventory') ? 'bg-gray-100 font-medium' : '' }}">
                                            Inventory Analytics
                                        </a>
                                        <a href="{{ route('admin.reports.customers') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.reports.customers') ? 'bg-gray-100 font-medium' : '' }}">
                                            Customer Analytics
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-white bg-white bg-opacity-20 hover:bg-opacity-30 focus:outline-none transition-all duration-200 backdrop-blur-sm">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('dashboard')">
                                    {{ __('Customer Dashboard') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.customers.index')" :active="request()->routeIs('admin.customers.*')">
                        {{ __('Customers') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                        {{ __('Orders') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.design-brochures.index')" :active="request()->routeIs('admin.design-brochures.*')">
                        {{ __('Design Brochures') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.inventory.index')" :active="request()->routeIs('admin.inventory.*')">
                        {{ __('Inventory') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">
                        {{ __('Reports') }}
                    </x-responsive-nav-link>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('dashboard')">
                            {{ __('Customer Dashboard') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-responsive-nav-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow-lg border-b border-gray-100">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2">
        @if (session('success'))
            <div class="toast-notification bg-gradient-to-r from-green-400 to-green-500 border border-green-400 text-white px-6 py-4 rounded-xl shadow-lg flex items-center min-w-80 max-w-96 transform translate-x-full opacity-0 transition-all duration-300 ease-out" role="alert">
                <div class="flex items-center flex-1">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="block font-medium">{{ session('success') }}</span>
                </div>
                <button type="button" class="ml-3 flex-shrink-0 text-white hover:text-green-200 transition-colors duration-200" onclick="dismissToast(this)">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="toast-notification bg-gradient-to-r from-red-400 to-red-500 border border-red-400 text-white px-6 py-4 rounded-xl shadow-lg flex items-center min-w-80 max-w-96 transform translate-x-full opacity-0 transition-all duration-300 ease-out" role="alert">
                <div class="flex items-center flex-1">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="block font-medium">{{ session('error') }}</span>
                </div>
                <button type="button" class="ml-3 flex-shrink-0 text-white hover:text-red-200 transition-colors duration-200" onclick="dismissToast(this)">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        @endif
    </div>

    <script>
        // Toast notification functions
        function dismissToast(button) {
            const toast = button.closest('.toast-notification');
            toast.style.transform = 'translateX(100%)';
            toast.style.opacity = '0';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }

        // Auto-show and auto-dismiss toast notifications
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.toast-notification');

            toasts.forEach(function(toast, index) {
                // Stagger the appearance of multiple toasts
                setTimeout(function() {
                    toast.style.transform = 'translateX(0)';
                    toast.style.opacity = '1';

                    // Auto-dismiss after 5 seconds
                    setTimeout(function() {
                        if (toast.parentNode) { // Check if toast still exists
                            toast.style.transform = 'translateX(100%)';
                            toast.style.opacity = '0';
                            setTimeout(function() {
                                if (toast.parentNode) {
                                    toast.remove();
                                }
                            }, 300);
                        }
                    }, 5000);
                }, index * 200); // 200ms delay between each toast
            });
        });
    </script>
</body>
</html>

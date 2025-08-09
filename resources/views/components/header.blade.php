<header class="bg-white dark:bg-gray-800 shadow">
    <div class="px-6 py-4 flex justify-between items-center">
        <!-- Sidebar Toggle (mobile) -->
        <button @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden text-gray-500 dark:text-gray-300 focus:outline-none">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 lg:hidden block">
            <a href="{{ route('dashboard') }}" class="font-bold text-2xl">{{ config('app.name') }}</a>
        </h2>

        <!-- Auth Dropdown -->
        <div class="ml-3 relative lg:ms-auto">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button
                        class="flex items-center text-sm font-medium text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white focus:outline-none transition">
                        <div>{{ Auth::user()->name }}</div>

                        <div class="ml-1">
                            <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 
                                111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 
                                010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <!-- Profile -->
                    <x-dropdown-link href="{{ route('profile.edit') }}">
                        {{ __('messages.header.profile') }}
                    </x-dropdown-link>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('messages.header.logout') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</header>

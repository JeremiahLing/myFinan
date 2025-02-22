<nav id="navbar" x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="w-auto mx-auto px-4 sm:px-6 lg:px-8 shadow-lg rounded-md mb-2 animate__animated animate__fadeInDown" style="background-color: #88CCEEaa;">
        <div class="flex justify-between">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <div>
                            <img src="{{ asset('favicon.png') }}" alt="Logo" style="max-width: 100px; max-height: 100px; object-fit: contain;">
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex">
                    <!-- Dashboard Link -->
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('budget.create')" :active="request()->routeIs('budget.create')">
                        {{ __('Budget') }}
                    </x-nav-link>
                    <x-nav-link :href="route('expense.create')" :active="request()->routeIs('expense.create')">
                        {{ __('Expense') }}
                    </x-nav-link>
                    <x-nav-link :href="route('income.create')" :active="request()->routeIs('income.create')">
                        {{ __('Income') }}
                    </x-nav-link>
                    <x-nav-link :href="route('report')" :active="request()->routeIs('report')">
                        {{ __('Financial Report') }}
                    </x-nav-link>
                    <x-nav-link :href="route('invoice')" :active="request()->routeIs('invoice')">
                        {{ __('Invoice') }}
                    </x-nav-link>
                    <x-nav-link :href="route('staff')" :active="request()->routeIs('staff')">
                        {{ __('Staff Management') }}
                    </x-nav-link>
                    <x-nav-link :href="route('attendance')" :active="request()->routeIs('attendance')">
                        {{ __('Attendance') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                
                <!--Profile Picture-->
                <img 
                    id="profile-picture"
                    src="{{ Auth::user()->getProfilePictureUrl() }}" 
                    alt="{{ __('Profile Picture') }}" 
                    class="w-16 h-16 rounded-full object-cover p-2 transition-transform duration-500 transform"
                />

                <x-dropdown align="right" width="48" class="ms-6">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-2 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150" style="background-color: #88CCEE;">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
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
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
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
        <div class="sm:px-4 px-2 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
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

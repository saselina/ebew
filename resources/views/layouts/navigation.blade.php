

<style>
nav {
    background-color: #ed82cd !important; /* warna abu gelap solid */
}
</style>

 <nav x-data="{ open: false }" class="bg-gray-900 border-b border-gray-700 py-4">

    <!-- Main Navigation -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo + Judul -->
            <div class="flex items-center space-x-3">
                <x-application-logo class="block h-9 w-auto fill-current text-white" />
                <span class="text-lg font-semibold text-white">Daftar Barang</span>
            </div>

            <!-- Profile Dropdown -->
            <div class="hidden sm:flex sm:items-center">
                <!-- Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-200 hover:text-white focus:outline-none transition">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-700 uppercase">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Logout') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>

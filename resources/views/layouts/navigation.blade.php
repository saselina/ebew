<style>
    nav {
        background-color: #e890cd !important;
    }

    .user-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #7f076b, #e1ced7);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        border: 2px solid #fff;
        box-shadow: 0 0 6px rgba(255, 255, 255, 0.4);
        transition: all 0.3s ease;
    }

    .user-avatar:hover {
        background: linear-gradient(135deg, #cd1d7b, #4b5563);
        transform: scale(1.05);
    }
</style>

<nav x-data="{ open: false }" class="py-5 shadow-md border-b border-pink-300">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
        <div class="relative flex items-center justify-center h-20">

        <!-- Logo kiri -->
        <div class="absolute left-0 flex items-center space-x-3">
            <x-application-logo class="block h-10 w-auto fill-current text-white" />
        </div>


        <div class="flex items-center justify-between w-full">
    <!-- Logo + Tulisan kiri -->
    <div class="flex items-center gap-6">
        <x-application-logo class="block h-10 w-auto fill-current text-white" />

        <span class="text-xl font-semibold text-white tracking-wide">
    @if(request()->is('profile'))
        Profile
    @else
        Daftar Barang
    @endif
</span>

    </div>


        <!-- Avatar user kanan -->
        <div class="absolute right-0 flex items-center">
            <x-dropdown align="right" width="64">
                <x-slot name="trigger">
                    <button class="focus:outline-none">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->email, 0, 1)) }}
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="px-4 py-2 text-sm text-white border-b border-gray-200">
                        {{ Auth::user()->email }}
                    </div>
                    <x-dropdown-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-dropdown-link>
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

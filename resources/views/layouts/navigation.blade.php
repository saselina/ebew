<style>
    nav {
        background-color: #ec92c8 !important; /* Pink lembut */
    }

    /* Tombol kembali */
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: white;
        background-color: rgba(255, 255, 255, 0.15);
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        background-color: rgba(255, 255, 255, 0.3);
        transform: translateY(-1px);
    }

    .back-icon {
        font-size: 18px;
        font-weight: bold;
        transition: transform 0.3s ease;
    }

    .back-btn:hover .back-icon {
        transform: translateX(-4px);
    }

    /* Judul tengah */
    .navbar-title {
        font-size: 18px;
        font-weight: 600;
        color: white;
        text-align: center;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        letter-spacing: 0.5px;
    }

    /* Avatar user (untuk halaman lain) */
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

@if(request()->is('profile'))
<!-- Navbar untuk halaman Profile -->
<nav x-data="{ open: false }" class="py-5 shadow-md border-b border-pink-300 relative">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 flex items-center justify-between h-20 relative">
        <!-- Tombol kembali -->
        <a href="{{ route('items.index') }}" class="back-btn">
            <span class="back-icon">←</span>
            Kembali
        </a>

        <!-- Judul tengah -->
        <span class="navbar-title">Profil Pengguna</span>
    </div>
</nav>

@else
<!-- Navbar umum (dashboard) -->
<nav x-data="{ open: false }" class="py-5 shadow-md border-b border-pink-300">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
        <div class="relative flex items-center justify-between h-20">

            <!-- Logo kiri -->
            <x-application-logo class="block h-10 w-auto fill-current text-white" />

            <!-- Tengah: Judul -->
            <span class="text-xl font-semibold text-white tracking-wide">
                Daftar Barang
            </span>

            <!-- Kanan: Avatar -->
            <div class="flex items-center">
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
@endif

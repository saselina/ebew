<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Ubah Password</h1>

    @if (session('success'))
        <div class="bg-green-200 text-green-700 p-3 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-200 text-red-700 p-3 rounded mb-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="mb-4">
            <label class="font-semibold">Password Lama</label>
            <input type="password" wire:model="old_password" class="w-full p-2 border rounded">
            @error('old_password') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="font-semibold">Password Baru</label>
            <input type="password" wire:model="new_password" class="w-full p-2 border rounded">
            @error('new_password') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="font-semibold">Konfirmasi Password Baru</label>
            <input type="password" wire:model="new_password_confirmation" class="w-full p-2 border rounded">
            @error('new_password_confirmation') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <button wire:click="updatePassword"
            class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
            Simpan
        </button>
    </div>
</div>

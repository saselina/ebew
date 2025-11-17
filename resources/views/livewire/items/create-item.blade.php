<div class="py-8 bg-gray-50 min-h-screen">
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- CARD CONTAINER --}}
    <div class="bg-white p-8 rounded-xl shadow-2xl border border-gray-100">

        <h1 class="text-3xl font-extrabold text-gray-800 mb-6 border-b pb-4">
            ➕ Tambah Barang Baru
        </h1>

        {{-- Notifikasi Sukses --}}
        @if (session()->has('success'))
            <div class="bg-green-100 text-green-800 border border-green-300 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-6">

            {{-- GROUP 1: DROPDOWNS (3 KOLOM) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- GEDUNG --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gedung</label>
                    <select wire:model.live="buildingId" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                        <!-- Placeholder Gedung: HANYA dirender jika buildingId KOSONG -->
                        @if (!$buildingId)
                            <option value="" selected>-- Pilih Gedung --</option>
                        @endif

                        @foreach ($buildings as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                    @error('buildingId') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- RUANGAN --}}
                @if ($buildingId)
                <div wire:key="room-select-{{ $buildingId }}">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan</label>
                    <select wire:model.live="roomId" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                        <!-- Placeholder Ruangan: HANYA dirender jika roomId KOSONG -->
                        @if (!$roomId)
                            <option value="" selected>-- Pilih Ruangan --</option>
                        @endif

                        @foreach ($rooms as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                    @error('roomId') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                @else
                {{-- Placeholder jika Gedung belum dipilih --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan</label>
                    <select disabled class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-100 text-gray-500 cursor-not-allowed">
                        <option>Pilih Gedung terlebih dahulu</option>
                    </select>
                </div>
                @endif

                {{-- KATEGORI --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select wire:model.live="categoryId" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                        <!-- Placeholder Kategori: HANYA dirender jika categoryId KOSONG -->
                        @if (!$categoryId)
                            <option value="" selected>-- Pilih Kategori --</option>
                        @endif
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('categoryId') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

            </div>

            {{-- GROUP 2: NAMA BARANG & KODE BARANG (2 KOLOM) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                    <input type="text" wire:model="name" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barang</label>
                    <input type="text" wire:model="kode_barang" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('kode_barang') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- GROUP 3: SATUAN, MERK, & QUANTITY (3 KOLOM) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                    <select wire:model="satuan" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                        <option value="">Pilih Satuan</option>
                        <option value="Unit">Unit</option>
                        <option value="Pcs">Pcs</option>
                        <option value="Box">Box</option>
                    </select>
                    @error('satuan') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Merk</label>
                    <input type="text" wire:model="merk" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('merk') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok Awal</label>
                    <input type="number" wire:model="quantity" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('quantity') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="flex gap-4 pt-4 border-t">
                <button wire:click="save" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition duration-150 shadow-md">
                    💾 Simpan Barang
                </button>

                <a href="{{ route('items.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-500 transition duration-150 shadow-md">
                    Kembali
                </a>
            </div>

        </div>
    </div>

</div>


</div>

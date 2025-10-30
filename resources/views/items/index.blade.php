 <x-app-layout> <x-slot name="header"> <h1 class="text-2xl font-semibold text-white bg-pink-400 p-4 rounded-t-lg text-center"> Daftar Barang </h1>  </x-slot><div class="py-6 px-4 sm:px-6 lg:px-8">
    <!-- Filter Section -->
    <div class="bg-white p-5 rounded-lg shadow mb-6 flex flex-wrap items-center gap-4 justify-between">
        <div class="flex flex-wrap gap-3">
            <form method="GET" action="{{ route('items.index') }}" class="flex flex-wrap gap-3 items-center">
                <select name="building_id" class="form-select border rounded-md p-2">
                    <option value="">Gedung</option>
                    @foreach ($buildings as $building)
                        <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                            {{ $building->name }}
                        </option>
                    @endforeach
                </select>

                <select name="room_id" class="form-select border rounded-md p-2">
                    <option value="">Ruangan</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                            {{ $room->name }}
                        </option>
                    @endforeach
                </select>

                <select name="category_id" class="form-select border rounded-md p-2">
                    <option value="">Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-black px-4 py-2 rounded-md">
                    Filter
                </button>
                <a href="{{ route('items.index') }}" class="text-gray-600 hover:text-gray-800">Reset</a>
            </form>
        </div>

       <div class="flex items-center gap-3">
    <a href="{{ route('items.create') }}"
       class="bg-pink-200 hover:bg-pink-300 text-black px-4 py-2 rounded-lg shadow-md transition duration-200">
       ➕ Tambah Barang
    </a>
            <form method="GET" action="{{ route('items.index') }}" class="flex gap-2 items-center">
                <input type="text" name="search" placeholder="Cari barang..." value="{{ request('search') }}"
                       class="border rounded-md px-3 py-2">
                <button type="submit" class="hidden"></button>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-100 text-gray-700 text-sm">
                <tr>
                    <th class="border px-4 py-2 text-center">No</th>
                    <th class="border px-4 py-2 text-center">Gedung</th>
                    <th class="border px-4 py-2 text-center">Ruangan</th>
                    <th class="border px-4 py-2 text-center">Kategori</th>
                    <th class="border px-4 py-2 text-center">Nama Barang</th>
                    <th class="border px-4 py-2 text-center">Deskripsi</th>
                    <th class="border px-4 py-2 text-center">Jumlah</th>
                    <th class="border px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr class="hover:bg-gray-50 text-center">
                        <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="border px-4 py-2">{{ $item->room->building->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $item->room->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $item->category->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $item->name }}</td>
                        <td class="border px-4 py-2">{{ $item->description ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $item->quantity ?? 0 }}</td>
                        <td class="border px-4 py-2 flex justify-center gap-3">
                            <button
                                class="text-purple-600 hover:underline detail-btn"
                                data-item='@json($item)'>
                                Detail
                            </button>
                            <a href="{{ route('items.edit', $item->id) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-gray-500">Tidak ada data ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $items->links() }}
        </div>
    </div>
</div>

<!-- MODAL DETAIL -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
        <h2 class="text-xl font-semibold mb-4 text-center">Detail Barang</h2>
        <div class="space-y-2 text-sm">
            <p><strong>Gedung:</strong> <span id="detailGedung"></span></p>
            <p><strong>Ruangan:</strong> <span id="detailRuangan"></span></p>
            <p><strong>Kategori:</strong> <span id="detailKategori"></span></p>
            <p><strong>Nama Barang:</strong> <span id="detailNama"></span></p>
            <p><strong>Deskripsi:</strong> <span id="detailDeskripsi"></span></p>
            <p><strong>Jumlah:</strong> <span id="detailJumlah"></span></p>
        </div>
    </div>
</div>

<script>
    // Ambil semua tombol detail
    const detailButtons = document.querySelectorAll('.detail-btn');
    const modal = document.getElementById('detailModal');
    const closeModal = document.getElementById('closeModal');

    detailButtons.forEach(button => {
        button.addEventListener('click', () => {
            const item = JSON.parse(button.dataset.item);
            document.getElementById('detailGedung').innerText = item.room?.building?.name ?? '-';
            document.getElementById('detailRuangan').innerText = item.room?.name ?? '-';
            document.getElementById('detailKategori').innerText = item.category?.name ?? '-';
            document.getElementById('detailNama').innerText = item.name ?? '-';
            document.getElementById('detailDeskripsi').innerText = item.description ?? '-';
            document.getElementById('detailJumlah').innerText = item.quantity ?? '0';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    closeModal.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

    modal.addEventListener('click', e => {
        if (e.target === modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    });
</script>
</x-app-layout>

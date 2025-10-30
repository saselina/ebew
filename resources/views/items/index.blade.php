<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-white bg-pink-400 p-4 rounded-t-lg text-center">
            Daftar Barang
        </h1>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <!-- Filter Section -->
        <div class="bg-white p-5 rounded-lg shadow mb-6 flex flex-wrap items-center justify-between gap-4">
            <form id="filterForm" method="GET" action="{{ route('items.index') }}" class="flex flex-wrap items-center gap-3">
                <!-- GEDUNG -->
                <div class="relative">
                    <select id="buildingSelect" name="building_id"
                        class="auto-submit border rounded-lg p-2 text-sm w-44 sm:w-52 lg:w-60 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">Pilih Gedung</option>
                        @foreach ($buildings as $building)
                            <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- RUANGAN -->
                <div class="relative">
                    <select id="roomSelect" name="room_id"
                        class="auto-submit border rounded-lg p-2 text-sm w-44 sm:w-52 lg:w-60 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">Pilih Ruangan</option>
                        @if (request('building_id'))
                            @foreach ($rooms as $room)
                                @if ($room->building_id == request('building_id'))
                                    <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                                        {{ $room->name }}
                                    </option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- KATEGORI -->
                <div class="relative">
                    <select name="category_id"
                        class="auto-submit border rounded-lg p-2 text-sm w-44 sm:w-52 lg:w-60 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol Reset -->
                <a href="{{ route('items.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow-md transition text-sm flex items-center justify-center">
                    🔄 Reset
                </a>
            </form>

            <!-- Tambah Barang + Pencarian -->
            <div class="flex items-center gap-3">
                <a href="{{ route('items.create') }}"
                    class="bg-pink-200 hover:bg-pink-300 text-black px-4 py-2 rounded-lg shadow-md transition text-sm">
                    ➕ Tambah Barang
                </a>

                <form method="GET" action="{{ route('items.index') }}" class="flex items-center gap-2">
                    <input type="text" name="search" placeholder="Cari barang..."
                        value="{{ request('search') }}"
                        class="border rounded-md px-3 py-2 text-sm w-[150px] sm:w-[200px] focus:outline-none focus:ring-2 focus:ring-pink-400">
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
                            <td class="border px-4 py-2">
                                {{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}
                            </td>
                            <td class="border px-4 py-2">{{ $item->room->building->name ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $item->room->name ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $item->category->name ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $item->name }}</td>
                            <td class="border px-4 py-2">{{ \Illuminate\Support\Str::limit($item->description, 30, '...') ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $item->quantity ?? 0 }}</td>

                            <td class="border px-4 py-2">
                                <div class="flex justify-center gap-3">
                                    <!-- Tombol Detail -->
                                    <button
                                        class="detail-btn bg-orange-100 hover:bg-orange-200 text-orange-500 w-10 h-10 rounded-xl flex items-center justify-center transition duration-200"
                                        title="Lihat Detail"
                                        data-item='@json($item)'>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 5c-7 0-11 7-11 7s4 7 11 7 11-7 11-7-4-7-11-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </button>

                                    <!-- Tombol Edit -->
                                    <a href="{{ route('items.edit', $item->id) }}"
                                        class="bg-indigo-100 hover:bg-indigo-200 text-indigo-500 w-10 h-10 rounded-xl flex items-center justify-center transition duration-200"
                                        title="Edit Barang">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm13.37-9.36l1.42-1.42a1.003 1.003 0 0 1 1.42 0l1.34 1.34a1.003 1.003 0 0 1 0 1.42l-1.42 1.42-2.76-2.76z"/>
                                        </svg>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-rose-100 hover:bg-rose-200 text-rose-500 w-10 h-10 rounded-xl flex items-center justify-center transition duration-200"
                                            title="Hapus Barang">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M9 3V4H4V6H5V19C5 20.1 5.9 21 7 21H17C18.1 21 19 20.1 19 19V6H20V4H15V3H9zM7 6H17V19H7V6zM9 8V17H11V8H9zM13 8V17H15V8H13z"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500">Tidak ada data ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4 flex justify-center">
                {{ $items->links('vendor.pagination.custom-pink') }}
            </div>

            <style>
            .pagination-active {
                background-color: #f9a8d4 !important; /* pink lembut */
                color: white !important;
            }
            </style>


        </div>
    </div>

    <!-- Modal Detail -->
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
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
        // Auto submit form ketika filter berubah
        document.querySelectorAll('.auto-submit').forEach(select => {
            select.addEventListener('change', () => {
                document.getElementById('filterForm').submit();
            });
        });

        // Handle dynamic ruangan
        const buildingSelect = document.querySelector('#buildingSelect');
        const roomSelect = document.querySelector('#roomSelect');
        buildingSelect.addEventListener('change', function () {
            const buildingId = this.value;
            roomSelect.innerHTML = '<option value="">Loading...</option>';
            if (!buildingId) {
                roomSelect.innerHTML = '<option value="">Pilih Ruangan</option>';
                document.getElementById('filterForm').submit();
                return;
            }
            fetch(`/get-rooms/${buildingId}`)
                .then(response => response.json())
                .then(data => {
                    roomSelect.innerHTML = '<option value="">Pilih Ruangan</option>';
                    data.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.id;
                        option.textContent = room.name;
                        roomSelect.appendChild(option);
                    });
                })
                .then(() => document.getElementById('filterForm').submit())
                .catch(err => {
                    console.error(err);
                    roomSelect.innerHTML = '<option value="">Gagal memuat ruangan</option>';
                });
        });

        // Modal Detail
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

<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-white bg-pink-400 p-4 rounded-t-lg text-center">
            Daftar Barang
        </h1>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">

        <!-- FILTER SECTION -->
        <div class="bg-white p-5 rounded-lg shadow mb-6 flex flex-wrap items-center justify-between gap-4">
            <form id="filterForm" method="GET" action="{{ route('items.index') }}" class="flex flex-wrap items-center gap-3">

                <!-- GEDUNG -->
                <div>
                    <select id="buildingSelect" name="building_id"
                        class="auto-submit border rounded-lg p-2 text-sm w-44 sm:w-52 lg:w-60">
                        <option value="">Pilih Gedung</option>
                        @foreach ($buildings as $building)
                            <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- RUANGAN -->
                <div>
                    <select id="roomSelect" name="room_id"
                        class="auto-submit border rounded-lg p-2 text-sm w-44 sm:w-52 lg:w-60">
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
                <div>
                    <select name="category_id"
                        class="auto-submit border rounded-lg p-2 text-sm w-44 sm:w-52 lg:w-60">
                        <option value="">Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- RESET -->
                <a href="{{ route('items.index') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow-md text-sm">
                    🔄 Reset
                </a>
            </form>

            <!-- ADD + SEARCH -->
            <div class="flex items-center gap-3">
                <a href="{{ route('items.create') }}"
                    class="bg-pink-200 hover:bg-pink-300 text-black px-4 py-2 rounded-lg shadow-md text-sm">
                    ➕ Tambah Barang
                </a>

                <form method="GET" action="{{ route('items.index') }}" class="flex items-center gap-2">
                    <input type="text" name="search" placeholder="Cari barang..."
                        value="{{ request('search') }}"
                        class="border rounded-md px-3 py-2 text-sm w-[200px]">
                </form>
            </div>
        </div>

        <!-- TABLE -->
        <div class="bg-white shadow-md border border-gray-200 rounded-xl overflow-hidden">
            <table class="min-w-full border-collapse border border-gray-200 rounded-lg">
                <thead class="bg-pink-50 text-gray-700 text-sm">
                    <tr>
                        <th class="border px-4 py-2 text-center">No</th>
                        <th class="border px-4 py-2 text-center">Gedung</th>
                        <th class="border px-4 py-2 text-center">Ruangan</th>
                        <th class="border px-4 py-2 text-center">Kategori</th>
                        <th class="border px-4 py-2 text-center">Nama Barang</th>

                        <!-- ✅ DESKRIPSI DIHAPUS -->

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

                            <!-- ✅ DESKRIPSI DIHAPUS TOTAL -->

                            <td class="border px-4 py-2">{{ $item->quantity }}</td>

                            <td class="border px-4 py-2">
                                <div class="flex justify-center gap-3">

                                    <!-- DETAIL -->
                                    <button
                                        class="detail-btn bg-orange-100 hover:bg-orange-200 text-orange-500 w-10 h-10 rounded-xl flex items-center justify-center"
                                        data-item='@json($item)'>
                                        👁
                                    </button>

                                    <!-- EDIT -->
                                    <a href="{{ route('items.edit', $item->id) }}"
                                        class="bg-indigo-100 hover:bg-indigo-200 text-indigo-500 w-10 h-10 rounded-xl flex items-center justify-center">
                                        ✏️
                                    </a>

                                    <!-- DELETE -->
                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-rose-100 hover:bg-rose-200 text-rose-500 w-10 h-10 rounded-xl">
                                            🗑
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada data ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4 flex justify-center">
                {{ $items->links('vendor.pagination.custom-pink') }}
            </div>
        </div>
    </div>

    <!-- ✅ MODAL DETAIL (TETAP ADA) -->
    <div id="detailModal" class="fixed inset-0 bg-black/40 hidden justify-center items-center z-50 p-4">
        <div class="bg-white w-full max-w-lg rounded-xl shadow-2xl border border-gray-200 relative p-6">

            <button id="closeModal"
                class="absolute top-3 right-3 text-gray-600 hover:text-gray-900 text-3xl font-bold leading-none">
                ×
            </button>

            <h2 class="text-xl font-bold text-center mb-6 text-gray-800">Detail Barang</h2>

            <div class="space-y-2 text-gray-700 text-sm">
                <div><strong>Gedung:</strong> <span id="detailGedung"></span></div>
                <div><strong>Ruangan:</strong> <span id="detailRuangan"></span></div>
                <div><strong>Kategori:</strong> <span id="detailKategori"></span></div>
                <div><strong>Nama Barang:</strong> <span id="detailNama"></span></div>
                <div><strong>Kode Barang:</strong> <span id="detailKode"></span></div>
                <div><strong>Satuan:</strong> <span id="detailSatuan"></span></div>
                <div><strong>Merk:</strong> <span id="detailMerk"></span></div>
                <div><strong>Deskripsi:</strong> <span id="detailDeskripsi"></span></div>
                <div><strong>Jumlah:</strong> <span id="detailJumlah"></span></div>
            </div>

            <div class="mt-6 text-center">
                <button class="px-5 py-2 bg-gray-300 hover:bg-gray-400 text-gray-900 rounded-lg font-medium"
                    id="closeModalBtn">
                    Tutup
                </button>
            </div>

        </div>
    </div>

    <!-- ✅ SCRIPT -->
    <script>
        document.querySelectorAll('.auto-submit').forEach(select => {
            select.addEventListener('change', () => {
                document.getElementById('filterForm').submit();
            });
        });

        const modal = document.getElementById('detailModal');

        document.querySelectorAll('.detail-btn').forEach(button => {
            button.addEventListener('click', () => {
                const item = JSON.parse(button.dataset.item);

                document.getElementById('detailGedung').innerText = item.room?.building?.name ?? '-';
                document.getElementById('detailRuangan').innerText = item.room?.name ?? '-';
                document.getElementById('detailKategori').innerText = item.category?.name ?? '-';
                document.getElementById('detailNama').innerText = item.name ?? '-';
                document.getElementById('detailKode').innerText = item.code ?? '-';
                document.getElementById('detailSatuan').innerText = item.satuan ?? '-';
                document.getElementById('detailMerk').innerText = item.merk ?? '-';
                document.getElementById('detailDeskripsi').innerText = item.description ?? '-';
                document.getElementById('detailJumlah').innerText = item.quantity ?? '0';

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        });

        document.getElementById('closeModal').addEventListener('click', () => modal.classList.add('hidden'));
        document.getElementById('closeModalBtn').addEventListener('click', () => modal.classList.add('hidden'));

        modal.addEventListener('click', e => {
            if (e.target === modal) modal.classList.add('hidden');
        });

    </script>

</x-app-layout>

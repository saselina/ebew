<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                📦 DAFTAR Barang
            </h1>
        </div>
    </x-slot>

    <div class="py-4 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto py-6 px-6 sm:px-8 lg:px-10 flex items-center"></div>

        {{-- ✅ Notifikasi sukses --}}
        @if (session('success'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 3000)"
                class="mb-4 bg-green-500 text-white px-4 py-2 rounded"
            >
                ✅ <strong>{{ session('success') }}</strong>
            </div>
        @endif

        {{-- 🔽 Filter & Sortir --}}
        <div class="mb-4">
            <form id="filterForm" method="GET" action="{{ route('items.index') }}"
                  class="flex flex-wrap justify-center items-center bg-white p-4 rounded-xl shadow-md border border-gray-200 max-w-6xl mx-auto gap-5">
                <select id="buildingSelect" name="building_id"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-44 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                    <option value="">Gedung</option>
                    @foreach ($buildings as $building)
                        <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                            {{ $building->name }}
                        </option>
                    @endforeach
                </select>

                <select id="roomSelect" name="room_id"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-44 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                    <option value="">Ruangan</option>
                    @if (request('building_id'))
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                                {{ $room->name }}
                            </option>
                        @endforeach
                    @endif
                </select>

                <select name="category_id"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-44 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                    <option value="">Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <a href="{{ route('items.index') }}" class="text-gray-600 hover:text-gray-800 text-sm underline">
                    Reset
                </a>
            </form>
        </div>

        {{-- ✅ Tombol tambah + Search bar --}}
        <div class="mb-4 flex justify-between items-center max-w-7xl mx-auto">
            <a href="{{ route('items.create') }}" class="btn btn-success">➕ Tambah Barang</a>

            <form id="searchForm" method="GET" action="{{ route('items.index') }}" class="flex items-center flex-grow max-w-xs ml-4">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari barang..."
                    class="border border-gray-300 rounded-lg px-4 py-2 text-sm w-full focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition shadow-sm">
            </form>
        </div>

        {{-- ✅ Tabel data --}}
        <div class="overflow-x-auto mt-6">
            <table class="min-w-full border border-gray-300 rounded-lg text-left text-lg">
                <thead class="bg-gray-100 text-gray-900">
                    <tr>
                        <th class="px-6 py-3 border-b text-center font-semibold">No</th>
                        <th class="px-6 py-3 border-b font-semibold">Gedung</th>
                        <th class="px-6 py-3 border-b font-semibold">Ruangan</th>
                        <th class="px-6 py-3 border-b font-semibold">Kategori</th>
                        <th class="px-6 py-3 border-b font-semibold">Nama Barang</th>
                        <th class="px-6 py-3 border-b font-semibold">Deskripsi</th>
                        <th class="px-6 py-3 border-b text-center font-semibold">Jumlah</th>
                        <th class="px-6 py-3 border-b text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white text-gray-800">
                    @forelse ($items as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 border-b text-center">{{ $index + 1 }}</td>
                            <td class="px-6 py-3 border-b">{{ $item->room->building->name ?? '-' }}</td>
                            <td class="px-6 py-3 border-b">{{ $item->room->name ?? '-' }}</td>
                            <td class="px-6 py-3 border-b">{{ $item->category->name ?? '-' }}</td>
                            <td class="px-6 py-3 border-b font-medium">{{ $item->name }}</td>
                            <td class="px-6 py-3 border-b">
                                @if ($item->description)
                                    {{ Str::limit($item->description, 40) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-3 border-b text-center font-semibold">{{ $item->quantity }}</td>
                            <td class="px-6 py-3 border-b text-center space-x-2">
                                <button type="button"
                                    class="text-blue-600 hover:text-blue-800 text-xl"
                                    onclick="showDetailModal(
                                        '{{ $item->name }}',
                                        '{{ $item->created_at }}',
                                        '{{ $item->updated_at }}',
                                        `{{ addslashes($item->description ?? '-') }}`
                                    )">
                                    📄
                                </button>

                                <a href="{{ route('items.edit', $item->id) }}" class="inline-block text-yellow-600 hover:text-yellow-800 text-xl">✏️</a>

                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline-block"
                                    onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 ml-2 text-xl">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500 italic">
                                Belum ada data barang
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ✅ Modal Detail Barang --}}
    <div id="detailModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden justify-center items-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96 relative">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">📋 Detail Barang</h2>
            <p><strong>Nama Barang:</strong> <span id="modalName" class="text-gray-700"></span></p>
            <p><strong>🕓 Waktu Input:</strong> <span id="modalCreated" class="text-gray-700"></span></p>
            <p><strong>🔁 Waktu Update:</strong> <span id="modalUpdated" class="text-gray-700"></span></p>

            <div class="mt-3">
                <strong>📝 Deskripsi:</strong>
                <p id="modalDescription" class="text-gray-700 mt-1 transition-all duration-300"></p>
                <button id="toggleDescBtn"
                    class="text-blue-600 text-sm mt-1 hover:underline focus:outline-none hidden"
                    onclick="toggleDescription()">
                    Lihat Selengkapnya
                </button>
            </div>

            <div class="flex justify-center mt-6">
                <button onclick="closeModal()"
                    class="bg-pink-500 hover:bg-pink-600 text-black font-semibold py-2 px-6 rounded-full shadow-md transition transform hover:scale-105">
                    CANCEL
                </button>
            </div>
        </div>
    </div>

{{-- ✅ Pagination --}}
<div class="mt-6 flex justify-center">
    {{ $items->links('pagination::tailwind') }}
</div>


    {{-- ✅ Script --}}
    <script>
        let fullDescription = '';
        let descExpanded = false;

        function showDetailModal(name, created, updated, description) {
            document.getElementById('modalName').textContent = name;
            document.getElementById('modalCreated').textContent = created;
            document.getElementById('modalUpdated').textContent = updated;

            fullDescription = description.trim();
            const descElement = document.getElementById('modalDescription');
            const toggleBtn = document.getElementById('toggleDescBtn');

            if (fullDescription.length > 100) {
                descElement.textContent = fullDescription.substring(0, 100) + '...';
                toggleBtn.textContent = 'Lihat Selengkapnya';
                toggleBtn.classList.remove('hidden');
            } else {
                descElement.textContent = fullDescription || '-';
                toggleBtn.classList.add('hidden');
            }

            document.getElementById('detailModal').classList.remove('hidden');
            document.getElementById('detailModal').classList.add('flex');
        }

        function toggleDescription() {
            const descElement = document.getElementById('modalDescription');
            const toggleBtn = document.getElementById('toggleDescBtn');

            if (descExpanded) {
                descElement.textContent = fullDescription.substring(0, 100) + '...';
                toggleBtn.textContent = 'Lihat Selengkapnya';
            } else {
                descElement.textContent = fullDescription;
                toggleBtn.textContent = 'Sembunyikan';
            }

            descExpanded = !descExpanded;
        }

        function closeModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.getElementById('detailModal').classList.remove('flex');
            descExpanded = false;
        }

        // 🔄 Filter otomatis
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('filterForm');
            const buildingSelect = document.getElementById('buildingSelect');
            const roomSelect = document.getElementById('roomSelect');
            const categorySelect = form.querySelector('select[name="category_id"]');

            buildingSelect.addEventListener('change', function () {
                const buildingId = this.value;
                roomSelect.innerHTML = '<option value="">Memuat...</option>';

                if (buildingId) {
                    fetch(`/get-rooms/${buildingId}`)
                        .then(response => response.json())
                        .then(rooms => {
                            let options = '<option value="">Ruangan</option>';
                            rooms.forEach(room => {
                                options += `<option value="${room.id}">${room.name}</option>`;
                            });
                            roomSelect.innerHTML = options;
                            form.submit();
                        })
                        .catch(() => {
                            roomSelect.innerHTML = '<option value="">Gagal memuat ruangan</option>';
                        });
                } else {
                    roomSelect.innerHTML = '<option value="">Ruangan</option>';
                    form.submit();
                }
            });

            [roomSelect, categorySelect].forEach(select => {
                select.addEventListener('change', () => form.submit());
            });

            const searchForm = document.getElementById('searchForm');
            const searchInput = searchForm.querySelector('input[name="search"]');
            let typingTimer;
            searchInput.addEventListener('keyup', () => {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => searchForm.submit(), 800);
            });
        });
    </script>

    {{-- 🎨 Styling tambahan --}}
<style>
    form.flex {
        min-height: 48px;
        align-items: center;
    }

    input[type="text"], select {
        min-width: 150px;
        height: 38px;
        box-sizing: border-box;
    }

    /* 🔹 Perbaikan border tabel */
    table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #d1d5db; /* abu-abu lembut */
    }

    th, td {
        border: 1px solid #d1d5db; /* border tiap sel */
    }

    /* 🔹 Warna dasar halaman */
    body {
        background-color: #f8fafc;
        color: #1f2937;
    }

    /* 🔹 Hilangkan teks info di bawah pagination */
    nav[role="navigation"] > div:first-child {
        display: none !important;
    }
</style>


</x-app-layout>

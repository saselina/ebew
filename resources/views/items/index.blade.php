<x-app-layout>
<x-slot name="header">
    <div class="flex items-center gap-2">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
            📦 DAFTAR Barang
        </h1>
    </div>
</x-slot>


    <div class="py-8 px-4 sm:px-6 lg:px-8">
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
        <div class="mb-6">
            <form id="filterForm" method="GET" action="{{ route('items.index') }}"
                  class="flex flex-wrap justify-center items-center bg-white p-4 rounded-xl shadow-md border border-gray-200 max-w-6xl mx-auto gap-5">

                {{-- 🔍 Pencarian --}}
                <div class="flex items-center ml-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari barang..."
                        class="border border-gray-300 rounded-lg px-4 py-2 text-sm w-64 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition shadow-sm">
                </div>

                {{-- 🏢 Gedung --}}
                <select id="buildingSelect" name="building_id"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-44 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                    <option value="">Gedung</option>
                    @foreach ($buildings as $building)
                        <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                            {{ $building->name }}
                        </option>
                    @endforeach
                </select>

                {{-- 🚪 Ruangan --}}
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

                {{-- 🏷️ Kategori --}}
                <select name="category_id"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-44 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                    <option value="">Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                {{-- 🔄 Reset --}}
                <a href="{{ route('items.index') }}"
                   class="text-gray-600 hover:text-gray-800 text-sm underline">
                    Reset
                </a>
            </form>
        </div>

        {{-- ✅ Tombol tambah --}}
        <div class="mb-4">
            <a href="{{ route('items.create') }}" class="btn btn-success">➕ Tambah Barang</a>
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
                            <td class="px-6 py-3 border-b">{{ $item->description ?? '-' }}</td>
                            <td class="px-6 py-3 border-b text-center font-semibold">{{ $item->quantity }}</td>
                            <td class="px-6 py-3 border-b text-center">
                                <a href="{{ route('items.edit', $item->id) }}"
                                   class="inline-block text-yellow-600 hover:text-yellow-800 text-xl">✏️</a>
                                <form action="{{ route('items.destroy', $item->id) }}" method="POST"
                                      class="inline-block"
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
  </div>

    {{-- ✅ Script sortir otomatis --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('filterForm');
        const buildingSelect = document.getElementById('buildingSelect');
        const roomSelect = document.getElementById('roomSelect');
        const searchInput = form.querySelector('input[name="search"]');

        // 🏢 Saat gedung berubah
        buildingSelect.addEventListener('change', function () {
            const buildingId = this.value;
            roomSelect.innerHTML = '<option value="">Memuat...</option>';

            if (buildingId) {
                fetch('/get-rooms/' + buildingId)
                    .then(response => response.json())
                    .then(rooms => {
                        let options = '<option value="">Ruangan</option>';
                        rooms.forEach(room => {
                            options += `<option value="${room.id}">${room.name}</option>`;
                        });
                        roomSelect.innerHTML = options;
                        form.submit(); // langsung kirim filter
                    })
                    .catch(() => {
                        roomSelect.innerHTML = '<option value="">Gagal memuat ruangan</option>';
                    });
            } else {
                roomSelect.innerHTML = '<option value="">Ruangan</option>';
                form.submit();
            }
        });

        // 🚪 Saat ruangan atau kategori berubah → langsung submit
        form.querySelectorAll('select[name="room_id"], select[name="category_id"]').forEach(select => {
            select.addEventListener('change', () => form.submit());
        });

        // 🔍 Search otomatis setelah berhenti mengetik 0.8 detik
        let typingTimer;
        if (searchInput) {
            searchInput.addEventListener('keyup', () => {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => form.submit(), 800);
            });
        }
    });
    </script>

    {{-- 🎨 Styling sortir --}}
    <style>
        form.flex {
            min-height: 48px;
            align-items: center;
        }

        input[type="text"],
        select {
            min-width: 150px;
            height: 38px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            height: 38px;
            line-height: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        tbody {
            min-height: 300px;
            display: table-row-group;
        }

        td[colspan] {
            height: 200px;
        }
    </style>
</x-app-layout>

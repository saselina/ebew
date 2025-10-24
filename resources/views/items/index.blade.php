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
        <div class="mb-4 flex justify-between items-center max-w-7xl mx-auto relative">
            <a href="{{ route('items.create') }}" class="btn btn-success">➕ Tambah Barang</a>

            <form id="searchForm" method="GET" action="{{ route('items.index') }}" class="relative">
                <input type="text"
                       id="search"
                       name="search"
                       placeholder="Cari barang..."
                       value="{{ request('search') }}"
                       class="border border-gray-300 rounded-3xl px-4 py-2 text-sm shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
                       style="width: 300px; height: 45px;">
                <ul id="suggestions"
                    class="absolute bg-white border border-gray-200 rounded-lg mt-2 shadow-lg w-full z-50 hidden">
                </ul>
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

        {{-- ✅ Pagination --}}
        <div class="mt-6 flex justify-center">
            {{ $items->links('pagination::tailwind') }}
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

    {{-- ✅ Script --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search');
        const suggestionBox = document.getElementById('suggestions');
        const searchForm = document.getElementById('searchForm');

        // Auto-suggest
        searchInput.addEventListener('keyup', function (e) {
            const query = this.value.trim();

            // ENTER langsung cari
            if (e.key === 'Enter') {
                e.preventDefault();
                const url = new URL(window.location.href);
                url.searchParams.set('search', query);
                window.location.href = url.toString();
                return;
            }

            if (query.length >= 2) {
                fetch(`/items/search?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        suggestionBox.innerHTML = '';
                        if (data.length > 0) {
                            suggestionBox.classList.remove('hidden');
                            data.forEach(item => {
                                const li = document.createElement('li');
                                li.textContent = item.nama_barang;
                                li.classList.add('px-4', 'py-2', 'hover:bg-blue-100', 'cursor-pointer');
                                li.addEventListener('click', () => {
                                    searchInput.value = item.nama_barang;
                                    suggestionBox.classList.add('hidden');
                                    const url = new URL(window.location.href);
                                    url.searchParams.set('search', item.nama_barang);
                                    window.location.href = url.toString();
                                });
                                suggestionBox.appendChild(li);
                            });
                        } else {
                            suggestionBox.classList.add('hidden');
                        }
                    })
                    .catch(() => suggestionBox.classList.add('hidden'));
            } else {
                suggestionBox.classList.add('hidden');
            }
        });

        document.addEventListener('click', function (e) {
            if (!suggestionBox.contains(e.target) && e.target !== searchInput) {
                suggestionBox.classList.add('hidden');
            }
        });
    });
    </script>

    {{-- 🎨 Styling tambahan --}}
    <style>
        body {
            background-color: #f8fafc;
            color: #1f2937;
        }
        table { border-collapse: collapse; border: 1px solid #d1d5db; width: 100%; }
        th, td { border: 1px solid #d1d5db; }
        nav[role="navigation"] > div:first-child { display: none !important; }
    </style>

    <script>
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('search');
  const suggestionsBox = document.createElement('ul');
  suggestionsBox.id = 'suggestions';
  suggestionsBox.className = 'list-group position-absolute bg-white border mt-1 rounded';
  searchInput.parentNode.appendChild(suggestionsBox);

  searchInput.addEventListener('input', function() {
    const query = this.value.trim();

    if (query.length >= 2) {
      fetch(`/items/search?query=${query}`)
        .then(response => response.json())
        .then(data => {
          suggestionsBox.innerHTML = '';
          if (data.length > 0) {
            data.forEach(item => {
              const li = document.createElement('li');
              li.textContent = item.nama_barang;
              li.className = 'list-group-item list-group-item-action';
              li.addEventListener('click', () => {
                searchInput.value = item.nama_barang;
                suggestionsBox.innerHTML = '';
                searchInput.form.submit(); // langsung enter
              });
              suggestionsBox.appendChild(li);
            });
          }
        })
        .catch(error => console.error('Error:', error));
    } else {
      suggestionsBox.innerHTML = '';
    }
  });

  // klik di luar untuk nutup dropdown
  document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target)) {
      suggestionsBox.innerHTML = '';
    }
  });
});
</script>

</x-app-layout>

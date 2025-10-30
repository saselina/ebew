<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                📦 DAFTAR Barang
            </h1>
        </div>
    </x-slot>

    <div class="py-4 px-4 sm:px-6 lg:px-8">
    {{-- ✅ Notifikasi sukses --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
             class="mb-4 bg-green-500 text-white px-4 py-2 rounded">
            ✅ <strong>{{ session('success') }}</strong>
        </div>
    @endif

    {{-- 🔽 Filter & Sortir otomatis --}}
   {{-- 🔽 Filter bergaya dashboard profesional --}}
<div class="bg-white shadow-md rounded-lg p-6 mb-6 border border-gray-200 max-w-6xl mx-auto">
  <h2 class="text-lg font-semibold text-gray-700 mb-4">Filter Barang</h2>

  <form id="filterForm" method="GET" action="{{ route('items.index') }}">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
      {{-- Gedung --}}
      <div>
        <label for="buildingSelect" class="block text-sm font-medium text-gray-600 mb-1">Gedung</label>
        <select id="buildingSelect" name="building_id"
          class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
          <option value="">Semua</option>
          @foreach ($buildings as $building)
            <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
              {{ $building->name }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Ruangan --}}
      <div>
        <label for="roomSelect" class="block text-sm font-medium text-gray-600 mb-1">Ruangan</label>
        <select id="roomSelect" name="room_id"
          class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
          <option value="">Semua</option>
          @if (request('building_id'))
            @foreach ($rooms as $room)
              <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                {{ $room->name }}
              </option>
            @endforeach
          @endif
        </select>
      </div>

      {{-- Kategori --}}
      <div>
        <label for="category_id" class="block text-sm font-medium text-gray-600 mb-1">Kategori</label>
        <select name="category_id" id="category_id"
          class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
          <option value="">Semua</option>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Tombol --}}
      <div class="flex gap-2">
        <button type="submit"
          class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Filter</button>
        <a href="{{ route('items.index') }}"
          class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition text-center">Reset</a>
      </div>

      {{-- Spacer kosong untuk grid 5 kolom --}}
      <div></div>
    </div>
  </form>
</div>

    {{-- ✅ Tombol tambah + Search bar --}}
    <div class="mb-4 flex justify-between items-center max-w-7xl mx-auto relative">
<a href="{{ route('items.create') }}"
   style="background-color:#f095c4 !important; color:white !important;"
   class="px-4 py-2 rounded-lg font-semibold shadow-md transition hover:opacity-90">
  + Tambah Barang
</a>


        <form id="searchForm" method="GET" action="{{ route('items.index') }}" class="relative">
            <input type="text" id="search" name="search" placeholder="Cari barang..."
                   value="{{ request('search') }}"
                   class="border border-gray-300 rounded-3xl px-4 py-2 text-sm shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
                   style="width: 300px; height: 45px;">
            <ul id="suggestions"
                class="absolute bg-white border border-gray-200 rounded-lg mt-2 shadow-lg w-full z-50 hidden"></ul>
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
                        <td class="px-6 py-3 border-b text-center">{{ $items->firstItem() + $index }}</td>
                        <td class="px-6 py-3 border-b">{{ $item->room->building->name ?? '-' }}</td>
                        <td class="px-6 py-3 border-b">{{ $item->room->name ?? '-' }}</td>
                        <td class="px-6 py-3 border-b">{{ $item->category->name ?? '-' }}</td>
                        <td class="px-6 py-3 border-b font-medium">{{ $item->name }}</td>
                        <td class="px-6 py-3 border-b">{{ $item->description ? Str::limit($item->description, 40) : '-' }}</td>
                        <td class="px-6 py-3 border-b text-center font-semibold">{{ $item->quantity }}</td>
                        <td class="px-6 py-3 border-b text-center space-x-2">
                            {{-- 📄 Detail --}}
                            <button type="button" class="text-blue-600 hover:text-blue-800 text-xl"
                                    onclick="showDetailModal('{{ $item->name }}','{{ $item->created_at }}','{{ $item->updated_at }}',`{{ addslashes($item->description ?? '-') }}`)">
                                📄
                            </button>

                            {{-- ✏️ Edit --}}
                            <a href="{{ route('items.edit', $item->id) }}" class="inline-block text-yellow-600 hover:text-yellow-800 text-xl">✏️</a>

                            {{-- 🗑️ Hapus --}}
                          <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline delete-form">
                         @csrf
                       @method('DELETE')
                       <button type="button" class="text-red-600 hover:text-red-800 ml-2 text-xl delete-btn">🗑️</button>
                    </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500 italic">Belum ada data barang</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ✅ Pagination --}}
    <div class="flex justify-center mt-4">
     {{ $items->links('vendor.pagination.custom-pink') }}
    </div>

<style>
/* Hapus semua garis/pink-line di bawah/sekitar pagination */
nav[role="navigation"],
nav[role="navigation"] * ,
nav[role="navigation"] ul,
nav[role="navigation"] li,
nav[role="navigation"] a,
nav[role="navigation"] span {
  border: none !important;
  border-bottom: none !important;
  border-top: none !important;
  box-shadow: none !important;
  background: transparent !important; /* only affects wrappers, tombol tetap punya bg dari class mereka */
  outline: none !important;
}

/* Kadang garis muncul dari pseudo-element -> sembunyikan */
nav[role="navigation"]::before,
nav[role="navigation"]::after,
nav[role="navigation"] *::before,
nav[role="navigation"] *::after {
  content: none !important;
  display: none !important;
}

/* Jika garis berada pada elemen setelah/before nav (mis. hr atau parent border) */
nav[role="navigation"] + *,
nav[role="navigation"] ~ *,
nav[role="navigation"] > * {
  border-top: none !important;
  box-shadow: none !important;
}

/* Jika pagination dipasang tepat setelah table, sembunyikan garis bawah table */
table + nav[role="navigation"],
table + div > nav[role="navigation"] {
  margin-top: 0.75rem;
}
table {
  border-bottom: none !important;
}

/* Override fokus ring Tailwind khusus untuk pagination (aman karena tombol punya visual lain) */
nav[role="navigation"] a:focus,
nav[role="navigation"] a:active,
nav[role="navigation"] span:focus {
  outline: none !important;
  box-shadow: none !important;
}

/* Jika masih muncul, ini akan menutupi garis horizontal yang sering jadi culprit */
hr, .divider, .line {
  border: none !important;
  background: transparent !important;
  box-shadow: none !important;
}
</style>


</div>

{{-- ✅ Modal Detail Barang --}}
<div id="detailModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96 relative transition-all transform scale-95 duration-300" id="modalBox">
        <h2 class="text-lg font-semibold mb-4 text-gray-800">📋 Detail Barang</h2>
        <p><strong>Nama Barang:</strong> <span id="modalName" class="text-gray-700"></span></p>
        <p><strong>🕓 Waktu Input:</strong> <span id="modalCreated" class="text-gray-700"></span></p>
        <p><strong>🔁 Waktu Update:</strong> <span id="modalUpdated" class="text-gray-700"></span></p>

        <div class="mt-3">
            <strong>📝 Deskripsi:</strong>
            <p id="modalDescription" class="text-gray-700 mt-1 transition-all duration-300"></p>
            <button id="toggleDescBtn" class="text-blue-600 text-sm mt-1 hover:underline focus:outline-none hidden" onclick="toggleDescription()">
                Lihat Selengkapnya
            </button>
        </div>

        <div class="flex justify-center mt-6">
            <button onclick="closeDetailModal()" class="bg-pink-500 hover:bg-pink-600 text-black font-semibold py-2 px-6 rounded-full shadow-md transition transform hover:scale-105">
                CANCEL
            </button>
        </div>
    </div>
</div>

{{-- ✅ Script Modal Detail --}}
<script>
    let fullDescription = '';
    let isExpanded = false;

    function safeFormatDate(isoString) {
        if (!isoString) return '-';
        const d = new Date(isoString);
        if (isNaN(d)) return isoString;
        return d.toLocaleString('id-ID');
    }

    function showDetailModal(name, createdAtIso, updatedAtIso, description) {
        fullDescription = description || '-';
        isExpanded = false;

        document.getElementById('modalName').textContent = name ?? '-';
        document.getElementById('modalCreated').textContent = safeFormatDate(createdAtIso);
        document.getElementById('modalUpdated').textContent = safeFormatDate(updatedAtIso);

        const descElement = document.getElementById('modalDescription');
        const toggleBtn = document.getElementById('toggleDescBtn');

        if (fullDescription && fullDescription.length > 100) {
            descElement.textContent = fullDescription.substring(0, 100) + '...';
            toggleBtn.classList.remove('hidden');
            toggleBtn.textContent = 'Lihat Selengkapnya';
        } else {
            descElement.textContent = fullDescription || '-';
            toggleBtn.classList.add('hidden');
        }

        const modal = document.getElementById('detailModal');
        const box = document.getElementById('modalBox');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => box.classList.remove('scale-95'), 50);
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        const box = document.getElementById('modalBox');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 150);
    }

    function toggleDescription() {
        const descElement = document.getElementById('modalDescription');
        const toggleBtn = document.getElementById('toggleDescBtn');
        if (isExpanded) {
            descElement.textContent = fullDescription.substring(0, 100) + '...';
            toggleBtn.textContent = 'Lihat Selengkapnya';
        } else {
            descElement.textContent = fullDescription;
            toggleBtn.textContent = 'Tutup';
        }
        isExpanded = !isExpanded;
    }

    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('detailModal');
        modal.addEventListener('click', e => {
            if (e.target === modal) closeDetailModal();
        });
    });
</script>

{{-- ✅ Script Sortir Otomatis --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        const selects = filterForm.querySelectorAll('select');

        selects.forEach(select => {
            select.addEventListener('change', function() {
                filterForm.submit();
            });
        });

        // Jika gedung berubah, kosongkan ruangan dulu
        const buildingSelect = document.getElementById('buildingSelect');
        const roomSelect = document.getElementById('roomSelect');

        buildingSelect.addEventListener('change', function() {
            roomSelect.value = '';
            filterForm.submit();
        });
    });
</script>

{{-- ✅ Script Search Suggest --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search');
        const suggestionBox = document.getElementById('suggestions');

        searchInput.addEventListener('keyup', function (e) {
            const query = this.value.trim();

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
    body { background-color: #f8fafc; color: #1f2937; }
    table { border-collapse: collapse; border: 1px solid #d1d5db; width: 100%; }
    th, td { border: 1px solid #d1d5db; }
    nav[role="navigation"] > div:first-child { display: none !important; }
</>

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
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const form = this.closest('form');

            Swal.fire({
                title: 'Yakin ingin hapus?',
                text: "Data yang dihapus tidak bisa dikembalikan yaa.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>

</x-app-layout>

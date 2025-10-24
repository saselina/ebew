{{-- resources/views/items/partials/table.blade.php --}}
<table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
    <thead class="bg-gray-100">
        <tr>
            <th class="px-4 py-2 text-left">No</th>
            <th class="px-4 py-2 text-left">Gedung</th>
            <th class="px-4 py-2 text-left">Ruangan</th>
            <th class="px-4 py-2 text-left">Kategori</th>
            <th class="px-4 py-2 text-left">Nama Barang</th>
            <th class="px-4 py-2 text-left">Deskripsi</th>
            <th class="px-4 py-2 text-left">Jumlah</th>
            <th class="px-4 py-2 text-center">Aksi</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($items as $item)
            <tr>
                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                <td class="px-4 py-2">{{ $item->building->name ?? '-' }}</td>
                <td class="px-4 py-2">{{ $item->room->name ?? '-' }}</td>
                <td class="px-4 py-2">{{ $item->category->name ?? '-' }}</td>
                <td class="px-4 py-2">{{ $item->name }}</td>
                <td class="px-4 py-2">{{ $item->description ?? '-' }}</td>
                <td class="px-4 py-2 text-center">{{ $item->quantity }}</td>
                <td class="px-4 py-2 text-center">
                    <a href="{{ route('items.show', $item->id) }}" class="text-blue-500 hover:text-blue-700">📄</a>
                    <a href="{{ route('items.edit', $item->id) }}" class="text-yellow-500 hover:text-yellow-700 mx-2">✏️</a>
                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700">🗑️</button>
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

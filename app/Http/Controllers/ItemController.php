<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Room;
use App\Models\Building;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // 🧾 Menampilkan semua item
    public function index(Request $request)
    {
        $buildings = Building::all();
        $rooms = Room::all();
        $categories = Category::all();

        $query = Item::with(['room.building', 'category']);

        // 🏢 Filter gedung
        if ($request->building_id) {
            $query->whereHas('room.building', function ($q) use ($request) {
                $q->where('id', $request->building_id);
            });

            $rooms = Room::where('building_id', $request->building_id)->get();
        }

        // 🚪 Filter ruangan
        if ($request->room_id) {
            $query->where('room_id', $request->room_id);
        }

        // 🏷️ Filter kategori
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // 🔍 Pencarian manual (enter)
        if ($request->search) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        // ↕️ Sortir
        if ($sort = $request->input('sort')) {
            switch ($sort) {
                case 'building':
                    $query->orderByRaw('(SELECT name FROM buildings
                        WHERE buildings.id = (
                            SELECT building_id FROM rooms WHERE rooms.id = items.room_id LIMIT 1
                        )
                    ) ASC');
                    break;

                case 'room':
                    $query->orderByRaw('(SELECT name FROM rooms WHERE rooms.id = items.room_id LIMIT 1) ASC');
                    break;

                case 'category':
                    $query->orderByRaw('(SELECT name FROM categories WHERE categories.id = items.category_id LIMIT 1) ASC');
                    break;

                case 'quantity':
                    $query->orderBy('quantity', 'asc');
                    break;

                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $items = $query->paginate(10)->appends($request->query());

        return view('items.index', compact('items', 'buildings', 'rooms', 'categories'));
    }

    // 🧠 AJAX Search (untuk saran otomatis)
    public function search(Request $request)
    {
        $query = $request->get('query');

        // Ambil data barang berdasarkan input minimal 2 huruf
        $items = Item::where('name', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name']);

        // Kembalikan dalam format JSON yang sesuai dengan JavaScript
        return response()->json($items->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_barang' => $item->name, // untuk JS, tetap pakai key 'nama_barang'
            ];
        }));
    }

    // 🏗️ Form tambah item
    public function create()
    {
        $categories = Category::all();
        $buildings = Building::all();
        return view('items.create', compact('categories', 'buildings'));
    }

    // 🚪 Ambil ruangan berdasarkan gedung (AJAX)
    public function getRooms($building_id)
    {
        $rooms = Room::where('building_id', $building_id)->get();
        return response()->json($rooms);
    }

    // 💾 Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'room_id' => 'required|exists:rooms,id',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        Item::create($request->all());
        return redirect()->route('items.index')->with('success', 'Data berhasil ditambahkan!');
    }

    // ✏️ Form edit item
    public function edit(Item $item)
    {
        $categories = Category::all();
        $buildings = Building::all();
        $rooms = Room::where('building_id', $item->room->building_id)->get();
        return view('items.edit', compact('item', 'categories', 'buildings', 'rooms'));
    }

    // 🔄 Update item
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'room_id' => 'required|exists:rooms,id',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $item->update($request->all());
        return redirect()->route('items.index')->with('success', 'Data berhasil diperbarui!');
    }

    // 🗑️ Hapus item
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Data berhasil dihapus!');
    }
}
 
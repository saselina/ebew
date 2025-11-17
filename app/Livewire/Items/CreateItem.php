<?php

namespace App\Livewire\Items;

use Livewire\Component;
use App\Models\Building;
use App\Models\Room;
use App\Models\Category;
use App\Models\Item; // Pastikan model Item di-import jika Anda menggunakannya di save()
use Illuminate\Support\Collection;

class CreateItem extends Component
{
    public $buildings;
    public $rooms;
    public $categories;

    public $buildingId = '';
    public $roomId = '';
    public $categoryId = '';

    public $name = '';
    public $kode_barang = '';
    public $merk = '';
    public $satuan = '';
    public $quantity = '';

    public function mount()
    {
        // 1. Ambil semua data master
        $this->buildings = Building::all();
        $this->categories = Category::all();
        $this->rooms = collect();

        // 2. Auto-select Gedung pertama dan muat ruangannya
        if ($this->buildings->isNotEmpty()) {
            $this->buildingId = $this->buildings->first()->id;
            // Langsung panggil logika update untuk memuat ruangan dan memilih ruangan pertama
            $this->updatedBuildingId($this->buildingId);
        } else {
            $this->buildingId = '';
        }

        // 3. Auto-select Kategori pertama
        if ($this->categories->isNotEmpty()) {
            $this->categoryId = $this->categories->first()->id;
        } else {
            $this->categoryId = '';
        }

        // Jika tidak ada gedung atau ruangan yang dimuat, pastikan roomId kosong
        if ($this->buildingId == '') {
            $this->roomId = '';
        }
    }

    public function updatedBuildingId($value)
    {
        // Reset roomId setiap kali buildingId berubah
        $this->reset('roomId');

        if ($value) {
            $this->rooms = Room::where('building_id', $value)->get();
        } else {
            $this->rooms = collect(); // Koleksi kosong jika tidak ada gedung
        }

        // Auto-select Ruangan pertama jika ada
        if ($this->rooms->isNotEmpty()) {
            $this->roomId = $this->rooms->first()->id;
        } else {
            $this->roomId = '';
        }
    }

    public function render()
    {
        return view('livewire.items.create-item')
            ->layout('layouts.app');
    }

    // Pastikan Anda memiliki method save()
    public function save()
    {
         $this->validate([
             'buildingId' => 'required',
             'roomId' => 'required',
             'categoryId' => 'required',
             'name' => 'required|string|max:255',
             'kode_barang' => 'required|string|max:255',
             'merk' => 'nullable|string|max:255',
             'satuan' => 'nullable|string|max:100',
             'quantity' => 'required|numeric|min:1',
         ]);

         // Asumsi Anda menggunakan model Item
         Item::create([
             'building_id' => $this->buildingId,
             'room_id' => $this->roomId,
             'category_id' => $this->categoryId,
             'name' => $this->name,
             'kode_barang' => $this->kode_barang,
             'merk' => $this->merk,
             'satuan' => $this->satuan,
             'quantity' => $this->quantity,
         ]);

         session()->flash('success', 'Berhasil menambahkan barang!');
         return redirect()->route('items.index');
    }
}

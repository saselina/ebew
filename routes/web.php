<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

// ✅ Kalau buka halaman utama (/) langsung redirect ke /items
Route::get('/', function () {
    return redirect('/items');
});

// ✅ Dashboard (bawaan Breeze, bisa dihapus kalau tidak dipakai)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ✅ Semua route di bawah hanya bisa diakses setelah login
Route::middleware(['auth'])->group(function () {

    // 🧍‍♀️ ROUTE PROFILE (bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 📦 ROUTE ITEM (Daftar Barang)
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show'); // 🆕 Tambahan route show
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

    // 🚪 AJAX: Ambil daftar ruangan berdasarkan gedung
    Route::get('/get-rooms/{building_id}', [ItemController::class, 'getRooms'])->name('getRooms');

    // 🔍 AJAX: Fitur pencarian otomatis (search bar)
    Route::get('/items/search', [ItemController::class, 'search'])->name('items.search');
});

// 🪪 Route autentikasi (bawaan Breeze)
require __DIR__.'/auth.php';

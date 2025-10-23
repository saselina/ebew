<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute; // Import ini penting
use Carbon\Carbon; // Import ini penting

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'room_id',
        'quantity',
        'description',
    ];

    // Relasi ke model lain (biar bisa pakai with(['room.building', 'category']))
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Accessor untuk created_at (Waktu Input)
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)
                // PENTING: Ganti 'Asia/Makassar' jika Anda menggunakan WIB ('Asia/Jakarta') atau WIT ('Asia/Jayapura')
                ->timezone('Asia/Makassar')
                // Menggunakan fungsi yang otomatis diterjemahkan (membutuhkan 'locale' di config/app.php = 'id')
                // Format: Hari(3), Tanggal(2), Bulan(3), Tahun(4), Jam:Menit:Detik
                ->translatedFormat('D, d M Y H:i:s'),
        );
    }

    // Accessor untuk updated_at (Waktu Update)
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)
                // PENTING: Ganti 'Asia/Makassar' jika Anda menggunakan WIB ('Asia/Jakarta') atau WIT ('Asia/Jayapura')
                ->timezone('Asia/Makassar')
                // Menggunakan fungsi yang otomatis diterjemahkan
                ->translatedFormat('D, d M Y H:i:s'),
        );
    }
}

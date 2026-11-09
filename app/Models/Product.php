<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Nama tabel terkait.
     * Secara konvensi Laravel, Model 'Product' otomatis terhubung ke tabel 'products'.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * Kolom-kolom yang boleh diisi secara mass-assignment (Create & Update).
     * Mencegah eksploitasi Mass Assignment Vulnerability.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
    ];

    /**
     * Konversi tipe data otomatis saat diakses dari Model (Casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    /**
     * Accessor kustom untuk format harga ke Rupiah (Rp).
     * Contoh penggunaan di Blade: $product->formatted_price
     *
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Helper status persediaan produk.
     * Contoh: Habis, Menipis, Tersedia
     *
     * @return array
     */
    public function getStockStatusAttribute(): array
    {
        if ($this->stock <= 0) {
            return ['label' => 'Habis', 'color' => 'bg-red-100 text-red-700 border-red-300'];
        } elseif ($this->stock < 10) {
            return ['label' => 'Menipis (' . $this->stock . ')', 'color' => 'bg-amber-100 text-amber-700 border-amber-300'];
        } else {
            return ['label' => 'Tersedia (' . $this->stock . ')', 'color' => 'bg-emerald-100 text-emerald-700 border-emerald-300'];
        }
    }
}

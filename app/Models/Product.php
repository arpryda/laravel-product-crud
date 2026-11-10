<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getStockStatusAttribute(): array
    {
        if ($this->stock <= 0) {
            return ['label' => 'Habis', 'color' => 'bg-red-100 text-red-700 border-red-300'];
        } elseif ($this->stock < 10) {
            return ['label' => 'Menipis (' . $this->stock . ')', 'color' => 'bg-amber-100 text-amber-700 border-amber-300'];
        }

        return ['label' => 'Tersedia (' . $this->stock . ')', 'color' => 'bg-emerald-100 text-emerald-700 border-emerald-300'];
    }
}

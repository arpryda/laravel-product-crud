<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     * Mengisi minimal 5 contoh data produk riil ritel/sembako (Aksesmu B2B).
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Beras Premium Ramos 5kg',
                'description' => 'Beras putih pulen kualitas premium hasil gilingan segar, cocok untuk konsumsi harian keluarga dan usaha kuliner.',
                'price' => 74500.00,
                'stock' => 85,
            ],
            [
                'name' => 'Minyak Goreng Bimoli Pouch 2 Liter',
                'description' => 'Minyak goreng kelapa sawit murni dengan teknologi pemurnian higienis, menghasilkan gorengan renyah tahan lama.',
                'price' => 38000.00,
                'stock' => 120,
            ],
            [
                'name' => 'Gula Pasir Gulaku Premium Putih 1kg',
                'description' => 'Gula kristal putih murni dari tebu pilihan dengan standar higienis tinggi, butiran halus dan cepat larut.',
                'price' => 17500.00,
                'stock' => 60,
            ],
            [
                'name' => 'Indomie Goreng Spesial (Karton isi 40 pcs)',
                'description' => 'Mie instan goreng legendaris dengan bumbu gurih lengkap, bawang goreng renyah, dan saus cabai lezat.',
                'price' => 118000.00,
                'stock' => 45,
            ],
            [
                'name' => 'Kopi Kapal Api Spesial Mix (Renceng isi 10 sachet)',
                'description' => 'Kopi hitam bubuk berpadu dengan takaran gula pas, aroma harum mantap khas kopi Indonesia.',
                'price' => 14000.00,
                'stock' => 150,
            ],
            [
                'name' => 'Susu UHT Frisian Flag Full Cream 1 Liter',
                'description' => 'Susu sapi cair segar siap minum kaya akan vitamin A, D3, B1, B2, B12, dan kalsium tinggi.',
                'price' => 19500.00,
                'stock' => 8, // Contoh stok menipis (<10) untuk pengujian badge UI
            ],
        ];

        foreach ($products as $item) {
            Product::updateOrCreate(
                ['name' => $item['name']],
                $item
            );
        }
    }
}

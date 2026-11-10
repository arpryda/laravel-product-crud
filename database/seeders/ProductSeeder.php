<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Beras Premium Ramos 5kg',
                'description' => 'Beras putih pulen kualitas premium.',
                'price' => 74500.00,
                'stock' => 85,
            ],
            [
                'name' => 'Minyak Goreng Bimoli Pouch 2 Liter',
                'description' => 'Minyak goreng kelapa sawit murni.',
                'price' => 38000.00,
                'stock' => 120,
            ],
            [
                'name' => 'Gula Pasir Gulaku Premium Putih 1kg',
                'description' => 'Gula kristal putih murni dari tebu pilihan.',
                'price' => 17500.00,
                'stock' => 60,
            ],
            [
                'name' => 'Indomie Goreng Spesial (Karton isi 40 pcs)',
                'description' => 'Mie instan goreng rasa spesial.',
                'price' => 118000.00,
                'stock' => 45,
            ],
            [
                'name' => 'Kopi Kapal Api Spesial Mix 10x25g',
                'description' => 'Kopi bubuk instan dengan gula.',
                'price' => 14000.00,
                'stock' => 150,
            ],
            [
                'name' => 'Susu UHT Frisian Flag Full Cream 1 Liter',
                'description' => 'Susu cair segar siap minum.',
                'price' => 19500.00,
                'stock' => 8,
            ],
        ];

        foreach ($products as $item) {
            Product::updateOrCreate(['name' => $item['name']], $item);
        }
    }
}

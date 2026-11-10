<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Rute web untuk aplikasi CRUD Produk.
| Menggunakan Resource Route untuk menangani standar RESTful CRUD:
| - GET    /products          -> index   (tampilkan list produk & filter)
| - GET    /products/export   -> export  (fitur upgrade: ekspor data ke CSV)
| - GET    /products/create   -> create  (form tambah)
| - POST   /products          -> store   (simpan data baru)
| - GET    /products/{id}     -> show    (detail produk)
| - GET    /products/{id}/edit-> edit    (form edit)
| - PUT    /products/{id}     -> update  (simpan perubahan)
| - DELETE /products/{id}     -> destroy (hapus produk)
|
*/

// Redirect halaman utama langsung ke daftar produk
Route::redirect('/', '/products');

// Fitur Upgrade: Ekspor data produk ke CSV
Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');

// Rute RESTful CRUD Produk
Route::resource('products', ProductController::class);

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::redirect('/', '/products');

Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
Route::resource('products', ProductController::class);

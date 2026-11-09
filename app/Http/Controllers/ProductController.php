<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar semua produk dalam bentuk tabel.
     * Mendukung fitur pencarian nama produk dan pagination.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $products = Product::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products', 'search'));
    }

    /**
     * Menampilkan form untuk menambahkan produk baru.
     */
    public function create(): View
    {
        return view('products.create');
    }

    /**
     * Menyimpan produk baru ke dalam database setelah divalidasi.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::create($request->validated());

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail spesifik dari suatu produk.
     */
    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    /**
     * Menampilkan form untuk mengedit produk yang sudah ada.
     */
    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Memperbarui data produk di database setelah divalidasi.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return redirect()
            ->route('products.index')
            ->with('success', 'Data produk "' . $product->name . '" berhasil diperbarui!');
    }

    /**
     * Menghapus produk dari database.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $productName = $product->name;
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk "' . $productName . '" berhasil dihapus dari sistem!');
    }
}

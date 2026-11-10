<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar semua produk dalam bentuk tabel.
     * Fitur Upgrade: Pencarian real-time, filter status stok, dan pengurutan dinamis.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status', 'all');
        $sort = $request->input('sort', 'latest');

        $query = Product::query();

        // 1. Fitur Pencarian Kata Kunci
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 2. Fitur Filter Status Persediaan Stok (Upgrade)
        if ($status === 'available') {
            $query->where('stock', '>=', 10);
        } elseif ($status === 'low') {
            $query->whereBetween('stock', [1, 9]);
        } elseif ($status === 'out') {
            $query->where('stock', '<=', 0);
        }

        // 3. Fitur Pengurutan Data / Sorting (Upgrade)
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'stock_desc':
                $query->orderBy('stock', 'desc');
                break;
            default:
                $query->latest('id');
                break;
        }

        $products = $query->paginate(10)->withQueryString();

        // Statistik Cepat untuk Dashboard Cards
        $totalItems = Product::count();
        $totalStock = Product::sum('stock');
        $avgPrice = Product::avg('price') ?? 0;
        $lowStockCount = Product::whereBetween('stock', [1, 9])->count();
        $outOfStockCount = Product::where('stock', '<=', 0)->count();

        return view('products.index', compact(
            'products', 
            'search', 
            'status', 
            'sort',
            'totalItems',
            'totalStock',
            'avgPrice',
            'lowStockCount',
            'outOfStockCount'
        ));
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
        $product = Product::create($request->validated());

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk "' . $product->name . '" berhasil ditambahkan ke katalog!');
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

    /**
     * Fitur Upgrade: Ekspor data seluruh produk ke format CSV.
     * Sangat berguna untuk integrasi analisis Data Mining.
     */
    public function export(): StreamedResponse
    {
        $fileName = 'katalog_produk_' . date('Y-m-d_His') . '.csv';
        $products = Product::orderBy('id', 'asc')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = function () use ($products) {
            $handle = fopen('php://output', 'w');
            // Menulis UTF-8 BOM agar terbaca sempurna di Microsoft Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header CSV
            fputcsv($handle, ['ID', 'Nama Produk', 'Deskripsi', 'Harga (Rp)', 'Stok', 'Status Stok', 'Dibuat Pada']);

            foreach ($products as $p) {
                fputcsv($handle, [
                    $p->id,
                    $p->name,
                    $p->description ?? '-',
                    $p->price,
                    $p->stock,
                    $p->stock_status['label'],
                    $p->created_at?->format('Y-m-d H:i:s') ?? '-',
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}

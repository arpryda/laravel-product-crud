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
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status', 'all');
        $sort = $request->input('sort', 'latest');

        $query = Product::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status === 'available') {
            $query->where('stock', '>=', 10);
        } elseif ($status === 'low') {
            $query->whereBetween('stock', [1, 9]);
        } elseif ($status === 'out') {
            $query->where('stock', '<=', 0);
        }

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

    public function create(): View
    {
        return view('products.create');
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $product = Product::create($request->validated());

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk "' . $product->name . '" berhasil ditambahkan.');
    }

    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return redirect()
            ->route('products.index')
            ->with('success', 'Data produk "' . $product->name . '" berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $productName = $product->name;
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk "' . $productName . '" berhasil dihapus.');
    }

    public function export(): StreamedResponse
    {
        $fileName = 'products_' . date('Ymd_His') . '.csv';
        $products = Product::orderBy('id', 'asc')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = function () use ($products) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($handle, ['ID', 'Nama', 'Deskripsi', 'Harga', 'Stok', 'Status', 'Dibuat']);

            foreach ($products as $p) {
                fputcsv($handle, [
                    $p->id,
                    $p->name,
                    $p->description ?? '-',
                    $p->price,
                    $p->stock,
                    $p->stock_status['label'],
                    $p->created_at?->format('Y-m-d H:i') ?? '-',
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}

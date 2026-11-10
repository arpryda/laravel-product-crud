@extends('layouts.app')

@section('title', 'Katalog Produk')

@section('content')
<div class="space-y-6">

    <!-- Header Section with Title, Export & Create Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Katalog Produk</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola data inventaris, harga satuan, dan ketersediaan stok barang.</p>
        </div>
        <div class="flex items-center space-x-2.5">
            <!-- Fitur Upgrade: Ekspor CSV -->
            <a href="{{ route('products.export') }}" 
               class="inline-flex items-center space-x-2 px-3.5 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-sm rounded-xl shadow-sm transition"
               title="Unduh seluruh data produk dalam format CSV / Excel">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Ekspor CSV</span>
            </a>

            <!-- Tombol Tambah Produk -->
            <a href="{{ route('products.create') }}" 
               class="inline-flex items-center space-x-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-sm hover:shadow transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Produk</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <!-- Total Produk -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Item</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalItems ?? $products->total() }} <span class="text-xs font-normal text-slate-500">item</span></p>
            </div>
        </div>

        <!-- Total Stok -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Stok</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($totalStock ?? 0, 0, ',', '.') }} <span class="text-xs font-normal text-slate-500">unit</span></p>
            </div>
        </div>

        <!-- Stok Menipis Warning Card -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Stok Menipis</p>
                <p class="text-2xl font-bold text-slate-800">{{ $lowStockCount ?? 0 }} <span class="text-xs font-normal text-slate-500">produk</span></p>
            </div>
        </div>

        <!-- Rata-rata Harga -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Rata-rata Harga</p>
                <p class="text-2xl font-bold text-slate-800">
                    Rp {{ number_format($avgPrice ?? 0, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Filter Tab Bar (Upgrade) -->
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-3">
        <a href="{{ route('products.index', array_merge(request()->except('status'), ['status' => 'all'])) }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ ($status ?? 'all') === 'all' ? 'bg-slate-900 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Semua Produk
        </a>
        <a href="{{ route('products.index', array_merge(request()->except('status'), ['status' => 'available'])) }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ ($status ?? '') === 'available' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Tersedia (&ge; 10)
        </a>
        <a href="{{ route('products.index', array_merge(request()->except('status'), ['status' => 'low'])) }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ ($status ?? '') === 'low' ? 'bg-amber-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Stok Menipis (1 - 9)
        </a>
        <a href="{{ route('products.index', array_merge(request()->except('status'), ['status' => 'out'])) }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ ($status ?? '') === 'out' ? 'bg-red-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Habis (0)
        </a>
    </div>

    <!-- Filter, Search and Sorting Controls -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row gap-3 justify-between items-center">
        <!-- Search Form -->
        <form action="{{ route('products.index') }}" method="GET" class="w-full md:w-auto flex-grow flex items-center gap-2 max-w-lg">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama atau deskripsi barang..." 
                       class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-xl transition">
                Cari
            </button>
            @if(!empty($search))
                <a href="{{ route('products.index', request()->except('search')) }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-xl transition" title="Reset filter pencarian">
                    Reset
                </a>
            @endif
        </form>

        <!-- Sorting Dropdown (Upgrade) -->
        <div class="flex items-center space-x-2 w-full md:w-auto justify-end">
            <label for="sort" class="text-xs font-semibold text-slate-500 whitespace-nowrap">Urutkan:</label>
            <form id="sortForm" action="{{ route('products.index') }}" method="GET">
                @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                <select name="sort" onchange="document.getElementById('sortForm').submit()" 
                        class="px-3 py-2 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="latest" {{ ($sort ?? '') === 'latest' ? 'selected' : '' }}>Terbaru (Default)</option>
                    <option value="name_asc" {{ ($sort ?? '') === 'name_asc' ? 'selected' : '' }}>Nama (A - Z)</option>
                    <option value="name_desc" {{ ($sort ?? '') === 'name_desc' ? 'selected' : '' }}>Nama (Z - A)</option>
                    <option value="price_asc" {{ ($sort ?? '') === 'price_asc' ? 'selected' : '' }}>Harga: Terendah &rarr; Tertinggi</option>
                    <option value="price_desc" {{ ($sort ?? '') === 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi &rarr; Terendah</option>
                    <option value="stock_desc" {{ ($sort ?? '') === 'stock_desc' ? 'selected' : '' }}>Stok: Terbanyak</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Products Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-semibold uppercase text-slate-500 tracking-wider">
                        <th class="py-3.5 px-4 text-center w-14">No</th>
                        <th class="py-3.5 px-4">Nama Produk & Deskripsi</th>
                        <th class="py-3.5 px-4 text-right">Harga Satuan</th>
                        <th class="py-3.5 px-4 text-center">Stok</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($products as $index => $product)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-4 text-center text-xs font-medium text-slate-400">
                                {{ method_exists($products, 'firstItem') && $products->firstItem() ? $products->firstItem() + $index : $index + 1 }}
                            </td>
                            <td class="py-4 px-4">
                                <a href="{{ route('products.show', $product) }}" class="font-semibold text-slate-900 hover:text-emerald-600 transition">
                                    {{ $product->name }}
                                </a>
                                @if ($product->description)
                                    <p class="text-xs text-slate-500 line-clamp-1 mt-0.5">{{ $product->description }}</p>
                                @else
                                    <p class="text-xs text-slate-400 italic mt-0.5">Tidak ada deskripsi</p>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-right font-semibold text-slate-900">
                                {{ $product->formatted_price }}
                            </td>
                            <td class="py-4 px-4 text-center font-medium text-slate-700">
                                {{ number_format($product->stock, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                @php $statusBadge = $product->stock_status; @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusBadge['color'] }}">
                                    {{ $statusBadge['label'] }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="inline-flex items-center space-x-1.5">
                                    <!-- Detail View Button -->
                                    <a href="{{ route('products.show', $product) }}" 
                                       class="p-1.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition" 
                                       title="Lihat Detail Produk">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>

                                    <!-- Edit Button -->
                                    <a href="{{ route('products.edit', $product) }}" 
                                       class="p-1.5 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition" 
                                       title="Edit Produk">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    <!-- Delete Button with Modal Trigger -->
                                    <button type="button" 
                                            onclick="confirmDelete('{{ route('products.destroy', $product) }}', '{{ addslashes($product->name) }}')"
                                            class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition" 
                                            title="Hapus Produk">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-500">
                                <div class="max-w-xs mx-auto space-y-3">
                                    <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    </div>
                                    <p class="font-medium text-slate-700">Tidak ada produk ditemukan</p>
                                    <p class="text-xs text-slate-400">Coba ubah kata kunci pencarian atau ganti filter status stok Anda.</p>
                                    <a href="{{ route('products.create') }}" class="inline-block mt-2 px-3.5 py-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg border border-emerald-200 transition">
                                        + Tambah Produk Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        @if (method_exists($products, 'hasPages') && $products->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Delete Confirmation Modal (Fitur Keamanan CRUD) -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 transform transition-all space-y-4">
        <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        
        <div class="text-center space-y-1">
            <h3 class="text-lg font-bold text-slate-900">Konfirmasi Hapus Produk</h3>
            <p class="text-sm text-slate-500">
                Apakah Anda yakin ingin menghapus produk <span id="productNameToDelete" class="font-semibold text-slate-800"></span>? Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>

        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="flex items-center justify-end space-x-3 pt-2">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-sm transition">
                    Ya, Hapus Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(actionUrl, productName) {
        document.getElementById('deleteForm').action = actionUrl;
        document.getElementById('productNameToDelete').innerText = '"' + productName + '"';
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>
@endpush
@endsection

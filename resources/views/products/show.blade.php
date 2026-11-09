@extends('layouts.app')

@section('title', 'Detail Produk: ' . $product->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex text-xs text-slate-500 space-x-2">
        <a href="{{ route('products.index') }}" class="hover:text-emerald-600 transition">Daftar Produk</a>
        <span>/</span>
        <span class="text-slate-800 font-semibold">Detail #{{ $product->id }}</span>
    </nav>

    <!-- Detail Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8 space-y-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ $product->name }}</h1>
                    <p class="text-xs text-slate-400 mt-1">Dibuat pada {{ $product->created_at?->format('d M Y, H:i') ?? '-' }} &bull; Terakhir diubah {{ $product->updated_at?->format('d M Y, H:i') ?? '-' }}</p>
                </div>
                @php $status = $product->stock_status; @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $status['color'] }}">
                    {{ $status['label'] }}
                </span>
            </div>

            <!-- Price & Stock Highlights -->
            <div class="grid grid-cols-2 gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Harga Satuan</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-0.5">{{ $product->formatted_price }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Stok Tersedia</p>
                    <p class="text-2xl font-bold text-slate-800 mt-0.5">{{ number_format($product->stock, 0, ',', '.') }} <span class="text-xs font-normal text-slate-500">unit</span></p>
                </div>
            </div>

            <!-- Description -->
            <div>
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Deskripsi Produk</h3>
                <div class="p-4 rounded-xl bg-slate-50/50 border border-slate-100 text-sm text-slate-700 leading-relaxed">
                    {{ $product->description ?: 'Tidak ada catatan deskripsi untuk produk ini.' }}
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-100 text-sm font-semibold transition">
                    &larr; Kembali
                </a>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('products.edit', $product) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                        Edit Data
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

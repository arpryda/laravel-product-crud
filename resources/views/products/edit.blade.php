@extends('layouts.app')

@section('title', 'Edit Produk: ' . $product->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex text-xs text-slate-500 space-x-2">
        <a href="{{ route('products.index') }}" class="hover:text-emerald-600 transition">Daftar Produk</a>
        <span>/</span>
        <span class="text-slate-800 font-semibold">Edit Produk</span>
    </nav>

    <!-- Header Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Perbarui Data Produk</h1>
                <p class="text-xs text-slate-500 mt-1">Mengedit informasi untuk ID Produk #{{ $product->id }}.</p>
            </div>
            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-100 text-slate-700">
                ID: {{ $product->id }}
            </span>
        </div>

        <form action="{{ route('products.update', $product) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Nama Produk -->
            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                    Nama Produk <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       maxlength="100"
                       value="{{ old('name', $product->name) }}" 
                       required
                       class="w-full px-4 py-2.5 rounded-xl border text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('name') border-red-300 bg-red-50/50 @else border-slate-200 @enderror">
                <div class="flex justify-between items-center mt-1">
                    @error('name')
                        <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
                    @else
                        <p class="text-xs text-slate-400">Maksimal 100 karakter.</p>
                    @enderror
                </div>
            </div>

            <!-- Deskripsi Produk -->
            <div>
                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                    Deskripsi Produk <span class="text-xs font-normal text-slate-400 lowercase">(opsional)</span>
                </label>
                <textarea name="description" 
                          id="description" 
                          rows="4" 
                          placeholder="Tuliskan spesifikasi, keunggulan, atau catatan produk..."
                          class="w-full px-4 py-2.5 rounded-xl border text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('description') border-red-300 bg-red-50/50 @else border-slate-200 @enderror">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid Harga & Stok -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Harga Produk -->
                <div>
                    <label for="price" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                        Harga (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm font-semibold">
                            Rp
                        </div>
                        <input type="number" 
                               name="price" 
                               id="price" 
                               step="0.01" 
                               min="0"
                               value="{{ old('price', $product->price) }}" 
                               required
                               class="w-full pl-11 pr-4 py-2.5 rounded-xl border text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('price') border-red-300 bg-red-50/50 @else border-slate-200 @enderror">
                    </div>
                    @error('price')
                        <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stok Produk -->
                <div>
                    <label for="stock" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                        Stok Produk <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="stock" 
                           id="stock" 
                           min="0"
                           step="1"
                           value="{{ old('stock', $product->stock) }}" 
                           required
                           class="w-full px-4 py-2.5 rounded-xl border text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('stock') border-red-300 bg-red-50/50 @else border-slate-200 @enderror">
                    @error('stock')
                        <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('products.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-100 text-sm font-semibold transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center space-x-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Manajemen Produk') - Aksesmu Catalog Hub</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#effef2',
                            100: '#d9fbe2',
                            500: '#16a34a',
                            600: '#15803d',
                            700: '#166534',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Top Navigation Bar -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white font-bold text-lg shadow-md shadow-emerald-200">
                        A
                    </div>
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="font-bold text-slate-900 text-lg tracking-tight">Aksesmu</span>
                            <span class="text-xs bg-emerald-100 text-emerald-800 font-semibold px-2 py-0.5 rounded-full border border-emerald-200">Product Hub</span>
                        </div>
                        <p class="text-xs text-slate-500">Technical Test Web Development - Laravel 11</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('products.index') }}" class="text-sm font-semibold text-slate-700 hover:text-emerald-600 transition">
                        Daftar Produk
                    </a>
                    <a href="{{ route('products.create') }}" class="inline-flex items-center space-x-1.5 px-3.5 py-2 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Produk</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Flash Messages Alert -->
        @if (session('success'))
            <div id="flash-success" class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start justify-between shadow-sm">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button onclick="document.getElementById('flash-success').remove()" class="text-emerald-500 hover:text-emerald-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 shadow-sm">
                <div class="flex items-center space-x-2 font-semibold text-sm mb-2">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span>Terdapat kesalahan pada input Anda:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500 mt-auto">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} PT Sumber Trijaya Lestari (Aksesmu). Dikerjakan untuk Technical Test Posisi Data Mining & Web Development Intern.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

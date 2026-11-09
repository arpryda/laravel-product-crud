import http.server
import socketserver
import urllib.parse
import json
import sqlite3
import os
import webbrowser

PORT = 8000
DB_FILE = os.path.join(os.path.dirname(__file__), 'preview_db.sqlite')

def init_db():
    conn = sqlite3.connect(DB_FILE)
    c = conn.cursor()
    c.execute('''
        CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            description TEXT,
            price REAL NOT NULL,
            stock INTEGER NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ''')
    c.execute('SELECT COUNT(*) FROM products')
    if c.fetchone()[0] == 0:
        seed = [
            ('Beras Premium Ramos 5kg', 'Beras putih pulen kualitas premium hasil gilingan segar, cocok untuk konsumsi harian keluarga dan usaha kuliner.', 74500.0, 85),
            ('Minyak Goreng Bimoli Pouch 2 Liter', 'Minyak goreng kelapa sawit murni dengan teknologi pemurnian higienis, menghasilkan gorengan renyah tahan lama.', 38000.0, 120),
            ('Gula Pasir Gulaku Premium Putih 1kg', 'Gula kristal putih murni dari tebu pilihan dengan standar higienis tinggi, butiran halus dan cepat larut.', 17500.0, 60),
            ('Indomie Goreng Spesial (Karton isi 40 pcs)', 'Mie instan goreng legendaris dengan bumbu gurih lengkap, bawang goreng renyah, dan saus cabai lezat.', 118000.0, 45),
            ('Kopi Kapal Api Spesial Mix (Renceng isi 10 sachet)', 'Kopi hitam bubuk berpadu dengan takaran gula pas, aroma harum mantap khas kopi Indonesia.', 14000.0, 150),
            ('Susu UHT Frisian Flag Full Cream 1 Liter', 'Susu sapi cair segar siap minum kaya akan vitamin A, D3, B1, B2, B12, dan kalsium tinggi.', 19500.0, 8)
        ]
        c.executemany('INSERT INTO products (name, description, price, stock) VALUES (?, ?, ?, ?)', seed)
        conn.commit()
    conn.close()

init_db()

def get_db():
    conn = sqlite3.connect(DB_FILE)
    conn.row_factory = sqlite3.Row
    return conn

def format_rupiah(val):
    return 'Rp ' + f'{int(val):,}'.replace(',', '.')

def get_stock_badge(stock):
    if stock <= 0:
        return ('Habis', 'bg-red-100 text-red-700 border-red-300')
    elif stock < 10:
        return (f'Menipis ({stock})', 'bg-amber-100 text-amber-700 border-amber-300')
    else:
        return (f'Tersedia ({stock})', 'bg-emerald-100 text-emerald-700 border-emerald-300')

def render_layout(title, content, flash_msg=''):
    flash_html = ''
    if flash_msg:
        flash_html = f'''
        <div id="flash-success" class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start justify-between shadow-sm">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="text-sm font-medium">{flash_msg}</span>
            </div>
            <button onclick="document.getElementById('flash-success').remove()" class="text-emerald-500 hover:text-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        '''

    return f'''<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{title} - Aksesmu Product Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body {{ font-family: 'Plus Jakarta Sans', sans-serif; }}</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white font-bold text-lg shadow-md shadow-emerald-200">A</div>
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="font-bold text-slate-900 text-lg tracking-tight">Aksesmu</span>
                            <span class="text-xs bg-emerald-100 text-emerald-800 font-semibold px-2 py-0.5 rounded-full border border-emerald-200">Live Preview</span>
                        </div>
                        <p class="text-xs text-slate-500">Technical Test Web Development - Laravel 11 CRUD</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/products" class="text-sm font-semibold text-slate-700 hover:text-emerald-600 transition">Daftar Produk</a>
                    <a href="/products/create" class="inline-flex items-center space-x-1.5 px-3.5 py-2 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Produk</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {flash_html}
        {content}
    </main>
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500 mt-auto">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; 2026 PT Sumber Trijaya Lestari (Aksesmu). Mode Live Test Preview Aplikasi Laravel CRUD Produk.</p>
        </div>
    </footer>
</body>
</html>'''

class Handler(http.server.SimpleHTTPRequestHandler):
    def do_GET(self):
        parsed = urllib.parse.urlparse(self.path)
        path = parsed.path
        params = urllib.parse.parse_qs(parsed.query)

        flash = params.get('flash', [''])[0]

        if path in ['/', '/products']:
            search = params.get('search', [''])[0].strip()
            conn = get_db()
            c = conn.cursor()
            if search:
                c.execute('SELECT * FROM products WHERE name LIKE ? OR description LIKE ? ORDER BY id DESC', (f'%{search}%', f'%{search}%'))
            else:
                c.execute('SELECT * FROM products ORDER BY id DESC')
            products = c.fetchall()

            c.execute('SELECT COUNT(*), SUM(stock), AVG(price) FROM products')
            stats = c.fetchone()
            total_items = stats[0] or 0
            total_stock = stats[1] or 0
            avg_price = stats[2] or 0.0
            conn.close()

            rows_html = ''
            for idx, p in enumerate(products, 1):
                badge_label, badge_color = get_stock_badge(p['stock'])
                desc_html = f'<p class="text-xs text-slate-500 line-clamp-1 mt-0.5">{p["description"]}</p>' if p["description"] else '<p class="text-xs text-slate-400 italic mt-0.5">Tidak ada deskripsi</p>'
                rows_html += f'''
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-4 px-4 text-center text-xs font-medium text-slate-400">{idx}</td>
                    <td class="py-4 px-4">
                        <span class="font-semibold text-slate-900">{p["name"]}</span>
                        {desc_html}
                    </td>
                    <td class="py-4 px-4 text-right font-semibold text-slate-900">{format_rupiah(p["price"])}</td>
                    <td class="py-4 px-4 text-center font-medium text-slate-700">{p["stock"]:,}</td>
                    <td class="py-4 px-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {badge_color}">
                            {badge_label}
                        </span>
                    </td>
                    <td class="py-4 px-4 text-center">
                        <div class="inline-flex items-center space-x-1.5">
                            <a href="/products/edit?id={p['id']}" class="p-1.5 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <button type="button" onclick="confirmDelete({p['id']}, '{p['name']}')" class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>'''

            if not rows_html:
                rows_html = '''<tr><td colspan="6" class="py-12 text-center text-slate-500">Tidak ada produk ditemukan.</td></tr>'''

            content = f'''
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Katalog Produk</h1>
                        <p class="text-sm text-slate-500 mt-1">Kelola data inventaris, harga, dan ketersediaan stok produk.</p>
                    </div>
                    <div>
                        <a href="/products/create" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-sm transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Tambah Produk Baru</span>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Item</p>
                            <p class="text-2xl font-bold text-slate-800">{total_items} <span class="text-xs font-normal text-slate-500">produk</span></p>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Stok</p>
                            <p class="text-2xl font-bold text-slate-800">{total_stock:,} <span class="text-xs font-normal text-slate-500">unit</span></p>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Rata-rata Harga</p>
                            <p class="text-2xl font-bold text-slate-800">{format_rupiah(avg_price)}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row gap-3 justify-between items-center">
                    <form action="/products" method="GET" class="w-full sm:w-96 flex items-center gap-2">
                        <div class="relative w-full">
                            <input type="text" name="search" value="{search}" placeholder="Cari nama atau deskripsi..." class="w-full pl-4 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-xl">Cari</button>
                        {f'<a href="/products" class="px-3 py-2 bg-slate-100 text-slate-600 text-sm font-medium rounded-xl">Reset</a>' if search else ''}
                    </form>
                    <p class="text-xs text-slate-500">Total: <span class="font-semibold text-slate-700">{len(products)}</span> produk ditampilkan</p>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-semibold uppercase text-slate-500 tracking-wider">
                                    <th class="py-3.5 px-4 text-center w-14">No</th>
                                    <th class="py-3.5 px-4">Nama Produk & Deskripsi</th>
                                    <th class="py-3.5 px-4 text-right">Harga</th>
                                    <th class="py-3.5 px-4 text-center">Stok</th>
                                    <th class="py-3.5 px-4 text-center">Status</th>
                                    <th class="py-3.5 px-4 text-center w-28">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                {rows_html}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal Konfirmasi Hapus -->
            <div id="deleteModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 space-y-4">
                    <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div class="text-center space-y-1">
                        <h3 class="text-lg font-bold text-slate-900">Konfirmasi Hapus Produk</h3>
                        <p class="text-sm text-slate-500">Apakah Anda yakin ingin menghapus <span id="delName" class="font-semibold text-slate-800"></span>? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <form id="delForm" method="POST" action="/products/delete">
                        <input type="hidden" name="id" id="delId" value="">
                        <div class="flex items-center justify-end space-x-3 pt-2">
                            <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl">Batal</button>
                            <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-sm">Ya, Hapus Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                function confirmDelete(id, name) {{
                    document.getElementById('delId').value = id;
                    document.getElementById('delName').innerText = '"' + name + '"';
                    document.getElementById('deleteModal').classList.remove('hidden');
                }}
            </script>
            '''
            self.send_response(200)
            self.send_header('Content-type', 'text/html; charset=utf-8')
            self.end_headers()
            self.wfile.write(render_layout('Daftar Produk', content, flash).encode('utf-8'))

        elif path == '/products/create':
            content = '''
            <div class="max-w-2xl mx-auto space-y-6">
                <nav class="flex text-xs text-slate-500 space-x-2">
                    <a href="/products" class="hover:text-emerald-600">Daftar Produk</a>
                    <span>/</span>
                    <span class="text-slate-800 font-semibold">Tambah Baru</span>
                </nav>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
                    <div>
                        <h1 class="text-xl font-bold text-slate-900">Form Tambah Produk Baru</h1>
                        <p class="text-xs text-slate-500 mt-1">Lengkapi data produk. Tanda bintang (<span class="text-red-500">*</span>) wajib diisi.</p>
                    </div>
                    <form action="/products/create" method="POST" class="space-y-5">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Nama Produk *</label>
                            <input type="text" name="name" maxlength="100" required placeholder="Contoh: Beras Ramos 5kg" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Deskripsi (Opsional)</label>
                            <textarea name="description" rows="3" placeholder="Spesifikasi / catatan produk..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Harga (Rp) *</label>
                                <input type="number" step="0.01" min="0" name="price" required placeholder="Contoh: 75000" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Stok Awal *</label>
                                <input type="number" step="1" min="0" name="stock" value="0" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                            <a href="/products" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-sm font-semibold">Batal</a>
                            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm">Simpan Produk</button>
                        </div>
                    </form>
                </div>
            </div>'''
            self.send_response(200)
            self.send_header('Content-type', 'text/html; charset=utf-8')
            self.end_headers()
            self.wfile.write(render_layout('Tambah Produk', content).encode('utf-8'))

        elif path == '/products/edit':
            prod_id = params.get('id', [''])[0]
            conn = get_db()
            c = conn.cursor()
            c.execute('SELECT * FROM products WHERE id = ?', (prod_id,))
            p = c.fetchone()
            conn.close()

            if not p:
                self.send_response(302)
                self.send_header('Location', '/products')
                self.end_headers()
                return

            content = f'''
            <div class="max-w-2xl mx-auto space-y-6">
                <nav class="flex text-xs text-slate-500 space-x-2">
                    <a href="/products" class="hover:text-emerald-600">Daftar Produk</a>
                    <span>/</span>
                    <span class="text-slate-800 font-semibold">Edit Produk</span>
                </nav>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-xl font-bold text-slate-900">Perbarui Data Produk</h1>
                            <p class="text-xs text-slate-500 mt-1">Mengedit informasi untuk ID #{p['id']}</p>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-100 text-slate-700">ID: {p['id']}</span>
                    </div>
                    <form action="/products/edit" method="POST" class="space-y-5">
                        <input type="hidden" name="id" value="{p['id']}">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Nama Produk *</label>
                            <input type="text" name="name" value="{p['name']}" maxlength="100" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Deskripsi (Opsional)</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">{p['description'] or ''}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Harga (Rp) *</label>
                                <input type="number" step="0.01" min="0" name="price" value="{p['price']}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Stok *</label>
                                <input type="number" step="1" min="0" name="stock" value="{p['stock']}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                            <a href="/products" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-sm font-semibold">Batal</a>
                            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>'''
            self.send_response(200)
            self.send_header('Content-type', 'text/html; charset=utf-8')
            self.end_headers()
            self.wfile.write(render_layout('Edit Produk', content).encode('utf-8'))
        else:
            self.send_response(404)
            self.end_headers()

    def do_POST(self):
        parsed = urllib.parse.urlparse(self.path)
        path = parsed.path
        length = int(self.headers.get('Content-Length', 0))
        body = self.rfile.read(length).decode('utf-8')
        data = urllib.parse.parse_qs(body)

        if path == '/products/create':
            name = data.get('name', [''])[0].strip()
            desc = data.get('description', [''])[0].strip()
            try: price = float(data.get('price', [0])[0])
            except: price = 0.0
            try: stock = int(data.get('stock', [0])[0])
            except: stock = 0

            conn = get_db()
            c = conn.cursor()
            c.execute('INSERT INTO products (name, description, price, stock) VALUES (?, ?, ?, ?)', (name, desc, price, stock))
            conn.commit()
            conn.close()

            self.send_response(302)
            self.send_header('Location', f'/products?flash={urllib.parse.quote("Produk baru berhasil ditambahkan!")}')
            self.end_headers()

        elif path == '/products/edit':
            prod_id = data.get('id', [''])[0]
            name = data.get('name', [''])[0].strip()
            desc = data.get('description', [''])[0].strip()
            try: price = float(data.get('price', [0])[0])
            except: price = 0.0
            try: stock = int(data.get('stock', [0])[0])
            except: stock = 0

            conn = get_db()
            c = conn.cursor()
            c.execute('UPDATE products SET name = ?, description = ?, price = ?, stock = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?', (name, desc, price, stock, prod_id))
            conn.commit()
            conn.close()

            self.send_response(302)
            self.send_header('Location', f'/products?flash={urllib.parse.quote("Data produk berhasil diperbarui!")}')
            self.end_headers()

        elif path == '/products/delete':
            prod_id = data.get('id', [''])[0]
            conn = get_db()
            c = conn.cursor()
            c.execute('DELETE FROM products WHERE id = ?', (prod_id,))
            conn.commit()
            conn.close()

            self.send_response(302)
            self.send_header('Location', f'/products?flash={urllib.parse.quote("Produk berhasil dihapus dari sistem!")}')
            self.end_headers()

if __name__ == '__main__':
    print(f'Server aktif di http://localhost:{PORT}')
    webbrowser.open(f'http://localhost:{PORT}')
    with socketserver.TCPServer(('', PORT), Handler) as httpd:
        httpd.serve_forever()

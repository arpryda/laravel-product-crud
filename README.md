# Product CRUD - Laravel 11

Aplikasi web sederhana untuk mengelola katalog produk menggunakan **Laravel 11** dan **Tailwind CSS**.

---

## Fitur Utama

- **List Produk**: Tabel data produk dengan pencarian nama/deskripsi, filter status stok, dan pengurutan (nama/harga/stok).
- **Tambah Produk**: Form tambah produk baru dengan validasi server-side.
- **Edit Produk**: Form edit produk dengan pre-filled data.
- **Hapus Produk**: Fitur hapus dengan modal dialog konfirmasi sebelum data dihapus.
- **Ekspor CSV**: Fitur unduh seluruh daftar produk ke format file CSV.
- **Seeder**: Contoh data awal produk sembako siap pakai.

---

## Struktur Tabel `products`

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT | Primary Key, Auto Increment |
| `name` | VARCHAR(100) | Nama produk (maks 100 karakter) |
| `description` | TEXT | Deskripsi produk (opsional) |
| `price` | DECIMAL(10,2) | Harga satuan produk |
| `stock` | INTEGER | Jumlah stok (default: 0) |
| `timestamps` | TIMESTAMP | `created_at` & `updated_at` |

---

## Cara Menjalankan

1. **Clone repository:**
   ```bash
   git clone <URL_REPOSITORY>
   cd laravel-product-crud
   ```

2. **Install dependency:**
   ```bash
   composer install
   ```

3. **Setup environment & generate key:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrate & seed database:**
   ```bash
   # Default menggunakan SQLite:
   touch database/database.sqlite
   php artisan migrate --seed
   ```

5. **Jalankan local server:**
   ```bash
   php artisan serve
   ```
   Buka di browser: `http://localhost:8000`

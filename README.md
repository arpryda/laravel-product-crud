# Aksesmu Product Hub - Technical Test Web Development (Laravel 11)

Aplikasi manajemen katalog produk berbasis **Laravel 11** dan **Tailwind CSS** yang dibangun untuk memenuhi kriteria **Technical Test Web Development - PT Sumber Trijaya Lestari (Aksesmu)**.

---

## 🚀 Fitur Aplikasi

Sesuai dengan spesifikasi pengujian teknis, aplikasi ini mengimplementasikan:

1. **Daftar Produk (Read)**:
   - Menampilkan seluruh katalog produk dalam tabel modern dan responsif.
   - Ringkasan statistik cepat: Total Item, Total Stok, dan Rata-rata Nilai Produk.
   - Fitur pencarian cepat (*live search query*) berdasarkan nama dan deskripsi produk.
   - Format mata uang Rupiah otomatis (*Custom Accessor*).
   - Indikator status ketersediaan stok dinamis (*Badge*: Tersedia, Menipis, Habis).
   - Paginasi data yang rapi dan terintegrasi (*Pagination*).

2. **Tambah Produk Baru (Create)**:
   - Formulir input intuitif dengan validasi data di sisi server (*Server-side Form Request Validation*).
   - Validasi nama produk wajib diisi (*max 100 karakter*).
   - Validasi harga berupa nominal positif (*min 0*).
   - Validasi stok bilangan bulat non-negatif (*min 0*).
   - Menampilkan pesan error spesifik berbahasa Indonesia tepat di bawah bidang input yang bersangkutan.

3. **Edit Produk (Update)**:
   - Formulir pembaruan data yang terisi otomatis dengan nilai sebelumnya (*pre-filled* dan *fallback old value*).
   - Menggunakan metode HTTP `PUT` sesuai standar RESTful.

4. **Hapus Produk (Delete)**:
   - Fitur hapus data yang dilengkapi **Modal Konfirmasi Interaktif** sebelum penghapusan dieksekusi untuk mencegah ketidaksengajaan pengguna.
   - Notifikasi *Flash Alert* yang informatif saat operasi berhasil.

5. **Data Seeder Riil**:
   - Dilengkapi dengan seeder minimal 6 produk ritel sembako (FMCG) riil yang relevan dengan ekosistem bisnis warung kelontong Aksesmu.

---

## 🛠️ Spesifikasi Database

Tabel `products` dirancang menggunakan migrasi Laravel dengan skema berikut:

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `BIGINT (Unsigned)` | Primary Key, Auto Increment |
| `name` | `VARCHAR(100)` | Nama produk (Maksimal 100 karakter, Wajib) |
| `description` | `TEXT` | Deskripsi detail produk (Nullable / Opsional) |
| `price` | `DECIMAL(10,2)` | Harga satuan produk |
| `stock` | `INTEGER` | Jumlah stok produk (Default: 0) |
| `created_at` | `TIMESTAMP` | Waktu pembuatan data |
| `updated_at` | `TIMESTAMP` | Waktu pembaruan data terakhir |

---

## 🏛️ Arsitektur & Clean Code Practices

Aplikasi ini menerapkan standar arsitektur dan konvensi *best practices* Laravel:
- **Separation of Concerns**: Validasi formulir dipisahkan secara modular ke dalam *Form Request classes* (`StoreProductRequest` dan `UpdateProductRequest`) sehingga `ProductController` tetap ramping dan fokus pada alur bisnis (*Single Responsibility Principle*).
- **RESTful Resource Controller**: Menggunakan `Route::resource` yang memetakan aksi ke konvensi standar Laravel (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`).
- **Eloquent Casting & Accessor**:
  - `$casts` untuk memastikan presisi nilai `price` dan integer pada `stock`.
  - Accessor `formatted_price` untuk konversi otomatis nominal Rupiah.
  - Accessor `stock_status` untuk logika warna badge UI.
- **Blade Templating**: Menggunakan layout modular (`layouts/app.blade.php`) dengan Tailwind CSS dan Google Fonts Plus Jakarta Sans.

---

## 💻 Panduan Menjalankan Proyek

### 1. Clone Repositori
```bash
git clone <URL_REPOSITORY_ANDA>
cd laravel-product-crud
```

### 2. Pasang Dependensi
```bash
composer install
```

### 3. Konfigurasi Environment
Salin file konfigurasi `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Generate App Key:
```bash
php artisan key:generate
```

### 4. Setup Database & Jalankan Seeder
Secara default, konfigurasi telah disiapkan menggunakan SQLite (zero-configuration):
```bash
# Buat file database sqlite jika belum ada
touch database/database.sqlite

# Jalankan migrasi dan seeder
php artisan migrate --seed
```

> **Catatan untuk MySQL:**
> Jika ingin menggunakan database MySQL, buka file `.env` lalu sesuaikan kredensial berikut:
> ```env
> DB_CONNECTION=mysql
> DB_HOST=127.0.0.1
> DB_PORT=3306
> DB_DATABASE=aksesmu_products
> DB_USERNAME=root
> DB_PASSWORD=
> ```
> Setelah itu jalankan kembali: `php artisan migrate:fresh --seed`

### 5. Jalankan Local Development Server
```bash
php artisan serve
```
Buka browser dan akses aplikasi melalui: `http://localhost:8000`

---

## 📁 Struktur Direktori Utama

```text
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── ProductController.php      # Controller CRUD Produk
│   │   └── Requests/
│   │       ├── StoreProductRequest.php    # Validasi Tambah Produk
│   │       └── UpdateProductRequest.php   # Validasi Edit Produk
│   └── Models/
│       └── Product.php                    # Model Eloquent Produk
├── database/
│   ├── migrations/
│   │   └── 2024_01_01_000001_create_products_table.php # Skema Tabel
│   └── seeders/
│       ├── DatabaseSeeder.php             # Master Seeder
│       └── ProductSeeder.php              # Seeder 6 Data Produk Sembako
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php              # Layout Utama Tailwind CSS
│       └── products/
│           ├── index.blade.php            # Tabel List & Modal Hapus
│           ├── create.blade.php           # Form Tambah Produk
│           ├── edit.blade.php             # Form Edit Produk
│           └── show.blade.php             # Tampilan Detail Produk
└── routes/
    └── web.php                            # Rute Web RESTful
```

---
Dibuat untuk keperluan evaluasi **Technical Test Web Development & Data Mining** di **PT Sumber Trijaya Lestari (Aksesmu)**.

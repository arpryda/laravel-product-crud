<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel 'products'.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            // 1. Primary Key, Auto Increment
            $table->id();

            // 2. String, Maksimal 100 karakter
            $table->string('name', 100);

            // 3. Text, Opsional (nullable)
            $table->text('description')->nullable();

            // 4. Decimal, 10 digit total, 2 digit desimal di belakang koma
            $table->decimal('price', 10, 2);

            // 5. Integer, Default: 0
            $table->integer('stock')->default(0);

            // 6. Timestamps: created_at & updated_at
            $table->timestamps();
        });
    }

    /**
     * Rollback migrasi: hapus tabel 'products' jika dibatalkan.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

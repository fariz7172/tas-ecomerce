# Fitur: Multi-Varian Produk Tas (Master Data & Katalog)

**Status**: In Progress  
**Tanggal**: 03 Oktober 2026  
**Stack**: Laravel 11 + Livewire 3 + Tailwind CSS  
**Lead Developer / Subagent**: dev-backend & dev-frontend (Supervised by Geta)  

---

## 1. Tujuan
Membangun struktur data master produk tas yang mendukung variasi warna, bahan spesifik, dan kontrol inventori per varian warna secara terisolasi agar tidak terjadi kehabisan stok (*overselling*) saat pembeli memesan warna tertentu.

---

## 2. User Story & Acceptance Criteria
- *Sebagai pembeli*, saya ingin melihat pilihan warna tas dan stok yang tersedia pada warna tersebut secara langsung tanpa harus me-refresh halaman.
- *Acceptance Criteria*:
  - Setiap produk tas dapat memiliki $\ge 1$ varian warna.
  - Setiap varian memiliki SKU unik, stok mandiri, dan harga khusus jika ada promo.
  - Jika stok varian = 0, tombol varian tersebut nonaktif (*disabled*) dan berlabel *"Habis"*.

---

## 3. Alur Kerja (Workflow)
1. Admin menginput data tas (Nama, Kategori, Bahan, Dimensi, Berat).
2. Admin menambahkan daftar varian warna (Nama Warna, Kode Hex Warna, SKU, Harga, Stok).
3. Pengunjung melihat kartu produk dan memilih varian warna di katalog/halaman detail produk.
4. Sistem menyinkronkan ketersediaan stok varian ke tombol *Checkout*.

---

## 4. Skema Database / Model
- Tabel `categories`
- Tabel `products`
- Tabel `product_variants`
- Tabel `product_images`

---

## 5. File yang Diubah / Dibuat
- `database/migrations/xxxx_create_products_and_variants_tables.php`
- `app/Models/Product.php`
- `app/Models/ProductVariant.php`
- `app/Models/Category.php`
- `resources/views/livewire/product/catalog.blade.php`

---

## 6. Cara Test & Verifikasi
- Menjalankan migrasi: `php artisan migrate`
- Menjalankan database seeder data tas: `php artisan db:seed`
- Menguji render katalog via Livewire tanpa error sintaksis.

---

## 7. Changelog Fitur
- **2026-10-03**: Inisialisasi dokumen fitur pasca penyusunan PRD master.

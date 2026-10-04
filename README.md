# 👜 Mikael On Shop — Luxury D2C Handbag & Leather Goods E-Commerce Platform

Platform e-commerce D2C (*Direct-to-Customer*) modern dan interaktif khusus penjualan produk tas luxury dan aksesori kulit original (**Mikael On Shop**). Dilengkapi dengan pengalaman visual 3D Three.js, kalkulasi ongkos kirim real-time, gateway pembayaran otomatis Midtrans (QRIS barcode streaming, Virtual Account, & COD), label pengiriman thermal 10x15 cm siap cetak, serta Panel Administrasi lengkap untuk tata kelola katalog, pesanan, dan invoice resmi toko.

---

## ✨ Fitur Utama (Key Features)

### 1. 🛍️ Customer Experience & Visual Commerce
- **Petal & Champagne Silk Aesthetic**: Desain antarmuka lembut, elegan, dan mewah yang dirancang khusus untuk barang fesyen premium.
- **Three.js 3D Interactive Canvas**: Visualisasi model tas 3D real-time dengan rotasi 360°, orbit controls, dan pencahayaan studio.
- **Varian & Galeri Dinamis**: Seleksi warna tas (Noir Black, Champagne Silk, Petal Rose, Cognac Brown) dengan update gambar instan dan validasi ketersediaan stok real-time.
- **Wishlist & History Pesanan**: Fitur simpan tas favorit dan pemantauan riwayat belanja pelanggan yang responsif di smartphone.
- **Anti-Overbooking Stock Reservation**: Sistem penguncian stok dengan database pessimistic locking (`lockForUpdate`) dan slot reservasi pembayaran otomatis (15 menit).

### 2. 💳 Pembayaran & Integrasi Midtrans
- **Midtrans Payment Gateway**: Mendukung Virtual Account Multi-Bank (BCA, Mandiri, BNI, BRI), Credit Card, dan COD (Bayar di Tempat).
- **Streaming Barcode QRIS Instan**: Backend proxy stream resmi dari Midtrans API (`/api/order/{order_number}/qris-image`) sehingga barcode dapat langsung di-scan melalui aplikasi m-banking / e-wallet pelanggan.
- **Webhook Status Sync**: Pembaruan status pembayaran otomatis dari `pending` ke `paid` secara real-time via notifikasi resmi Midtrans.

### 3. 🚚 Logistik & Pengiriman Cerdas
- **Pencarian Lokasi Cepat**: Autocomplete kota/kecamatan seluruh Indonesia melalui API internal yang di-cache.
- **Kalkulasi Ongkir Real-time**: Perhitungan akurat tarif ekspedisi (JNE, SiCepat, J&T, POS) berdasarkan berat gram tas.
- **Label Resi Thermal 10x15 cm**: Cetak label tempel resi paket gudang dengan barcode standar printer thermal shipping label.

### 4. 🛡️ Keamanan, Hak Akses & Integritas Toko
- **Sistem Autentikasi Bertingkat (RBAC)**: Pemisahan hak akses yang ketat antara akun `customer` dan `admin` via `AdminMiddleware`.
- **Integrity Guard (Admin Anti-Order)**: Proteksi 3 lapis (UI Blade guard, Controller redirect, dan Endpoint HTTP 403) yang melarang akun administrator melakukan transaksi belanja di toko sendiri guna menjaga integritas data kasir dan audit inventaris.
- **Proteksi Navigasi Panel**: Tautan dan portal Admin Panel di landing page hanya terlihat jika user yang sedang login berstatus Admin.

### 5. 📊 Panel Administrasi & Gudang (Admin Hub)
- **Dashboard Manajemen Pesanan**:
  - Pelacakan pesanan real-time dengan pencarian multi-kolom (No. Order, Nama Pembeli, No. HP/WA, Resi, Kota).
  - Multi-filter Status Pesanan (Menunggu Bayar, Lunas, Dipacking, Dikirim, Selesai, Batal) dan Metode Pembayaran.
  - Pagination server-side terintegrasi (`paginate(10)`).
- **Detail Pesanan Lengkap**: Rincian item tas, catatan pelanggan, breakdown pembayaran, update status cepat, input nomor resi, dan tombol WhatsApp instan.
- **Cetak Invoice Resmi Toko**: Layout *Official Tax Invoice* siap cetak kertas A4 / simpan PDF dengan tanda tangan otorisasi elektronik.
- **Katalog & Inventaris Tas**:
  - Manajemen Produk, Varian Warna, Kategori, dan Merk (Brand).
  - Pencarian tas, filter merk & kategori, serta pagination.
  - Auto-Restock rekomendasi otomatis ketika stok berada di bawah batas ambang (*safety stock*).

---

## 🛠️ Tech Stack

- **Backend**: Laravel 11.x (PHP 8.2+)
- **Frontend**: Blade Templating, Tailwind CSS, Alpine.js
- **3D Engine**: Three.js (WebGL, OrbitControls, GLTFLoader)
- **Database**: MySQL / MariaDB (Pessimistic Locking `lockForUpdate`)
- **Payment Gateway**: Midtrans Snap & Core API (Sandbox / Production)
- **Authentication**: Laravel Session Auth, Google OAuth (Socialite)

---

## 🚀 Panduan Menjalankan Aplikasi

1. Clone repositori:
   ```bash
   git clone https://github.com/fariz7172/tas-ecomerce.git
   cd tas-ecomerce
   ```

2. Install dependensi & generate key:
   ```bash
   composer install
   php artisan key:generate
   php artisan storage:link
   ```

3. Jalankan server lokal:
   ```bash
   php artisan serve
   ```

Akses di browser:
- **Toko Utama**: `http://localhost:8000`
- **Admin Hub**: `http://localhost:8000/admin/dashboard`
- **Katalog Admin**: `http://localhost:8000/admin/products`

---

## 🔒 Akun Administrator Default
- **Email**: `mikael@gmail.com`
- **Role**: `admin`

---

## 📄 Lisensi
Hak Cipta © 2026 **Mikael On Shop**. Dikelola dan dikembangkan oleh Fariz Ahmad.

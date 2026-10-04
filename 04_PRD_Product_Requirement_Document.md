# 04. Product Requirement Document (PRD) — E-Commerce Tas Platform

**Versi**: 1.0.0  
**Tanggal**: 03 Oktober 2026  
**Direktori Proyek**: `D:\program file\Laravel\e-comerce-tas`  
**Status**: APPROVED & READY FOR DEVELOPMENT  
**Lead Project & Head of Product**: Geta  

---

## 1. Identifikasi Pengguna & Persona
1. **End Customer (Shopper)**:
   - Pengguna mobile (85% traffic) yang ingin membeli tas kerja, tas hangout, atau ransel travel secara cepat, jelas melihat kapasitas kantong tas, dan checkout mudah via QRIS/VA.
2. **Admin Toko & Gudang**:
   - Staf internal yang mengelola katalog tas, memantau stok varian warna yang menipis, mencetak invoice & label resi, dan memproses pesanan masuk.
3. **Owner / Manajemen (Fariz)**:
   - Memantau laporan omset penjualan harian/bulanan, performa varian tas terlaris, dan tingkat retensi pelanggan.

---

## 2. Kebutuhan Fungsional (Functional Requirements)

### Modul 1: Katalog & Navigasi Produk Tas
- [x] **FR-1.1**: Tampilan katalog dengan filter instan Livewire: Kategori (Handbag, Shoulder Bag, Totebag, Sling Bag, Ransel, Duffle), Bahan (Kulit Sintetis PU, Kulit Asli, Kanvas Premium, Cordura), Rentang Harga, dan Ketersediaan Stok.
- [x] **FR-1.2**: Pencarian produk berbasis teks (*Full-text instant search*) dengan toleransi typo.
- [x] **FR-1.3**: Badge status dinamis pada kartu tas: *"BESTSELLER"*, *"NEW ARRIVAL"*, *"LIMITED EDITION"*.

### Modul 2: Halaman Detail Produk (PDP) & Interaksi Spesifik Tas
- [x] **FR-2.1**: Galeri visual multi-sudut (*Front, Side, Back, Interior, Detail Jahitan/Lining*) teroptimasi format WebP kualitas tinggi.
- [x] **FR-2.2**: Varian Warna Interaktif (Color Swatches) yang secara otomatis menyinkronkan foto galeri sesuai warna yang diklik.
- [x] **FR-2.3**: **Panduan Dimensi & Kompartemen Tas (Visual Bag Specs)**:
  - Visualisasi dimensi tas (Panjang x Lebar x Tinggi cm).
  - Informasi kompartemen dalam: jumlah kantong resleting, slot kartu/HP, dan jenis penutup (resleting zipper / magnetik kancing).
- [x] **FR-2.4**: Rincian Spesifikasi Material Terstruktur:
  - Dimensi Fisik: Panjang x Lebar x Tinggi (cm).
  - Berat Tas Kosong (gram).
  - Material Luar & Bahan Furing/Lining Dalam.
  - Panjang Tali Selempang (*Strap Drop Length* cm, adjustable / removable).

### Modul 3: Keranjang & Single-Page Checkout
- [x] **FR-3.1**: Mini-cart drawer slide-out dari kanan layar tanpa reload halaman.
- [x] **FR-3.2**: Single-Page Checkout (Guest checkout didukung tanpa wajib register akun terlebih dahulu).
- [x] **FR-3.3**: Integrasi API RajaOngkir / Ekspedisi Logistik:
  - Dropdown bertingkat Provinsi ➔ Kota/Kabupaten ➔ Kecamatan.
  - Pilihan kurir resmi: JNE (OKE, REG, YES), SiCepat, J&T Express.
  - Perhitungan berat otomatis (berat tas asli + estimasi dimensi volumetrik dus pengiriman).
- [x] **FR-3.4**: Input Kode Kupon Diskon (Potongan harga nominal atau persentase).

### Modul 4: Integrasi Pembayaran & Webhook
- [x] **FR-4.1**: Integrasi Midtrans Snap / Xendit Payment Gateway:
  - QRIS (GoPay, OVO, DANA, ShopeePay, LinkAja, BCA QR).
  - Virtual Account Bank (BCA, Mandiri, BNI, BRI, Permata).
  - Kartu Kredit / Debit Online (Visa / Mastercard 3D Secure).
- [x] **FR-4.2**: Listener Webhook Otomatis:
  - Mengubah status pesanan secara instan saat notifikasi pembayaran berhasil diterima dari gateway.
  - Mekanisme Idempotency Key untuk mencegah eksekusi ganda atau double-stock deduction.
- [x] **FR-4.3**: Auto-Cancel Pesanan Kedaluwarsa (Expired Order):
  - Pesanan pending yang tidak dibayar dalam batas waktu (misal 2 jam untuk QRIS atau 24 jam untuk VA) otomatis dibatalkan dan kuota stok varian dikembalikan ke sistem.

### Modul 5: Panel Admin Toko & Gudang
- [x] **FR-5.1**: CRUD Master Produk Tas & Multi-Varian SKU (Warna, Ukuran, Stok, Barcode).
- [x] **FR-5.2**: Order Management Board (Kanban / Tabel Tabulasi: *Menunggu Pembayaran*, *Perlu Diproses*, *Siap Dikirim*, *Sedang Dikirim*, *Selesai*, *Dibatalkan*).
- [x] **FR-5.3**: Cetak Label Pengiriman (Shipping Label Thermal 10x15 cm) & Invoice PDF standar A4.
- [x] **FR-5.4**: Input Nomor Resi Pengiriman dan kirim notifikasi update status ke pelanggan.
- [x] **FR-5.5**: Dashboard Analitik & Omset Penjualan (Grafik pendapatan, tas terlaris, persentase pembayaran sukses).

---

## 3. Kebutuhan Non-Fungsional (Non-Functional Requirements)
1. **Performa Kecepatan (Speed & Core Web Vitals)**:
   - First Contentful Paint (FCP) < 1.2 detik.
   - Wajib kompresi gambar otomatis ke format WebP agar foto katalog tas resolusi tinggi tidak membebani kuota dan bandwidth pengguna seluler.
2. **Keamanan (Security)**:
   - CSRF Protection pada setiap form transaksi.
   - SQL Injection & XSS sanitization ketat.
   - Verifikasi Signature Key pada Webhook Payment Gateway.
3. **Responsivitas & Mobile-First UX**:
   - Layout 100% responsif di layar smartphone (360px - 430px) hingga layar desktop ultra-wide.
   - Touch-friendly button (target klik minimal 44x44 pixel).

---

## 4. Rencana Roadmap Pengembangan (Phased Rollout)
- **Fase 1 (Fondasi & Desain UI Ritel)**: Setup Laravel, Tailwind UI Bespoke anti-slop, Migrasi Database relasi Tas & Varian, Seeder Master Data.
- **Fase 2 (Interaktivitas Belanja)**: Katalog, Filter Kategori/Bahan Tas, Panduan Dimensi Tas, Galeri Varian Warna, Keranjang Belanja Livewire.
- **Fase 3 (Checkout & Gateway)**: Integrasi Ongkir Kurir (Kecamatan level), Integrasi Midtrans Snap/QRIS, Webhook Handler, Pengurangan Stok Aman.
- **Fase 4 (Admin Dashboard & Logistik)**: Cetak Resi/Shipping Label, Manajemen Order, WhatsApp Notification, Uji Coba Transaksi Sandbox.
- **Fase 5 (Deployment & Go-Live)**: Konfigurasi Nginx/LiteSpeed, SSL HTTPS, Domain, dan Production Hardening.

# 01. Deskripsi & Arsitektur Proyek — E-Commerce Tas Premium

**Nama Inisiatif**: E-Commerce Platform Penjualan Tas (Boutique & Utility Bags)  
**Direktori Proyek**: `D:\program file\Laravel\e-comerce-tas`  
**Lead Project & Solution Architect**: Geta  
**Target Target Pasar**: Segmen Tas Wanita & Pria (Handbag, Shoulder Bag, Totebag, Sling Bag, Ransel Santai/Kerja, Duffle Bag) di Indonesia.  

---

## 1. Ringkasan Eksekutif & Visi Produk
Platform ini adalah sistem e-commerce *Direct-to-Consumer (D2C)* modern yang dirancang khusus untuk industri tas ritel. Industri tas memiliki karakteristik unik dibanding fashion pakaian:
1. **Fokus pada Detail Material & Fungsionalitas**: Pembeli tas sangat memperhatikan bahan (kulit asli/sintetis PU, kanvas premium, nilon tebal), jumlah kantong kompartemen dalam, kerapian jahitan, ketahanan resleting (YKK), dan berat tas kosong.
2. **Kebutuhan Visual Eksploratif**: Pembeli ingin melihat sudut 360°, detail tekstur jahitan/lining dalam, serta perbandingan ukuran tas saat dipakai oleh model (*wear test & scale reference*).
3. **Penyusutan Retur Melalui Spesifikasi Akurat**: Tas memiliki tingkat retur tinggi jika pembeli merasa dimensi fisik tidak sesuai ekspektasi. Platform menyediakan panduan dimensi fisik presisi (Panjang x Lebar x Tinggi cm).

---

## 2. Tech Stack & Fondasi Arsitektur
Sesuai standar operasional Fariz (performa tinggi, *simple > overly clever*, minim overhead, dan mobile-first):
- **Backend Framework**: Laravel 11 / PHP 8.3
- **Frontend Interactivity**: Livewire 3 + Alpine.js (SPA feel tanpa beban rumit frontend decoupling)
- **Styling UI**: Tailwind CSS (Desain Bespoke anti-slop: tipografi tegas *Space Grotesk / Plus Jakarta Sans*, pastel glass blur, high contrast visual, kartu produk bergaya butik mewah)
- **Database**: MySQL / MariaDB (Dengan relasi terindeks untuk SKU varian, inventori per varian warna/ukuran)
- **Payment Gateway**: Midtrans / Xendit (Snap Popup / API payment: QRIS, BCA/Mandiri VA, GoPay, OVO, ShopeePay, Kartu Kredit)
- **Shipping Logistics Engine**: RajaOngkir Pro / Biteship API (Hitung ongkir otomatis JNE, J&T, SiCepat, Anteraja hingga level kecamatan dengan berat volumetrik)
- **Asset Storage & Media Optimization**: Spatie Media Library + Intervention Image V3 (Auto-convert WebP kualitas 85% untuk galeri foto produk agar loading secepat kilat)

---

## 3. Fitur Unggulan Pembeda (*Key Value Proposition*)
1. **Interactive Bag Dimensions & Capacity Visualizer**:
   - Calon pembeli dapat melihat perbandingan ukuran tas (Panjang x Lebar x Tinggi cm), detail furing dalam, serta kapasitas kantong/slot barang bawaan.
2. **High-Definition Fabric & Texture Zoom**:
   - Zoom mikro untuk melihat tekstur kulit asli/sintetis PU, kerapatan rajutan kanvas, dan detail jahitan serta resleting.
3. **Multi-Variant Colorways with Live Photo Sync**:
   - Klik varian warna (Black, Mocca, Sage Green, Ivory, Espresso, dll) langsung mengganti carousel foto produk secara mulus tanpa me-reload halaman.
4. **Seamless 1-Page Checkout (Guest & Registered)**:
   - Form checkout super ringkas: Input Nama, WA, Alamat (Dropdown Kecamatan dinamis) ➔ Pilih Kurir ➔ Bayar via QRIS / VA langsung muncul popup. Mendukung checkout tanpa ribet daftar akun (Guest Checkout).
5. **WhatsApp Instant Notification & Order Tracking**:
   - Notifikasi otomatis konfirmasi pesanan, resi pengiriman, dan status barang via gateway WhatsApp Fonnte / Wablas ke nomor pembeli.

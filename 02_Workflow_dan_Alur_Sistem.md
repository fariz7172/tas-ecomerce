# 02. Workflow & Alur Sistem — E-Commerce Tas

**Direktori Proyek**: `D:\program file\Laravel\e-comerce-tas`  
**Lead Project**: Geta  

---

## 1. Customer Journey Workflow (Alur Pembeli)

```
[ Homepage / Kategori Tas ]
         │
         ▼
[ Filter & Search (Kategori Tas, Bahan, Warna, Rentang Harga) ]
         │
         ▼
[ Halaman Detail Produk (PDP) ]
  ├── Lihat Galeri Foto High-Res (WebP) per Varian Warna
  ├── Cek Spesifikasi Dimensi Tas (Panjang x Lebar x Tinggi cm)
  ├── Pilih Varian Warna (Black, Mocca, Sage Green, Camel, dll)
  └── Klik "Tambah ke Keranjang" atau "Beli Sekarang (Instant Checkout)"
         │
         ▼
[ Single-Page Checkout ]
  ├── Isi Data Penerima (Nama, No WhatsApp, Email opsional)
  ├── Pilih Provinsi ➔ Kota ➔ Kecamatan (Ajax Search)
  ├── Sistem Hitung Ongkir Otomatis via API Ekspedisi (JNE/J&T/SiCepat)
  ├── Masukkan Kode Voucher Diskon / Promo Ongkir (jika ada)
  └── Pilih Metode Pembayaran (QRIS, VA Bank, E-Wallet)
         │
         ▼
[ Payment Gateway Interaction (Midtrans/Xendit) ]
  ├── Pembeli Membayar via QRIS / Virtual Account
  └── Webhook Callback diterima Laravel secara realtime
         │
         ▼
[ Order Confirmation & Real-time Update ]
  ├── Status pesanan berubah menjadi 'PAID / DIPROSES'
  ├── Pengurangan stok otomatis pada varian tas yang dibeli
  ├── WhatsApp otomatis terkirim ke HP pembeli berisi Invoice & Link Cek Status
  └── Notifikasi masuk ke Dashboard Admin Gudang
```

---

## 2. Order Fulfillment & Warehouse Workflow (Alur Toko & Gudang)

```
[ Pesanan Masuk (Status: PAID) ]
         │
         ▼
[ Admin/Petugas Gudang Cetak Shipping Label & Invoice ]
  ├── Scan Barcode / Cek SKU Barang di Rak
  ├── Quality Control Fisik Tas (Cek jahitan, resleting, strap tali)
  ├── Packing Aman (Dus pelindung + Bubble wrap + Dustbag tas gratis)
  └── Update Status: 'SIAP DIKIRIM' (Ready for Pickup)
         │
         ▼
[ Penyerahan ke Kurir / Drop Point Ekspedisi ]
  ├── Input No Resi Manual atau Auto-AWB (Request Pickup API)
  └── Status Pesanan berubah menjadi 'DIKIRIM (SHIPPED)'
         │
         ▼
[ Pelacakan Paket & Penerimaan ]
  ├── Pembeli dapat melacak posisi kurir di halaman `/track-order/{order_number}`
  ├── Paket Tiba di Pembeli ➔ Status: 'SELESAI (COMPLETED)'
  └── Form Ulasan & Foto Produk aktif untuk pembeli
```

---

## 3. Database Schema Workflow & Entity Relations

```
[ Categories ] 1 ──< [ Products ] 1 ──< [ Product Variants (Color/Size/SKU) ]
                            │                           │
                            │                           │
                            ▼                           ▼
                   [ Product Images ]          [ Order Items ]
                                                        │
                                                        ▼
[ Users / Customers ] 1 ─────────────────────────< [ Orders ]
         │                                              │
         ▼                                              ▼
[ Saved Addresses ]                             [ Payments / Invoices ]
                                                        │
                                                        ▼
                                                [ Shipments (Resi/Kurir) ]
```

### Penjelasan Relasi Entitas Kunci:
1. **`products`**: Menyimpan informasi umum tas (Nama Tas, Slug, Deskripsi, Dimensi P x L x T cm, Berat Bersih gram, Bahan/Material Kulit/Kanvas, Model/Tipe Tas).
2. **`product_variants`**: Setiap tas memiliki varian warna/ukuran dengan SKU mandiri, harga promo, dan **stok terisolasi** (mencegah *overselling* saat warna Hitam habis tapi warna Cokelat masih ada).
3. **`orders`**: Menyimpan order number acak unik (misal: `BAG-202610-8891`), total tagihan, biaya ongkir, diskon voucher, dan status state-machine (`pending`, `paid`, `processing`, `shipped`, `completed`, `cancelled`).
4. **`order_items`**: Menyimpan snapshot harga beli dan nama tas saat transaksi terjadi (sehingga riwayat pesanan tidak berubah meskipun harga produk di master data kelak naik).
5. **`shipments`**: Menyimpan nama kurir, jenis layanan (REG/YES/Cargo), nomor resi, dan riwayat checkpoint log pelacakan.

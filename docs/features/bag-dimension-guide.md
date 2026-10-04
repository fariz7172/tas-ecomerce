# Fitur: Panduan Dimensi & Kompartemen Tas (Visual Bag Specs)

**Status**: Planned  
**Tanggal**: 03 Oktober 2026  
**Stack**: Laravel 11 + Livewire 3 + Alpine.js + Tailwind CSS  
**Lead Developer / Subagent**: dev-frontend (Supervised by Geta)  

---

## 1. Tujuan
Menampilkan visualisasi dimensi fisik tas (Panjang x Lebar x Tinggi cm), rincian kantong kompartemen dalam/luar, panjang tali selempang (*strap drop*), dan berat tas kosong agar pembeli memiliki gambaran presisi tentang ukuran dan kapasitas tas tanpa keraguan.

---

## 2. User Story & Acceptance Criteria
- *Sebagai pembeli*, saya ingin mengetahui ukuran fisik tas dan seberapa banyak kompartemen yang ada agar saya tahu apakah tas tersebut cocok untuk kebutuhan harian saya.
- *Acceptance Criteria*:
  - Halaman produk menampilkan rincian dimensi: Panjang (cm), Lebar (cm), Tinggi (cm).
  - Menampilkan panjang tali selempang (*strap length*) dan apakah tali bisa dilepas (*removable*) atau disesuaikan (*adjustable*).
  - Menampilkan rincian kompartemen: jumlah kantong resleting dalam, slot handphone/kartu, dan jenis kancing/penutup (zipper atau magnet).

---

## 3. Alur Kerja (Workflow)
1. Admin memasukkan data dimensi (P x L x T), material luar, material furing dalam, dan rincian tali selempang.
2. Pengunjung membuka halaman detail tas.
3. Tab spesifikasi menampilkan kartu visual dimensi tas dan rincian kompartemen secara interaktif.

---

## 4. File yang Diubah / Dibuat
- `resources/views/livewire/product/detail.blade.php`
- `resources/views/components/bag-specs-card.blade.php`

---

## 5. Changelog Fitur
- **2026-10-03**: Inisialisasi dokumen fitur panduan dimensi tas menggantikan fitur generik sebelumnya.

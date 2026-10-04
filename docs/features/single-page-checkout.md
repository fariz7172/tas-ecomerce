# Fitur: Single-Page Instant Checkout & WhatsApp Notification

**Status**: Planned  
**Tanggal**: 03 Oktober 2026  
**Stack**: Laravel 11 + Livewire 3 + Midtrans / Xendit API + Fonnte / Wablas API  
**Lead Developer / Subagent**: dev-backend & dev-frontend (Supervised by Geta)  

---

## 1. Tujuan
Menyediakan pengalaman checkout satu halaman (*1-Page Checkout*) super cepat tanpa mewajibkan registrasi akun, menghitung ongkos kirim otomatis berdasarkan kecamatan, memunculkan popup pembayaran QRIS/VA secara instan, dan mengirimkan notifikasi konfirmasi ke WhatsApp pembeli secara otomatis.

---

## 2. User Story & Acceptance Criteria
- *Sebagai pembeli*, saya ingin memasukkan alamat, memilih kurir, dan langsung membayar via QRIS dalam satu halaman yang sama tanpa terputus alurnya.
- *Acceptance Criteria*:
  - Guest checkout didukung tanpa login/password.
  - Perhitungan ongkir kurir memuat pilihan layanan (REG/YES/Hemat) secara dinamis.
  - Popup Midtrans Snap / QRIS muncul seketika setelah tombol *"Bayar Sekarang"* ditekan.
  - Saat pembayaran lunas, webhook memperbarui status pesanan menjadi `PAID` dan memicu pengiriman pesan WhatsApp secara realtime.

---

## 3. Alur Kerja (Workflow)
1. Pembeli mengisi form: Nama, No WhatsApp, Alamat Jalan, Provinsi, Kota, Kecamatan.
2. Livewire merender pilihan kurir dan tarif ongkir secara reaktif.
3. Pembeli memilih metode pembayaran dan menekan tombol *Bayar Sekarang*.
4. Token pembayaran digenerate oleh backend, popup pembayaran terbuka.
5. Pembeli melakukan scan QRIS atau transfer VA.
6. Webhook menerima callback sukses ➔ stok dipotong ➔ notifikasi WhatsApp meluncur ke nomor pembeli.

---

## 4. Skema Database / Model
- Tabel `orders`
- Tabel `order_items`
- Tabel `payments`
- Tabel `shipments`

---

## 5. File yang Diubah / Dibuat
- `app/Services/ShippingService.php`
- `app/Services/PaymentGatewayService.php`
- `app/Services/WhatsAppNotificationService.php`
- `app/Http/Controllers/PaymentWebhookController.php`
- `resources/views/livewire/checkout/index.blade.php`

---

## 6. Cara Test & Verifikasi
- Pengujian checkout guest dengan data dummy nomor WhatsApp dan alamat.
- Simulasi webhook callback Midtrans sandbox (memverifikasi perubahan status menjadi `PAID` dan pengurangan stok).
- Verifikasi pengiriman payload pesan ke gateway WhatsApp.

---

## 7. Changelog Fitur
- **2026-10-03**: Inisialisasi spesifikasi fitur checkout dan notifikasi WhatsApp.

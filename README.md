# PawonSync — Prototype Statis

Implementasi UI **PawonSync** (Figma *Katering v2 hijau*) sebagai aplikasi Laravel + Tailwind CSS 4.
Tahap ini **statis**: seluruh layar bisa dibuka, belum ada database, autentikasi, atau logika bisnis.

## 39 Layar

| Peran | Jumlah | Layar |
| --- | --- | --- |
| Auth | 4 | Splash, Login Buyer, Login Seller, Login Kurir |
| Buyer | 15 | Beranda, Katalog, Filter, Detail Menu, Keranjang, Checkout, Pembayaran, Konfirmasi, Pesanan, Detail Pesanan, Live Tracking, Konfirmasi Tiba, Ulasan, Notifikasi, Profil |
| Seller | 14 | Dashboard, Pesanan Masuk, Detail Pesanan, Milestone, Packaging, Penugasan Kurir, Katalog, Form Menu, Kalkulasi Bahan, Kalender, Keuangan, Promosi, Pengaturan, Retur |
| Kurir | 6 | Beranda, Daftar Tugas, Detail Tugas, Departure Gate, Live Location, Bukti Serah Terima |

Daftar semua layar beserta tautannya ada di halaman **`/screens`**.

## Menjalankan

```bash
cd pawonsync-static

# sekali saja
composer install
npm install
npm run build

php artisan serve
```

Buka <http://127.0.0.1:8000> untuk splash screen, atau <http://127.0.0.1:8000/screens> untuk daftar seluruh layar.

Saat mengembangkan tampilan, jalankan `npm run dev` di terminal terpisah agar CSS langsung ter-rebuild.

## Struktur

```
resources/views/
├── layouts/phone.blade.php        # frame HP + @vite
├── components/                    # icon, app-bar, bottom-nav, badge, dll.
├── auth/                          # splash + login
├── buyer/                         # 15 layar buyer
├── seller/                        # 14 layar seller
├── kurir/                         # 6 layar kurir
└── screens.blade.php              # sitemap prototype
config/demo.php                    # data dummy terpusat
routes/web.php                     # seluruh route bernama
```

## Catatan integrasi database

Semua data contoh diambil dari `config/demo.php` agar mudah diganti dengan query Eloquent.
Route memakai closure; nanti bisa dipindah ke controller tanpa mengubah nama route.
Tombol aksi saat ini hanya bernavigasi antar layar (belum menyimpan data).

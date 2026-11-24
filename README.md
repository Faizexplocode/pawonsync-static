# PawonSync

Aplikasi katering berbasis Laravel untuk tiga peran — **buyer**, **seller (dapur)**, dan **kurir** — dengan database PostgreSQL Supabase dan penyimpanan foto di Supabase Storage.

Seluruh 39 layar prototipe Figma sudah terhubung ke database dan mendukung alur baca + tulis penuh: keranjang, checkout, pembayaran, konfirmasi seller, milestone dapur, packaging, penugasan kurir, live tracking, bukti serah terima, konfirmasi tiba, dan ulasan.

## Peran & layar

| Peran | Jumlah | Layar |
| --- | --- | --- |
| Auth | 4 | Splash, Login Buyer, Login Seller, Login Kurir |
| Buyer | 15 | Beranda, Katalog, Filter, Detail Menu, Keranjang, Checkout, Pembayaran, Konfirmasi, Pesanan, Detail Pesanan, Live Tracking, Konfirmasi Tiba, Ulasan, Notifikasi, Profil |
| Seller | 14 | Dashboard, Pesanan Masuk, Detail Pesanan, Milestone, Packaging, Penugasan Kurir, Katalog, Form Menu, Kalkulasi Bahan, Kalender, Keuangan, Promosi, Pengaturan, Retur |
| Kurir | 6 | Beranda, Daftar Tugas, Detail Tugas, Departure Gate, Live Location, Bukti Serah Terima |

Daftar semua layar beserta tautannya ada di halaman **`/screens`**.

## Alur status pesanan

```
menunggu_pembayaran → dibayar → dikonfirmasi → diproses → siap_kirim → dikirim → selesai
                                                                              ↘ dibatalkan
```

- Buyer membuat pesanan dari keranjang, lalu memilih metode pembayaran (QRIS/transfer/COD).
- Seller mengonfirmasi, memperbarui milestone dapur, dan menandai packaging selesai.
- Seller menugaskan kurir; kurir berangkat, mengirim lokasi, lalu mengunggah bukti serah terima.
- Buyer mengonfirmasi penerimaan dan dapat memberi ulasan.

## Menjalankan

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan storage:link
```

Isi koneksi database di `.env` (lihat bagian Supabase), lalu:

```bash
php artisan migrate --seed
npm run build
php artisan serve
```

Buka <http://127.0.0.1:8000> untuk splash screen, atau <http://127.0.0.1:8000/screens> untuk daftar seluruh layar.

Saat mengembangkan tampilan, jalankan `npm run dev` di terminal terpisah agar CSS langsung ter-rebuild.

## Konfigurasi Supabase

### Database (PostgreSQL)

Ambil **Session Pooler** connection string dari *Supabase Dashboard → Project Settings → Database*, lalu isi:

```env
DB_CONNECTION=pgsql
DB_HOST=aws-0-<region>.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.<project-ref>
DB_PASSWORD=<password-database>
DB_SSLMODE=require
```

Jalankan `php artisan config:clear` sebelum migrasi. Gunakan `php artisan migrate --seed` (bukan `migrate:fresh`) bila project sudah berisi data.

### Storage (foto)

Buat bucket **public** bernama `pawonsync` di *Supabase Dashboard → Storage*, lalu isi:

```env
SUPABASE_URL=https://<project-ref>.supabase.co
SUPABASE_SERVICE_ROLE_KEY=<service_role-secret>
SUPABASE_BUCKET=pawonsync
```

Konfigurasi disk ada di `config/filesystems.php` (`disks.supabase`). Foto menu, milestone, dan bukti serah terima diunggah lewat REST API di `app/Support/SupabaseStorage.php`. Jika `SUPABASE_URL`/`SUPABASE_SERVICE_ROLE_KEY` kosong, upload otomatis memakai disk lokal `public`.

## Akun demo

Semua akun memakai password `password`.

| Peran | Email |
| --- | --- |
| Buyer | `buyer@pawonsync.test` |
| Seller | `seller@pawonsync.test` |
| Kurir | `kurir@pawonsync.test` |

Seeder juga membuat beberapa buyer, seller, dan kurir tambahan beserta contoh pesanan pada berbagai status.

## Struktur

```
app/
├── Enums/                     # status pesanan, pembayaran, pengiriman, milestone
├── Http/Controllers/          # Auth, Buyer, Seller, Kurir
├── Http/Middleware/EnsureRole.php
├── Models/                    # 17 model sesuai ERD
└── Support/
    ├── PawonSync.php          # view-model pesanan/menu/tugas/notifikasi
    └── SupabaseStorage.php    # upload foto + fallback lokal
config/katalog.php             # kategori, minimal porsi, metode pembayaran
database/migrations/           # skema ERD
database/seeders/              # user, katalog, pesanan demo
resources/views/
├── layouts/phone.blade.php    # frame HP + @vite
├── components/                # icon, app-bar, bottom-nav, photo, dll.
├── auth/ buyer/ seller/ kurir/
└── screens.blade.php          # sitemap aplikasi
routes/web.php                 # route bernama per peran
tests/Feature/                 # uji semua layar + alur pesanan end-to-end
```

## Pengujian

```bash
php artisan test --compact
vendor/bin/pint --dirty
```

Uji `tests/Feature/AlurPesananTest.php` menjalankan alur lengkap dari keranjang sampai ulasan, termasuk pengecekan hak akses antar-pengguna.

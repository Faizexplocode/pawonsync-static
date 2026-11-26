<?php

namespace Database\Seeders;

use App\Models\DummyPlate;
use App\Models\KalenderKapasitas;
use App\Models\MenuKatering;
use App\Models\OpsiTambahan;
use App\Models\PaketMenu;
use App\Models\User;
use Illuminate\Database\Seeder;

class KatalogSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = User::where('role', 'seller')->pluck('id', 'nama');

        $menus = [
            [
                'seller' => 'Pawon Lestari',
                'nama' => 'Nasi Box Ayam Geprek',
                'foto' => '/images/menu/ayam-geprek.jpg',
                'deskripsi' => 'Nasi putih pulen, ayam geprek sambal bawang, lalapan, dan kerupuk. Cocok untuk wisuda, seminar, dan arisan.',
                'harga' => 38000,
                'plate' => ['gramasi' => 450, 'deskripsi' => 'Nasi 150 g · Ayam geprek 180 g · Sayur/lalapan 80 g · Sambal 30 g'],
                'paket' => [
                    ['Nasi Box Standar', 38000],
                    ['Nasi Box + Es Teh', 42000],
                    ['Nasi Box Premium', 48000],
                ],
                'opsi' => [
                    ['Es teh manis', 4000],
                    ['Kemasan eco-friendly', 1500],
                    ['Sambal terpisah', 0],
                ],
            ],
            [
                'seller' => 'Dapur Bu Sari',
                'nama' => 'Nasi Box Rendang Spesial',
                'foto' => '/images/menu/rendang.jpg',
                'deskripsi' => 'Rendang sapi empuk dimasak slow cook, nasi pandan, sambal ijo, dan kerupuk.',
                'harga' => 52000,
                'plate' => ['gramasi' => 480, 'deskripsi' => 'Nasi pandan 150 g · Rendang sapi 170 g · Sambal ijo 40 g · Kerupuk 20 g'],
                'paket' => [
                    ['Paket Reguler', 52000],
                    ['Paket Lengkap', 59000],
                ],
                'opsi' => [
                    ['Es teh manis', 4000],
                    ['Buah potong', 6000],
                ],
            ],
            [
                'seller' => 'Warung Nusa',
                'nama' => 'Snack Box Rapat Lengkap',
                'foto' => '/images/menu/snack-box.jpg',
                'deskripsi' => 'Isi 4 macam kue basah, air mineral, dan tisu. Praktis untuk rapat dan seminar.',
                'harga' => 24000,
                'plate' => ['gramasi' => 260, 'deskripsi' => 'Kue basah 4 pcs · Air mineral 330 ml · Tisu'],
                'paket' => [
                    ['Snack Box Standar', 24000],
                    ['Snack Box + Air Mineral', 27000],
                ],
                'opsi' => [
                    ['Air mineral', 3000],
                    ['Tisu tambahan', 500],
                ],
            ],
            [
                'seller' => 'Pawon Lestari',
                'nama' => 'Prasmanan Nusantara',
                'foto' => '/images/menu/prasmanan.jpg',
                'deskripsi' => 'Pilihan 6 lauk nusantara, nasi, sayur, dan dessert. Sudah termasuk peralatan saji.',
                'harga' => 52000,
                'plate' => ['gramasi' => 520, 'deskripsi' => 'Nasi 150 g · 6 lauk 300 g · Sayur 50 g · Dessert 20 g'],
                'paket' => [
                    ['Prasmanan 6 Lauk', 52000],
                    ['Prasmanan Premium', 65000],
                ],
                'opsi' => [
                    ['Peralatan saji', 2500],
                    ['Dessert tambahan', 5000],
                ],
            ],
            [
                'seller' => 'Dapur Bu Sari',
                'nama' => 'Tumpeng Mini Syukuran',
                'foto' => '/images/menu/tumpeng.jpg',
                'deskripsi' => 'Tumpeng mini lengkap 7 lauk, cocok untuk syukuran kantor dan keluarga.',
                'harga' => 410000,
                'plate' => ['gramasi' => 1800, 'deskripsi' => 'Nasi kuning 800 g · 7 lauk 900 g · Hiasan sayur 100 g'],
                'paket' => [
                    ['Tumpeng Mini 7 Lauk', 410000],
                    ['Tumpeng Mini Premium', 480000],
                ],
                'opsi' => [
                    ['Kemasan tumpeng', 10000],
                ],
            ],
            [
                'seller' => 'Pawon Lestari',
                'nama' => 'Paket Arisan 50 Porsi',
                'foto' => '/images/menu/arisan.jpg',
                'deskripsi' => 'Paket hemat 50 porsi untuk arisan: nasi, 3 lauk, sayur, dan dessert.',
                'harga' => 29000,
                'plate' => ['gramasi' => 430, 'deskripsi' => 'Nasi 150 g · 3 lauk 220 g · Sayur 40 g · Dessert 20 g'],
                'paket' => [
                    ['Paket Arisan 50 Porsi', 29000],
                ],
                'opsi' => [
                    ['Dessert tambahan', 3000],
                ],
            ],
        ];

        foreach ($menus as $data) {
            $menu = MenuKatering::updateOrCreate(
                ['nama' => $data['nama']],
                [
                    'seller_id' => $sellers[$data['seller']],
                    'deskripsi' => $data['deskripsi'],
                    'harga' => $data['harga'],
                    'foto' => $data['foto'],
                ],
            );

            DummyPlate::updateOrCreate(
                ['menu_id' => $menu->id],
                [...$data['plate'], 'foto' => $data['foto']],
            );

            foreach ($data['paket'] as [$nama, $harga]) {
                PaketMenu::updateOrCreate(
                    ['menu_id' => $menu->id, 'nama' => $nama],
                    ['harga' => $harga],
                );
            }

            foreach ($data['opsi'] as [$nama, $harga]) {
                OpsiTambahan::updateOrCreate(
                    ['menu_id' => $menu->id, 'nama' => $nama],
                    ['harga' => $harga],
                );
            }
        }

        $start = now()->subDays(14)->startOfDay();
        $end = now()->addDays(45)->startOfDay();

        foreach ($sellers as $sellerId) {
            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                KalenderKapasitas::updateOrCreate(
                    ['seller_id' => $sellerId, 'tanggal' => $date->toDateString()],
                    ['kapasitas_maks' => 200],
                );
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Enums\StatusMilestone;
use App\Enums\StatusPembayaran;
use App\Enums\StatusPengiriman;
use App\Enums\StatusPesanan;
use App\Models\KalenderKapasitas;
use App\Models\MenuKatering;
use App\Models\Notifikasi;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PesananSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::pluck('id', 'nama');
        $menus = MenuKatering::with(['paketMenu', 'opsiTambahan'])->get()->keyBy('nama');

        $orders = [
            [
                'buyer' => 'Bu Diana',
                'seller' => 'Pawon Lestari',
                'tanggal' => now()->addDays(3),
                'waktu' => '11:00:00',
                'venue' => 'Graha ITS Sukolilo, Surabaya',
                'porsi' => 150,
                'status' => StatusPesanan::Diproses,
                'items' => [
                    ['menu' => 'Nasi Box Ayam Geprek', 'paket' => 'Nasi Box Standar', 'opsi' => ['Es teh manis'], 'qty' => 150],
                ],
                'pembayaran' => ['metode' => 'transfer', 'status' => StatusPembayaran::Berhasil, 'waktu' => now()->subDays(2)],
                'milestones' => [StatusMilestone::Dikonfirmasi, StatusMilestone::Belanja, StatusMilestone::Memasak],
                'packaging' => ['selesai' => false, 'jumlah_box' => 98],
                'pengiriman' => ['kurir' => 'Budi Santoso', 'status' => StatusPengiriman::Berangkat, 'eta' => 25, 'berangkat' => now()->subMinutes(15)],
                'lokasi' => [
                    [-7.2755000, 112.7984000, now()->subMinutes(12)],
                    [-7.2791000, 112.7968000, now()->subMinutes(7)],
                    [-7.2819000, 112.7956000, now()->subMinutes(2)],
                ],
            ],
            [
                'buyer' => 'Bu Ratna',
                'seller' => 'Pawon Lestari',
                'tanggal' => now()->addDays(5),
                'waktu' => '12:00:00',
                'venue' => 'Jl. Kertajaya 12, Surabaya',
                'porsi' => 50,
                'status' => StatusPesanan::MenungguPembayaran,
                'items' => [
                    ['menu' => 'Paket Arisan 50 Porsi', 'paket' => 'Paket Arisan 50 Porsi', 'opsi' => [], 'qty' => 50],
                ],
                'pembayaran' => ['metode' => 'qris', 'status' => StatusPembayaran::Menunggu, 'waktu' => null],
            ],
            [
                'buyer' => 'Pak Reza',
                'seller' => 'Warung Nusa',
                'tanggal' => now()->addDays(2),
                'waktu' => '10:00:00',
                'venue' => 'Gedung FEB Unair, Surabaya',
                'porsi' => 80,
                'status' => StatusPesanan::SiapKirim,
                'items' => [
                    ['menu' => 'Snack Box Rapat Lengkap', 'paket' => 'Snack Box Standar', 'opsi' => ['Air mineral'], 'qty' => 80],
                ],
                'pembayaran' => ['metode' => 'transfer', 'status' => StatusPembayaran::Berhasil, 'waktu' => now()->subDay()],
                'milestones' => [StatusMilestone::Dikonfirmasi, StatusMilestone::Belanja, StatusMilestone::Memasak, StatusMilestone::Packing, StatusMilestone::SiapKirim],
                'packaging' => ['selesai' => true, 'jumlah_box' => 80],
                'pengiriman' => ['kurir' => 'Agus Prasetyo', 'status' => StatusPengiriman::Ditugaskan, 'eta' => 30, 'berangkat' => null],
            ],
            [
                'buyer' => 'Bu Diana',
                'seller' => 'Pawon Lestari',
                'tanggal' => now()->subDays(10),
                'waktu' => '09:00:00',
                'venue' => 'Aula RW 05, Surabaya',
                'porsi' => 40,
                'status' => StatusPesanan::Selesai,
                'items' => [
                    ['menu' => 'Nasi Box Ayam Geprek', 'paket' => 'Nasi Box Standar', 'opsi' => [], 'qty' => 40],
                ],
                'pembayaran' => ['metode' => 'transfer', 'status' => StatusPembayaran::Berhasil, 'waktu' => now()->subDays(11)],
                'milestones' => [StatusMilestone::Dikonfirmasi, StatusMilestone::Belanja, StatusMilestone::Memasak, StatusMilestone::Packing, StatusMilestone::SiapKirim],
                'packaging' => ['selesai' => true, 'jumlah_box' => 40],
                'pengiriman' => ['kurir' => 'Agus Prasetyo', 'status' => StatusPengiriman::Tiba, 'eta' => 20, 'berangkat' => now()->subDays(10)->subHour()],
                'bukti' => true,
                'ulasan' => ['rating' => 5, 'komentar' => 'Makanan datang tepat waktu dan porsinya pas. Kurirnya juga ramah.'],
            ],
        ];

        foreach ($orders as $data) {
            $pesanan = Pesanan::updateOrCreate(
                [
                    'buyer_id' => $users[$data['buyer']],
                    'seller_id' => $users[$data['seller']],
                    'alamat_venue' => $data['venue'],
                ],
                [
                    'tanggal_acara' => $data['tanggal']->toDateString(),
                    'waktu_acara' => $data['waktu'],
                    'jumlah_porsi' => $data['porsi'],
                    'total_harga' => 0,
                    'status' => $data['status'],
                ],
            );

            $pesanan->itemPesanan()->delete();
            $total = 0;

            foreach ($data['items'] as $item) {
                $menu = $menus[$item['menu']];
                $paket = $menu->paketMenu->firstWhere('nama', $item['paket']);
                $opsi = $menu->opsiTambahan->whereIn('nama', $item['opsi']);

                $itemPesanan = $pesanan->itemPesanan()->create([
                    'menu_id' => $menu->id,
                    'paket_id' => $paket?->id,
                    'qty' => $item['qty'],
                ]);

                $itemPesanan->opsiTambahan()->sync($opsi->pluck('id'));

                $hargaSatuan = (float) ($paket?->harga ?? $menu->harga) + (float) $opsi->sum('harga');
                $total += $hargaSatuan * $item['qty'];
            }

            $pesanan->update(['total_harga' => $total]);

            $pesanan->pembayaran()->delete();
            $pesanan->pembayaran()->create([
                'jumlah' => $total,
                'metode' => $data['pembayaran']['metode'],
                'status' => $data['pembayaran']['status'],
                'waktu_bayar' => $data['pembayaran']['waktu'],
            ]);

            $pesanan->milestoneDapur()->delete();
            foreach ($data['milestones'] ?? [] as $index => $status) {
                $pesanan->milestoneDapur()->create([
                    'status' => $status,
                    'waktu' => now()->subDays(count($data['milestones']) - $index),
                ]);
            }

            if (isset($data['packaging'])) {
                $pesanan->packagingChecklist()->updateOrCreate([], $data['packaging']);
            }

            if (isset($data['pengiriman'])) {
                $pengiriman = $pesanan->pengiriman()->updateOrCreate([], [
                    'kurir_id' => $users[$data['pengiriman']['kurir']],
                    'status' => $data['pengiriman']['status'],
                    'eta_menit' => $data['pengiriman']['eta'],
                    'waktu_berangkat' => $data['pengiriman']['berangkat'],
                ]);

                $pengiriman->lokasiKurir()->delete();
                foreach ($data['lokasi'] ?? [] as [$lat, $long, $waktu]) {
                    $pengiriman->lokasiKurir()->create([
                        'latitude' => $lat,
                        'longitude' => $long,
                        'waktu' => $waktu,
                    ]);
                }

                if (! empty($data['bukti'])) {
                    $pengiriman->buktiSerahTerima()->updateOrCreate([], [
                        'foto_url' => 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=800',
                        'waktu' => now()->subDays(10)->addMinutes(20),
                    ]);
                }
            }

            if (isset($data['ulasan'])) {
                $pesanan->ulasan()->updateOrCreate([], $data['ulasan'] + ['waktu' => now()->subDays(9)]);
            }

            $pesanan->notifikasi()->delete();

            $notifikasi = match ($data['status']) {
                StatusPesanan::MenungguPembayaran => ['Pembayaran pesanan '.$data['venue'].' belum selesai.'],
                StatusPesanan::Diproses => ['Pesanan '.$data['venue'].' sedang dimasak.', 'Pembayaran pesanan '.$data['venue'].' sudah diterima.', 'Kurir menuju '.$data['venue'].'.'],
                StatusPesanan::SiapKirim => ['Pesanan '.$data['venue'].' siap dikirim.'],
                default => ['Pesanan '.$data['venue'].' sudah selesai. Terima kasih!'],
            };

            foreach ($notifikasi as $index => $pesan) {
                Notifikasi::create([
                    'user_id' => $users[$data['buyer']],
                    'pesanan_id' => $pesanan->id,
                    'pesan' => $pesan,
                    'dibaca' => $index > 0,
                    'waktu' => now()->subMinutes(30 * ($index + 1)),
                ]);
            }

            Notifikasi::create([
                'user_id' => $users[$data['seller']],
                'pesanan_id' => $pesanan->id,
                'pesan' => 'Pesanan baru untuk '.$data['venue'].' ('.$data['porsi'].' porsi).',
                'dibaca' => false,
                'waktu' => $data['tanggal']->copy()->subDay(),
            ]);
        }

        $this->syncKapasitasTerpakai();
    }

    private function syncKapasitasTerpakai(): void
    {
        $rows = Pesanan::query()
            ->selectRaw('seller_id, tanggal_acara, sum(jumlah_porsi) as total')
            ->groupBy('seller_id', 'tanggal_acara')
            ->get();

        foreach ($rows as $row) {
            KalenderKapasitas::updateOrCreate(
                ['seller_id' => $row->seller_id, 'tanggal' => $row->tanggal_acara],
                ['kapasitas_maks' => 200],
            )->update(['terpakai' => (int) $row->total]);
        }
    }
}

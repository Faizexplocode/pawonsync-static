<?php

namespace App\Support;

use App\Models\KalenderKapasitas;
use App\Models\MenuKatering;
use App\Models\Notifikasi;
use App\Models\Pengiriman;
use App\Models\Pesanan;
use Illuminate\Support\Facades\DB;

class PawonSync
{
    /**
     * @return array<string, mixed>
     */
    public static function menuRingkas(MenuKatering $menu): array
    {
        $rating = self::ratingMenu($menu->id);

        return [
            'id' => $menu->id,
            'name' => $menu->nama,
            'seller' => $menu->seller?->nama ?? 'Dapur PawonSync',
            'price' => (float) $menu->harga,
            'rating' => $rating['rating'],
            'reviews' => $rating['reviews'],
            'description' => $menu->deskripsi,
            'photo' => $menu->foto,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function menuDetail(MenuKatering $menu): array
    {
        $menu->loadMissing(['seller', 'paketMenu', 'opsiTambahan', 'dummyPlate']);

        $rating = self::ratingMenu($menu->id);
        $plate = $menu->dummyPlate;

        $plateRows = [];

        if ($plate?->deskripsi) {
            $plateRows[] = ['name' => 'Komposisi', 'value' => $plate->deskripsi];
        }

        if ($plate?->gramasi) {
            $plateRows[] = ['name' => 'Total gramasi', 'value' => number_format((float) $plate->gramasi, 0, ',', '.').' g'];
        }

        return [
            ...self::menuRingkas($menu),
            'packages' => $menu->paketMenu
                ->map(fn ($paket) => [
                    'id' => $paket->id,
                    'name' => $paket->nama,
                    'note' => 'Rp '.number_format((float) $paket->harga, 0, ',', '.').' / porsi',
                    'price' => (float) $paket->harga,
                ])
                ->values()
                ->all(),
            'extras' => $menu->opsiTambahan
                ->map(fn ($opsi) => [
                    'id' => $opsi->id,
                    'name' => $opsi->nama,
                    'price' => (float) $opsi->harga,
                    'unit' => $opsi->harga > 0 ? 'Per porsi' : 'Gratis',
                ])
                ->values()
                ->all(),
            'plate' => $plateRows,
            'plate_photo' => $plate?->foto,
            'min' => (int) config('katalog.min_porsi', 30),
            'kapasitas' => self::kapasitasTersisa($menu->seller_id),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function pesananRingkas(Pesanan $order): array
    {
        return [
            'id' => $order->id,
            'kode' => $order->kode(),
            'title' => $order->judul(),
            'status' => $order->status->label(),
            'status_value' => $order->status->value,
            'tone' => $order->status->tone(),
            'meta' => self::metaPesanan($order),
            'progress' => $order->progress(),
            'steps' => 4,
            'eta' => self::etaPesanan($order),
            'total' => (float) $order->total_harga,
            'courier' => $order->pengiriman?->kurir?->nama ?? 'Belum ditugaskan',
            'customer' => $order->buyer?->nama ?? '-',
            'tanggal' => $order->tanggal_acara?->locale('id')->translatedFormat('j M Y') ?? '-',
            'jam' => self::jam($order),
            'porsi' => $order->jumlah_porsi,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function pesananDetail(Pesanan $order): array
    {
        $order->loadMissing([
            'buyer',
            'seller',
            'itemPesanan.menu',
            'itemPesanan.paket',
            'itemPesanan.opsiTambahan',
            'pembayaran',
            'milestoneDapur',
            'packagingChecklist',
            'pengiriman.kurir',
            'ulasan',
        ]);

        $pengiriman = $order->pengiriman;

        return [
            ...self::pesananRingkas($order),
            'alamat' => $order->alamat_venue,
            'seller' => $order->seller?->nama ?? '-',
            'buyer' => $order->buyer?->nama ?? '-',
            'items' => $order->itemPesanan
                ->map(fn ($item) => [
                    'name' => trim(($item->menu?->nama ?? 'Menu').($item->paket ? ' · '.$item->paket->nama : '')),
                    'photo' => $item->menu?->foto,
                    'qty' => $item->qty,
                    'price' => $item->hargaSatuan(),
                    'subtotal' => $item->subtotal(),
                    'opsi' => $item->opsiTambahan->pluck('nama')->all(),
                ])
                ->values()
                ->all(),
            'steps' => self::tahapan($order),
            'pembayaran' => $order->pembayaran->sortByDesc('id')->first(),
            'pengiriman' => $pengiriman,
            'ulasan' => $order->ulasan,
            'packaging' => $order->packagingChecklist,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function tugas(Pengiriman $pengiriman): array
    {
        $pengiriman->loadMissing(['pesanan.buyer', 'pesanan.seller', 'pesanan.packagingChecklist', 'kurir']);

        $pesanan = $pengiriman->pesanan;

        return [
            'id' => $pengiriman->id,
            'kode' => 'TGS-'.str_pad((string) $pengiriman->id, 4, '0', STR_PAD_LEFT),
            'title' => $pesanan->judul(),
            'status' => $pengiriman->status->label(),
            'tone' => $pengiriman->status->tone(),
            'address' => $pesanan->alamat_venue,
            'load' => $pesanan->jumlah_porsi.' box',
            'eta' => ($pesanan->tanggal_acara?->locale('id')->translatedFormat('j M') ?? '-').' · '.self::jam($pesanan),
            'distance' => '-',
            'buyer' => $pesanan->buyer?->nama ?? '-',
            'seller' => $pesanan->seller?->nama ?? '-',
            'eta_menit' => $pengiriman->eta_menit,
            'waktu_berangkat' => $pengiriman->waktu_berangkat,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function notifikasi(Notifikasi $notifikasi): array
    {
        $teks = (string) $notifikasi->pesan;
        $judul = str($teks)->before('.')->limit(46)->toString();
        $isi = str($teks)->after('.')->trim()->toString();
        $lower = strtolower($teks);

        [$icon, $tone] = match (true) {
            str_contains($lower, 'bayar') => ['credit-card', 'mint'],
            str_contains($lower, 'kurir') => ['truck', 'amber'],
            str_contains($lower, 'selesai') => ['star', 'green'],
            default => ['check', 'green'],
        };

        return [
            'id' => $notifikasi->id,
            'icon' => $icon,
            'tone' => $tone,
            'title' => $judul,
            'body' => $isi !== '' ? $isi : $judul,
            'time' => $notifikasi->waktu?->locale('id')->diffForHumans(short: true) ?? '-',
            'unread' => ! $notifikasi->dibaca,
            'pesanan_id' => $notifikasi->pesanan_id,
        ];
    }

    /**
     * @return array{rating: float, reviews: int}
     */
    public static function ratingMenu(int $menuId): array
    {
        $row = DB::table('ulasan')
            ->join('pesanan', 'pesanan.id', '=', 'ulasan.pesanan_id')
            ->join('item_pesanan', 'item_pesanan.pesanan_id', '=', 'pesanan.id')
            ->where('item_pesanan.menu_id', $menuId)
            ->selectRaw('count(distinct ulasan.id) as total, avg(ulasan.rating) as rata')
            ->first();

        return [
            'rating' => $row && $row->rata ? round((float) $row->rata, 1) : 0.0,
            'reviews' => (int) ($row->total ?? 0),
        ];
    }

    /**
     * @return array{rating: float, reviews: int}
     */
    public static function ratingSeller(int $sellerId): array
    {
        $row = DB::table('ulasan')
            ->join('pesanan', 'pesanan.id', '=', 'ulasan.pesanan_id')
            ->where('pesanan.seller_id', $sellerId)
            ->selectRaw('count(distinct ulasan.id) as total, avg(ulasan.rating) as rata')
            ->first();

        return [
            'rating' => $row && $row->rata ? round((float) $row->rata, 1) : 0.0,
            'reviews' => (int) ($row->total ?? 0),
        ];
    }

    public static function kapasitasTersisa(int $sellerId): int
    {
        $kapasitas = KalenderKapasitas::query()
            ->where('seller_id', $sellerId)
            ->whereDate('tanggal', '>=', now()->toDateString())
            ->whereColumn('terpakai', '<', 'kapasitas_maks')
            ->orderBy('tanggal')
            ->first();

        return $kapasitas?->sisa() ?? 0;
    }

    /**
     * @return array<int, array{0: string, 1: string, 2: bool}>
     */
    public static function tahapan(Pesanan $order): array
    {
        $order->loadMissing('milestoneDapur');

        $status = $order->status;
        $milestones = $order->milestoneDapur->pluck('status')->map(fn ($item) => $item->value)->all();
        $progress = $status->progress();

        return [
            ['Pesanan dikonfirmasi', 'Seller menerima pesanan', in_array('dikonfirmasi', $milestones, true) || $progress >= 1],
            ['Sedang dimasak', 'Dapur menyiapkan '.$order->jumlah_porsi.' porsi', in_array('memasak', $milestones, true) || $progress >= 2],
            ['Pengiriman', 'Kurir menuju '.$order->judul(), $progress >= 4 || $order->pengiriman?->status?->value === 'tiba'],
            ['Selesai', 'Pesanan diterima di venue', $status->value === 'selesai'],
        ];
    }

    /**
     * @return array<int, array{0: string, 1: string, 2: bool}>
     */
    public static function tahapanTugas(Pengiriman $pengiriman): array
    {
        $status = $pengiriman->status->value;

        return [
            ['Tugas diterima', $pengiriman->pesanan?->seller?->nama ?? 'Seller', true],
            ['Berangkat dari dapur', $pengiriman->waktu_berangkat?->locale('id')->translatedFormat('j M, H.i') ?? 'Belum berangkat', in_array($status, ['berangkat', 'tiba'], true)],
            ['Tiba di venue', $pengiriman->pesanan?->alamat_venue ?? '-', $status === 'tiba'],
            ['Bukti serah terima', $pengiriman->buktiSerahTerima ? 'Sudah diunggah' : 'Belum diunggah', $pengiriman->buktiSerahTerima !== null],
        ];
    }

    public static function metaPesanan(Pesanan $order): string
    {
        return $order->jumlah_porsi.' porsi · '.$order->alamat_venue.' · '.($order->tanggal_acara?->locale('id')->translatedFormat('j M Y') ?? '-');
    }

    public static function etaPesanan(Pesanan $order): string
    {
        $pengiriman = $order->pengiriman;

        if ($pengiriman?->eta_menit) {
            return 'ETA '.$pengiriman->eta_menit.' mnt';
        }

        return 'Antar '.self::jam($order);
    }

    public static function jam(Pesanan $order): string
    {
        return substr((string) $order->waktu_acara, 0, 5);
    }
}

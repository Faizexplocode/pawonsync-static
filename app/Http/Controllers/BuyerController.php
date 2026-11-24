<?php

namespace App\Http\Controllers;

use App\Enums\StatusPembayaran;
use App\Enums\StatusPesanan;
use App\Models\KalenderKapasitas;
use App\Models\MenuKatering;
use App\Models\Notifikasi;
use App\Models\Pesanan;
use App\Support\PawonSync;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BuyerController extends Controller
{
    public function beranda(Request $request): View
    {
        $buyer = $request->user();
        $menus = MenuKatering::with('seller')
            ->orderByDesc('id')
            ->take(4)
            ->get()
            ->map(fn (MenuKatering $menu) => PawonSync::menuRingkas($menu))
            ->all();

        $order = $buyer->pesananAktif();

        return view('buyer.beranda', [
            'buyer' => $buyer,
            'alamat' => $buyer->pesananSebagaiBuyer()->latest('id')->value('alamat_venue'),
            'categories' => config('katalog.kategori'),
            'menus' => $menus,
            'order' => $order ? PawonSync::pesananRingkas($order) : null,
            'notif' => $buyer->notifikasi()->where('dibaca', false)->count(),
            'promo' => config('katalog.promo'),
            'flashSale' => config('katalog.flash_sale'),
            'flashSaleBerakhir' => gmdate('H:i:s', (int) now()->diffInSeconds(now()->endOfDay())),
        ]);
    }

    public function katalog(Request $request): View
    {
        $cari = trim((string) $request->query('q', ''));
        $urut = (string) $request->query('urut', 'populer');

        $query = MenuKatering::with('seller');

        if ($cari !== '') {
            $query->whereRaw('lower(nama) like ?', ['%'.strtolower($cari).'%']);
        }

        match ($urut) {
            'murah' => $query->orderBy('harga'),
            'mahal' => $query->orderByDesc('harga'),
            default => $query->orderByDesc('id'),
        };

        $menus = $query->get()->map(fn (MenuKatering $menu) => PawonSync::menuRingkas($menu))->all();

        return view('buyer.katalog', [
            'menus' => $menus,
            'categories' => config('katalog.kategori'),
            'cari' => $cari,
            'urut' => $urut,
        ]);
    }

    public function filter(): View
    {
        return view('buyer.filter', [
            'categories' => config('katalog.kategori'),
        ]);
    }

    public function menu(MenuKatering $menu): View
    {
        return view('buyer.menu', [
            'menu' => PawonSync::menuDetail($menu),
        ]);
    }

    public function keranjang(Request $request): View
    {
        $cart = $this->ambilKeranjang($request);
        $items = $this->bangunItemKeranjang($cart);

        return view('buyer.keranjang', [
            'items' => $items,
            'total' => collect($items)->sum('subtotal'),
            'porsi' => collect($items)->sum('qty'),
            'kosong' => $items === [],
        ]);
    }

    public function tambahKeranjang(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'menu_id' => ['required', 'integer', 'exists:menu_katering,id'],
            'paket_id' => ['nullable', 'integer', 'exists:paket_menu,id'],
            'opsi_ids' => ['nullable', 'array'],
            'opsi_ids.*' => ['integer', 'exists:opsi_tambahan,id'],
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        $menu = MenuKatering::with(['paketMenu', 'opsiTambahan'])->findOrFail($data['menu_id']);
        $min = (int) config('katalog.min_porsi', 30);

        if ($data['qty'] < $min) {
            throw ValidationException::withMessages([
                'qty' => 'Minimal pemesanan '.$min.' porsi.',
            ]);
        }

        $paketId = $data['paket_id'] ?? null;
        if ($paketId !== null && ! $menu->paketMenu->contains('id', $paketId)) {
            throw ValidationException::withMessages(['paket_id' => 'Paket tidak tersedia untuk menu ini.']);
        }

        $opsiIds = collect($data['opsi_ids'] ?? [])->map(fn ($id) => (int) $id)->unique()->sort()->values();
        if ($opsiIds->diff($menu->opsiTambahan->pluck('id'))->isNotEmpty()) {
            throw ValidationException::withMessages(['opsi_ids' => 'Opsi tambahan tidak sesuai menu.']);
        }

        $cart = $this->ambilKeranjang($request);

        if ($cart['seller_id'] !== null && $cart['seller_id'] !== $menu->seller_id) {
            $cart = ['seller_id' => $menu->seller_id, 'items' => []];
        }

        $cart['seller_id'] = $menu->seller_id;
        $key = $this->kunciItem($menu->id, $paketId, $opsiIds->all());

        if (isset($cart['items'][$key])) {
            $cart['items'][$key]['qty'] += $data['qty'];
        } else {
            $cart['items'][$key] = [
                'menu_id' => $menu->id,
                'paket_id' => $paketId,
                'opsi_ids' => $opsiIds->all(),
                'qty' => $data['qty'],
            ];
        }

        $request->session()->put('keranjang', $cart);

        if ($request->boolean('beli')) {
            return redirect()->route('buyer.checkout');
        }

        return redirect()->route('buyer.keranjang')->with('status', $menu->nama.' ditambahkan ke keranjang.');
    }

    public function updateKeranjang(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'key' => ['required', 'string'],
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $this->ambilKeranjang($request);

        if (! isset($cart['items'][$data['key']])) {
            return back()->withErrors(['keranjang' => 'Item tidak ditemukan di keranjang.']);
        }

        $cart['items'][$data['key']]['qty'] = $data['qty'];
        $request->session()->put('keranjang', $cart);

        return back()->with('status', 'Jumlah porsi diperbarui.');
    }

    public function hapusKeranjang(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'key' => ['required', 'string'],
        ]);

        $cart = $this->ambilKeranjang($request);
        unset($cart['items'][$data['key']]);

        if ($cart['items'] === []) {
            $cart = ['seller_id' => null, 'items' => []];
        }

        $request->session()->put('keranjang', $cart);

        return back()->with('status', 'Item dihapus dari keranjang.');
    }

    public function checkout(Request $request): View|RedirectResponse
    {
        $cart = $this->ambilKeranjang($request);
        $items = $this->bangunItemKeranjang($cart);

        if ($items === []) {
            return redirect()->route('buyer.katalog')->withErrors(['keranjang' => 'Keranjang masih kosong.']);
        }

        $buyer = $request->user();
        $alamat = $buyer->pesananSebagaiBuyer()->latest('id')->value('alamat_venue') ?? '';

        return view('buyer.checkout', [
            'items' => $items,
            'total' => collect($items)->sum('subtotal'),
            'porsi' => collect($items)->sum('qty'),
            'buyer' => $buyer,
            'alamat' => $alamat,
            'tanggal' => now()->addDays(3)->toDateString(),
            'jam' => '11:00',
        ]);
    }

    public function simpanCheckout(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tanggal_acara' => ['required', 'date', 'after_or_equal:today'],
            'waktu_acara' => ['required', 'date_format:H:i'],
            'alamat_venue' => ['required', 'string', 'max:500'],
        ]);

        $cart = $this->ambilKeranjang($request);
        $items = $this->bangunItemKeranjang($cart);

        if ($items === []) {
            throw ValidationException::withMessages(['keranjang' => 'Keranjang masih kosong.']);
        }

        $buyer = $request->user();
        $sellerId = (int) $cart['seller_id'];
        $porsi = (int) collect($items)->sum('qty');
        $total = (float) collect($items)->sum('subtotal');

        $kapasitas = KalenderKapasitas::query()
            ->where('seller_id', $sellerId)
            ->whereDate('tanggal', $data['tanggal_acara'])
            ->first();

        if ($kapasitas === null) {
            $kapasitas = KalenderKapasitas::create([
                'seller_id' => $sellerId,
                'tanggal' => $data['tanggal_acara'],
                'kapasitas_maks' => 200,
            ]);
        }

        if ($kapasitas->sisa() < $porsi) {
            throw ValidationException::withMessages([
                'tanggal_acara' => 'Kapasitas dapur pada tanggal tersebut tidak mencukupi. Sisa '.$kapasitas->sisa().' porsi.',
            ]);
        }

        $pesanan = DB::transaction(function () use ($data, $items, $buyer, $sellerId, $porsi, $total, $kapasitas) {
            $pesanan = Pesanan::create([
                'buyer_id' => $buyer->id,
                'seller_id' => $sellerId,
                'tanggal_acara' => $data['tanggal_acara'],
                'waktu_acara' => $data['waktu_acara'],
                'alamat_venue' => $data['alamat_venue'],
                'jumlah_porsi' => $porsi,
                'total_harga' => $total,
                'status' => StatusPesanan::MenungguPembayaran,
            ]);

            foreach ($items as $item) {
                $itemPesanan = $pesanan->itemPesanan()->create([
                    'menu_id' => $item['menu_id'],
                    'paket_id' => $item['paket_id'],
                    'qty' => $item['qty'],
                ]);

                $itemPesanan->opsiTambahan()->sync($item['opsi_ids']);
            }

            $kapasitas->increment('terpakai', $porsi);

            Notifikasi::create([
                'user_id' => $sellerId,
                'pesanan_id' => $pesanan->id,
                'pesan' => 'Pesanan baru untuk '.$data['alamat_venue'].' menunggu pembayaran buyer.',
                'dibaca' => false,
                'waktu' => now(),
            ]);

            Notifikasi::create([
                'user_id' => $buyer->id,
                'pesanan_id' => $pesanan->id,
                'pesan' => 'Pesanan '.$pesanan->kode().' dibuat. Selesaikan pembayaran agar seller dapat memproses.',
                'dibaca' => false,
                'waktu' => now(),
            ]);

            return $pesanan;
        });

        $request->session()->forget('keranjang');

        return redirect()->route('buyer.pembayaran', $pesanan);
    }

    public function pembayaran(Request $request, Pesanan $pesanan): View|RedirectResponse
    {
        $this->pastikanMilik($request, $pesanan);

        if ($pesanan->status !== StatusPesanan::MenungguPembayaran) {
            return redirect()->route('buyer.pesanan.detail', $pesanan);
        }

        return view('buyer.pembayaran', [
            'order' => PawonSync::pesananDetail($pesanan),
            'metode' => config('katalog.metode_pembayaran'),
        ]);
    }

    public function bayar(Request $request, Pesanan $pesanan): RedirectResponse
    {
        $this->pastikanMilik($request, $pesanan);

        $data = $request->validate([
            'metode' => ['required', 'string', 'in:qris,transfer,cod'],
        ]);

        if ($pesanan->status !== StatusPesanan::MenungguPembayaran) {
            return redirect()->route('buyer.pesanan.detail', $pesanan);
        }

        DB::transaction(function () use ($pesanan, $data) {
            $pesanan->pembayaran()->updateOrCreate([], [
                'jumlah' => $pesanan->total_harga,
                'metode' => $data['metode'],
                'status' => StatusPembayaran::Berhasil,
                'waktu_bayar' => now(),
            ]);

            $pesanan->update(['status' => StatusPesanan::Dibayar]);

            Notifikasi::create([
                'user_id' => $pesanan->seller_id,
                'pesanan_id' => $pesanan->id,
                'pesan' => 'Pembayaran pesanan '.$pesanan->kode().' sudah diterima. Konfirmasi pesanan sekarang.',
                'dibaca' => false,
                'waktu' => now(),
            ]);

            Notifikasi::create([
                'user_id' => $pesanan->buyer_id,
                'pesanan_id' => $pesanan->id,
                'pesan' => 'Pembayaran pesanan '.$pesanan->kode().' berhasil. Menunggu konfirmasi seller.',
                'dibaca' => false,
                'waktu' => now(),
            ]);
        });

        return redirect()->route('buyer.konfirmasi', $pesanan);
    }

    public function konfirmasi(Request $request, Pesanan $pesanan): View
    {
        $this->pastikanMilik($request, $pesanan);

        return view('buyer.konfirmasi', [
            'order' => PawonSync::pesananDetail($pesanan),
        ]);
    }

    public function pesanan(Request $request): View
    {
        $buyer = $request->user();
        $tab = (string) $request->query('tab', 'aktif');

        $query = $buyer->pesananSebagaiBuyer()->with(['pengiriman.kurir']);

        match ($tab) {
            'selesai' => $query->where('status', StatusPesanan::Selesai->value),
            'batal' => $query->where('status', StatusPesanan::Dibatalkan->value),
            default => $query->whereNotIn('status', [StatusPesanan::Selesai->value, StatusPesanan::Dibatalkan->value]),
        };

        $orders = $query->orderByDesc('tanggal_acara')->get()
            ->map(fn (Pesanan $order) => PawonSync::pesananRingkas($order))
            ->all();

        return view('buyer.pesanan', [
            'orders' => $orders,
            'tab' => $tab,
            'jumlahAktif' => $buyer->pesananSebagaiBuyer()->whereNotIn('status', [StatusPesanan::Selesai->value, StatusPesanan::Dibatalkan->value])->count(),
        ]);
    }

    public function pesananDetail(Request $request, Pesanan $pesanan): View
    {
        $this->pastikanMilik($request, $pesanan);

        return view('buyer.pesanan-detail', [
            'order' => PawonSync::pesananDetail($pesanan),
        ]);
    }

    public function tracking(Request $request, Pesanan $pesanan): View|RedirectResponse
    {
        $this->pastikanMilik($request, $pesanan);

        $pengiriman = $pesanan->pengiriman()->with(['kurir', 'lokasiKurir'])->first();

        if (! $pengiriman) {
            return redirect()->route('buyer.pesanan.detail', $pesanan)
                ->withErrors(['tracking' => 'Pesanan ini belum memiliki data pengiriman.']);
        }

        $lokasi = $pengiriman->lokasiKurir;
        $terakhir = $lokasi->last();

        return view('buyer.tracking', [
            'order' => PawonSync::pesananDetail($pesanan),
            'pengiriman' => $pengiriman,
            'lokasi' => $lokasi,
            'terakhir' => $terakhir,
            'progress' => match ($pengiriman->status->value) {
                'berangkat' => 65,
                'tiba' => 100,
                default => 25,
            },
        ]);
    }

    public function tiba(Request $request, Pesanan $pesanan): View|RedirectResponse
    {
        $this->pastikanMilik($request, $pesanan);

        $pengiriman = $pesanan->pengiriman()->with('buktiSerahTerima')->first();

        if (! $pengiriman) {
            return redirect()->route('buyer.pesanan.detail', $pesanan);
        }

        return view('buyer.tiba', [
            'order' => PawonSync::pesananDetail($pesanan),
            'pengiriman' => $pengiriman,
        ]);
    }

    public function konfirmasiTiba(Request $request, Pesanan $pesanan): RedirectResponse
    {
        $this->pastikanMilik($request, $pesanan);

        if ($pesanan->status === StatusPesanan::Selesai) {
            return redirect()->route('buyer.pesanan.detail', $pesanan);
        }

        DB::transaction(function () use ($pesanan) {
            $pesanan->update(['status' => StatusPesanan::Selesai]);

            Notifikasi::create([
                'user_id' => $pesanan->seller_id,
                'pesanan_id' => $pesanan->id,
                'pesan' => 'Buyer mengonfirmasi pesanan '.$pesanan->kode().' sudah diterima.',
                'dibaca' => false,
                'waktu' => now(),
            ]);

            if ($pesanan->pengiriman) {
                Notifikasi::create([
                    'user_id' => $pesanan->pengiriman->kurir_id,
                    'pesanan_id' => $pesanan->id,
                    'pesan' => 'Buyer mengonfirmasi serah terima pesanan '.$pesanan->kode().'.',
                    'dibaca' => false,
                    'waktu' => now(),
                ]);
            }
        });

        return redirect()->route('buyer.pesanan.detail', $pesanan)
            ->with('status', 'Terima kasih! Pesanan sudah selesai.');
    }

    public function ulasan(Request $request, Pesanan $pesanan): View|RedirectResponse
    {
        $this->pastikanMilik($request, $pesanan);

        if ($pesanan->ulasan()->exists() || $pesanan->status !== StatusPesanan::Selesai) {
            return redirect()->route('buyer.pesanan.detail', $pesanan);
        }

        return view('buyer.ulasan', [
            'order' => PawonSync::pesananDetail($pesanan),
        ]);
    }

    public function simpanUlasan(Request $request, Pesanan $pesanan): RedirectResponse
    {
        $this->pastikanMilik($request, $pesanan);

        if ($pesanan->ulasan()->exists()) {
            return redirect()->route('buyer.pesanan.detail', $pesanan);
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'komentar' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($pesanan, $data) {
            $pesanan->ulasan()->create([
                'rating' => $data['rating'],
                'komentar' => $data['komentar'] ?? null,
                'waktu' => now(),
            ]);

            Notifikasi::create([
                'user_id' => $pesanan->seller_id,
                'pesanan_id' => $pesanan->id,
                'pesan' => 'Ulasan baru ('.$data['rating'].' bintang) untuk pesanan '.$pesanan->kode().'.',
                'dibaca' => false,
                'waktu' => now(),
            ]);
        });

        return redirect()->route('buyer.pesanan.detail', $pesanan)
            ->with('status', 'Ulasan berhasil dikirim.');
    }

    public function notifikasi(Request $request): View
    {
        $notifikasi = $request->user()->notifikasi()
            ->orderByDesc('waktu')
            ->get()
            ->map(fn (Notifikasi $item) => PawonSync::notifikasi($item))
            ->all();

        return view('buyer.notifikasi', [
            'notifications' => $notifikasi,
            'belumDibaca' => collect($notifikasi)->where('unread', true)->count(),
        ]);
    }

    public function tandaiNotifikasi(Request $request): RedirectResponse
    {
        $request->user()->notifikasi()->where('dibaca', false)->update(['dibaca' => true]);

        return back()->with('status', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function profil(Request $request): View
    {
        $buyer = $request->user();

        return view('buyer.profil', [
            'buyer' => $buyer,
            'jumlahPesanan' => $buyer->pesananSebagaiBuyer()->count(),
            'jumlahUlasan' => $buyer->pesananSebagaiBuyer()->whereHas('ulasan')->count(),
        ]);
    }

    public function updateProfil(Request $request): RedirectResponse
    {
        $buyer = $request->user();

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$buyer->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $buyer->update(array_filter([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password' => $data['password'] ?? null,
        ], fn ($value) => $value !== null));

        return back()->with('status', 'Profil berhasil diperbarui.');
    }

    private function pastikanMilik(Request $request, Pesanan $pesanan): void
    {
        abort_unless($pesanan->buyer_id === $request->user()->id, 403);
    }

    /**
     * @return array{seller_id: int|null, items: array<string, array<string, mixed>>}
     */
    private function ambilKeranjang(Request $request): array
    {
        $cart = $request->session()->get('keranjang', ['seller_id' => null, 'items' => []]);

        if (! isset($cart['items'])) {
            $cart = ['seller_id' => null, 'items' => []];
        }

        return $cart;
    }

    /**
     * @param  array<int, int>  $opsiIds
     */
    private function kunciItem(int $menuId, ?int $paketId, array $opsiIds): string
    {
        sort($opsiIds);

        return $menuId.'-'.($paketId ?? '0').'-'.implode(',', $opsiIds);
    }

    /**
     * @param  array{seller_id: int|null, items: array<string, array<string, mixed>>}  $cart
     * @return array<int, array<string, mixed>>
     */
    private function bangunItemKeranjang(array $cart): array
    {
        return collect($cart['items'])
            ->map(function (array $item, string $key) {
                $menu = MenuKatering::with(['seller', 'paketMenu', 'opsiTambahan'])->find($item['menu_id']);

                if (! $menu) {
                    return null;
                }

                $paket = $menu->paketMenu->firstWhere('id', $item['paket_id']);
                $opsi = $menu->opsiTambahan->whereIn('id', $item['opsi_ids'] ?? []);
                $harga = (float) ($paket?->harga ?? $menu->harga) + (float) $opsi->sum('harga');

                return [
                    'key' => $key,
                    'menu_id' => $menu->id,
                    'menu_nama' => $menu->nama,
                    'seller' => $menu->seller?->nama ?? '-',
                    'paket_id' => $paket?->id,
                    'paket_nama' => $paket?->nama,
                    'opsi_ids' => $opsi->pluck('id')->all(),
                    'opsi_nama' => $opsi->pluck('nama')->all(),
                    'qty' => (int) $item['qty'],
                    'price' => $harga,
                    'subtotal' => $harga * (int) $item['qty'],
                    'photo' => $menu->foto,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }
}

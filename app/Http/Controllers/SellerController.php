<?php

namespace App\Http\Controllers;

use App\Enums\StatusMilestone;
use App\Enums\StatusPembayaran;
use App\Enums\StatusPengiriman;
use App\Enums\StatusPesanan;
use App\Models\KalenderKapasitas;
use App\Models\MenuKatering;
use App\Models\MilestoneDapur;
use App\Models\Notifikasi;
use App\Models\Pesanan;
use App\Models\User;
use App\Support\PawonSync;
use App\Support\SupabaseStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SellerController extends Controller
{
    public function dashboard(Request $request): View
    {
        $seller = $request->user();

        $pesananBaru = $seller->pesananSebagaiSeller()->where('status', StatusPesanan::Dibayar->value)->count();
        $perluKonfirmasi = $pesananBaru;

        $kirimHariIni = $seller->pesananSebagaiSeller()
            ->whereDate('tanggal_acara', now()->toDateString())
            ->whereIn('status', [StatusPesanan::SiapKirim->value, StatusPesanan::Dikirim->value])
            ->count();

        $perluUpdate = $seller->pesananSebagaiSeller()
            ->whereIn('status', [StatusPesanan::Dikonfirmasi->value, StatusPesanan::Diproses->value])
            ->count();

        $omzetHariIni = (float) $seller->pesananSebagaiSeller()
            ->whereDate('tanggal_acara', now()->toDateString())
            ->where('status', StatusPesanan::Selesai->value)
            ->sum('total_harga');

        $pesananMendatang = $seller->pesananSebagaiSeller()
            ->with(['buyer', 'pengiriman'])
            ->whereNotIn('status', [StatusPesanan::Selesai->value, StatusPesanan::Dibatalkan->value])
            ->orderBy('tanggal_acara')
            ->take(4)
            ->get()
            ->map(fn (Pesanan $order) => [
                ...PawonSync::pesananRingkas($order),
                'detail' => $order->jumlah_porsi.' porsi',
            ])
            ->all();

        $menus = $seller->menuKatering()
            ->withCount(['itemPesanan as terjual' => fn ($query) => $query->selectRaw('coalesce(sum(qty), 0)')])
            ->orderByDesc('terjual')
            ->take(3)
            ->get()
            ->map(fn (MenuKatering $menu) => [
                ...PawonSync::menuRingkas($menu),
                'sold' => (int) $menu->terjual,
            ])
            ->all();

        $bars = [];
        $days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = now()->subDays($i);
            $total = (float) $seller->pesananSebagaiSeller()
                ->whereDate('tanggal_acara', $tanggal->toDateString())
                ->where('status', StatusPesanan::Selesai->value)
                ->sum('total_harga');
            $bars[] = $total;
        }

        $maks = max(1, max($bars));
        $bars = array_map(fn ($total) => max(6, (int) round($total / $maks * 100)), $bars);

        $rating = PawonSync::ratingSeller($seller->id);
        $porsiBulanIni = (int) $seller->pesananSebagaiSeller()
            ->whereMonth('tanggal_acara', now()->month)
            ->whereYear('tanggal_acara', now()->year)
            ->sum('jumlah_porsi');

        return view('seller.dashboard', [
            'seller' => $seller,
            'stats' => [
                'baru' => $pesananBaru,
                'perlu_konfirmasi' => $perluKonfirmasi,
                'kirim_hari_ini' => $kirimHariIni,
                'perlu_update' => $perluUpdate,
                'omzet_hari_ini' => $omzetHariIni,
                'porsi_bulan_ini' => $porsiBulanIni,
                'rating' => $rating['rating'],
                'reviews' => $rating['reviews'],
            ],
            'orders' => $pesananMendatang,
            'menus' => $menus,
            'bars' => $bars,
            'days' => $days,
        ]);
    }

    public function pesanan(Request $request): View
    {
        $seller = $request->user();
        $tab = (string) $request->query('tab', 'baru');

        $statuses = match ($tab) {
            'diproses' => [StatusPesanan::Dikonfirmasi, StatusPesanan::Diproses],
            'siap' => [StatusPesanan::SiapKirim, StatusPesanan::Dikirim],
            'selesai' => [StatusPesanan::Selesai],
            'batal' => [StatusPesanan::Dibatalkan],
            'semua' => [],
            default => [StatusPesanan::Dibayar],
        };

        $query = $seller->pesananSebagaiSeller()->with(['buyer', 'pengiriman.kurir']);

        if ($statuses !== []) {
            $query->whereIn('status', array_map(fn (StatusPesanan $status) => $status->value, $statuses));
        }

        $orders = $query->orderBy('tanggal_acara')->get()
            ->map(fn (Pesanan $order) => PawonSync::pesananRingkas($order))
            ->all();

        return view('seller.pesanan', [
            'orders' => $orders,
            'tab' => $tab,
            'jumlahBaru' => $seller->pesananSebagaiSeller()->where('status', StatusPesanan::Dibayar->value)->count(),
        ]);
    }

    public function pesananDetail(Request $request, Pesanan $pesanan): View
    {
        $this->pastikanMilik($request, $pesanan);

        $detail = PawonSync::pesananDetail($pesanan);
        $detail['milestones'] = $pesanan->milestoneDapur->map(fn (MilestoneDapur $item) => [
            'status' => $item->status,
            'label' => $item->status->label(),
            'waktu' => $item->waktu?->locale('id')->translatedFormat('j M, H.i') ?? '-',
        ])->all();

        return view('seller.pesanan-detail', [
            'order' => $detail,
            'pesanan' => $pesanan,
        ]);
    }

    public function konfirmasiPesanan(Request $request, Pesanan $pesanan): RedirectResponse
    {
        $this->pastikanMilik($request, $pesanan);

        if ($pesanan->status !== StatusPesanan::Dibayar) {
            return back()->withErrors(['pesanan' => 'Pesanan tidak dalam status menunggu konfirmasi.']);
        }

        DB::transaction(function () use ($pesanan) {
            $pesanan->update(['status' => StatusPesanan::Dikonfirmasi]);

            $pesanan->milestoneDapur()->firstOrCreate(
                ['status' => StatusMilestone::Dikonfirmasi->value],
                ['waktu' => now()],
            );

            $this->notif($pesanan->buyer_id, $pesanan->id, 'Pesanan '.$pesanan->kode().' dikonfirmasi seller. Dapur mulai menyiapkan pesananmu.');
        });

        return redirect()->route('seller.pesanan.detail', $pesanan)
            ->with('status', 'Pesanan dikonfirmasi dan masuk antrean produksi.');
    }

    public function tolakPesanan(Request $request, Pesanan $pesanan): RedirectResponse
    {
        $this->pastikanMilik($request, $pesanan);

        if (in_array($pesanan->status, [StatusPesanan::Selesai, StatusPesanan::Dibatalkan], true)) {
            return back()->withErrors(['pesanan' => 'Pesanan ini tidak dapat ditolak.']);
        }

        DB::transaction(function () use ($pesanan) {
            $pesanan->update(['status' => StatusPesanan::Dibatalkan]);

            if ($pesanan->pembayaran()->exists()) {
                $pesanan->pembayaran()->update(['status' => StatusPembayaran::Gagal]);
            }

            KalenderKapasitas::query()
                ->where('seller_id', $pesanan->seller_id)
                ->whereDate('tanggal', $pesanan->tanggal_acara)
                ->update(['terpakai' => DB::raw('greatest(0, terpakai - '.(int) $pesanan->jumlah_porsi.')')]);

            $this->notif($pesanan->buyer_id, $pesanan->id, 'Pesanan '.$pesanan->kode().' ditolak seller. Silakan hubungi dapur untuk informasi lanjutan.');
        });

        return redirect()->route('seller.pesanan')
            ->with('status', 'Pesanan ditolak dan pembayaran ditandai gagal.');
    }

    public function milestone(Request $request, Pesanan $pesanan): View
    {
        $this->pastikanMilik($request, $pesanan);

        $sudah = $pesanan->milestoneDapur->keyBy(fn (MilestoneDapur $item) => $item->status->value);

        $milestones = collect(StatusMilestone::cases())->map(fn (StatusMilestone $status) => [
            'status' => $status,
            'title' => $status->label(),
            'desc' => match ($status) {
                StatusMilestone::Dikonfirmasi => 'Pesanan diterima dan masuk jadwal dapur',
                StatusMilestone::Belanja => 'Belanja bahan selesai dan dicek kualitasnya',
                StatusMilestone::Memasak => 'Dapur mulai memasak pesanan',
                StatusMilestone::Packing => 'Packing selesai dan box berlabel',
                StatusMilestone::SiapKirim => 'Pesanan menunggu diambil kurir',
            },
            'time' => $sudah->get($status->value)?->waktu?->locale('id')->translatedFormat('j M, H.i') ?? 'Belum',
            'done' => $sudah->has($status->value),
        ])->all();

        return view('seller.milestone', [
            'order' => PawonSync::pesananDetail($pesanan),
            'pesanan' => $pesanan,
            'milestones' => $milestones,
            'progress' => $pesanan->status->progress(),
        ]);
    }

    public function simpanMilestone(Request $request, Pesanan $pesanan): RedirectResponse
    {
        $this->pastikanMilik($request, $pesanan);

        $data = $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', StatusMilestone::values())],
            'catatan' => ['nullable', 'string', 'max:500'],
            'foto' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($pesanan->status === StatusPesanan::Dibatalkan) {
            throw ValidationException::withMessages(['status' => 'Pesanan sudah dibatalkan.']);
        }

        $milestone = StatusMilestone::from($data['status']);
        $statusBaru = match ($milestone) {
            StatusMilestone::Dikonfirmasi => StatusPesanan::Dikonfirmasi,
            StatusMilestone::SiapKirim => StatusPesanan::SiapKirim,
            default => StatusPesanan::Diproses,
        };

        DB::transaction(function () use ($request, $pesanan, $data, $milestone, $statusBaru) {
            $foto = $request->hasFile('foto')
                ? SupabaseStorage::upload($request->file('foto'), 'milestone')
                : null;

            $pesanan->milestoneDapur()->updateOrCreate(
                ['status' => $milestone->value],
                ['waktu' => now(), ...($foto ? ['foto_url' => $foto] : [])],
            );

            if ($pesanan->status->canTransitionTo($statusBaru) || $pesanan->status === $statusBaru) {
                $pesanan->update(['status' => $statusBaru]);
            }

            $pesan = 'Update dapur untuk pesanan '.$pesanan->kode().': '.$milestone->label().'.';
            if (! empty($data['catatan'])) {
                $pesan .= ' Catatan: '.$data['catatan'];
            }

            $this->notif($pesanan->buyer_id, $pesanan->id, $pesan);
        });

        return redirect()->route('seller.milestone', $pesanan)
            ->with('status', 'Milestone '.$milestone->label().' tersimpan.');
    }

    public function packaging(Request $request, Pesanan $pesanan): View
    {
        $this->pastikanMilik($request, $pesanan);

        $packaging = $pesanan->packagingChecklist()->firstOrCreate([], [
            'selesai' => false,
            'jumlah_box' => 0,
        ]);

        return view('seller.packaging', [
            'order' => PawonSync::pesananDetail($pesanan),
            'pesanan' => $pesanan,
            'packaging' => $packaging,
            'persen' => $packaging->persen(),
        ]);
    }

    public function simpanPackaging(Request $request, Pesanan $pesanan): RedirectResponse
    {
        $this->pastikanMilik($request, $pesanan);

        $data = $request->validate([
            'jumlah_box' => ['required', 'integer', 'min:0', 'max:'.$pesanan->jumlah_porsi],
            'selesai' => ['nullable', 'boolean'],
        ]);

        $selesai = $request->boolean('selesai');

        DB::transaction(function () use ($pesanan, $data, $selesai) {
            $pesanan->packagingChecklist()->updateOrCreate([], [
                'jumlah_box' => $data['jumlah_box'],
                'selesai' => $selesai,
            ]);

            if ($selesai && $pesanan->status === StatusPesanan::Diproses) {
                $pesanan->update(['status' => StatusPesanan::SiapKirim]);

                $pesanan->milestoneDapur()->firstOrCreate(
                    ['status' => StatusMilestone::SiapKirim->value],
                    ['waktu' => now()],
                );

                $this->notif($pesanan->buyer_id, $pesanan->id, 'Pesanan '.$pesanan->kode().' sudah selesai di-packing dan siap dikirim.');
            }
        });

        return redirect()->route('seller.kurir', $pesanan)
            ->with('status', 'Data packaging diperbarui.');
    }

    public function kurir(Request $request, Pesanan $pesanan): View
    {
        $this->pastikanMilik($request, $pesanan);

        $couriers = User::query()
            ->where('role', 'kurir')
            ->orderBy('nama')
            ->get()
            ->map(fn (User $kurir) => [
                'id' => $kurir->id,
                'name' => $kurir->nama,
                'vehicle' => $kurir->email,
                'status' => $kurir->pengirimanSebagaiKurir()
                    ->whereIn('status', [StatusPengiriman::Ditugaskan->value, StatusPengiriman::Berangkat->value])
                    ->exists() ? 'Sibuk' : 'Tersedia',
            ])
            ->all();

        return view('seller.kurir', [
            'order' => PawonSync::pesananDetail($pesanan),
            'pesanan' => $pesanan,
            'couriers' => $couriers,
            'pengiriman' => $pesanan->pengiriman,
        ]);
    }

    public function tugaskanKurir(Request $request, Pesanan $pesanan): RedirectResponse
    {
        $this->pastikanMilik($request, $pesanan);

        $data = $request->validate([
            'kurir_id' => ['required', 'integer', 'exists:users,id'],
            'eta_menit' => ['nullable', 'integer', 'min:5', 'max:600'],
            'instruksi' => ['nullable', 'string', 'max:500'],
        ]);

        $kurir = User::query()->where('role', 'kurir')->find($data['kurir_id']);

        if (! $kurir) {
            throw ValidationException::withMessages(['kurir_id' => 'Kurir tidak ditemukan.']);
        }

        if ($pesanan->status !== StatusPesanan::SiapKirim) {
            throw ValidationException::withMessages(['kurir_id' => 'Pesanan belum siap dikirim.']);
        }

        DB::transaction(function () use ($pesanan, $data, $kurir) {
            $pesanan->pengiriman()->updateOrCreate([], [
                'kurir_id' => $kurir->id,
                'status' => StatusPengiriman::Ditugaskan,
                'eta_menit' => $data['eta_menit'] ?? 45,
            ]);

            $this->notif($kurir->id, $pesanan->id, 'Tugas baru: antar pesanan '.$pesanan->kode().' ke '.$pesanan->alamat_venue.'.');
            $this->notif($pesanan->buyer_id, $pesanan->id, 'Kurir '.$kurir->nama.' ditugaskan untuk mengantar pesanan '.$pesanan->kode().'.');

            if (! empty($data['instruksi'])) {
                $this->notif($kurir->id, $pesanan->id, 'Instruksi seller: '.$data['instruksi']);
            }
        });

        return redirect()->route('seller.pesanan.detail', $pesanan)
            ->with('status', 'Kurir '.$kurir->nama.' berhasil ditugaskan.');
    }

    public function katalog(Request $request): View
    {
        $seller = $request->user();

        $menus = $seller->menuKatering()
            ->withCount(['itemPesanan as terjual' => fn ($query) => $query->selectRaw('coalesce(sum(qty), 0)')])
            ->orderByDesc('id')
            ->get()
            ->map(fn (MenuKatering $menu) => [
                ...PawonSync::menuRingkas($menu),
                'sold' => (int) $menu->terjual,
            ])
            ->all();

        $rating = collect($menus)->avg('rating') ?: 0;

        return view('seller.katalog', [
            'menus' => $menus,
            'total' => count($menus),
            'rating' => round((float) $rating, 1),
            'terjual' => array_sum(array_column($menus, 'sold')),
        ]);
    }

    public function menuForm(Request $request, ?MenuKatering $menu = null): View
    {
        if ($menu) {
            $this->pastikanMilikMenu($request, $menu);
            $menu->load(['paketMenu', 'opsiTambahan', 'dummyPlate']);
        }

        return view('seller.menu-form', [
            'menu' => $menu,
            'paket' => $menu?->paketMenu ?? collect(),
            'opsi' => $menu?->opsiTambahan ?? collect(),
            'plate' => $menu?->dummyPlate,
        ]);
    }

    public function simpanMenu(Request $request, ?MenuKatering $menu = null): RedirectResponse
    {
        if ($menu) {
            $this->pastikanMilikMenu($request, $menu);
        }

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'harga' => ['required', 'numeric', 'min:0'],
            'foto' => ['nullable', 'image', 'max:4096'],
            'paket_nama' => ['nullable', 'array'],
            'paket_nama.*' => ['nullable', 'string', 'max:150'],
            'paket_harga' => ['nullable', 'array'],
            'paket_harga.*' => ['nullable', 'numeric', 'min:0'],
            'opsi_nama' => ['nullable', 'array'],
            'opsi_nama.*' => ['nullable', 'string', 'max:150'],
            'opsi_harga' => ['nullable', 'array'],
            'opsi_harga.*' => ['nullable', 'numeric', 'min:0'],
            'gramasi' => ['nullable', 'numeric', 'min:0'],
            'plate_deskripsi' => ['nullable', 'string', 'max:1000'],
        ]);

        $menu = DB::transaction(function () use ($request, $menu, $data) {
            $foto = $request->hasFile('foto')
                ? SupabaseStorage::upload($request->file('foto'), 'menu')
                : $menu?->foto;

            $menu ??= new MenuKatering(['seller_id' => $request->user()->id]);

            $menu->fill([
                'seller_id' => $request->user()->id,
                'nama' => $data['nama'],
                'deskripsi' => $data['deskripsi'] ?? null,
                'harga' => $data['harga'],
                'foto' => $foto,
            ])->save();

            $menu->paketMenu()->delete();
            foreach ($data['paket_nama'] ?? [] as $index => $nama) {
                if (trim((string) $nama) === '') {
                    continue;
                }

                $menu->paketMenu()->create([
                    'nama' => $nama,
                    'harga' => $data['paket_harga'][$index] ?? $data['harga'],
                ]);
            }

            $menu->opsiTambahan()->delete();
            foreach ($data['opsi_nama'] ?? [] as $index => $nama) {
                if (trim((string) $nama) === '') {
                    continue;
                }

                $menu->opsiTambahan()->create([
                    'nama' => $nama,
                    'harga' => $data['opsi_harga'][$index] ?? 0,
                ]);
            }

            if (! empty($data['gramasi']) || ! empty($data['plate_deskripsi'])) {
                $menu->dummyPlate()->updateOrCreate([], [
                    'gramasi' => $data['gramasi'] ?? null,
                    'deskripsi' => $data['plate_deskripsi'] ?? null,
                ]);
            }

            return $menu;
        });

        return redirect()->route('seller.katalog')
            ->with('status', 'Menu '.$menu->nama.' berhasil disimpan.');
    }

    public function hapusMenu(Request $request, MenuKatering $menu): RedirectResponse
    {
        $this->pastikanMilikMenu($request, $menu);

        $menu->delete();

        return redirect()->route('seller.katalog')->with('status', 'Menu dihapus.');
    }

    public function kalkulasi(Request $request): View
    {
        $seller = $request->user();
        $menu = $seller->menuKatering()
            ->when($request->query('menu'), fn ($query, $id) => $query->whereKey($id))
            ->orderBy('id')
            ->first();

        if (! $menu) {
            return view('seller.kalkulasi', ['menu' => null]);
        }

        $menu->load(['dummyPlate', 'paketMenu', 'opsiTambahan']);

        $porsi = (int) $request->query('porsi', 100);
        $porsi = max(1, $porsi);
        $hppPerPorsi = round((float) $menu->harga * 0.32, 2);
        $totalHpp = $hppPerPorsi * $porsi;
        $revenue = (float) $menu->harga * $porsi;

        return view('seller.kalkulasi', [
            'menu' => $menu,
            'porsi' => $porsi,
            'hppPerPorsi' => $hppPerPorsi,
            'totalHpp' => $totalHpp,
            'revenue' => $revenue,
            'margin' => $revenue - $totalHpp,
            'foodCost' => 32,
            'marginPersen' => 68,
        ]);
    }

    public function kalender(Request $request): View
    {
        $seller = $request->user();

        $bulan = (string) $request->query('bulan', now()->format('Y-m'));
        if (! preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            $bulan = now()->format('Y-m');
        }

        $awal = Carbon::createFromFormat('Y-m-d', $bulan.'-01')->startOfMonth();
        $akhir = (clone $awal)->endOfMonth();

        $orders = $seller->pesananSebagaiSeller()
            ->whereBetween('tanggal_acara', [$awal->toDateString(), $akhir->toDateString()])
            ->orderBy('tanggal_acara')
            ->get();

        $events = $orders->map(fn (Pesanan $order) => [
            'id' => $order->id,
            'date' => (int) $order->tanggal_acara->format('j'),
            'title' => $order->judul(),
            'load' => $order->jumlah_porsi.' box',
            'time' => substr((string) $order->waktu_acara, 0, 5),
            'tone' => $order->status->tone(),
        ])->all();

        $busy = $orders->groupBy(fn (Pesanan $order) => (int) $order->tanggal_acara->format('j'))
            ->map->count()
            ->all();

        $kapasitas = KalenderKapasitas::query()
            ->where('seller_id', $seller->id)
            ->whereDate('tanggal', '>=', $awal->toDateString())
            ->whereDate('tanggal', '<=', $akhir->toDateString())
            ->orderBy('tanggal')
            ->get();

        $totalPorsi = (int) $orders->sum('jumlah_porsi');
        $kapasitasMaks = (int) $kapasitas->sum('kapasitas_maks');

        return view('seller.kalender', [
            'bulanLabel' => $awal->locale('id')->translatedFormat('F Y'),
            'bulan' => $bulan,
            'awal' => $awal,
            'hariDalamBulan' => (int) $awal->daysInMonth,
            'offsetHari' => (int) $awal->dayOfWeekIso,
            'events' => $events,
            'busy' => $busy,
            'kapasitas' => $kapasitas,
            'totalPesanan' => $orders->count(),
            'totalPorsi' => $totalPorsi,
            'kapasitasPersen' => $kapasitasMaks > 0 ? (int) round($totalPorsi / $kapasitasMaks * 100) : 0,
        ]);
    }

    public function keuangan(Request $request): View
    {
        $seller = $request->user();

        $selesai = $seller->pesananSebagaiSeller()
            ->with('buyer')
            ->where('status', StatusPesanan::Selesai->value)
            ->orderByDesc('tanggal_acara')
            ->get();

        $masuk = (float) $selesai->sum('total_harga');
        $komisi = round($masuk * 0.05, 2);
        $laba = $masuk - $komisi;

        $transactions = $selesai->take(6)->map(fn (Pesanan $order) => [
            'title' => 'Pencairan '.$order->judul(),
            'date' => $order->tanggal_acara?->locale('id')->translatedFormat('j M Y') ?? '-',
            'amount' => (float) $order->total_harga,
            'type' => 'in',
            'status' => 'Masuk',
        ])->values()->all();

        if ($komisi > 0) {
            $transactions[] = [
                'title' => 'Komisi platform 5%',
                'date' => now()->locale('id')->translatedFormat('j M Y'),
                'amount' => -$komisi,
                'type' => 'out',
                'status' => 'Potongan',
            ];
        }

        return view('seller.keuangan', [
            'seller' => $seller,
            'masuk' => $masuk,
            'komisi' => $komisi,
            'laba' => $laba,
            'saldo' => max(0, $laba),
            'transactions' => $transactions,
            'jumlahSelesai' => $selesai->count(),
        ]);
    }

    public function promosi(): View
    {
        return view('seller.promosi');
    }

    public function pengaturan(Request $request): View
    {
        return view('seller.pengaturan', [
            'seller' => $request->user(),
        ]);
    }

    public function updatePengaturan(Request $request): RedirectResponse
    {
        $seller = $request->user();

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$seller->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $seller->update(array_filter([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password' => $data['password'] ?? null,
        ], fn ($value) => $value !== null));

        return back()->with('status', 'Pengaturan toko diperbarui.');
    }

    public function retur(): View
    {
        return view('seller.retur');
    }

    private function pastikanMilik(Request $request, Pesanan $pesanan): void
    {
        abort_unless($pesanan->seller_id === $request->user()->id, 403);
    }

    private function pastikanMilikMenu(Request $request, MenuKatering $menu): void
    {
        abort_unless($menu->seller_id === $request->user()->id, 403);
    }

    private function notif(int $userId, int $pesananId, string $pesan): void
    {
        Notifikasi::create([
            'user_id' => $userId,
            'pesanan_id' => $pesananId,
            'pesan' => $pesan,
            'dibaca' => false,
            'waktu' => now(),
        ]);
    }
}

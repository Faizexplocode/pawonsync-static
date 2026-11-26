<?php

namespace App\Http\Controllers;

use App\Enums\StatusPengiriman;
use App\Enums\StatusPesanan;
use App\Models\BuktiSerahTerima;
use App\Models\LokasiKurir;
use App\Models\Notifikasi;
use App\Models\Pengiriman;
use App\Support\PawonSync;
use App\Support\SupabaseStorage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class KurirController extends Controller
{
    public function beranda(Request $request): View
    {
        $kurir = $request->user();

        $tasks = $this->tugasKurir($request)
            ->whereIn('status', [StatusPengiriman::Ditugaskan->value, StatusPengiriman::Berangkat->value])
            ->with(['pesanan.buyer', 'pesanan.seller', 'pesanan.packagingChecklist'])
            ->orderBy('id')
            ->get()
            ->map(fn (Pengiriman $pengiriman) => PawonSync::tugas($pengiriman))
            ->all();

        return view('kurir.beranda', [
            'courier' => $kurir,
            'tasks' => $tasks,
            'active' => $tasks[0] ?? null,
            'jumlahHariIni' => count($tasks),
            'totalMuatan' => collect($tasks)->sum(fn (array $task) => (int) str_replace(' box', '', $task['load'])),
        ]);
    }

    public function tugas(Request $request): View
    {
        $tab = (string) $request->query('tab', 'aktif');

        $query = $this->tugasKurir($request)
            ->with(['pesanan.buyer', 'pesanan.seller', 'pesanan.packagingChecklist']);

        $statuses = match ($tab) {
            'selesai' => [StatusPengiriman::Tiba],
            'batal' => [StatusPengiriman::Dibatalkan],
            default => [StatusPengiriman::Ditugaskan, StatusPengiriman::Berangkat],
        };

        $tasks = $query->whereIn('status', array_map(fn (StatusPengiriman $status) => $status->value, $statuses))
            ->orderBy('id')
            ->get()
            ->map(fn (Pengiriman $pengiriman) => PawonSync::tugas($pengiriman))
            ->all();

        return view('kurir.tugas', [
            'tasks' => $tasks,
            'tab' => $tab,
            'jumlahAktif' => $this->tugasKurir($request)
                ->whereIn('status', [StatusPengiriman::Ditugaskan->value, StatusPengiriman::Berangkat->value])
                ->count(),
        ]);
    }

    public function tugasDetail(Request $request, Pengiriman $pengiriman): View
    {
        $this->pastikanMilik($request, $pengiriman);

        return view('kurir.tugas-detail', [
            'task' => PawonSync::tugas($pengiriman),
            'pengiriman' => $pengiriman,
            'steps' => PawonSync::tahapanTugas($pengiriman),
        ]);
    }

    public function departure(Request $request, Pengiriman $pengiriman): View
    {
        $this->pastikanMilik($request, $pengiriman);

        return view('kurir.departure', [
            'task' => PawonSync::tugas($pengiriman),
            'pengiriman' => $pengiriman,
            'checklist' => [
                ['Jumlah box sesuai ('.$pengiriman->pesanan->jumlah_porsi.')', $pengiriman->status !== StatusPengiriman::Ditugaskan],
                ['Pesanan sudah dikemas seller', (bool) $pengiriman->pesanan->packagingChecklist?->selesai],
                ['Instruksi seller sudah dibaca', true],
                ['Kondisi kendaraan aman', true],
            ],
        ]);
    }

    public function mulaiBerangkat(Request $request, Pengiriman $pengiriman): RedirectResponse
    {
        $this->pastikanMilik($request, $pengiriman);

        if (! $pengiriman->pesanan->packagingChecklist?->selesai) {
            throw ValidationException::withMessages(['gate' => 'Seller belum menandai pesanan selesai di-packing.']);
        }

        if ($pengiriman->status === StatusPengiriman::Berangkat) {
            return redirect()->route('kurir.live', $pengiriman);
        }

        DB::transaction(function () use ($pengiriman) {
            $pengiriman->update([
                'status' => StatusPengiriman::Berangkat,
                'waktu_berangkat' => now(),
            ]);

            $pesanan = $pengiriman->pesanan;
            if ($pesanan->status === StatusPesanan::SiapKirim) {
                $pesanan->update(['status' => StatusPesanan::Dikirim]);
            }

            $this->notif($pesanan->buyer_id, $pesanan->id, 'Kurir '.$pengiriman->kurir->nama.' sudah berangkat menuju '.$pesanan->alamat_venue.'.');
            $this->notif($pesanan->seller_id, $pesanan->id, 'Pesanan '.$pesanan->kode().' sudah diambil dan dalam perjalanan.');
        });

        return redirect()->route('kurir.live', $pengiriman)
            ->with('status', 'Perjalanan dimulai.');
    }

    public function live(Request $request, Pengiriman $pengiriman): View
    {
        $this->pastikanMilik($request, $pengiriman);

        return view('kurir.live', [
            'task' => PawonSync::tugas($pengiriman),
            'pengiriman' => $pengiriman,
            'terakhir' => $pengiriman->lokasiTerakhir(),
        ]);
    }

    public function kirimLokasi(Request $request, Pengiriman $pengiriman): RedirectResponse|JsonResponse
    {
        $this->pastikanMilik($request, $pengiriman);

        $data = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $lokasi = LokasiKurir::create([
            'pengiriman_id' => $pengiriman->id,
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'waktu' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'latitude' => (float) $lokasi->latitude,
                'longitude' => (float) $lokasi->longitude,
                'waktu' => $lokasi->waktu?->toIso8601String(),
            ]);
        }

        return back()->with('status', 'Lokasi terbaru tersimpan.');
    }

    public function bukti(Request $request, Pengiriman $pengiriman): View
    {
        $this->pastikanMilik($request, $pengiriman);

        return view('kurir.bukti', [
            'task' => PawonSync::tugas($pengiriman),
            'pengiriman' => $pengiriman,
            'bukti' => $pengiriman->buktiSerahTerima,
        ]);
    }

    public function simpanBukti(Request $request, Pengiriman $pengiriman): RedirectResponse
    {
        $this->pastikanMilik($request, $pengiriman);

        if ($pengiriman->buktiSerahTerima()->exists()) {
            return redirect()->route('kurir.bukti', $pengiriman)
                ->withErrors(['foto' => 'Bukti serah terima sudah dikirim dan tidak dapat diubah.']);
        }

        $data = $request->validate([
            'foto' => ['required', 'image', 'max:4096'],
            'penerima' => ['required', 'string', 'max:100'],
            'jumlah_box' => ['required', 'integer', 'min:1', 'max:'.$pengiriman->pesanan->jumlah_porsi],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($request, $pengiriman, $data) {
            $foto = SupabaseStorage::upload($request->file('foto'), 'bukti');

            BuktiSerahTerima::create([
                'pengiriman_id' => $pengiriman->id,
                'foto_url' => $foto,
                'waktu' => now(),
            ]);

            $pengiriman->update(['status' => StatusPengiriman::Tiba]);

            $pesanan = $pengiriman->pesanan;
            $pesan = 'Kurir sudah tiba di '.$pesanan->alamat_venue.' dan mengunggah bukti serah terima. Diterima oleh '.$data['penerima'].' ('.$data['jumlah_box'].' box).';

            $this->notif($pesanan->buyer_id, $pesanan->id, $pesan.' Konfirmasi penerimaan di aplikasi.');
            $this->notif($pesanan->seller_id, $pesanan->id, 'Bukti serah terima pesanan '.$pesanan->kode().' sudah diunggah kurir.');

            if (! empty($data['catatan'])) {
                $this->notif($pesanan->buyer_id, $pesanan->id, 'Catatan kurir: '.$data['catatan']);
            }
        });

        return redirect()->route('kurir.tugas')
            ->with('status', 'Bukti serah terima tersimpan. Tugas selesai.');
    }

    private function tugasKurir(Request $request)
    {
        return $request->user()->pengirimanSebagaiKurir();
    }

    private function pastikanMilik(Request $request, Pengiriman $pengiriman): void
    {
        abort_unless($pengiriman->kurir_id === $request->user()->id, 403);
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

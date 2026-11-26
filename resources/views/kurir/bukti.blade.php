@extends('layouts.phone')

@section('title', 'Bukti Serah Terima')

@section('content')
    <x-app-bar title="Bukti Serah Terima" :subtitle="$task['kode']" :back="route('kurir.live', $task['id'])">
        <x-slot:right>
            <span class="text-caption font-semibold text-leaf-dark">{{ $bukti ? 'Terkirim' : 'Tiba' }}</span>
        </x-slot:right>
    </x-app-bar>

    @if ($bukti)
        <main class="stagger flex-1 space-y-4 bg-cream px-4 pb-6 pt-4">
            <x-badge color="green" class="whitespace-normal">Bukti sudah dikirim dan tidak dapat diubah</x-badge>
            <x-photo :src="$bukti->foto_url" :label="'Diunggah '.$bukti->waktu?->locale('id')->translatedFormat('j M Y, H.i')" class="h-60 w-full rounded-[20px]" />
            <section class="space-y-1.5 surface px-4 py-3.5">
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Pesanan</span>
                    <span class="font-semibold text-ink">{{ $task['kode'] }}</span>
                </div>
                <div class="flex items-center justify-between text-[13px]">
                    <span class="text-muted">Jumlah box</span>
                    <span class="font-semibold text-ink">{{ $task['load'] }}</span>
                </div>
                <p class="text-caption text-muted">Tugas selesai. Buyer akan mengonfirmasi penerimaan pesanan.</p>
            </section>
        </main>

        <div class="sticky bottom-0 z-20 mt-auto border-t border-line bg-white px-4 py-3">
            <x-button :href="route('kurir.tugas', ['tab' => 'selesai'])">Lihat Tugas Selesai</x-button>
        </div>
    @else
        <form action="{{ route('kurir.bukti.store', $task['id']) }}" method="post" enctype="multipart/form-data" class="flex flex-1 flex-col">
            @csrf

            <main class="stagger flex-1 space-y-4 bg-cream px-4 pb-6 pt-4">
                <x-badge color="green" class="whitespace-normal">Foto pesanan ditata di venue = bukti sah pengantaran selesai</x-badge>

                <label class="grid h-60 w-full cursor-pointer place-items-center rounded-[20px] bg-linear-[138deg] from-mint-soft from-0% to-mint to-70% px-4 text-center text-[11px] font-medium text-leaf-dark">
                    <input type="file" name="foto" accept="image/*" capture="environment" required class="w-full text-caption" />
                </label>
                <p class="text-[11px] text-muted">Pastikan jumlah box terlihat jelas dan penerima berada di lokasi. Maksimal 4 MB.</p>

                <x-field label="Diterima oleh" name="penerima" :value="old('penerima', $task['buyer'])" required />
                <x-field label="Jumlah box diterima" name="jumlah_box" :value="old('jumlah_box', $task['load'])" type="number" required />
                <label class="block">
                    <span class="mb-1.5 block text-caption font-medium text-muted">Keterangan</span>
                    <textarea name="catatan" rows="2" placeholder="Contoh: box ditata di meja prasmanan lantai 1" class="w-full rounded-2xl border border-line/70 bg-white px-3.5 py-[13px] text-body text-ink outline-none placeholder:text-muted/70 focus:border-leaf">{{ old('catatan') }}</textarea>
                </label>

                <section class="space-y-1.5 surface px-4 py-3.5">
                    <p class="text-[15px] font-semibold text-ink">Konfirmasi serah terima</p>
                    @foreach (['Jumlah box diterima lengkap', 'Ditata sesuai permintaan buyer', 'Foto siap diunggah'] as $langkah)
                        <div class="flex items-center gap-3 py-1">
                            <span class="grid size-[22px] shrink-0 place-items-center rounded-[7px] border-2 border-leaf-dark bg-leaf-dark text-[12px] font-bold text-white">✓</span>
                            <span class="text-body font-medium text-ink">{{ $langkah }}</span>
                        </div>
                    @endforeach
                </section>

                <x-alert tone="warning">Setelah dikirim, bukti tidak dapat diubah. Pastikan foto sudah benar.</x-alert>
            </main>

            <div class="sticky bottom-0 z-20 mt-auto border-t border-line bg-white px-4 py-3">
                <x-button type="submit">Kirim Bukti &amp; Selesaikan</x-button>
            </div>
        </form>
    @endif
@endsection

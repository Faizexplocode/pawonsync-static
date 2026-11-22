@extends('layouts.phone')

@section('title', 'Bukti Serah Terima')

@section('content')
    <x-app-bar title="Bukti Serah Terima" subtitle="Wisuda ITS · 150 box" :back="route('kurir.live')">
        <x-slot:right>
            <x-badge color="amber">Belum selesai</x-badge>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center justify-between">
                <p class="text-[12px] font-bold text-forest-deep">Foto serah terima</p>
                <span class="text-[9px] font-bold text-amber">Wajib</span>
            </div>
            <div class="mt-2 grid grid-cols-2 gap-2">
                <x-photo label="Box di venue" class="h-28" />
                <x-photo label="+ Ambil foto" class="h-28 border border-dashed border-line bg-white text-muted" />
            </div>
            <p class="mt-1.5 text-[9px] leading-relaxed text-muted">Pastikan jumlah box terlihat jelas dan penerima berada di lokasi.</p>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Data penerima</p>
            <div class="mt-3 space-y-3">
                <x-field label="Nama penerima" value="Bu Diana" icon="user" />
                <x-field label="Jumlah box diterima" value="150" type="number" icon="package" />
                <x-field label="Catatan" placeholder="Contoh: diterima oleh staf venue" icon="edit" />
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Tanda tangan penerima</p>
            <div class="mt-2 grid h-32 place-items-center rounded-xl border border-dashed border-line bg-mist text-center">
                <div>
                    <x-icon name="edit" class="mx-auto size-6 text-muted" />
                    <p class="mt-1 text-[10px] font-semibold text-muted">Minta penerima tanda tangan di sini</p>
                </div>
            </div>
            <div class="mt-2 flex gap-2">
                <a href="#" class="flex-1 rounded-xl border border-line py-2 text-center text-[10px] font-bold text-muted">Hapus</a>
                <a href="#" class="flex-1 rounded-xl bg-mint-soft py-2 text-center text-[10px] font-bold text-forest">Ulangi</a>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Checklist akhir</p>
            <div class="mt-2 space-y-2">
                @foreach ([['Jumlah box sesuai', true], ['Kondisi box baik', true], ['Penerima menandatangani', false], ['Foto tersimpan', true]] as [$label, $done])
                    <div class="flex items-center gap-2 text-[11px]">
                        <span @class(['grid size-5 place-items-center rounded-md border-2', 'border-leaf bg-leaf text-white' => $done, 'border-line bg-white' => ! $done])>
                            @if ($done)<x-icon name="check" class="size-3" />@endif
                        </span>
                        <span class="{{ $done ? 'font-semibold text-forest-deep' : 'text-muted' }}">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl bg-forest p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-semibold text-mint/70">Pendapatan tugas ini</p>
                    <p class="mt-0.5 text-[20px] font-extrabold">Rp 95.000</p>
                </div>
                <span class="grid size-11 place-items-center rounded-2xl bg-forest-deep/60 text-mint"><x-icon name="wallet" class="size-5" /></span>
            </div>
            <p class="mt-2 text-[9px] text-mint/70">Masuk ke saldo setelah buyer mengonfirmasi penerimaan.</p>
        </section>

        <div class="rounded-2xl bg-amber-soft p-3 text-[10px] leading-relaxed text-amber">
            Setelah dikirim, bukti tidak dapat diubah. Pastikan foto dan tanda tangan sudah benar.
        </div>

        <a href="{{ route('kurir.beranda') }}" class="flex items-center justify-center gap-2 rounded-2xl bg-forest px-4 py-3.5 text-[13px] font-bold text-white">
            <x-icon name="check" class="size-4" />
            Kirim bukti &amp; selesaikan tugas
        </a>
    </main>

    <x-bottom-nav role="kurir" active="kurir.bukti" />
@endsection

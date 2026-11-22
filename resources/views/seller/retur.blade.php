@extends('layouts.phone')

@section('title', 'Retur & Refund')

@php
    $reasons = [
        ['label' => 'Jumlah kurang', 'checked' => true],
        ['label' => 'Menu tidak sesuai', 'checked' => false],
        ['label' => 'Kemasan rusak', 'checked' => false],
        ['label' => 'Terlambat antar', 'checked' => false],
    ];
@endphp

@section('content')
    <x-app-bar title="Retur & Refund" subtitle="Wisuda ITS · 150 box" :back="route('seller.pesanan.detail', 'wisuda-its')">
        <x-slot:right>
            <x-badge color="amber">Menunggu</x-badge>
        </x-slot:right>
    </x-app-bar>

    <main class="flex-1 space-y-4 px-4 py-4">
        <section class="rounded-2xl border border-line bg-white p-4">
            <div class="flex items-center gap-3">
                <span class="grid size-10 place-items-center rounded-full bg-mint text-[12px] font-extrabold text-forest">BD</span>
                <div class="min-w-0 flex-1">
                    <p class="text-[12px] font-bold text-ink">Bu Diana</p>
                    <p class="text-[9px] text-muted">Diajukan 12 Okt 2026 · 10.42</p>
                </div>
                <a href="#" class="grid size-9 place-items-center rounded-full bg-mint-soft text-forest"><x-icon name="phone" class="size-4" /></a>
            </div>
            <div class="mt-3 rounded-xl bg-mist p-3">
                <p class="text-[10px] font-semibold text-muted">Alasan pengajuan</p>
                <p class="mt-0.5 text-[11px] font-bold text-forest-deep">Jumlah box kurang 5 porsi</p>
                <p class="mt-1 text-[10px] leading-relaxed text-muted">"Saat dihitung di venue, box hanya 145. Mohon dicek kembali."</p>
            </div>
            <div class="mt-3 grid grid-cols-3 gap-2">
                <x-photo label="Bukti 1" class="h-16" />
                <x-photo label="Bukti 2" class="h-16" />
                <x-photo label="Bukti 3" class="h-16" />
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Detail klaim</p>
            <div class="mt-3 space-y-2 text-[11px]">
                <div class="flex justify-between text-muted"><span>Nilai pesanan</span><span class="font-semibold text-ink">Rp 5.337.500</span></div>
                <div class="flex justify-between text-muted"><span>Porsi diklaim kurang</span><span class="font-semibold text-ink">5 box</span></div>
                <div class="flex justify-between text-muted"><span>Nilai retur</span><span class="font-semibold text-amber">Rp 190.000</span></div>
                <div class="flex justify-between border-t border-line pt-2 text-[12px] font-extrabold text-forest-deep"><span>Total refund</span><span class="text-forest">Rp 190.000</span></div>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Verifikasi penyebab</p>
            <div class="mt-3 space-y-2">
                @foreach ($reasons as $reason)
                    <label @class([
                        'flex cursor-pointer items-center gap-3 rounded-xl border p-3',
                        'border-leaf bg-mint-soft' => $reason['checked'],
                        'border-line bg-white' => ! $reason['checked'],
                    ])>
                        <span @class(['grid size-5 shrink-0 place-items-center rounded-full border-2', 'border-leaf bg-leaf text-white' => $reason['checked'], 'border-line' => ! $reason['checked']])>
                            @if ($reason['checked'])<x-icon name="check" class="size-3" />@endif
                        </span>
                        <span class="text-[11px] font-semibold text-forest-deep">{{ $reason['label'] }}</span>
                    </label>
                @endforeach
            </div>
            <label class="mt-3 block">
                <span class="mb-1.5 block text-[11px] font-semibold text-forest-deep">Catatan untuk buyer</span>
                <textarea rows="3" placeholder="Contoh: kami cek ulang, akan kirim 5 box susulan..." class="w-full rounded-xl border border-line bg-mist px-3 py-2.5 text-[11px] leading-relaxed text-ink outline-none placeholder:text-muted/70 focus:border-leaf focus:ring-2 focus:ring-mint"></textarea>
            </label>
        </section>

        <section class="rounded-2xl border border-line bg-white p-4">
            <p class="text-[12px] font-bold text-forest-deep">Pilih penyelesaian</p>
            <div class="mt-3 space-y-2">
                <div class="flex items-center gap-3 rounded-xl bg-mint-soft p-3">
                    <span class="grid size-8 place-items-center rounded-lg bg-white text-leaf"><x-icon name="banknote" class="size-4" /></span>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-bold text-forest-deep">Refund sebagian</p>
                        <p class="text-[9px] text-muted">Rp 190.000 dikembalikan ke buyer</p>
                    </div>
                    <span class="grid size-5 place-items-center rounded-full border-2 border-leaf bg-leaf text-white"><x-icon name="check" class="size-3" /></span>
                </div>
                <div class="flex items-center gap-3 rounded-xl border border-line p-3">
                    <span class="grid size-8 place-items-center rounded-lg bg-mist text-muted"><x-icon name="package" class="size-4" /></span>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-bold text-forest-deep">Kirim ulang 5 box</p>
                        <p class="text-[9px] text-muted">Susulan dikirim hari ini</p>
                    </div>
                    <span class="size-5 rounded-full border-2 border-line"></span>
                </div>
            </div>
        </section>

        <div class="rounded-2xl bg-amber-soft p-3 text-[10px] leading-relaxed text-amber">
            Selesaikan dalam 1x24 jam. Jika tidak direspons, platform akan memproses refund otomatis ke buyer.
        </div>

        <div class="grid grid-cols-2 gap-2">
            <a href="#" class="flex items-center justify-center rounded-2xl border border-line bg-white px-4 py-3.5 text-[13px] font-bold text-muted">Tolak klaim</a>
            <a href="{{ route('seller.pesanan.detail', 'wisuda-its') }}" class="flex items-center justify-center gap-2 rounded-2xl bg-forest px-4 py-3.5 text-[13px] font-bold text-white">
                Setujui refund
                <x-icon name="check" class="size-4" />
            </a>
        </div>
    </main>
@endsection
